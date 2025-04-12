<?php
require 'Conexion.php';

$sql = "SELECT id, titulo, fecha_publicacion, isbn, editorial_id, categoria_id FROM libros";
$result = sqlsrv_query($conn, $sql);

if ($result === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}
?>
<style>
        .tabla-libros {
    width: 100%;
    overflow-x: auto;
    margin-top: 20px;
    padding: 1px; /* Para evitar que el box-shadow se corte */
}

.tabla-libros table {
    width: 100%;
    border-collapse: collapse;
    background-color: #1a1a1a;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.tabla-libros th,
.tabla-libros td {
    padding: 12px 16px;
    border: 1px solid #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Anchos de columnas */
.tabla-libros th:nth-child(1),
.tabla-libros td:nth-child(1) {
    width: 8%;
    text-align: center;
}

.tabla-libros th:nth-child(2),
.tabla-libros td:nth-child(2) {
    width: 30%;
    text-align: left;
}

.tabla-libros th:nth-child(3),
.tabla-libros td:nth-child(3) {
    width: 20%;
    text-align: center;
}

.tabla-libros th:nth-child(4),
.tabla-libros td:nth-child(4) {
    width: 20%;
    text-align: center;
}

.tabla-libros th:nth-child(5),
.tabla-libros td:nth-child(5) {
    width: 120px; /* Ancho fijo para la columna de acciones */
    min-width: 120px; /* Asegura un ancho mínimo */
    padding: 8px;
}

.tabla-libros th {
    background-color: #2c2c2c;
    color: #ffffff;
    font-weight: 500;
    font-size: 14px;
    border-bottom: 2px solid #444;
}

.tabla-libros td {
    font-size: 14px;
    color: #ffffff;
    line-height: 1.4;
    background-color: #1a1a1a;
}

.tabla-libros tr:hover td {
    background-color: #2c2c2c;
}

.acciones {
    display: flex;
    flex-direction: column;
    gap: 6px;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.acciones form {
    margin: 0;
    width: 100%;
}

.btn-editar,
.btn-eliminar {
    display: block;
    width: 100%;
    padding: 6px 8px;
    font-size: 13px;
    font-weight: normal;
    border-radius: 3px;
    cursor: pointer;
    border: none;
    text-align: center;
    white-space: nowrap; /* Evita que el texto se rompa */
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-editar {
    background-color: #4CAF50;
    color: white;
    transition: all 0.2s ease;
}

.btn-eliminar {
    background-color: #dc3545;
    color: white;
    transition: all 0.2s ease;
}

.btn-editar:hover {
    background-color: #45a049;
    transform: scale(1.05);
}

.btn-eliminar:hover {
    background-color: #c82333;
    transform: scale(1.05);
}

/* Ajuste responsive */
@media screen and (max-width: 768px) {
    .tabla-libros th:nth-child(5),
    .tabla-libros td:nth-child(5) {
        width: 100px;
        min-width: 100px;
    }
    
    .btn-editar,
    .btn-eliminar {
        font-size: 12px;
        padding: 5px 6px;
    }
}
    </style>
<div class="tabla-libros">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Fecha de Publicación</th>
                <th>ISBN</th>
                <th>Editorial</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                    <td><?php 
                        // Formatear la fecha
                        $fecha = $row['fecha_publicacion'];
                        if ($fecha instanceof DateTime) {
                            echo $fecha->format('d/m/Y');
                        } else {
                            echo date('d/m/Y', strtotime($fecha));
                        }
                    ?></td>
                    <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                    <td><?php echo htmlspecialchars($row['editorial_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['categoria_id']); ?></td>
                    <td class="acciones">
    <form method="POST" action="../Php/actualizar_libro.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <button type="submit" class="btn-editar">Actualizar</button>
    </form>
    <form method="POST" action="../Php/eliminar_libro.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <button type="submit" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este libro?')">Eliminar</button>
    </form>
</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
sqlsrv_close($conn);
?>
