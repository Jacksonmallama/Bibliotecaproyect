<?php
$host = "localhost";
$port = "3305";   
$db   = "biblioteca";
$user = "biblioteca_user";
$pass = "biblioteca_pass";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la BD: " . $e->getMessage());
}
?>
