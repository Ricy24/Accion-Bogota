<?php
session_start();
include 'db.php'; // Incluye el archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashear la contraseña
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $biografia = $_POST['biografia'];

    // Seleccionar la tabla según el tipo de registro
    $tabla = $tipo === 'empresa' ? 'empresas' : 'usuarios_premium';

    // Preparar la consulta
    $sql = "INSERT INTO $tabla (nombre, email, password, telefono, direccion, biografia) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $email, $password, $telefono, $direccion, $biografia);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro exitoso.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
