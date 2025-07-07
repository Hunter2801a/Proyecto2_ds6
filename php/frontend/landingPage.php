<?php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tecno Y | Electrónica para ti</title>
    <link rel="stylesheet" href="../../css/landingPage.css">
</head>
<body>
    <header class="header-landing">
        <div class="header-left">
            <img src="../../image/logo2.png" alt="Logo Epsilon" class="logo-epsilon">
            <span class="empresa-nombre">Tecno Y</span>
        </div>        <nav class="header-links">
            <a href="carrito.php" class="carrito-link">🛒 Tu Carrito</a>
            <a href="login.php" class="login-link">👤 Iniciar sesión</a>
        </nav>
    </header>
    <main class="main-landing">
        <section class="frase-empresa">
            <h2>Bienvenido, nuestro productos están a tu disposición</h2>
            <p class="frase">Tu pequeño centro tecnológico en La Chorrera</p>
        </section>
        <div class="categoria-select-container">
            <select id="categoriaSelectLanding" class="form-input">
                <option value="" selected>Mostrar todas las categorías</option>
            </select>
        </div>
        <div id="categorias-con-productos"></div>
    </main>
    
    <!-- Footer -->
    <footer class="footer-landing">
        <div class="footer-content">
            <p>&copy; 2025 Tecno Y - Página hecha con fines educativos</p>
            <p>Todos los derechos reservados | Proyecto Desarrollo de Software VI</p>
        </div>
    </footer>
    
    <!-- Modal de producto -->
    <div id="modalProducto" class="modal-producto" style="display:none;">
        <div class="modal-producto-content">
            <span class="modal-close" onclick="cerrarModalProducto()">&times;</span>
            <img id="modal-img" src="" alt="" class="modal-producto-img">
            <h3 id="modal-nombre" class="modal-producto-nombre"></h3>
            <p id="modal-descripcion" class="modal-producto-descripcion"></p>
            <div id="modal-precio" class="modal-producto-precio"></div>
            <div id="modal-stock" class="modal-producto-stock"></div>
            <div class="modal-producto-actions">
                <button id="btn-agregar-carrito" class="btn-agregar-carrito" onclick="agregarAlCarrito()">
                    Agregar al Carrito
                </button>
                <button class="btn-cancelar" onclick="cerrarModalProducto()">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Sistema de notificaciones -->
    <div id="notificaciones" class="notifications-container"></div>

    <script>
// ============================================
// SISTEMA DE NOTIFICACIONES
// ============================================
const notifications = {
    show: function(message, type = 'info') {
        const container = document.getElementById('notificaciones');
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️';
        notification.innerHTML = `
            <span class="notification-icon">${icon}</span>
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.remove()">×</button>
        `;
        
        container.appendChild(notification);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
        
        // Agregar animación de entrada
        setTimeout(() => {
            notification.classList.add('notification-show');
        }, 10);
    }
};

// ============================================
// VARIABLES GLOBALES
// ============================================
let categoriasData = [];
let productosData = [];

// ============================================
// FUNCIÓN PARA ACTUALIZAR CONTADOR DEL CARRITO
// ============================================
function actualizarContadorCarrito() {
    fetch('../../php/backend/carrito.php?accion=obtener')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const carritoLink = document.querySelector('.carrito-link');
                const totalItems = data.carrito.reduce((sum, item) => sum + parseInt(item.cantidad), 0);
                carritoLink.innerHTML = `🛒 Tu Carrito (${totalItems})`;
            }
        })
        .catch(error => {
            console.error('Error al actualizar contador del carrito:', error);
        });
}

// ============================================
// CARGA INICIAL DE DATOS
// ============================================
fetch('../../php/backend/LoadProd.php')
    .then(res => res.json())
    .then(data => {
        categoriasData = data;
        // Llenar el select de categorías
        const select = document.getElementById('categoriaSelectLanding');
        data.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat.id;
            opt.textContent = cat.nombre;
            select.appendChild(opt);
        });
        // Unir todos los productos en un solo array con referencia a su categoría
        productosData = [];
        data.forEach(cat => {
            cat.productos.forEach(prod => {
                productosData.push({
                    ...prod,
                    categoria_id: cat.id,
                    categoria_nombre: cat.nombre,
                    categoria_imagen: cat.imagen
                });
            });
        });
        renderProductos(); // Mostrar todos al inicio
        actualizarContadorCarrito(); // Inicializar contador del carrito
    })
    .catch(error => {
        console.error('Error al cargar productos:', error);
        notifications.show('Error al cargar productos', 'error');
    });

// ============================================
// EVENT LISTENERS
// ============================================
document.getElementById('categoriaSelectLanding').addEventListener('change', function() {
    renderProductos(this.value);
});

