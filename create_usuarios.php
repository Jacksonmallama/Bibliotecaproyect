<?php
header('Content-Type: application/json');
require 'connectdb.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    if ($action === 'list') {
        $stmt = $pdo->query("SELECT ID, Nombre, Email, Telefono FROM usuarios ORDER BY ID ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    if ($action === 'get') {
        $id = intval($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT ID, Nombre, Email, Telefono FROM usuarios WHERE ID = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        exit;
    }

    if ($action === 'insert') {
        $nombre = trim($_POST['Nombre'] ?? '');
        $email = trim($_POST['Email'] ?? '');
        $telefono = trim($_POST['Telefono'] ?? null);

        if ($nombre === '' || $email === '') {
            echo json_encode(['status' => 'error', 'message' => 'Nombre y Email son requeridos']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (Nombre, Email, Telefono) VALUES (?,?,?)");
        $stmt->execute([$nombre, $email, $telefono]);
        echo json_encode(['status' => 'created', 'id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($action === 'update') {
        $id = intval($_POST['ID'] ?? 0);
        $nombre = trim($_POST['Nombre'] ?? '');
        $email = trim($_POST['Email'] ?? '');
        $telefono = trim($_POST['Telefono'] ?? null);

        if ($id <= 0 || $nombre === '' || $email === '') {
            echo json_encode(['status' => 'error', 'message' => 'ID, Nombre y Email son requeridos']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE usuarios SET Nombre = ?, Email = ?, Telefono = ? WHERE ID = ?");
        $stmt->execute([$nombre, $email, $telefono, $id]);
        echo json_encode(['status' => 'updated']);
        exit;
    }

    if ($action === 'delete') {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE ID = ?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'deleted']);
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>
