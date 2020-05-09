<?php
// this script updates all plugins in the database with the latest information from github, without updating the "submitted time", so we don't change the home page order
// usage: php update_plugins.php GitHub API Token <optional: gihtub link>

// exclusive to command line
if (php_sapi_name() != 'cli') {
    throw new \Exception('This application must be run on the command line.');
    exit;
}

if(!$argv[1]) {
    die('you need to provide a github API token');
}

$token = $argv[1];
putenv("GITHUB_TOKEN=$token");

require_once "../code/lib/functions.php";
require_once "../code/lib/db/db_conn.php";
require_once "../code/lib/db/getPlugins.php";
require_once "../code/lib/db/submit.php";

print_r($_ENV);

exit();
if ($argv[2]) {

    $githubUrl = $argv[2];
    savePlugin($githubUrl, true);
    
} else {

    // build the query
    $query = "SELECT `plugins`.`id`,`plugins`.`name`,`plugins`.`owner`,`users`.`username` ";
    $query .= "FROM `plugins` ";
    $query .= "LEFT JOIN `users` ";
    $query .= "ON `plugins`.`owner` = `users`.`id`;";

    // get plugins
    $stmt_plugins = $pdo->prepare($query)->execute();

    // loops each plugin
    while ($row_plugins = $stmt_plugins->fetch()) {

        // builds the github URL
        $githubUrl = "https://github.com/" . $row_plugins['name'] . "/" . $row_plugins['username'];
        savePlugin($githubUrl, true);
    }
}
