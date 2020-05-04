<?php

// all the logic for submitting new plugins

// gets submitted url
$url['raw'] = $_POST['githubUrl'];

// parsed the URL to get the path
$url['parsed'] = parse_url($url['raw']);

// explodes every slash into an array owner will be [1] and name will be [2]
$url['repoData'] = explode("/", $url['parsed']['path']);

// removes ".git" from name, in case user submitted the .git URL
$url['repoData'][2] = str_replace(".git", "", $url['repoData'][2]);

if ($_ENV['DEBUG']) {
    echo '<!--Repo Data: ';
    print_r($url);
    echo '-->';
}

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
      repositoryTopics(first: 20) {
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

// To-Do: input these things in the database

if ($_ENV['DEBUG']) {
    echo '<!-- RESULT:';
    print_r($result);
    echo '-->';
}
