<?php 

// set meta information
$meta['title'] = 'Newest Plug-ins | OpenRCT2 Plug-ins Directory';
$meta['description'] = 'Get the latest plug-ins submitted to our directly of OpenRCT2 plug-ins';

$nav['active'] = 'new';

// gets list of plugins from database
$list = getPluginList($p);

// loads view
include_once("views/list/index.php");