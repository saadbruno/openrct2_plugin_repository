<?php
// this script updates all plugins in the database with the latest information from github, without updating the "submitted time", so we don't change the home page order
// usage: php update_plugins.php <optional: gihtub link>

// exclusive to command line
if (php_sapi_name() != 'cli') {
    throw new \Exception('This application must be run on the command line.');
    exit;
}

// changes directory to current script, so the relative paths work, independently of the user's current directory
chdir(dirname(__FILE__));

require_once "../lib/functions.php";
require_once "../lib/db/db_conn.php";
require_once "../lib/db/getPlugins.php";
require_once "../lib/db/submit.php";


if ($argv[1]) { // if the user provided a specific github URL to update

    $githubUrl = $argv[1];
    savePlugin($githubUrl, true);
    
} else { // else we run for all plugins

    // build the query
    $query = "SELECT `plugins`.`id`,`plugins`.`name`,`plugins`.`owner`,`users`.`username` ";
    $query .= "FROM `plugins` ";
    $query .= "LEFT JOIN `users` ";
    $query .= "ON `plugins`.`owner` = `users`.`id`;";

    // get plugins
    $stmt_plugins = $pdo->prepare($query); 
    $stmt_plugins->execute();
    $plugins_array = $stmt_plugins->fetchAll();

    foreach ($plugins_array as $row_plugins) {
        // builds the github URL
        $githubUrl = "https://github.com/" . $row_plugins['username'] . "/" . $row_plugins['name'];
        savePlugin($githubUrl, true);
    }
}
