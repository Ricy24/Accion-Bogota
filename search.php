<?php
// Conexión a la base de datos
include 'db.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Escapamos el valor para evitar inyecciones SQL
    $query = $conn->real_escape_string($query);

    // Consulta SQL para buscar en los campos 'titulo', 'contenido' y 'resumen'
    $sql = "SELECT * FROM noticias 
            WHERE titulo LIKE '%$query%' 
            OR contenido LIKE '%$query%' 
            OR resumen LIKE '%$query%' 
            ORDER BY fecha_publicacion DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h1>Resultados de la Búsqueda</h1>";
        while ($row = $result->fetch_assoc()) {
            // Mostrar el título, resumen, imagen y enlace a la noticia completa
            echo "<div class='noticia'>";
            if (!empty($row['imagen'])) {
                // Ajusta la ruta de la imagen para que apunte a la carpeta donde almacenas imágenes
                $imagen_path = 'admin/uploads/' . htmlspecialchars($row['imagen']);
                echo "<img src='$imagen_path' alt='" . htmlspecialchars($row['titulo']) . "' class='noticia-imagen'>";
            }
            echo "<h2><a href='detalle.php?noticia=" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['titulo']) . "</a></h2>";
            echo "<p>" . htmlspecialchars($row['resumen']) . "</p>";
            echo "<p><small>" . htmlspecialchars($row['fecha_publicacion']) . "</small></p>";
            echo "</div>";
        }
    } else {
        echo "<p>No se encontraron resultados para tu búsqueda.</p>";
    }
} else {
    echo "<p>No se ha enviado ninguna consulta de búsqueda.</p>";
}


?>


<style>
    .noticia {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
    }
    .noticia-imagen {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .noticia h2 a {
        text-decoration: none;
        color: #3498db;
    }
    .noticia h2 a:hover {
        text-decoration: underline;
    }
    .noticia p {
        margin: 5px 0;
    }
</style>

