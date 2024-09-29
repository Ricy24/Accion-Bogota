<?php
// voluntario.php

// Incluir la conexión a la base de datos
include 'db.php';

// Verificar si se recibió el formulario de voluntariado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noticia_id = isset($_POST['noticia_id']) ? intval($_POST['noticia_id']) : 0;
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);

    if ($noticia_id > 0 && !empty($nombre) && !empty($correo) && !empty($telefono)) {
        $sql = "INSERT INTO voluntarios (noticia_id, nombre, correo, telefono) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss', $noticia_id, $nombre, $correo, $telefono);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            header('Location: detalle.php?noticia=' . $noticia_id . '&mensaje=Registro+exitoso');
        } else {
            header('Location: detalle.php?noticia=' . $noticia_id . '&mensaje=Error+al+registrarse');
        }
        $stmt->close();
    } else {
        header('Location: detalle.php?noticia=' . $noticia_id . '&mensaje=Datos+incompletos');
    }
} else {
    header('Location: index.php');
}

$conn->close();
?>
