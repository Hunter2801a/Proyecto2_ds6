<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include '../backend/conexion.php';

// Obtener categorías
$categorias = $conn->query("SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
    <link rel="stylesheet" href="../../css/pag_adm.css">
</head>
<body>
    <header class="header-admin">
        <div class="header-left">
            <img src="../../image/logo2.png" alt="Logo Epsilon" class="logo-epsilon">
            <span class="empresa-nombre">Tecno Y</span>
        </div>
        <nav class="header-links">
            <a href="productos.php"> + Productos</a>
            <a href="categorias.php">+ Categorías</a>
            <a href="logout.php" class="logout-link">Cerrar sesión</a>
        </nav>
    </header>
    <div class="main-content">
        <h2>Bienvenido, <?php echo $_SESSION['usuario']; ?> (Administrador)</h2>
        <h3>Categorías registradas</h3>
        <div class="categoria-select-container">
            <select id="categoriaSelect" class="form-input">
                <option value="" disabled selected>Seleccione una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option 
                        value="<?= $cat['id'] ?>" 
                        data-img="../../<?= htmlspecialchars($cat['imagen']) ?>"
                    >
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div id="categoriaPreview" class="categoria-preview"></div>
        </div>
        <div id="productosCards" class="productos-cards"></div>
    </div>
    <script>
const categoriaSelect = document.getElementById('categoriaSelect');
const categoriaPreview = document.getElementById('categoriaPreview');
const productosCards = document.getElementById('productosCards');

categoriaSelect.addEventListener('change', function() {
    const selected = categoriaSelect.options[categoriaSelect.selectedIndex];
    const img = selected.getAttribute('data-img');
    categoriaPreview.innerHTML = img ? `<img src="${img}" alt="" style="height:60px;border-radius:8px;">` : '';

    if (!this.value) {
        productosCards.innerHTML = '';
        return;
    }

    fetch(`../../php/backend/prodXCat.php?categoria_id=${this.value}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                productosCards.innerHTML = '<p class="empty-state">No hay productos en esta categoría.</p>';
                return;
            }
            productosCards.innerHTML = data.map(prod => `
                <div class="producto-card">
                    <img src="../../${prod.imagen}" alt="${prod.nombre}" class="producto-img" style="width:90px;height:90px;object-fit:cover;border-radius:10px;">
                    <div class="producto-info">
                        <h4>${prod.nombre}</h4>
                        <p>${prod.descripcion}</p>
                        <span class="producto-precio">$${parseFloat(prod.precio).toFixed(2)}</span>
                    </div>
                </div>
            `).join('');
        });
});
</script>
</body>
</html>
