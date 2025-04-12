<?php
require_once 'Conexion.php';

// Verificar si se ha recibido una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id !== null && is_numeric($id)) {
        
        $sql = "DELETE FROM libros WHERE id = ?";
        $params = array($id);
        $stmt = sqlsrv_query($conn, $sql, $params);

        $success = $stmt ? true : false;
        $message = $stmt ? 'Libro eliminado correctamente' : 'Error al eliminar el libro';
    } else {
        $success = false;
        $message = 'ID no válido';
    }
} else {
    $success = false;
    $message = 'Método no permitido';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Libro</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Space Mono', monospace;
            background-color: #121212;
            color: #e0e0e0;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .message {
            background-color: #1a1a1a;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin-top: 1rem;
            animation: fadeIn 0.5s ease-in-out;
        }
        .checkmark {
            font-size: 2rem;
            color: #1a73e8;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        .btn {
            margin-top: 1rem;
            display: inline-block;
            background-color: #1a73e8;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background 0.3s;
        }
        .btn:hover {
            background-color: #1558b0;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="message">
        <?php if ($success): ?>
            <div class="checkmark">✔</div>
        <?php endif; ?>
        <p><?php echo $message; ?></p>
        <a href="../Index-HTML/Index.php" class="btn">Volver al Inicio</a>
    </div>
</body>
</html>
