<?php 

$nav['active'] = '';

$user = getUserInfo($_GET['q2']);

if (empty($user['username'])) {
    include("views/home/404.php");
    exit;
}

// gets list of plugins from database
$list = getPluginList($p, 8, 'new', 'desc', $_GET['q2']);


// set meta information
$meta['title'] = $user['username'] . "'s plug-ins | OpenRCT2 Plug-ins Directory";
$meta['description'] = 'OpenRCT2 plugins by ' . $user['username'];

$list['info']['title'] = $user['username'] . "'s plug-ins";
// loads view
include_once("views/user/index.php");