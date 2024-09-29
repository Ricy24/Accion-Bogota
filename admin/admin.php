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

// Manejar la eliminación de una noticia
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    $sql = "DELETE FROM noticias WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $mensaje = 'Noticia eliminada exitosamente.';
    } else {
        $mensaje = 'Error al eliminar la noticia.';
    }
    $stmt->close();
}

// Obtener todas las noticias
$sql = "SELECT * FROM noticias";
$result = $conn->query($sql);

$noticias = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $noticias[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Noticias</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-newspaper icon"></i> Administración de Noticias</h1>
        
        <?php if (isset($mensaje)) : ?>
            <div class="message <?php echo isset($error) ? 'error' : ''; ?>">
                <i class="fas fa-info-circle icon"></i>
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <a href="agregar.php" class="contact-btn"><i class="fas fa-plus-circle icon"></i> Agregar Noticia</a>

        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Resumen</th>
                    <th>Fecha de Publicación</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($noticias as $noticia) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($noticia['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($noticia['resumen']); ?></td>
                        <td><?php echo htmlspecialchars($noticia['fecha_publicacion']); ?></td>
                        <td><img src="uploads/<?php echo htmlspecialchars($noticia['imagen']); ?>" alt="Imagen" width="100"></td>
                        <td>
                            <a href="editar.php?id=<?php echo $noticia['id']; ?>" class="btn-edit"><i class="fas fa-edit icon"></i> Editar</a>
                            <a href="admin.php?delete=<?php echo $noticia['id']; ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de que quieres eliminar esta noticia?');"><i class="fas fa-trash icon"></i> Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../index.php"><i class="fas fa-home icon"></i> Volver al Inicio</a>
    </div>
</body>
</html>
