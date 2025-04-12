// Función para obtener el carrito del localStorage o devolver un arreglo vacío
function getCart() {
    const cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : [];
  }
  
  // Función para guardar el carrito en el localStorage
  function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
  }
  
  // Función para renderizar los elementos del carrito en la tabla
  function renderCart() {
    const cart = getCart();
    const cartTableBody = document.querySelector('#cartTable tbody');
    cartTableBody.innerHTML = '<tr><td colspan="2">El carrito está vacío.</td></tr>';
  
    if (cart.length === 0) {
      cartTableBody.innerHTML = '<tr><td colspan="2">El carrito está vacío.</td></tr>';
      return;
    }
  
    cart.forEach((item, index) => {
      const tr = document.createElement('tr');
  
      // Columna del título del libro
      const tdTitle = document.createElement('td');
      tdTitle.textContent = item.title;
      tr.appendChild(tdTitle);
  
      // Columna de acciones
      const tdActions = document.createElement('td');
      const removeButton = document.createElement('button');
      removeButton.textContent = 'Eliminar';
      removeButton.className = 'btn';
      removeButton.onclick = () => removeFromCart(index);
      tdActions.appendChild(removeButton);
      tr.appendChild(tdActions);
  
      cartTableBody.appendChild(tr);
    });
  }
  
  // Función para eliminar un elemento del carrito por su índice
  function removeFromCart(index) {
    let cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
    renderCart();
  }
  
  // Función para vaciar completamente el carrito
  function clearCart() {
    if (confirm('¿Estás seguro de que deseas vaciar el carrito?')) {
      localStorage.removeItem('cart');
      renderCart();
    }
  }
  
  // Función para redireccionar a la página principal o de catálogo
  function continueShopping() {
    window.location.href = '..\Index-HTML\Index.php'; // Ajusta la ruta según la estructura de tu proyecto
  }
  
  // Función para agregar un libro al carrito (puedes llamarla desde otra página)
  function addToCart(book) {
    let cart = getCart();
    cart.push(book);
    saveCart(cart);
    alert(`El libro "${book.title}" se agregó al carrito.`);
  }
  
  // Ejecuta la función de renderizado al cargar la página
  document.addEventListener('DOMContentLoaded', renderCart);
  