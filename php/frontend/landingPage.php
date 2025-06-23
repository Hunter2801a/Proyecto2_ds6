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
        </div>
        <nav class="header-links">
            <a href="login.php" class="login-link">Iniciar sesión</a>
        </nav>
    </header>
    <main class="main-landing">
        <section class="frase-empresa">
            <h2>Bienvenido, nuestro productos están a tu disposición</h2>
            <p class="frase">Tu pequeño centro tecnologico en la Chorrera</p>
        </section>
        <div class="categoria-select-container">
            <select id="categoriaSelectLanding" class="form-input">
                <option value="" selected>Mostrar todas las categorías</option>
            </select>
        </div>
        <div id="categorias-con-productos"></div>
    </main>
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
        `;
        cat.productos.forEach(prod => {
            catHtml += `
            <div class="producto-card-landing">
                ${prod.imagen ? `<img src="../../${prod.imagen}" alt="${prod.nombre}" class="producto-img-landing">` : ''}
                <div class="producto-info-landing">
                    <strong>${prod.nombre}</strong>
                    <p>${prod.descripcion}</p>
                    <span class="producto-precio">$${parseFloat(prod.precio).toFixed(2)}</span>
                </div>
            </div>
            `;
        });
        catHtml += `</div></section>`;
        cont.innerHTML += catHtml;
    });
}
</script>
</body>
</html>