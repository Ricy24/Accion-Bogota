<?php
// Incluir la conexión a la base de datos
include '../db.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Manejar el envío del formulario para actualizar una noticia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $titulo = trim($_POST['titulo']);
    $resumen = !empty($_POST['resumen']) ? trim($_POST['resumen']) : NULL; // Resumen es opcional
    $contenido = trim($_POST['contenido']);
    $fecha_publicacion = $_POST['fecha_publicacion'];

    // Verificar que los campos obligatorios estén completos
    if (!empty($titulo) && !empty($contenido) && !empty($fecha_publicacion)) {
        // Actualizar la noticia sin modificar la imagen
        $sql = "UPDATE noticias SET titulo = ?, resumen = ?, contenido = ?, fecha_publicacion = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $titulo, $resumen, $contenido, $fecha_publicacion, $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $mensaje = 'Noticia actualizada exitosamente.';
        } else {
            $mensaje = 'Error al actualizar la noticia.';
        }
        $stmt->close();
    } else {
        $mensaje = 'Todos los campos obligatorios deben estar completos.';
    }
}

// Obtener la noticia actual para editar
$id = intval($_GET['id']);
$sql = "SELECT * FROM noticias WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-edit icon"></i> Editar Noticia</h1>
        
        <?php if (isset($mensaje)) : ?>
            <div class="message <?php echo isset($error) ? 'error' : ''; ?>">
                <i class="fas fa-info-circle icon"></i>
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form action="editar.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($noticia['id']); ?>">

            <label for="titulo"><i class="fas fa-heading icon"></i> Título:</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>

            <label for="resumen"><i class="fas fa-file-alt icon"></i> Resumen (Opcional):</label>
            <input type="text" name="resumen" id="resumen" value="<?php echo htmlspecialchars($noticia['resumen']); ?>">

            <label for="contenido"><i class="fas fa-align-left icon"></i> Contenido:</label>
            <textarea name="contenido" id="contenido" rows="6" required><?php echo htmlspecialchars($noticia['contenido']); ?></textarea>

            <label for="fecha_publicacion"><i class="fas fa-calendar-day icon"></i> Fecha de Publicación:</label>
            <input type="date" name="fecha_publicacion" id="fecha_publicacion" value="<?php echo htmlspecialchars(explode(' ', $noticia['fecha_publicacion'])[0]); ?>" required>

            <button type="submit"><i class="fas fa-save icon"></i> Guardar Cambios</button>
        </form>

        <a href="admin.php"><i class="fas fa-arrow-left icon"></i> Volver al Admin</a>
    </div>
</body>
</html>
