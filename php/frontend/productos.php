<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include '../backend/conexion.php';

// Obtener categorías
$categorias = $conn->query("SELECT id, nombre FROM categorias");

// Insertar nuevo producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria_id = $_POST['categoria_id'];
    $imagen = null;

    // Procesar imagen si se subió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imgTmp = $_FILES['imagen']['tmp_name'];
        $imgName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $carpeta = "../../image/img_productos/";
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $destino = $carpeta . $imgName;
        if (move_uploaded_file($imgTmp, $destino)) {
            $imagen = "image/img_productos/" . $imgName;
        }
    }

    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, categoria_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen, $categoria_id);
    $stmt->execute();
    $stmt->close();

    header("Location: productos.php");
    exit;
}

// Obtener productos
$productos = $conn->query("
    SELECT p.id, p.nombre, p.imagen, c.nombre AS categoria, p.categoria_id 
    FROM productos p
    JOIN categorias c ON p.categoria_id = c.id
");

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    // 1. Obtener la ruta de la imagen
    $stmt = $conn->prepare("SELECT imagen FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagen);
    $stmt->fetch();
    $stmt->close();

    // 2. Eliminar la imagen física si existe y no está vacía
    if ($imagen && file_exists("../../" . $imagen)) {
        unlink("../../" . $imagen);
    }

    // 3. Eliminar el producto
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="../../css/productos.css">
</head>
<body>
    <main class="productos-main">

<div class="productos-container">
    <div class="back-nav">
        <a href="pag_adm.php" class="back-button">⟵ Volver</a>
        <a href="#listado-productos" class="back-button">Lista de productos</a>
    </div>
    <h2 class="page-title">Productos</h2>

    <form method="POST" enctype="multipart/form-data" class="form-producto">
        <div class="form-group">
            <label class="form-label">Categoría:</label>
            <select name="categoria_id" class="form-input" required>
                <option value="" disabled selected>Seleccione una categoría</option>
                <?php while ($cat = $categorias->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Nombre del producto:</label>
            <input type="text" name="nombre" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Descripción:</label>
            <textarea name="descripcion" class="form-textarea" required></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Precio:</label>
            <input type="number" step="0.01" name="precio" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Imagen:</label>
            <input type="file" name="imagen" class="form-input" accept="image/*">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Agregar Producto</button>
            <button type="button" class="btn btn-cancelar" onclick="window.location.href='pag_adm.php'">Cancelar</button>
        </div>
    </form>

    <div class="categoria-select-container">
        <select id="categoriaFiltro" class="form-input">
            <option value="" selected>Mostrar todas las categorías</option>
            <?php
            // Vuelve a consultar las categorías porque el primer while ya las consumió
            $categorias2 = $conn->query("SELECT id, nombre FROM categorias");
            while ($cat = $categorias2->fetch_assoc()):
            ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <h3 id="listado-productos" class="section-title">Listado de productos</h3>
    <ul class="productos-list" id="productosList">
        <?php while ($prod = $productos->fetch_assoc()): ?>
            <li class="producto-card" data-categoria-id="<?= $prod['categoria_id'] ?>">
                <div class="producto-img">
                    <?php if (!empty($prod['imagen'])): ?>
                        <img src="../../<?= htmlspecialchars($prod['imagen']) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>" class="producto-img-thumb">
                    <?php endif; ?>
                </div>
                <div class="producto-info">
                    <strong><?= htmlspecialchars($prod['nombre']) ?></strong>
                    <span class="producto-categoria"> | Categoría: <?= htmlspecialchars($prod['categoria']) ?></span>
                </div>
                <div class="product-actions">
                    <a href="#" class="btn btn-warning btn-small" title="Editar"
                       onclick="abrirModalEditar(<?= $prod['id'] ?>); return false;">&#9998;</a>
                    <a href="#" class="btn btn-danger btn-small" onclick="confirmarEliminar(<?= $prod['id'] ?>, '<?= htmlspecialchars(addslashes($prod['nombre'])) ?>'); return false;" title="Eliminar">&#128465;</a>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<!-- Modal de edición de producto -->
<div id="modalEditarProducto" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close-modal" onclick="cerrarModalEditar()">&times;</span>
    <h3>Editar producto</h3>
    <form id="formEditarProducto" enctype="multipart/form-data">
      <input type="hidden" name="id" id="edit-id">
      <div class="form-group">
        <label>Nombre:</label>
        <input type="text" name="nombre" id="edit-nombre" class="form-input" required>
      </div>
      <div class="form-group">
        <label>Descripción:</label>
        <textarea name="descripcion" id="edit-descripcion" class="form-textarea" required></textarea>
      </div>
      <div class="form-group">
        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" id="edit-precio" class="form-input" required>
      </div>
      <div class="form-group">
        <label>Imagen:</label>
        <input type="file" name="imagen" id="edit-imagen" class="form-input" accept="image/*">
        <img id="edit-preview" src="" alt="Vista previa" style="max-width:60px;max-height:60px;margin-top:8px;">
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <button type="button" class="btn btn-cancelar" onclick="cerrarModalEditar()">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal de confirmación -->
<div id="modalConfirmarEliminar" class="modal" style="display:none;">
  <div class="modal-content" style="max-width:340px;">
    <span class="close-modal" onclick="cerrarModalEliminar()">&times;</span>
    <h3 id="modal-eliminar-titulo" style="color:var(--danger-color);margin-bottom:12px;">Confirmar eliminación</h3>
    <p id="modal-eliminar-mensaje">¿Estás seguro de eliminar este elemento?</p>
    <div class="form-actions" style="margin-top:18px;display:flex;gap:12px;justify-content:center;">
      <button class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
      <button class="btn btn-cancelar" type="button" onclick="cerrarModalEliminar()">Cancelar</button>
    </div>
  </div>
</div>

<script>
function abrirModalEditar(id) {
    fetch('../../php/backend/obtener_producto.php?id=' + id)
        .then(res => res.json())
        .then(prod => {
            document.getElementById('modalEditarProducto').style.display = 'flex';
            document.getElementById('edit-id').value = prod.id;
            document.getElementById('edit-nombre').value = prod.nombre;
            document.getElementById('edit-descripcion').value = prod.descripcion;
            document.getElementById('edit-precio').value = prod.precio;
            document.getElementById('edit-preview').src = prod.imagen ? '../../' + prod.imagen : '';
        });
}
function cerrarModalEditar() {
    document.getElementById('modalEditarProducto').style.display = 'none';
}

// Enviar el formulario por AJAX
document.getElementById('formEditarProducto').onsubmit = function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    fetch('../../php/backend/editar_producto.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success){
            location.reload();
        } else {
            alert('Error al editar el producto');
        }
    });
};

// Confirmar eliminación de producto
let idProductoEliminar;
function confirmarEliminar(id, nombre) {
    idProductoEliminar = id;
    document.getElementById('modal-eliminar-mensaje').innerText = '¿Estás seguro de eliminar el producto "' + nombre + '"?';
    document.getElementById('modalConfirmarEliminar').style.display = 'flex';
}
function cerrarModalEliminar() {
    document.getElementById('modalConfirmarEliminar').style.display = 'none';
}
document.getElementById('btnConfirmarEliminar').onclick = function() {
    if (idProductoEliminar) {
        window.location.href = '?eliminar=' + idProductoEliminar;
    }
};

// Filtro por categoría
document.getElementById('categoriaFiltro').addEventListener('change', function() {
    const catId = this.value;
    document.querySelectorAll('.producto-card').forEach(card => {
        if (!catId || card.getAttribute('data-categoria-id') === catId) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

    </main>

</body>
</html>
