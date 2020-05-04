<?php 

// loads envs to a friendlier array (with fallbacks where applicable)
$config = array(
    'TLD'=> $_ENV['TLD'],
);

$config['TLD'] = $config['TLD'] ? $config['TLD'] : 'com';

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

<!-- <pre>
<?php print_r($_GET); ?>
</pre> -->
