<?php

include '../vendor/autoload.php';


$dbHost = getenv('DB_HOST') ?: 'mysql';
$dbName = getenv('DB_NAME') ?: 'php';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '123456';

$conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);


$stmt = $conn->prepare('SELECT nome FROM empresa LIMIT 0,1');
$stmt->execute();

$rec = $stmt->fetch();

$versaoPhp = explode('.', phpversion());

echo $rec['nome'] . "|" . 
     file_get_contents(__DIR__ . '/../temp/file.txt') . "|" . 
     $versaoPhp[0].$versaoPhp[1];