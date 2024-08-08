<?php 

// set meta information
$meta['title'] = 'Newest Plug-ins | OpenRCT2 Plug-ins Directory';
$meta['description'] = 'Get the latest plug-ins submitted to our directly of OpenRCT2 plug-ins';

// number of list items per page, limited to 100
$resultsPerPage = isset($_GET['results']) ? intval($_GET['results']) : 8;
if ($resultsPerPage > 100) {
    $resultsPerPage = 100;
}

if (!empty($_GET['search'])) {

    $list = searchPlugins($_GET['search']);

    $meta['title'] = 'Search results for ' . $_GET['search'] . ' | OpenRCT2 Plug-ins Directory';
    $list['info']['title'] = 'Search results for ' . $_GET['search'];

} else {

    switch ($_GET['sort']) {
        case 'rating':
            $meta['title'] = 'Most starred Plug-ins | OpenRCT2 Plug-ins Directory';
            $meta['description'] = 'Get the highest rated plug-ins submitted to our directory of OpenRCT2 plug-ins';
            $nav['active'] = 'rating';
            $list = getPluginList($p, $resultsPerPage, 'rating');
            $list['info']['title'] = "Most starred plug-ins";
            break;
        case 'name':
            $meta['title'] = 'Plug-ins directory | OpenRCT2 Plug-ins Directory';
            $meta['description'] = 'Plug-ins submitted to our directory, in alphabetical order';
            $nav['active'] = 'name';
            $list = getPluginList($p, $resultsPerPage, 'name');
            $list['info']['title'] = "Plug-ins in alphabetical order";
            break;
        case 'updated':
            $meta['title'] = 'Recently updated plug-ins | OpenRCT2 Plug-ins Directory';
            $meta['description'] = 'Get the most recently updated plug-ins submitted to our directory of OpenRCT2 plug-ins';
            $nav['active'] = 'updated';
            $list = getPluginList($p, $resultsPerPage, 'updated');
            $list['info']['title'] = "Recently updated plug-ins";
            break;
        case 'new':
        default:
            $nav['active'] = 'new';
            $list = getPluginList($p, $resultsPerPage);
            $list['info']['title'] = "Newest plug-ins";
            break;
    }

}

// if the user requested the info as JSON
if (isset($_GET['json'])) {
    header('Content-Type: application/json');
    $json = json_encode($list);
    echo $json;
    die;
}

// loads view
include_once("views/list/list.php");