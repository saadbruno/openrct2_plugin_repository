<?php

// gets plugin info
$plugin = getPluginDetails($_GET['q2']);

// if the user requested the info as JSON
if (isset($_GET['json'])) {
    header('Content-Type: application/json');
    $json = json_encode($plugin);
    echo $json;
    die;
}

if (empty($plugin['id'])) {
    include("views/home/404.php");
    exit;
}
// set meta information
$meta['title'] = $plugin['name'] . ' | OpenRCT2 Plug-ins Directory';
$meta['description'] = $plugin['description'];
$meta['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_ENV['DOMAIN_NAME'] . '/plugin/' . $plugin['id'] . '/' . urlencode($plugin['name']);
if ($plugin['usesCustomOpenGraphImage'] == 1) {
    $meta['image'] = $plugin['thumbnail'];
}

$nav['active'] = '';

// let's load the HTML purifier to prevent XSS and other stuff
require_once 'lib/htmlpurifier-4.13.0/library/HTMLPurifier.auto.php';
$hp_config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($hp_config);

// loads the Parsedown library
require_once('lib/Parsedown.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(false);

// loads view
include_once("views/plugin/plugin.php");
