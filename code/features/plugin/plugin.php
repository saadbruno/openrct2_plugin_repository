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
$meta['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_ENV['DOMAIN_NAME'] . '/plugin/' . $plugin['id'] . '/' . urlencode($plugin['name']);
if ($plugin['usesCustomOpenGraphImage'] == 1) {
    $meta['image'] = $plugin['thumbnail'];
}

$nav['active'] = '';

// loads the Parsedown library
require_once('lib/Parsedown.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);

// loads view
include_once("views/plugin/plugin.php");
