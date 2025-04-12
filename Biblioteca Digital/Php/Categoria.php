<?php
include 'Conexion.php';

// Agregar categoría
if (isset($_POST['agregar_categoria'])) {
    $nombre = $_POST['nombre_categoria'];
    $stmt = $pdo->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
    $stmt->execute(['nombre' => $nombre]);
}

// Eliminar categoría
if (isset($_GET['eliminar'])) {
    $id_categoria = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = :id");
    $stmt->execute(['id' => $id_categoria]);
}

// Filtrar categorías
$filtrar = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$sql = "SELECT * FROM categorias WHERE nombre LIKE :filtro";
$stmt = $pdo->prepare($sql);
$stmt->execute(['filtro' => '%' . $filtrar . '%']);
$categorias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
</head>
<body>
    <h1>Categorías</h1>

    <!-- Formulario para agregar una categoría -->
    <form method="POST" action="categorias.php">
        <label for="nombre_categoria">Nueva categoría:</label>
        <input type="text" name="nombre_categoria" id="nombre_categoria" required>
        <button type="submit" name="agregar_categoria">Agregar Categoría</button>
    </form>

    <hr>

    <!-- Filtro por categoría -->
    <form method="GET" action="categorias.php">
        <label for="categoria">Filtrar por categoría:</label>
        <input type="text" name="categoria" id="categoria" value="<?php echo htmlspecialchars($filtrar); ?>">
        <button type="submit">Filtrar</button>
    </form>

    <hr>

    <!-- Sidebar de categorías -->
    <div style="float: left; width: 20%; padding-right: 20px;">
        <h2>Categorías</h2>
        <ul>
            <?php foreach ($categorias as $categoria): ?>
                <li>
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                    <a href="categorias.php?eliminar=<?php echo $categoria['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?')">Eliminar</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Mostrar las categorías filtradas -->
    <div style="float: left; width: 75%;">
        <h2>Libros en esta categoría</h2>
        <?php
        // Aquí agregas tu consulta de libros filtrados por categoría, si deseas
        // Este es un ejemplo simple de cómo mostrar los resultados
        if ($filtrar) {
            echo "<p>Filtrando por: " . htmlspecialchars($filtrar) . "</p>";
            // Agregar consulta para libros con la categoría seleccionada
        }
        ?>
    </div>
</body>
</html>
