<?php 

// set meta information
$meta['title'] = 'OpenRCT2 Plug-ins Directory';
$meta['description'] = 'A community-driven directory for OpenRCT2 Plug-ins';

// gets list of plugins from database
$list = getPluginList($p);

// loads view
include_once("views/home/index.php");