<?php
include '../Php/Conexion.php'; // Incluye la conexión a SQL Server

// Agregar categoría
if (isset($_POST['agregar_categoria'])) {
    $nombre = $_POST['nombre_categoria'];
    $sql = "INSERT INTO categorias (nombre) VALUES (?)";
    $params = array($nombre);
    $stmt = sqlsrv_query($conn, $sql, $params);
}

// Eliminar categoría
if (isset($_GET['eliminar'])) {
    $id_categoria = intval($_GET['eliminar']);
    $sql = "DELETE FROM categorias WHERE id = ?";
    $params = array($id_categoria);
    $stmt = sqlsrv_query($conn, $sql, $params);
}

// Filtrar categorías
$filtrar = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$sql = "SELECT * FROM categorias WHERE nombre LIKE ?";
$params = array("%$filtrar%");
$stmt = sqlsrv_query($conn, $sql, $params);

$categorias = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $categorias[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <style>
        /* Estilos generales y variables */
        :root {
            --primary-color: #3b82f6;          /* Azul principal */
            --secondary-color: #1e1e1e;        /* Negro suave para fondos */
            --accent-color: rgb(207, 2, 36);   /* Naranja para acentos */
            --success-color: #22c55e;          /* Verde para éxito */
            --warning-color: #ef4444;          /* Rojo para advertencias */
            --bg-color: #111111;               /* Negro para fondo principal */
            --card-bg: #1a1a1a;                /* Negro suave para tarjetas */
            --text-color: #f3f4f6;             /* Texto claro */
            --border-color: #333333;           /* Bordes oscuros */
            --border-radius: 16px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Space Mono', monospace;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        /* Contenedor principal */
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Estilos del título */
        h1 {
            color: var(--primary-color);
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Formulario */
        .form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        .form label {
            color: var(--text-color);
            font-size: 1.1rem;
        }

        .form input {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            color: var(--text-color);
        }

        .form input:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(72, 149, 239, 0.2);
        }

        .form button {
            padding: 10px 15px;
            border: none;
            background-color: var(--primary-color);
            color: white;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form button:hover {
            background-color: var(--secondary-color);
        }

        /* Sidebar de categorías */
        .sidebar {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            width: 250px;
        }

        .sidebar h2 {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-color);
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar li a.delete {
            color: var(--warning-color);
            font-size: 1.2rem;
            text-decoration: none;
        }

        .sidebar li a.delete:hover {
            color: var(--success-color);
        }

        /* Contenido principal */
        .main {
            flex: 1;
            margin-left: 20px;
        }

        .main h2 {
            color: var(--primary-color);
        }

        .content {
            display: flex;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Categorías</h1>

        <!-- Formulario para agregar una categoría -->
        <form method="POST" action="categorias.php" class="form">
            <label for="nombre_categoria">Nueva categoría:</label>
            <input type="text" name="nombre_categoria" id="nombre_categoria" required>
            <button type="submit" name="agregar_categoria">Agregar</button>
        </form>

        <hr>

        <!-- Filtro por categoría -->
        <form method="GET" action="categorias.php" class="form">
            <label for="categoria">Filtrar:</label>
            <input type="text" name="categoria" id="categoria" value="<?php echo htmlspecialchars($filtrar); ?>">
            <button type="submit">Buscar</button>
        </form>

        <div class="content">
            <!-- Sidebar de categorías -->
            <div class="sidebar">
                <h2>Categorías</h2>
                <ul>
                    <?php foreach ($categorias as $categoria): ?>
                        <li>
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                            <a href="categorias.php?eliminar=<?php echo $categoria['id']; ?>" class="delete" onclick="return confirm('¿Eliminar esta categoría?')">🗑️</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Contenido principal -->
            <div class="main">
                <h2>Libros en esta categoría</h2>
                <?php
                if ($filtrar) {
                    echo "<p>Filtrando por: " . htmlspecialchars($filtrar) . "</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
