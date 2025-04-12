<?php
include 'Conexion.php';

// Obtener datos del formulario
$titulo = $_POST['titulo'];
$fecha_publicacion = $_POST['fecha_publicacion'];
$isbn = $_POST['isbn'];

// Verificar si el ISBN ya existe en la base de datos
$sql = "SELECT COUNT(*) FROM libros WHERE isbn = ?";
$params = array($isbn);
$stmt = sqlsrv_query($conn, $sql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row[''] > 0) {
    echo json_encode(['status' => 'exists', 'message' => 'El ISBN ya está registrado.']);
    exit;  // Detener la ejecución si el ISBN ya está registrado
}

// Insertar el nuevo libro en la base de datos
$sql = "INSERT INTO libros (titulo, fecha_publicacion, isbn) VALUES (?, ?, ?)";
$params = array($titulo, $fecha_publicacion, $isbn);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Responder con éxito
echo json_encode(['status' => 'success', 'message' => 'Libro agregado exitosamente.']);
?>
