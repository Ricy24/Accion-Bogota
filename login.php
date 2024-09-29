<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bogota');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM perfiles WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['biografia'] = $user['biografia'];
            $_SESSION['foto_perfil'] = $user['foto_perfil'];
            header('Location: index.php'); // Redirigir al inicio
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">
  <link rel="stylesheet" href="css/login.css">
  <link rel="icon" href="img/logo1.png" type="image/x-icon">
</head>
<body>
  <!-- Partial: Login screen -->
  <div class="screen-1">

  <img class="logo" src="img/logo1.png" alt="Tu Logo" width="300" height="300">


    <!-- Login form -->
    <form method="POST" action="#">
      <div class="email">
        <label for="email">Email </label>
        <div class="sec-2">
          <ion-icon name="mail-outline"></ion-icon>
          <input type="email" name="email" placeholder="Username@gmail.com" required />
        </div>
      </div>
      <div class="password">
        <label for="password">Password</label>
        <div class="sec-2">
          <ion-icon name="lock-closed-outline"></ion-icon>
          <input class="pas" type="password" name="password" placeholder="············" required />
          <ion-icon class="show-hide" name="eye-outline"></ion-icon>
        </div>
      </div>
      <button type="submit" class="login">Login</button>
    </form>

    <!-- Footer options -->
    <div class="footer">
      <span><a href="register.php">Regístrate</a></span>
    </div>
  </div>
</body>
</html>


<style>
  .logo {
    width: 300px; /* Ancho del logo */
    height: 300px; /* Alto del logo */
    border-radius: 50%; /* Hace el logo circular */
    object-fit: cover; /* Mantiene la relación de aspecto y recorta la imagen si es necesario */
    filter: saturate(0.8); /* Reduce la saturación a un 80% */
    transition: filter 0.3s; /* Transición suave al pasar el mouse */
}

.logo:hover {
    filter: saturate(1); /* Vuelve a la saturación normal al pasar el mouse */
}

</style>