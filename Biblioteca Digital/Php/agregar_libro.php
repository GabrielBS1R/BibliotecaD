<?php
// Evitar que se imprima cualquier cosa antes del JSON
ob_start();
header('Content-Type: application/json');

// Incluir conexión
include 'Conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["titulo"]);
    $fecha_publicacion = trim($_POST["fecha_publicacion"]);
    $isbn = trim($_POST["isbn"]);
    $editorial_id = trim($_POST["editorial_id"]);

    if (!$conn) {
        echo json_encode(["status" => "error", "message" => "Error de conexión: " . print_r(sqlsrv_errors(), true)]);
        exit();
    }

    $sql = "INSERT INTO libros (titulo, fecha_publicacion, isbn, editorial_id) VALUES (?, ?, ?, ?)";
    $params = array($titulo, $fecha_publicacion, $isbn, $editorial_id);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Error al insertar: " . print_r(sqlsrv_errors(), true)]);
    } else {
        echo json_encode(["status" => "success", "message" => "Libro agregado exitosamente."]);
    }

    sqlsrv_close($conn);
    exit();
}
?>
