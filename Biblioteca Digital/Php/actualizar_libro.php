<?php
include 'Conexion.php';

// Variable para almacenar el mensaje de actualización
$mensaje = "";

// Verificar si se ha enviado el formulario para actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_libro'])) {
    $id = $_POST['id_libro'];
    $titulo = $_POST['titulo'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $isbn = $_POST['isbn'];

    // Actualizar el libro en la base de datos
    $sql = "UPDATE libros SET titulo = ?, fecha_publicacion = ?, isbn = ? WHERE id = ?";
    $params = array($titulo, $fecha_publicacion, $isbn, $id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $mensaje = "Libro actualizado exitosamente.";
}

// Verificar si hay un libro para editar
$libro = null;
if (isset($_GET['id'])) {
    $id_libro = $_GET['id'];

    // Obtener datos del libro
    $sql = "SELECT * FROM libros WHERE id = ?";
    $params = array($id_libro);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $libro = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

// Obtener la lista de libros
$sql = "SELECT * FROM libros";
$stmt = sqlsrv_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilos generales de la página */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
            color: #333;
        }

        /* Estilos de la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            font-size: 1rem;
            border: 1px solid #ddd;
        }

        th {
            background-color: #555;
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #fff;
        }

        td button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        td button:hover {
            background-color: #45a049;
        }

        /* Estilos para el formulario */
        form {
            background-color: rgb(3, 3, 3);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin: 2rem auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        form input[type="text"],
        form input[type="number"],
        form button {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            color: #ffffff;
            background-color: #333333;
            border: 1px solid #444;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
        }

        form input[type="text"]:focus,
        form input[type="number"]:focus {
            border-color: #555;
            box-shadow: 0 0 8px rgba(85, 85, 85, 0.5);
        }

        form button {
            background-color: #555;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #666;
        }

        form input::placeholder {
            color: #888;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            overflow: auto;
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
        }

        /* Modal close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<a href="../Index-HTML/Index.php" style="display: block; width: 200px; margin: 20px auto; text-align: center; padding: 10px; background-color: #555; color: white; text-decoration: none; border-radius: 8px;">Volver a Inicio</a>

<h2>Lista de Libros</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Fecha de Publicación</th>
        <th>ISBN</th>
        <th>Acción</th>
    </tr>
    <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) : ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['titulo']; ?></td>
            <td><?php echo $row['fecha_publicacion']->format('Y-m-d'); ?></td>
            <td><?php echo $row['isbn']; ?></td>
            <td>
                <button class="btn-editar" onclick="openModal(<?php echo $row['id']; ?>)">Editar</button>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- Modal para editar -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        
        <?php if ($libro) : ?>
            <h2>Editar Libro</h2>

            <?php if (!empty($mensaje)) : ?>
                <p style="color: green;"><?php echo $mensaje; ?></p>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="id_libro" id="id_libro" value="<?php echo $libro['id']; ?>">

                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo $libro['titulo']; ?>" required><br>

                <label for="fecha_publicacion">Fecha de Publicación:</label>
                <input type="date" id="fecha_publicacion" name="fecha_publicacion" 
                       value="<?php echo $libro['fecha_publicacion']->format('Y-m-d'); ?>" required><br>

                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn" value="<?php echo $libro['isbn']; ?>" required><br>

                <button type="submit">Actualizar</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
    // Función para abrir el modal
    function openModal(id) {
        const modal = document.getElementById("myModal");
        modal.style.display = "block";
        document.getElementById("id_libro").value = id;
    }

    // Función para cerrar el modal
    function closeModal() {
        const modal = document.getElementById("myModal");
        modal.style.display = "none";
    }

    // Cerrar el modal si se hace clic fuera del contenido
    window.onclick = function(event) {
        const modal = document.getElementById("myModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
