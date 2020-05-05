<?php
session_start();

require_once "lib/functions.php";
require_once "lib/view_functions.php";
require_once "lib/db/db_conn.php";
require_once "lib/db/getPlugins.php";
require_once "lib/db/getUser.php";


// loads envs to a friendlier array
$config = array(
    'TLD' => $_ENV['TLD'],
    'version' => $_ENV['VERSION'],
);

// fallbacks for the above
$config['TLD'] = $config['TLD'] ? $config['TLD'] : 'com';
$config['version'] = $config['version'] ? $config['version'] : '1.0.0';

// Global Meta tags that can be overriden
$meta['title'] = 'OpenRCT2 Plug-ins Directory';
$meta['description'] = 'A community-driven directory for OpenRCT2 Plug-ins';
$meta['image'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_ENV['DOMAIN_NAME'] . '/public/media/img/openrct2-plugins-thumbnail.jpg';
$meta['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_ENV['DOMAIN_NAME'] . $_SERVER['REQUEST_URI'];

// GET variables
$p = preg_replace('/[^0-9]/', '', filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
$p = $p ? $p : 1;

switch ($_GET['q1']) {
    case 'list':
        require_once("./features/list/list.php");
        break;

    case 'user':
        require_once("./features/user/user.php");
        break;

    case 'plugin':
        require_once("./features/plugin/plugin.php");
        break;

    case 'home':
    default:

        require_once("./features/home/home.php");

        break;
}



?>

<!-- 
<?php
if ($_ENV['DEBUG']) {
    echo "DB status: " . $db_status;
    echo "\n\n_GET: \n";
    print_r($_GET);
    echo "\n\n_POST: \n";
    print_r($_POST);
    echo "\n\n_COOKIE: \n";
    print_r($_COOKIE);
    echo "\n\n_ENV: \n";
    print_r($_ENV);
}
?>
-->