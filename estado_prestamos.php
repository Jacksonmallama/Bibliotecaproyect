<?php
header('Content-Type: application/json');
require 'connectdb.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    if ($action === 'list') {
        $stmt = $pdo->query("SELECT p.id_prestamo, p.isbn, p.id_usuario, p.fecha_prestamo, p.fecha_devolucion, p.estado, p.observaciones, u.Nombre AS usuario_nombre, l.Name AS libro_nombre
            FROM prestamos p
            JOIN usuarios u ON p.id_usuario = u.ID
            JOIN libros l ON p.isbn = l.ISBN
            ORDER BY p.id_prestamo DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    if ($action === 'get') {
        $id = intval($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM prestamos WHERE id_prestamo = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        exit;
    }

    if ($action === 'insert') {
        $stmt = $pdo->prepare("INSERT INTO prestamos (isbn, id_usuario, fecha_prestamo, fecha_devolucion, estado, observaciones) VALUES (?,?,?,?,?,?)");
        $stmt->execute([
            $_POST['isbn'],
            $_POST['id_usuario'],
            $_POST['fecha_prestamo'],
            $_POST['fecha_devolucion'] ?: null,
            $_POST['estado'],
            $_POST['observaciones'] ?? null
        ]);
        echo json_encode(["status" => "created"]);
        exit;
    }

    if ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE prestamos SET isbn = ?, id_usuario = ?, fecha_prestamo = ?, fecha_devolucion = ?, estado = ?, observaciones = ? WHERE id_prestamo = ?");
        $stmt->execute([
            $_POST['isbn'],
            $_POST['id_usuario'],
            $_POST['fecha_prestamo'],
            $_POST['fecha_devolucion'] ?: null,
            $_POST['estado'],
            $_POST['observaciones'] ?? null,
            intval($_POST['id_prestamo'] ?? 0)
        ]);
        echo json_encode(["status" => "updated"]);
        exit;
    }

    if ($action === 'delete') {
        $id = intval($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM prestamos WHERE id_prestamo = ?");
        $stmt->execute([$id]);
        echo json_encode(["status" => "deleted"]);
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
