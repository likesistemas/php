<?php

include '../vendor/autoload.php';

$conn = new PDO('mysql:host=mysql;dbname=php', 'root', '123456');

$stmt = $conn->prepare('SELECT nome FROM empresa LIMIT 0,1');
$stmt->execute();

$rec = $stmt->fetch();

$versaoPhp = explode('.', phpversion());

echo $rec['nome'] . "|" . 
     file_get_contents(__DIR__ . '/../temp/file.txt') . "|" . 
     $versaoPhp[0].$versaoPhp[1];