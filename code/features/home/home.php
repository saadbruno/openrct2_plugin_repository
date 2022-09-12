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

// loads view
include_once("views/home/home.php");
