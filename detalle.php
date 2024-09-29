<?php
session_start();

// Incluir la conexión a la base de datos
include 'db.php';

// Obtener el ID de la noticia desde la URL
$noticia_id = isset($_GET['noticia']) ? intval($_GET['noticia']) : 0;

if ($noticia_id > 0) {
    // Consultar la noticia específica
    $sql = "SELECT titulo, contenido, fecha_publicacion, imagen FROM noticias WHERE id = $noticia_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Mostrar los detalles de la noticia
        $row = $result->fetch_assoc();
        $titulo = $row['titulo'];
        $contenido = $row['contenido'];
        $fecha = $row['fecha_publicacion'];
        $imagen = $row['imagen']; // Obtener la imagen
    } else {
        echo "Noticia no encontrada.";
        exit();
    }
} else {
    echo "ID de noticia no válido.";
    exit();
}

// Manejar el envío del comentario si se recibe una solicitud AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentario = trim($_POST['comentario']);
    if (!empty($comentario)) {
        $sql = "INSERT INTO comentarios (noticia_id, comentario, fecha_comentario) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $noticia_id, $comentario);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'comentario' => $comentario, 'fecha' => date('Y-m-d H:i:s')]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar el comentario.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'El comentario no puede estar vacío.']);
    }
    $conn->close();
    exit();
}

// Obtener el mensaje de notificación desde la URL
$mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?></title>
    <link rel="stylesheet" href="css/detalless.css">
    <link rel="icon" href="img/logo1.png" type="image/x-icon">
</head>
<body>

<br><br>
    <div class="container">

        <a href="index.php" class="volver-inicio">Volver al Inicio</a>

        <!-- Imagen de la noticia -->
        <?php if (!empty($imagen)) : ?>
            <?php
            $imagen_path = 'admin/uploads/' . htmlspecialchars($imagen);
            ?>
            <img src="<?php echo $imagen_path; ?>" alt="<?php echo htmlspecialchars($titulo); ?>" class="imagen-noticia">
        <?php endif; ?>

        <h1><?php echo htmlspecialchars($titulo); ?></h1>
        <p><small>Publicado el: <?php echo $fecha; ?></small></p>
        <p><?php echo nl2br(htmlspecialchars($contenido)); ?></p>

        <!-- Mostrar el mensaje de notificación -->
        <?php if (!empty($mensaje)) : ?>
            <div class="notificacion"><?php echo $mensaje; ?></div>
        <?php endif; ?>

<!-- Sección de registro para voluntarios -->
<h2>Únete como voluntario</h2>
<form action="voluntario.php" method="POST">
    <input type="hidden" name="noticia_id" value="<?php echo $noticia_id; ?>">
    <input type="text" name="nombre" placeholder="Tu nombre" required><br>
    <input type="email" name="correo" placeholder="Tu correo" required><br>
    <input type="text" name="telefono" placeholder="Tu teléfono" required><br>
    
    <!-- Casilla de verificación para confirmar edad -->
    <div>
        <input type="checkbox" id="mayorEdad" name="mayor_edad" required>
        <label for="mayorEdad">Confirmo que soy mayor de edad</label>
    </div><br>
    
    <button type="submit">Registrarse</button>
</form>




<div id="lista-comentarios">
    <?php
    // Conectar a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'bogota');

    // Verificar si hay errores de conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consultar y mostrar los comentarios existentes junto con el nombre de usuario de la tabla perfiles
    $sql = "
    SELECT c.comentario, c.fecha_comentario, p.nombre
    FROM comentarios c
    JOIN perfiles p ON c.user_id = p.id
    WHERE c.noticia_id = $noticia_id
    ORDER BY c.fecha_comentario DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='comentario'>";
            echo "<strong>" . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . ":</strong>"; // Mostrar el nombre del usuario
            echo "<p>" . htmlspecialchars($row['comentario'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<small>Enviado el: " . $row['fecha_comentario'] . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No hay comentarios todavía. ¡Sé el primero en comentar!</p>";
    }
    ?>
</div>

<div id="comentario-form">
    <?php
    // Verificar si el usuario está logueado
    if (isset($_SESSION['user_id'])) {
        // Mostrar el formulario si está logueado
        ?>
        <form action="guardar_comentario.php" method="POST">
            <textarea name="comentario" placeholder="Escribe tu comentario aquí..." required></textarea>
            <input type="hidden" name="noticia_id" value="<?php echo $noticia_id; ?>">
            <button type="submit">Enviar Comentario</button>
        </form>
        <?php
    } else {
        // Mostrar un mensaje para iniciar sesión si no está logueado
        echo '<p><a href="login.php">Inicia sesión para dejar un comentario</a></p>';
    }
    ?>
</div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const comentarioForm = document.getElementById('comentarioForm');
        const listaComentarios = document.getElementById('lista-comentarios');

        comentarioForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(comentarioForm);

            fetch('detalle.php?noticia=<?php echo $noticia_id; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Crear y agregar el nuevo comentario a la lista
                    const nuevoComentario = document.createElement('div');
                    nuevoComentario.className = 'comentario';
                    nuevoComentario.innerHTML = `<p>${data.comentario}</p><small>Enviado el: ${data.fecha}</small>`;
                    listaComentarios.prepend(nuevoComentario);

                    // Limpiar el formulario
                    comentarioForm.reset();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    </script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
