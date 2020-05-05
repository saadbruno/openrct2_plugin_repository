<?php 

// set meta information
$meta['title'] = 'OpenRCT2 Plug-ins Directory';
$meta['description'] = 'A community-driven directory for OpenRCT2 Plug-ins';

$nav['active'] = 'home';

// gets list of plugins from database
$list_new = getPluginList($p, 3, 'new');
$list_rating = getPluginList($p, 3, 'rating');

// loads view
include_once("views/home/index.php");