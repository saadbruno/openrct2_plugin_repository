<?php 

// loads envs to a friendlier array
$config = array(
    'TLD'=> $_ENV['TLD'],
    'version'=> $_ENV['VERSION'],
);

// fallbacks for the above
$config['TLD'] = $config['TLD'] ? $config['TLD'] : 'com';
$config['version'] = $config['version'] ? $config['version'] : '1.0.0';

switch ($_GET['q1']) {
    case 'value':
        # code...
        break;
    
    case 'home':
    default:
        require_once("./features/home/index.php");
        break;
}



?>

<!-- 
<?php print_r($_GET); ?>
-->
