<?php 

// set meta information
$meta['title'] = 'Newest Plug-ins | OpenRCT2 Plug-ins Directory';
$meta['description'] = 'Get the latest plug-ins submitted to our directly of OpenRCT2 plug-ins';


if (!empty($_GET['search'])) {

    $list = searchPlugins($_GET['search']);

    $meta['title'] = 'Search results for ' . $_GET['search'] . ' | OpenRCT2 Plug-ins Directory';
    $list['info']['title'] = 'Search results for ' . $_GET['search'];

} else {

    switch ($_GET['sort']) {
        case 'rating':
            $meta['title'] = 'Most starred Plug-ins | OpenRCT2 Plug-ins Directory';
            $meta['description'] = 'Get the highest rated plug-ins submitted to our directly of OpenRCT2 plug-ins';
            $nav['active'] = 'rating';
            $list = getPluginList($p, 8, 'rating');
            $list['info']['title'] = "Most starred plug-ins";
            break;
        case 'name':
            $meta['title'] = 'Plug-ins directory | OpenRCT2 Plug-ins Directory';
            $meta['description'] = 'Plug-ins submitted to our directly, in alphabetical order';
            $nav['active'] = 'name';
            $list = getPluginList($p, 8, 'name');
            $list['info']['title'] = "Plug-ins in alphabetical order";
            break;

        case 'new':
        default:
            $nav['active'] = 'new';
            $list = getPluginList($p);
            $list['info']['title'] = "Newest plug-ins";
            break;
    }

}

// loads view
include_once("views/list/list.php");