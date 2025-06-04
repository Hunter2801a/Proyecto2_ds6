<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include '../backend/conexion.php';

// Agregar nueva categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $imagen = null;

    // Procesar imagen si se subió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imgTmp = $_FILES['imagen']['tmp_name'];
        $imgName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $carpeta = "../../image/img_categorias/";
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $destino = $carpeta . $imgName;
        if (move_uploaded_file($imgTmp, $destino)) {
            $imagen = "image/img_categorias/" . $imgName;

        }
    }

    $stmt = $conn->prepare("INSERT INTO categorias (nombre, imagen) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $imagen);
    $stmt->execute();
    $stmt->close();

    header("Location: categorias.php");
    exit;
}

// Eliminar categoría
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    // 1. Obtener la ruta de la imagen
    $stmt = $conn->prepare("SELECT imagen FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagen);
    $stmt->fetch();
    $stmt->close();

    // 2. Eliminar la imagen física si existe y no está vacía
    if ($imagen && file_exists("../../" . $imagen)) {
        unlink("../../" . $imagen);
    }

    // 3. Eliminar la categoría
    $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: categorias.php");
    exit;
}

// Listar categorías
$categorias = $conn->query("SELECT * FROM categorias");
?>

<link rel="stylesheet" href="../../css/categorias.css">

<div class="categorias-container">
    <div class="back-nav">
        <a href="pag_adm.php" class="back-button">⟵ Volver</a>
    </div>
    <h2 class="page-title">Categorías</h2>
    <form method="POST" enctype="multipart/form-data" class="form-categoria" id="form-categoria">
        <div class="form-group">
            <label class="form-label">Nombre de la categoría:</label>
            <input type="text" name="nombre" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Imagen:</label>
            <input type="file" name="imagen" class="form-input" accept="image/*">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary" onclick="return confirmarAgregar()">Agregar Categoría</button>
            <button type="button" class="btn btn-cancelar" onclick="cancelarOperacion()">Cancelar</button>
        </div>
    </form>

    <h3 class="section-title">Listado de categorías</h3>
    <ul class="categories-list">
        <?php while ($cat = $categorias->fetch_assoc()): ?>
            <li class="category-card">
                <div class="category-img">
                    <?php if ($cat['imagen']): ?>
                        <img src="../../<?= htmlspecialchars($cat['imagen']) ?>" alt="<?= htmlspecialchars($cat['nombre']) ?>">
                    <?php endif; ?>
                </div>
                <div class="category-info">
                    <span class="category-name"><?= htmlspecialchars($cat['nombre']) ?></span>
                </div>
                <div class="category-actions">
                    <a href="#" class="btn btn-warning btn-small" title="Editar"
                        onclick="abrirModalEditarCategoria(
                            <?= $cat['id'] ?>,
                            '<?= htmlspecialchars(addslashes($cat['nombre'])) ?>',
                            '<?= htmlspecialchars($cat['imagen']) ?>'
                        ); return false;">&#9998;</a>
                    <a href="#" class="btn btn-danger btn-small"
                        onclick="mostrarModalEliminar(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['nombre'])) ?>'); return false;"
                        title="Eliminar">&#128465;</a>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<!-- Modal de edición de categoría -->
<div id="modalEditarCategoria" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close-modal" onclick="cerrarModalEditarCategoria()">&times;</span>
    <h3>Editar categoría</h3>
    <form id="formEditarCategoria" enctype="multipart/form-data">
      <input type="hidden" name="id" id="edit-cat-id">
      <div class="form-group">
        <label>Nombre:</label>
        <input type="text" name="nombre" id="edit-cat-nombre" class="form-input" required>
      </div>
      <div class="form-group">
        <label>Imagen:</label>
        <input type="file" name="imagen" id="edit-cat-imagen" class="form-input" accept="image/*">
        <img id="edit-cat-preview" src="" alt="Vista previa" class="img-preview-modal">
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <button type="button" class="btn btn-cancelar" onclick="cerrarModalEditarCategoria()">Cancelar</button>
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
function cancelarOperacion() 
{
    if (confirm('¿Estás seguro de cancelar la operación?')) 
    {
        window.location.href = 'pag_adm.php';
    }
}

function confirmarAgregar() {
    return confirm('¿Estás seguro de agregar esta categoría?');
}

function abrirModalEditarCategoria(id, nombre, imagen) {
    document.getElementById('modalEditarCategoria').style.display = 'flex';
    document.getElementById('edit-cat-id').value = id;
    document.getElementById('edit-cat-nombre').value = nombre;
    document.getElementById('edit-cat-preview').src = imagen ? '../../' + imagen : '';
}

function cerrarModalEditarCategoria() {
    document.getElementById('modalEditarCategoria').style.display = 'none';
}

function cerrarModalEliminar() {
    document.getElementById('modalConfirmarEliminar').style.display = 'none';
}

// Enviar el formulario por AJAX
document.getElementById('formEditarCategoria').onsubmit = function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    fetch('../../php/backend/editar_categoria.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success){
            location.reload();
        } else {
            alert('Error al editar la categoría');
        }
    });
};

document.getElementById('edit-cat-imagen').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('edit-cat-preview').src = URL.createObjectURL(file);
    }
});

// Confirmar eliminación de categoría
document.querySelectorAll('.btn-danger').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.closest('.category-card').querySelector('.category-info').dataset.id;
        const nombre = this.closest('.category-card').querySelector('.category-name').innerText;
        document.getElementById('modal-eliminar-mensaje').innerText = '¿Estás seguro de eliminar la categoría "' + nombre + '"?';
        document.getElementById('btnConfirmarEliminar').onclick = function() {
            window.location.href = '?eliminar=' + id;
        }
        document.getElementById('modalConfirmarEliminar').style.display = 'flex';
    });
});

function mostrarModalEliminar(id, nombre) {
    document.getElementById('modal-eliminar-mensaje').innerText = '¿Estás seguro de eliminar la categoría "' + nombre + '"?';
    document.getElementById('btnConfirmarEliminar').onclick = function() {
        window.location.href = '?eliminar=' + id;
    }
    document.getElementById('modalConfirmarEliminar').style.display = 'flex';
}
</script>