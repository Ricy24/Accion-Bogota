<?php
// Incluir archivo de conexión a la base de datos
include '../db.php';

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $resumen = !empty($_POST['resumen']) ? $_POST['resumen'] : NULL; // Resumen es opcional
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $categoria = $_POST['categoria']; // Obtener la categoría seleccionada
    
    // Verificar si se subió una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombreImagen = $_FILES['imagen']['name'];
        $rutaImagen = 'uploads/' . $nombreImagen;

        // Mover la imagen a la carpeta 'uploads'
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            // Preparar la consulta SQL
            $sql = "INSERT INTO noticias (titulo, contenido, resumen, fecha_publicacion, imagen, categoria) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $titulo, $contenido, $resumen, $fecha_publicacion, $nombreImagen, $categoria);

            if ($stmt->execute()) {
                $mensaje = "Noticia agregada exitosamente.";
            } else {
                $mensaje = "Error al agregar la noticia: " . $conn->error;
            }
        } else {
            $mensaje = "Error al subir la imagen.";
        }
    } else {
        $mensaje = "Por favor, sube una imagen.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Noticia</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-plus-circle icon"></i> Agregar Noticia</h1>

        <?php if ($mensaje) : ?>
            <div class="message">
                <i class="fas fa-info-circle icon"></i>
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form action="agregar.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>

            <div class="form-group">
                <label for="contenido">Contenido:</label>
                <textarea id="contenido" name="contenido" rows="6" required></textarea>
            </div>

            <div class="form-group">
                <label for="resumen">Resumen (Opcional):</label>
                <textarea id="resumen" name="resumen" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="fecha_publicacion">Fecha de Publicación:</label>
                <input type="date" id="fecha_publicacion" name="fecha_publicacion" required>
            </div>

            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <option value="General">General</option>
                    <option value="Deportes">Deportes</option>
                    <option value="Negocios">Negocios</option>
                    <option value="Tecnología">Tecnología</option>
                </select>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
            </div>

            <div class="form-group">
                <button type="submit" class="contact-btn"><i class="fas fa-check-circle icon"></i> Agregar Noticia</button>
            </div>
        </form>

        <a href="admin.php"><i class="fas fa-arrow-left icon"></i> Volver a Administración</a>
    </div>
</body>
</html>
