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

    <script>
let categoriasData = [];
let productosData = [];

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
    });

document.getElementById('categoriaSelectLanding').addEventListener('change', function() {
    renderProductos(this.value);
});

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
        `;        cat.productos.forEach(prod => {
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

function mostrarDetalleProducto(prodId) {
    console.log('Intentando mostrar producto con ID:', prodId);
    console.log('Array de productos:', productosData);
    
    const prod = productosData.find(p => p.id == prodId);
    if (!prod) {
        console.log('Producto no encontrado con ID:', prodId);
        return;
    }
    
    console.log('Producto encontrado:', prod);
    
    document.getElementById('modal-img').src = `../../${prod.imagen}`;
    document.getElementById('modal-nombre').textContent = prod.nombre;
    document.getElementById('modal-descripcion').textContent = prod.descripcion;
    document.getElementById('modal-precio').textContent = `$${parseFloat(prod.precio).toFixed(2)}`;
    
    // Mostrar stock con estilo
    const stockElement = document.getElementById('modal-stock');
    const stock = parseInt(prod.stock);
    let stockHtml = '';
    
    if (stock > 0) {
        stockHtml = `<span class="stock-disponible"> En stock: ${stock} unidades</span>`;
        document.getElementById('btn-agregar-carrito').disabled = false;
    } else {
        stockHtml = `<span class="stock-agotado">❌ Sin stock disponible</span>`;
        document.getElementById('btn-agregar-carrito').disabled = true;
    }
    
    stockElement.innerHTML = stockHtml;
    
    // Guardar ID del producto actual para el carrito
    window.currentProductId = prodId;
    
    document.getElementById('modalProducto').style.display = 'flex';
}

function agregarAlCarrito() {
    // Por ahora solo mostrar una notificación
    const prod = productosData.find(p => p.id == window.currentProductId);
    if (!prod) return;
    
    // Crear notificación temporal
    const notification = document.createElement('div');
    notification.className = 'carrito-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">✅</span>
            <span class="notification-text">"${prod.nombre}" se agregó al carrito</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remover notificación después de 3 segundos
    setTimeout(() => {
        document.body.removeChild(notification);
    }, 3000);
    
    // Cerrar modal
    cerrarModalProducto();
}

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
</script>
</body>
</html>