<?php

// gets plugin info
$plugin = getPluginDetails($_GET['q2']);

if (empty($plugin['id'])) {
    include("views/home/404.php");
    exit;
}

// set meta information
$meta['title'] = $plugin['name'] . ' | OpenRCT2 Plug-ins Directory';
$meta['description'] = $plugin['description'];

$nav['active'] = '';

// loads the Parsedown library
require_once('lib/Parsedown.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);

// loads view
include_once("views/plugin/index.php");
