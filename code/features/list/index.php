<?php 

// set meta information
$meta['title'] = 'Newest Plug-ins | OpenRCT2 Plug-ins Directory';
$meta['description'] = 'Get the latest plug-ins submitted to our directly of OpenRCT2 plug-ins';




switch ($_GET['sort']) {
    case 'rating':
        $nav['active'] = 'rating';
        $list = getPluginList($p, 8, 'rating');
        $list['info']['title'] = "Most starred plug-ins";
        break;
    case 'name':
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


// loads view
include_once("views/list/index.php");