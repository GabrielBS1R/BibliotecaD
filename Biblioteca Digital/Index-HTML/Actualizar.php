<?php
include 'Conexion.php';

// Si se envía el formulario de actualización (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanitizar los datos del libro a actualizar
    $id = $_POST['id_libro'];
    $titulo = $_POST['titulo'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $isbn = $_POST['isbn'];

    // Consulta parametrizada para actualizar el libro
    $sql = "UPDATE libros SET titulo = ?, fecha_publicacion = ?, isbn = ? WHERE id = ?";
    $params = array($titulo, $fecha_publicacion, $isbn, $id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Redirigir al mismo script para evitar reenvío de formulario y limpiar la URL
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Obtener el ID del libro a editar (modo inline)
$editId = isset($_GET['edit']) ? $_GET['edit'] : null;

// Consulta para obtener todos los libros
$sql = "SELECT id, titulo, fecha_publicacion, isbn FROM libros";
$result = sqlsrv_query($conn, $sql);
if ($result === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Digital - Inline Editing</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #333;
            text-align: center;
        }
        input[type="text"],
        input[type="date"] {
            width: 90%;
            padding: 5px;
        }
        .btn {
            padding: 6px 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #45a049;
        }
        a.cancelar {
            margin-left: 5px;
            color: #333;
            text-decoration: none;
        }
        /* Estilos para el modal */
        .modal {
            display: none; /* Oculto por defecto */
            position: fixed; /* Fijo en la pantalla */
            z-index: 1; /* Por encima de otros elementos */
            left: 0;
            top: 0;
            width: 100%; /* Ancho completo */
            height: 100%; /* Alto completo */
            overflow: auto; /* Habilitar desplazamiento si es necesario */
            background-color: rgb(0,0,0); /* Color de fondo */
            background-color: rgba(0,0,0,0.4); /* Fondo negro con opacidad */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* Margen superior */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Ancho del modal */
        }
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
    <h2>Lista de Libros</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Fecha de Publicación</th>
                <th>ISBN</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <script>
                function toggleEditForm(row) {
                    const form = row.nextElementSibling; // Selecciona la siguiente fila que contendrá el formulario
                    const isVisible = form.style.display === 'table-row';
                    form.style.display = isVisible ? 'none' : 'table-row';
                }
            </script>

            <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                    <td>
                        <?php 
                            if ($row['fecha_publicacion'] instanceof DateTime) {
                                echo $row['fecha_publicacion']->format('Y-m-d');
                            } else {
                                echo date('Y-m-d', strtotime($row['fecha_publicacion']));
                            }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                    <td>
                        <!-- Al hacer clic se activa la función para mostrar el formulario -->
                        <a href="javascript:void(0);" onclick="toggleEditForm(this.closest('tr'))" class="btn">Editar</a>
                    </td>
                </tr>
                <tr style="display: none;">
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <td>
                            <input type="hidden" name="id_libro" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <?php echo htmlspecialchars($row['id']); ?>
                        </td>
                        <td>
                            <input type="text" name="titulo" value="<?php echo htmlspecialchars($row['titulo']); ?>" required>
                        </td>
                        <td>
                            <input type="date" name="fecha_publicacion" value="<?php echo date('Y-m-d', strtotime($row['fecha_publicacion'])); ?>" required>
                        </td>
                        <td>
                            <input type="text" name="isbn" value="<?php echo htmlspecialchars($row['isbn']); ?>" required>
                        </td>
                        <td>
                            <button type="submit" class="btn">Guardar</button>
                            <a href="javascript:void(0);" onclick="toggleEditForm(this.closest('tr').previousElementSibling)" class="cancelar">Cancelar</a>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
