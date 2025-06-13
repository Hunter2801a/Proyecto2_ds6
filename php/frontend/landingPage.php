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
            <a href="login.php" class="login-link">Iniciar sesión como administrador</a>
        </nav>
    </header>
    <main class="main-landing">
        <section class="frase-empresa">
            <h2>Bienvenido, nuestro productos están a tu dispocisión</h2>
            <p class="frase">Tu pequeño centro tecnologico en la Chorrera</p>
        </section>
        <div id="categorias-con-productos"></div>
    </main>
    <script>
    fetch('../../php/backend/LoadProd.php')
        .then(res => res.json())
        .then(data => {
            const cont = document.getElementById('categorias-con-productos');
            cont.innerHTML = '';
            data.forEach(cat => {
                let catHtml = `
                <section class="categoria-section">
                    <h3 class="categoria-titulo">
                        ${cat.imagen ? `<img src="../../${cat.imagen}" alt="${cat.nombre}" class="categoria-img">` : ''}
                        ${cat.nombre}
                    </h3>
                    <div class="productos-list-landing">
                `;
                if (cat.productos.length > 0) {
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
                } else {
                    catHtml += `<p class="empty-state">No hay productos en esta categoría.</p>`;
                }
                catHtml += `</div></section>`;
                cont.innerHTML += catHtml;
            });
        });
    </script>
</body>
</html>