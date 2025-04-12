// Función para eliminar un libro
function eliminarLibro(id) {
    const formData = new FormData();
    formData.append("id", id);
  
    // Hacer la solicitud POST
    fetch("eliminar_libro.php", {
      method: "POST",
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert(data.message);  // Mostrar alerta de éxito
      } else {
        alert(data.message);  // Mostrar alerta de error
      }
    })
    .catch(error => {
      alert('Hubo un problema al enviar la solicitud.');
    });
  }
  
  // Función para editar un libro
  function editarLibro(id) {
    const titulo = document.getElementById("titulo").value;
    const fecha_publicacion = document.getElementById("fecha_publicacion").value;
    const isbn = document.getElementById("isbn").value;
  
    const formData = new FormData();
    formData.append("id", id);
    formData.append("titulo", titulo);
    formData.append("fecha_publicacion", fecha_publicacion);
    formData.append("isbn", isbn);
  
    // Hacer la solicitud POST
    fetch("editar_libro.php", {
      method: "POST",
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert(data.message);  // Mostrar alerta de éxito
      } else {
        alert(data.message);  // Mostrar alerta de error
      }
    })
    .catch(error => {
      alert('Hubo un problema al enviar la solicitud.');
    });
  }
  