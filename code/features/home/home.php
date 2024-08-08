<?php

// if a plugin was submitted
if (isset($_POST['githubUrl'])) {
    $githubUrl = filter_input(INPUT_POST, 'githubUrl', FILTER_SANITIZE_URL);
    die(savePlugin($githubUrl));
}

$nav['active'] = 'home';

// gets list of plugins from database
$list_new = getPluginList($p, 6, 'new');
$list_rating = getPluginList($p, 6, 'rating');

// if the user requested the info as JSON
if (isset($_GET['json'])) {
    header('Content-Type: application/json');
    $list = array( "new" => $list_new, "rating" => $list_rating); // merge the new and rating lists
    $json = json_encode($list);
    echo $json;
    die;
}
// loads view
include_once("views/home/home.php");
