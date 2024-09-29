<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'bogota');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $biografia = $_POST['biografia'];

    // Manejo de la subida de la imagen de perfil
    if (!empty($_FILES['foto_perfil']['name'])) {
        $foto_perfil = 'uploads/' . basename($_FILES['foto_perfil']['name']);
        move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil);
    } else {
        $foto_perfil = 'image/avatar_default.jpg'; // Imagen por defecto
    }

    $sql = "INSERT INTO perfiles (nombre, email, password, biografia, foto_perfil)
            VALUES ('$nombre', '$email', '$password', '$biografia', '$foto_perfil')";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario registrado con éxito";
    } else {
        echo "Error: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap'>
  <link rel="stylesheet" href="./style.css">
  <link rel="icon" href="img/logo1.png" type="image/x-icon">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .screen-1 {
      background-color: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
    }

    .logo {
      display: block;
      margin: 0 auto 30px auto;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    form .sec-2 {
      display: flex;
      align-items: center;
      background-color: #f0f0f0;
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 20px;
    }

    form input, form textarea {
      border: none;
      background: none;
      outline: none;
      flex: 1;
      padding-left: 10px;
      font-size: 14px;
    }

    textarea {
      resize: none;
      height: 100px;
    }

    ion-icon {
      font-size: 20px;
      color: #555;
    }

    label {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 5px;
    }

    .nombre, .email, .password, .biografia, .foto_perfil {
      margin-bottom: 25px;
    }

    .foto_perfil input {
      padding-left: 0;
    }

    button.login {
      background-color: #3d4785;
      color: white;
      border: none;
      padding: 15px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
      transition: background-color 0.3s;
    }

    button.login:hover {
      background-color: #2c356a;
    }

    .footer {
      text-align: center;
      margin-top: 30px;
    }

    .footer span {
      font-size: 14px;
      color: #555;
    }
  </style>
</head>
<body>
  <!-- Pantalla de Registro -->
  <div class="screen-1">
    <!-- Reemplaza la ruta y el nombre del archivo con el de tu logo -->
    <img class="logo" src="img/logo1.png" alt="Tu Logo" width="300" height="300">
    <!-- Formulario de Registro -->
    <form method="POST" enctype="multipart/form-data" action="#">
      <!-- Nombre -->
      <div class="nombre">
        <label for="nombre">Nombre</label>
        <div class="sec-2">
          <ion-icon name="person-outline"></ion-icon>
          <input type="text" name="nombre" placeholder="Tu nombre" required />
        </div>
      </div>

      <!-- Email -->
      <div class="email">
        <label for="email">Email Address</label>
        <div class="sec-2">
          <ion-icon name="mail-outline"></ion-icon>
          <input type="email" name="email" placeholder="Username@gmail.com" required />
        </div>
      </div>

      <!-- Contraseña -->
      <div class="password">
        <label for="password">Password</label>
        <div class="sec-2">
          <ion-icon name="lock-closed-outline"></ion-icon>
          <input class="pas" type="password" name="password" placeholder="············" required />
          <ion-icon class="show-hide" name="eye-outline"></ion-icon>
        </div>
      </div>

      <!-- Biografía -->
      <div class="biografia">
        <label for="biografia">Biografía</label>
        <div class="sec-2">
          <ion-icon name="document-text-outline"></ion-icon>
          <textarea name="biografia" placeholder="Escribe algo sobre ti" required></textarea>
        </div>
      </div>

      <!-- Foto de Perfil -->
      <div class="foto_perfil">
        <label for="foto_perfil">Foto de perfil (opcional)</label>
        <div class="sec-2">
          <ion-icon name="camera-outline"></ion-icon>
          <input type="file" name="foto_perfil" />
        </div>
      </div>

      <!-- Botón de Registro -->
      <button type="submit" class="login">Registrarse</button>
    </form>

    <!-- Footer de opciones -->
    <div class="footer">
      <span><a href="login.php">¿Ya tienes una cuenta? Iniciar sesión</a></span>
    </div>
  </div>
</body>
</html>
