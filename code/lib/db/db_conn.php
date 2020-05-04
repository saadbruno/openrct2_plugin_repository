<?php

$db_host = 'mysql';
$db_port = '3306';
$db_name = $_ENV['MYSQL_DATABASE'];
$db_user = $_ENV['MYSQL_USER'];
$db_pass = $_ENV['MYSQL_PASSWORD'];

$db_charset = 'utf8mb4';

$dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=$db_charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $opt);
    $db_status = 'Database Connected Successfully!';
} catch (PDOException $e) {
    $db_status = 'Error connecting to the database: ' . $e->getMessage() . " ¯\_(ツ)_/¯";
}
