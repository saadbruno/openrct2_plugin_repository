<?php 

$nav['active'] = '';

$user = getUserInfo($_GET['q2']);

if (empty($user['username'])) {
    include("views/home/404.php");
    exit;
}

// number of list items per page, limited to 100
$resultsPerPage = isset($_GET['results']) ? intval($_GET['results']) : 8;
if ($resultsPerPage > 100) {
    $resultsPerPage = 100;
}

// gets list of plugins from database
$list = getPluginList($p, $resultsPerPage, 'new', 'desc', $_GET['q2']);


// set meta information
$meta['title'] = $user['username'] . "'s plug-ins | OpenRCT2 Plug-ins Directory";
$meta['description'] = 'OpenRCT2 plugins by ' . $user['username'];
$meta['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_ENV['DOMAIN_NAME'] . '/user/' . $user['id'] . '/' . urlencode($user['username']);

$list['info']['title'] = $user['username'] . "'s plug-ins";

// if the user requested the info as JSON
if (isset($_GET['json'])) {
    header('Content-Type: application/json');
    $json = json_encode($list);
    echo $json;
    die;
}

// loads view
include_once("views/user/user.php");