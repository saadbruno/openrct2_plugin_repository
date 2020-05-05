<?php 

$user = 

// set meta information
$meta['title'] = 'OpenRCT2 Plug-ins Directory';
$meta['description'] = 'A community-driven directory for OpenRCT2 Plug-ins';

$nav['active'] = '';

$user = getUserInfo($_GET['q2']);

// gets list of plugins from database
$list = getPluginList($p, 8, 'new', 'desc', $_GET['q2']);

$list['info']['title'] = $user['username'] . "'s plug-ins";
// loads view
include_once("views/user/index.php");