<?php 
session_start();

// set meta informatio
$meta['title'] = 'OpenRCT2 Plug-ins Directory';
$meta['description'] = 'A community-driven directory for OpenRCT2 Plug-ins';



// loads view
include_once("views/home/index.php");