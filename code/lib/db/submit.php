<?php

function savePlugin($githubUrl)
{
  // all the logic for submitting new plugins

  global $pdo;

  // gets submitted url
  $url['raw'] = $githubUrl;

  // parsed the URL to get the path
  $url['parsed'] = parse_url($url['raw']);

  // explodes every slash into an array owner will be [1] and name will be [2]
  $url['repoData'] = explode("/", $url['parsed']['path']);

  // removes ".git" from name, in case user submitted the .git URL
  $url['repoData'][2] = str_replace(".git", "", $url['repoData'][2]);

  // VALIDATION
  // if it's not https, the host is not github.com, either of the repo datas are empty, throw an error
  if ($url['parsed']['scheme'] != 'https' || $url['parsed']['host'] != 'github.com' || empty($url['repoData'][1]) || empty($url['repoData'][2])) {
    header('Content-Type: application/json; charset=UTF-8');
    $result = array();
    $result['status'] = 'error';
    $result['message'] = 'URL is not valid';
    return json_encode($result);
  }

  // puts array into variables so we can use them below:
  $repoOwner = $url['repoData'][1];
  $repoName = $url['repoData'][2];

  $gitQuery = <<<GRAPHQL
query {
  
    rateLimit {
      cost
      remaining
    }

    repository(owner: "$repoOwner", name: "$repoName") {
      id
      name
      url
      description
      pushedAt
      updatedAt
      usesCustomOpenGraphImage
      openGraphImageUrl
      licenseInfo {
        nickname
        url
        name
        spdxId
      }
      stargazers {
        totalCount
      }
      owner {
        id
        login
        avatarUrl
        url
      }
			releases (first: 1) {
			  edges {
			    node {
            publishedAt
			    }
			  }
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
      readme5: object(expression: "main:readme.md") {
        ... on Blob {
          text
        }
      }
      readme6: object(expression: "main:README.md") {
        ... on Blob {
          text
        }
      }
      readme7: object(expression: "main:readme.MD") {
        ... on Blob {
          text
        }
      }
      readme8: object(expression: "main:README.MD") {
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

  // debug($result, 'Raw GitHub API result');

  // If Git|Hub returns an error, we return before trying to mess with the database
  if (isset($result['errors'])) {
    header('Content-Type: application/json; charset=UTF-8');
    $result = array();
    $result['status'] = 'error';
    $result['message'] = 'GitHub link not found. Are you sure the URL is correct and the repo is public?';

    return json_encode($result);
  }

  // API cost info
  debug($result['data']['rateLimit']['cost'], "GitHub API Rate Limit Cost", 'logs', true);
  debug($result['data']['rateLimit']['remaining'], "GitHub API Rate Limit Remaining", 'logs', true);

  // Let's get the latest update timestamp for the repo.
  // If the repo has a release on GitHub, we use the release date. Otherwise we get the time of the latest commit
  if (isset($result['data']['repository']['releases']['edges'][0]['node']['publishedAt'])) {
    $updatedAt =  date("U", strtotime($result['data']['repository']['releases']['edges'][0]['node']['publishedAt']));
  } else {
    $updatedAt =  date("U", strtotime($result['data']['repository']['pushedAt']));
  }

  debug($result['data']['repository']['releases']['edges'][0]['node']['publishedAt'], "Latest release date (including pre-releases)");

  $submittedAt = time();

  // converts OG image to boolean
  if ($result['data']['repository']['usesCustomOpenGraphImage'] == 1) {
    $usesOGImg = 1;
  } else {
    $usesOGImg = 0;
  }

  // deals with License inconsistencies
  $license = $result['data']['repository']['licenseInfo']['nickname'];
  // if there's no nickname, use the spdxId
  if (empty($license)) {
    $license = $result['data']['repository']['licenseInfo']['spdxId'];
  }
  // if the spdxId is "NOASSERTION", then use the actual name
  if ($license == 'NOASSERTION') {
    $license = $result['data']['repository']['licenseInfo']['name'];
  }

  // Appends all the 4 readme checks. Since most likely only 1 of them will have any text, we can just append them all. Easier than doing a bunch of if/ elses
  $readme = $result['data']['repository']['readme1']['text'];
  $readme .= $result['data']['repository']['readme2']['text'];
  $readme .= $result['data']['repository']['readme3']['text'];
  $readme .= $result['data']['repository']['readme4']['text'];
  $readme .= $result['data']['repository']['readme5']['text'];
  $readme .= $result['data']['repository']['readme6']['text'];
  $readme .= $result['data']['repository']['readme7']['text'];
  $readme .= $result['data']['repository']['readme8']['text'];

  $readme = replaceRelativeImageLinks($readme, $repoOwner, $repoName);

  // PLUGINS db insert
  $sql = "INSERT INTO `plugins` (`id`, `name`, `url`, `description`, ";
  $sql .= "`submittedAt`, ";
  $sql .= "`updatedAt`, `usesCustomOpenGraphImage`, `thumbnail`, `stargazers`, `owner`, `readme`, `licenseName`, `licenseUrl`) ";
  $sql .= "VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $sql .= "ON DUPLICATE KEY UPDATE ";
  $sql .= "`name` = ?, ";
  $sql .= "`url` = ?,  ";
  $sql .= "`description` = ?,  ";
  $sql .= "`updatedAt` = ?, ";
  $sql .= "`usesCustomOpenGraphImage` = ?, ";
  $sql .= "`thumbnail` = ?, ";
  $sql .= "`stargazers` = ?, ";
  $sql .= "`owner` = ?, ";
  $sql .= "`readme` = ?, ";
  $sql .= "`licenseName` = ?, ";
  $sql .= "`licenseUrl` = ? ";
  $sql .= ";";

  // builds the arguments for the query execution (it uses the array_push cause we have some if/elses in there)
  $args = [];

  array_push($args, $result['data']['repository']['id']);
  array_push($args, $result['data']['repository']['name']);
  array_push($args, $result['data']['repository']['url']);
  array_push($args, $result['data']['repository']['description']);
  array_push($args, $submittedAt);
  array_push($args, $updatedAt);
  array_push($args, $usesOGImg);
  array_push($args, $result['data']['repository']['openGraphImageUrl']);
  array_push($args, $result['data']['repository']['stargazers']['totalCount']);
  array_push($args, $result['data']['repository']['owner']['id']);
  array_push($args, $readme);
  array_push($args, $license);
  array_push($args, $result['data']['repository']['licenseInfo']['url']);
  array_push($args, $result['data']['repository']['name']);
  array_push($args, $result['data']['repository']['url']);
  array_push($args, $result['data']['repository']['description']);
  array_push($args, $updatedAt);
  array_push($args, $usesOGImg);
  array_push($args, $result['data']['repository']['openGraphImageUrl']);
  array_push($args, $result['data']['repository']['stargazers']['totalCount']);
  array_push($args, $result['data']['repository']['owner']['id']);
  array_push($args, $readme);
  array_push($args, $license);
  array_push($args, $result['data']['repository']['licenseInfo']['url']);

  debug($sql, 'QUERY');
  // debug($args, 'QUERY ARGS');

  try {
    $pdo->prepare($sql)->execute($args);
  } catch (Exception $e) {
    header('Content-Type: application/json; charset=UTF-8');
    $result = array();
    $result['status'] = 'error';
    $result['message'] = 'Error when adding plugin ';

    if ($_ENV['DEBUG'] == 1) {
      $result['message'] .= $e;
    }

    return json_encode($result);
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
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    $result = array();
    $result['status'] = 'error';
    $result['message'] = 'Error when adding user';
    return json_encode($result);
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
      header('HTTP/1.1 500 Internal Server Error');
      header('Content-Type: application/json; charset=UTF-8');
      $result = array();
      $result['status'] = 'error';
      $result['message'] = 'Error when adding tags';
      return json_encode($result);
    }
  }

  // if we got to this point, everything went well. Send message back saying so.

  // builds redirect url
  $redirect = "/plugin/" . $result['data']['repository']['id'] . "/" . urlencode($result['data']['repository']['name']);

  $result = array();
  $result['status'] = 'success';
  $result['message'] = 'success';
  $result['redirect'] = $redirect;
  header('Content-Type: application/json; charset=UTF-8');

  // debugging code
  debug($url, "URL Debugging");
  debug( json_encode($result), "RESULT");

  return json_encode($result, true);
}

function replaceRelativeImageLinks($readme, $repoOwner, $repoName)
{
    $githubRawBaseUrl = "https://raw.githubusercontent.com/$repoOwner/$repoName/master/";
    $pattern = '/!\[.*?\]\((?!http)(.*?)\)/';

    $replaceCallback = function ($matches) use ($githubRawBaseUrl) {
        return "![image]($githubRawBaseUrl{$matches[1]})";
    };

    $readme = preg_replace_callback($pattern, $replaceCallback, $readme);

    return $readme;
}