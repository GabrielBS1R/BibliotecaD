<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Metadatos básicos -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../IMG/favicon.ico">
    <title>Biblioteca Digital</title>

    <!-- Hojas de estilo CSS -->
    <link rel="stylesheet" href="../CSS/BibliotecaDigital.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Fuentes tipográficas -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- HEADER - ENCABEZADO DE LA PÁGINA -->
    <header>
        <div class="logo">
            <img src="../IMG/Logo sin fondo.webp" alt="Logo Biblioteca Digital" style="height: 55px; width: auto;">
            <div class="h1">
                <h1>Biblioteca Digital</h1>
            </div>
        </div>
        <div class="carrito">
            <a href="Carrito.html" title="Ver carrito">
                <i class="fas fa-shopping-cart" style="font-size: 24px;"></i>
            </a>
        </div>
    </header>

    <!-- MAIN - CONTENIDO PRINCIPAL -->
    <main>
        <div class="content-wrapper">
            <!-- SIDEBAR - BARRA LATERAL -->
            <aside class="sidebar">
                <h3>Categorías</h3>
                <ul>
                    <li><a href="Ficcion.html">Ficción</a></li>
                    <li><a href="NoFiccion.html">No Ficción</a></li>
                    <li><a href="Ciencia.html">Ciencia</a></li>
                    <li><a href="Historia.html">Historia</a></li>
                    <li><a href="Arte.html">Arte</a></li>
                    <li><a href="Filosofia.html">Filosofía</a></li>
                    <li><a href="Psicologia.html">Psicología</a></li>
                    <li><a href="Economia.html">Economía</a></li>
                    <li><a href="Biografias.html">Biografías</a></li>
                    <li><a href="Fantasia.html">Fantasía</a></li>
                </ul>
            </aside>

            <!-- SECCIÓN DE LIBROS -->
            <section id="lista-libros">
                <h2>Libros Disponibles</h2>
                <div>
                    <button id="toggleForm" class="btn">
                        <i class="fas fa-plus-circle"></i> Agregar Libro
                    </button>
                    <div id="formContainer" style="display: none;">
                        <form id="formLibro" method="POST" action="../Php/agregar_libro.php">
                            <input type="text" name="titulo" placeholder="Título" required>
                            <input type="date" name="fecha_publicacion" placeholder="Fecha de publicación" required>
                            <input type="text" name="isbn" placeholder="ISBN" required>
                            <input type="text" name="editorial" placeholder="Editorial" required>
                            <button type="submit" class="btn">
                                <i class="fas fa-save"></i> Guardar Libro
                            </button>
                        </form>
                    </div>
                </div>

                <div id="libros-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Portada</th>
                                <th>Título</th>
                                <th>Detalles</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            include '../Php/mostrar_libro.php';
                            
                            if (isset($libros) && is_array($libros)) {
                                foreach($libros as $libro) {
                                    echo '<tr id="libro-' . $libro['id'] . '">';
                                    echo '<td><img src="data:image/webp;base64,' . base64_encode($libro['portada']) . '" alt="Portada de ' . $libro['titulo'] . '" width="80" style="border-radius: 8px;"></td>';
                                    echo '<td><strong>' . $libro['titulo'] . '</strong></td>';
                                    echo '<td>';
                                    echo '<p>ISBN: ' . $libro['isbn'] . '</p>';
                                    echo '<p>Fecha: ' . $libro['fecha_publicacion']->format('Y-m-d') . '</p>';
                                    echo '<p>Editorial: ' . $libro['editorial'] . '</p>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<div style="display: flex; gap: 10px; margin-bottom: 10px;">';
                                    echo '<a href="../Index-HTML/Carrito.html" class="btn-agregar">';
                                    echo '<i class="fas fa-shopping-cart"></i> Agregar</a>';
                                    echo '</div>';
                                    echo '<a href="../Index-HTML/Carrito.html" class="btn-volver">';
                                    echo '<i class="fas fa-arrow-left"></i> Volver al carrito</a><br><br>';
                                    echo '<a href="../Index-HTML/Actualizar.php?id=' . $libro['id'] . '" class="btn-editar" style="background-color: black;">';
                                    echo '<i class="fas fa-edit"></i> Editar</a><br><br>';
                                    echo '<button onclick="eliminarLibro(this)" data-id="' . $libro['id'] . '" class="btn-eliminar" style="background-color: black;">';
                                    echo '<i class="fas fa-trash"></i> Eliminar</button>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4"></td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>

    <!-- FOOTER - PIE DE PÁGINA -->
    <footer>
        <p>&copy; 2024 Biblioteca Digital. Todos los derechos reservados.</p>
    </footer>

    <!-- SCRIPTS - JAVASCRIPT -->
    <script src="../JAVASCRIPT/script_libros.js"></script>
    <script src="../JAVASCRIPT/Carrito.js"></script>
</body>
</html>