// ============================================
// FUNCIÓN PARA RENDERIZAR PRODUCTOS
// ============================================
function renderProductos(filtroCatId = "") {
    const cont = document.getElementById('categorias-con-productos');
    cont.innerHTML = '';
    let productosFiltrados = filtroCatId
        ? productosData.filter(p => p.categoria_id == filtroCatId)
        : productosData;

    if (productosFiltrados.length === 0) {
        cont.innerHTML = `<p class="empty-state">No hay productos en esta categoría.</p>`;
        return;
    }

    // Agrupar por categoría para mostrar igual que antes
    let cats = {};
    productosFiltrados.forEach(prod => {
        if (!cats[prod.categoria_id]) {
            cats[prod.categoria_id] = {
                nombre: prod.categoria_nombre,
                imagen: prod.categoria_imagen,
                productos: []
            };
        }
        cats[prod.categoria_id].productos.push(prod);
    });

    Object.values(cats).forEach(cat => {
        let catHtml = `
        <section class="categoria-section">
            <h3 class="categoria-titulo">
                ${cat.imagen ? `<img src="../../${cat.imagen}" alt="${cat.nombre}" class="categoria-img">` : ''}
                ${cat.nombre}
            </h3>
            <div class="productos-list-landing">
        `;
        
        cat.productos.forEach(prod => {
            catHtml += `
            <div class="producto-card-landing" onclick="mostrarDetalleProducto(${prod.id})">
                ${prod.imagen ? `<img src="../../${prod.imagen}" alt="${prod.nombre}" class="producto-img-landing">` : ''}
                <div class="producto-info-landing">
                    <strong>${prod.nombre}</strong>
                </div>
            </div>
            `;
        });
        catHtml += `</div></section>`;
        cont.innerHTML += catHtml;
    });
}

// ============================================
// FUNCIÓN PARA MOSTRAR DETALLE DEL PRODUCTO
// ============================================
function mostrarDetalleProducto(prodId) {
    const prod = productosData.find(p => p.id == prodId);
    if (!prod) {
        notifications.show('Producto no encontrado', 'error');
        return;
    }
    
    document.getElementById('modal-img').src = `../../${prod.imagen}`;
    document.getElementById('modal-nombre').textContent = prod.nombre;
    document.getElementById('modal-descripcion').textContent = prod.descripcion;
    document.getElementById('modal-precio').textContent = `$${parseFloat(prod.precio).toFixed(2)}`;
    
    // Mostrar stock con estilo
    const stockElement = document.getElementById('modal-stock');
    const stock = parseInt(prod.stock);
    const btnAgregar = document.getElementById('btn-agregar-carrito');
    
    if (stock > 0) {
        stockElement.innerHTML = `<span class="stock-disponible">📦 En stock: ${stock} unidades</span>`;
        btnAgregar.disabled = false;
        btnAgregar.style.opacity = '1';
    } else {
        stockElement.innerHTML = `<span class="stock-agotado">❌ Sin stock disponible</span>`;
        btnAgregar.disabled = true;
        btnAgregar.style.opacity = '0.5';
    }
    
    // Guardar ID del producto actual para el carrito
    window.currentProductId = prodId;
    
    document.getElementById('modalProducto').style.display = 'flex';
}

// ============================================
// FUNCIÓN PARA AGREGAR AL CARRITO
// ============================================
function agregarAlCarrito() {
    // Obtener el producto actual
    const prod = productosData.find(p => p.id == window.currentProductId);
    if (!prod) {
        notifications.show('Error: Producto no encontrado', 'error');
        return;
    }
    
    // Verificar stock
    if (prod.stock <= 0) {
        notifications.show('Producto sin stock disponible', 'error');
        return;
    }
    
    // Preparar datos para enviar
    const formData = new FormData();
    formData.append('accion', 'agregar');
    formData.append('producto_id', prod.id);
    formData.append('cantidad', 1);
    
    // Mostrar indicador de carga
    const btnAgregar = document.getElementById('btn-agregar-carrito');
    const textoOriginal = btnAgregar.innerHTML;
    btnAgregar.innerHTML = '⏳ Agregando...';
    btnAgregar.disabled = true;
    
    fetch('../../php/backend/carrito.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            notifications.show(`${prod.nombre} agregado al carrito`, 'success');
            actualizarContadorCarrito();
            cerrarModalProducto();
            
            // Actualizar stock en memoria para reflejar el cambio
            prod.stock -= 1;
        } else {
            notifications.show(data.message || 'Error al agregar al carrito', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        notifications.show('Error de conexión al agregar al carrito', 'error');
    })
    .finally(() => {
        // Restaurar botón
        btnAgregar.innerHTML = textoOriginal;
        btnAgregar.disabled = false;
    });
}

// ============================================
// FUNCIONES DEL MODAL
// ============================================
function cerrarModalProducto() {
    document.getElementById('modalProducto').style.display = 'none';
}

// Cerrar modal al hacer clic fuera del contenido
document.addEventListener('click', function(e) {
    if (e.target.id === 'modalProducto') {
        cerrarModalProducto();
    }
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalProducto();
    }
});

// ============================================
// INICIALIZACIÓN
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Landing page cargada correctamente');
});
    </script>
</body>
</html>