<?php
// Datos de conexión
$serverName = "DESKTOP-NQ8VIDM";
$connectionOptions = array(
    "Database" => "Biblioteca",
    "Uid" => "sa",
    "PWD" => "Mazda787B",
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true,  
    "Encrypt" => true                 
);

try {
    // Conectar a SQL Server
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    
    if ($conn === false) {
        throw new Exception("Error de conexión: " . formatErrors(sqlsrv_errors()));
    }
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función auxiliar para formatear errores
function formatErrors($errors) {
    if (!is_array($errors)) {
        return "Error desconocido";
    }
    $error_message = "";
    foreach ($errors as $error) {
        $error_message .= "SQLSTATE: " . $error['SQLSTATE'] . "\n";
        $error_message .= "Código: " . $error['code'] . "\n";
        $error_message .= "Mensaje: " . $error['message'] . "\n";
    }
    return $error_message;
}
?>