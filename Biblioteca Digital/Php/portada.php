<?php
// Conexión a SQL Server
$serverName = "DESKTOP-NQ8VIDM"; 
$connectionOptions = [
    "Database" => "Biblioteca",
    "Uid" => "sa",
    "PWD" => "Mazda787B"
];

// Conectar a la base de datos
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die("Error de conexión: " . print_r(sqlsrv_errors(), true));
}

// Consulta para obtener libros con portada
$sql = "SELECT id, titulo, isbn, fecha_publicacion, portada FROM libros";
$stmt = sqlsrv_query($conn, $sql);
$libros = [];

if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $libros[] = [
            'id' => $row['id'],
            'titulo' => $row['titulo'],
            'isbn' => $row['isbn'],
            'fecha_publicacion' => $row['fecha_publicacion'],
            'portada' => base64_encode($row['portada']) // Convertir la imagen binaria a Base64
        ];
    }
}

// Liberar la consulta y cerrar conexión
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Enviar los datos en formato JSON
echo json_encode($libros);
?>

       
