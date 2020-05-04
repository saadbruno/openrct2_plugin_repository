<?php 
session_start();

require_once "lib/functions.php";

// loads envs to a friendlier array
$config = array(
    'TLD'=> $_ENV['TLD'],
    'version'=> $_ENV['VERSION'],
);

// fallbacks for the above
$config['TLD'] = $config['TLD'] ? $config['TLD'] : 'com';
$config['version'] = $config['version'] ? $config['version'] : '1.0.0';

switch ($_GET['q1']) {
    case 'submit':
        # code...
        break;
    
    case 'home':
    default:

        if (isset($_POST['githubUrl'])) {
            include_once "./features/home/submit.php";
        }

        require_once("./features/home/index.php");

        break;
}



?>

<!-- 
<?php print_r($_GET); ?>
-->
