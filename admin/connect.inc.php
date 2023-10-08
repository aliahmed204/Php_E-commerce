<?php

$dsn ='mysql:host=localhost;dbname=shop';
$dbUser = 'root';
$dbPass = '';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND=> 'SET NAMES utf8'
);

try{
    $pdo = new PDO($dsn,$dbUser,$dbPass,$option);
    $pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
    die('Connection Error'.$e->getMessage());
}

