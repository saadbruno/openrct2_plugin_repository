<?php

// all the logic for submitting new plugins

// gets submitted url (sanitize, cause why not?)
$url['raw'] = filter_input(INPUT_POST, 'githubUrl', FILTER_SANITIZE_URL);

// parsed the URL to get the path
$url['parsed'] = parse_url($url['raw']);

// explodes every slash into an array owner will be [1] and name will be [2]
$url['repoData'] = explode("/", $url['parsed']['path']);

// removes ".git" from name, in case user submitted the .git URL
$url['repoData'][2] = str_replace(".git", "", $url['repoData'][2]);

// puts array into variables so we can use them below:
$repoOwner = $url['repoData'][1];
$repoName = $url['repoData'][2];

$gitQuery = <<<GRAPHQL
query {
    repository(owner: "$repoOwner", name: "$repoName") {
      id
      name
      url
      description
      pushedAt
      updatedAt
      usesCustomOpenGraphImage
      openGraphImageUrl
      stargazers {
        totalCount
      }
      owner {
        id
        login
        avatarUrl
        url
      }
      readme1: object(expression: "master:readme.md") {
        ... on Blob {
          text
        }
      }
      readme2: object(expression: "master:README.md") {
        ... on Blob {
          text
        }
      }
      readme3: object(expression: "master:readme.MD") {
        ... on Blob {
          text
        }
      }
      readme4: object(expression: "master:README.MD") {
        ... on Blob {
          text
        }
      }
      repositoryTopics(first: 25) {
        edges {
          node {
            topic {
              name
            }
          }
        }
      }
    }
  }

GRAPHQL;
$result = graphql_query('https://api.github.com/graphql', $gitQuery, [], $_ENV['GITHUB_TOKEN']);

// converts the timestamp
$timestamp =  date("U",strtotime($result['data']['repository']['updatedAt']));

// converts OG image to boolean
if (empty($result['data']['repository']['usesCustomOpenGraphImage'])) {
  $usesOGImg = 0;
} else {
  $usesOGImg = 0;
}

// Appends all the 4 readme checks. Since most likely only 1 of them will have any text, we can just append them all. Easier than doing a bunch of if/ elses
$readme = $result['data']['repository']['readme1']['text'];
$readme .= $result['data']['repository']['readme2']['text'];
$readme .= $result['data']['repository']['readme3']['text'];
$readme .= $result['data']['repository']['readme4']['text'];

// PLUGINS db insert
$sql = "INSERT INTO `plugins`
        (`id`, `name`, `url`, `description`, `updatedAt`, `usesCustomOpenGraphImage`, `thumbnail`, `stargazers`, `owner`, `readme`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        `name` = ?,
        `url` = ?, 
        `description` = ?, 
        `updatedAt` = ?,
        `usesCustomOpenGraphImage` = ?,
        `thumbnail` = ?,
        `stargazers` = ?,
        `owner` = ?,
        `readme` = ?
        ;";
try {
    $pdo->prepare($sql)->execute([
        $result['data']['repository']['id'],
        $result['data']['repository']['name'],
        $result['data']['repository']['url'],
        $result['data']['repository']['description'],
        $timestamp,
        $usesOGImg,
        $result['data']['repository']['openGraphImageUrl'],
        $result['data']['repository']['stargazers']['totalCount'],
        $result['data']['repository']['owner']['id'],
        $readme,
        $result['data']['repository']['name'],
        $result['data']['repository']['url'],
        $result['data']['repository']['description'],
        $timestamp,
        $usesOGImg,
        $result['data']['repository']['openGraphImageUrl'],
        $result['data']['repository']['stargazers']['totalCount'],
        $result['data']['repository']['owner']['id'],
        $readme
    ]);
} catch (Exception $e) {
    // sets notices in the session. In the future, if we add toasts, we can use this.
    $_SESSION['notice']['type'] = "error";
    $_SESSION['notice']['message'] = "There was an error trying to add this plugin";
    if (isset($_ENV['DEBUG'])) {
        $_SESSION['notice']['message'] .= "<br>" . $e;
    }
}


// USERS db insert
$sql = "INSERT INTO `users`
        (`id`, `username`, `avatarUrl`, `url`) 
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        `username` = ?,
        `avatarUrl` = ?, 
        `url` = ?
        ;";
try {
    $pdo->prepare($sql)->execute([
        $result['data']['repository']['owner']['id'],
        $result['data']['repository']['owner']['login'],
        $result['data']['repository']['owner']['avatarUrl'],
        $result['data']['repository']['owner']['url'],
        $result['data']['repository']['owner']['login'],
        $result['data']['repository']['owner']['avatarUrl'],
        $result['data']['repository']['owner']['url']
    ]);
} catch (Exception $e) {
    // sets notices in the session. In the future, if we add toasts, we can use this.
    $_SESSION['notice']['type'] = "error";
    $_SESSION['notice']['message'] = "There was an error trying to add the user";
    if (isset($_ENV['DEBUG'])) {
        $_SESSION['notice']['message'] .= "<br>" . $e;
    }
}


// TAGS db insert
foreach ($result['data']['repository']['repositoryTopics']['edges'] as $key => $topic) {
  $sql = "INSERT INTO `tags`
          (`plugin_id`, `tag`) 
          VALUES (?, ?)
          ON DUPLICATE KEY UPDATE
          `plugin_id` = ?,
          `tag` = ?
          ;";
  try {
      $pdo->prepare($sql)->execute([
          $result['data']['repository']['id'],
          $topic['node']['topic']['name'],
          $result['data']['repository']['id'],
          $topic['node']['topic']['name']
      ]);
  } catch (Exception $e) {
      // sets notices in the session. In the future, if we add toasts, we can use this.
      $_SESSION['notice']['type'] = "error";
      $_SESSION['notice']['message'] = "There was an error trying to add tags";
      if (isset($_ENV['DEBUG'])) {
          $_SESSION['notice']['message'] .= "<br>" . $e;
      }
  }
}

// debugging code
if ($_ENV['DEBUG']) {

    echo "<!--";

    echo "\n\nTImestamp: " . $timestamp ."\n";

    echo "\n\nSession notices:\n";
    print_r($_SESSION['notice']);

    echo "\n\nURL debugging:\n";
    print_r($url);

    echo "\n\nGitHub API response:\n";
    print_r($result);

    echo "-->";
}

