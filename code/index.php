<?
# Test database connection.
try {
    $dbh = new PDO('mysql:host=mysql;port=3306;dbname=orct2p', 'admin', '1h030PUVhZLCsM');
    $dbi = 'Database connected successfully!';
} catch (PDOException $e) {
    $dbi = 'Error!: ' . $e->getMessage();
}

echo $dbi;
?>


<!-- <pre>
<?php print_r($_GET); ?>
</pre> -->
