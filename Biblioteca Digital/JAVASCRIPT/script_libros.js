document.addEventListener('DOMContentLoaded', () => {
    const toggleFormBtn = document.getElementById('toggleForm');
    const formContainer = document.getElementById('formContainer');
    const formLibro = document.getElementById('formLibro');
    const guardarLibroBtn = document.getElementById('guardarLibro');
    const listaLibros = document.getElementById('listaLibros');

    const crearAlerta = (tipo, mensaje) => {
        const alerta = document.createElement('div');
        alerta.className = `alerta ${tipo}`;
        alerta.innerHTML = `
            <div class="alerta-contenido">
                <span>${mensaje}</span>
                <button class="alerta-cerrar">&times;</button>
            </div>
        `;
        document.body.appendChild(alerta);

        alerta.querySelector('.alerta-cerrar').onclick = () => alerta.remove();
        setTimeout(() => alerta.remove(), 5000);
    };

    const toggleFormulario = () => {
        if (formContainer) {
            formContainer.style.display = formContainer.style.display === 'block' ? 'none' : 'block';
        }
    };

    const validarFormulario = (formData) => {
        const camposObligatorios = ['titulo', 'isbn', 'fecha_publicacion', 'editorial_id'];
        return camposObligatorios
            .filter(campo => !formData.get(campo)?.trim())
            .map(campo => `El campo "${campo}" es obligatorio`);
    };

    const actualizarListaLibros = async () => {
        if (!listaLibros) return;

        try {
            const response = await fetch('../Php/mostrar_libro.php');
            const data = await response.json();

            listaLibros.innerHTML = '';

            if (!Array.isArray(data)) {
                crearAlerta('error', 'Formato de datos incorrecto');
                return;
            }

            if (data.length === 0) {
                listaLibros.innerHTML = '<p>No hay libros registrados actualmente.</p>';
                return;
            }

            data.forEach(({ titulo, autor, genero }) => {
                const libro = document.createElement('div');
                libro.className = 'libro-item';
                libro.innerHTML = `
                    <h3>${titulo}</h3>
                    <p><strong>Autor:</strong> ${autor}</p>
                    <p><strong>Género:</strong> ${genero}</p>
                `;
                listaLibros.appendChild(libro);
            });
        } catch (error) {
            console.error('Error al obtener libros:', error);
            crearAlerta('error', 'No se pudieron cargar los libros');
        }
    };

    const enviarFormulario = async (event) => {
        event.preventDefault();
        const formData = new FormData(formLibro);
        const errores = validarFormulario(formData);

        if (errores.length) {
            crearAlerta('error', errores.join(', '));
            return;
        }

        crearAlerta('info', 'Enviando datos...');

        try {
            const res = await fetch('../Php/agregar_libro.php', {
                method: 'POST',
                body: formData,
            });
            const data = await res.json();

            if (data.status === 'success') {
                crearAlerta('exito', 'Libro agregado correctamente');
                formLibro.reset();
                formContainer.style.display = 'none';
                await actualizarListaLibros();
                setTimeout(() => location.reload(), 1500);
            } else {
                crearAlerta('error', data.message || 'Error inesperado');
            }
        } catch (error) {
            console.error('Error en la petición:', error);
            crearAlerta('error', 'Error de conexión con el servidor');
        }
    };

    const agregarEstilosAlertas = () => {
        const estilo = document.createElement('style');
        estilo.textContent = `
            .alerta {
                position: fixed;
                top: 20px;
                right: 20px;
                max-width: 300px;
                z-index: 1000;
                border-radius: 5px;
                padding: 10px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                animation: fadeIn 0.3s;
            }
            .alerta-contenido {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .alerta-cerrar {
                background: none;
                border: none;
                font-size: 20px;
                cursor: pointer;
            }
            .exito {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            .info {
                background-color: #d1ecf1;
                color: #0c5460;
                border: 1px solid #bee5eb;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(estilo);
    };

    // Eventos
    toggleFormBtn?.addEventListener('click', toggleFormulario);
    formLibro?.addEventListener('submit', enviarFormulario);
    guardarLibroBtn?.addEventListener('click', () => {
        if (formContainer?.style.display === 'block') {
            formLibro.dispatchEvent(new Event('submit'));
        } else {
            crearAlerta('error', 'El formulario está oculto. Ábrelo primero.');
        }
    });

    // Inicializar
    agregarEstilosAlertas();
    actualizarListaLibros();
});
