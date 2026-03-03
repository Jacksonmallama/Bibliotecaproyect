<?php
header('Content-Type: application/json');
require 'connectdb.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'list') {
    $stmt = $pdo->query("SELECT * FROM libros ORDER BY ISBN ASC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'get') {
    $isbn = $_GET['isbn'];
    $stmt = $pdo->prepare("SELECT * FROM libros WHERE ISBN = ?");
    $stmt->execute([$isbn]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'insert') {
    $stmt = $pdo->prepare("INSERT INTO libros (ISBN, Name, Autor, Year_edition) VALUES (?,?,?,?)");
    $stmt->execute([
        $_POST['ISBN'],
        $_POST['Name'],
        $_POST['Autor'],
        $_POST['Year_edition']
    ]);
    echo json_encode(["status" => "created"]);
    exit;
}

if ($action === 'update') {
    $stmt = $pdo->prepare("
        UPDATE libros 
        SET ISBN = ?, Name = ?, Autor = ?, Year_edition = ?
        WHERE ISBN = ?
    ");
    $stmt->execute([
        $_POST['ISBN'],
        $_POST['Name'],
        $_POST['Autor'],
        $_POST['Year_edition'],
        $_POST['oldISBN']
    ]);
    echo json_encode(["status" => "updated"]);
    exit;
}

if ($action === 'delete') {
    $isbn = $_GET['isbn'];
    $stmt = $pdo->prepare("DELETE FROM libros WHERE ISBN = ?");
    $stmt->execute([$isbn]);
    echo json_encode(["status" => "deleted"]);
    exit;
}
?>