<?php
session_start();
include 'db.php';

// Consultar las noticias recientes
$sql = "SELECT id, titulo, resumen, fecha_publicacion, imagen FROM noticias ORDER BY fecha_publicacion DESC";
$result = $conn->query($sql);


$foto_perfil = 'image/avatar_default.jpg'; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];


    $query = "SELECT foto_perfil FROM perfiles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($foto_perfil);
    $stmt->fetch();
    $stmt->close();


    $foto_perfil = !empty($foto_perfil) ? $foto_perfil : 'image/avatar_default.jpg';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css" integrity="sha512-HHsOC+h3najWR7OKiGZtfhFIEzg5VRIPde0kB0bG2QRidTQqf+sbfcxCTB16AcFB93xMjnBIKE29/MjdzXE+qw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo1.png" type="image/x-icon">
    <title>Únete</title>
</head>
<body>
<div class="header">
    <div class="logo">
        <img src="img/logo1.png" alt="">
        <div class="logo-text">
            <p style="font-size: 14px; color: green; font-style: italic; margin: 0;">Acción Bogotá</p>
            <p style="font-size: 12px; color: purple; font-style: italic; margin: 0;">voluntariados</p>
        </div>
    </div>

    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="index.php">General</a></li>
            <li><a href="index.php">Deportes</a></li>
            <li><a href="index.php">Negocios</a></li>
            <li><a href="index.php ">Tecnología</a></li>
        </ul>
        <div class="bar">
            <i class="open fa-solid fa-bars-staggered"></i>
            <i class="close fa-solid fa-xmark"></i>
        </div>
    </nav>

    <div class="input-container">
    <button type="button" id="search-button" aria-label="Buscar" onclick="toggleSearchInput()">
        <i class="fa-solid fa-magnifying-glass" id="search-icon"></i> <!-- Ícono de lupa -->
    </button>
    <input type="text" name="query" placeholder="¿Qué deseas buscar?" aria-label="Buscar" id="search-input" style="display: none;" onkeypress="handleEnter(event)">
</div>





    <div class="user-section">
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="logout-link">Cerrar Sesión</a>
        <?php 
        // Configura la ruta de la foto de perfil
        $foto_perfil = !empty($foto_perfil) ? $foto_perfil : 'image/avatar_default.jpg';
        ?>
        <img src="uploads/<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="profile-pic">
    <?php else: ?>
        <a href="login.php" class="login-link">Iniciar Sesión</a>
    <?php endif; ?>
</div>
</div>


<br><br><br>
<br><br><br>
<div class="page2">
    <div class="premium-info" style="margin-top: 20px; text-align: center; background-color: #f7f9fc; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #333; margin-bottom: 30px;">Beneficios de Volverse Premium</h2>
        
        <div class="benefit-section" style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;">
            <div class="user-section" style="flex: 1; margin: 10px; max-width: 300px; text-align: center;">
                <i class="fa-solid fa-user" style="font-size: 60px; color: #800080; cursor: pointer;" onclick="toggleUserSubscriptionForm()"></i> <!-- Icono de Usuario en morado -->
                <h3 style="color: #800080; margin-top: 10px;">Usuarios Premium</h3>
                <p style="color: #555;">Recibe noticias exclusivas directamente en tu correo.</p>
            </div>
            
            <div class="company-section" style="flex: 1; margin: 10px; max-width: 300px; text-align: center;">
                <i class="fa-solid fa-building" style="font-size: 60px; color: #800080; cursor: pointer;" onclick="toggleCompanySubscriptionForm()"></i> <!-- Icono de Empresa en morado -->
                <h3 style="color: #800080; margin-top: 10px;">Empresas Premium</h3>
                <p style="color: #555;">Puedes poner tu icono en la página principal para publicidad y atraer más visitantes.</p>
            </div>
        </div>
    </div>

    <!-- Formulario de Suscripción para Usuarios -->
    <section id="user-subscription-form" class="subscription-form" style="display: none; margin-top: 20px; background-color: #f2f2f2; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div class="form-header" style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #800080;">Suscripción Premium</h2>
        </div>
        <div class="form-content">
            <p style="text-align: center; color: #555;">Suscríbete para recibir las últimas noticias directamente en tu correo electrónico. Esta suscripción tiene un costo mensual de $10,000.</p>
            <form action="subscribe.php" method="post" id="subscribe-form" style="max-width: 400px; margin: 0 auto;">
                <div style="margin-bottom: 20px;">
                    <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="email" name="email" placeholder="Ingrese su correo electrónico" required>
                </div>
                <div style="margin-bottom: 20px;">
                    <button style="width: 100%; padding: 10px; background-color: #800080; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;" type="button" onclick="togglePaymentOptions('user')">Pagar</button>
                </div>
                <div id="user-payment-options" style="display:none; padding: 20px; background-color: white; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <p style="margin-bottom: 10px; font-weight: bold; color: black;">Seleccione un método de pago:</p>
                    <label style="display: block; margin-bottom: 10px; color: black;">
                        <input type="radio" name="user-payment-method" value="tarjeta" required> Tarjeta de Crédito
                    </label>
                    <div id="user-card-info" style="display:none; margin-bottom: 20px;">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; color: black;" type="text" name="user_card_number" placeholder="Número de Tarjeta">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; color: black;" type="text" name="user_card_name" placeholder="Nombre en la Tarjeta">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; color: black;" type="text" name="user_card_expiration" placeholder="Fecha de Expiración (MM/AA)">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="text" name="user_card_cvv" placeholder="CVV">
                    </div>
                    <label style="display: block; margin-bottom: 10px; color: black;">
                        <input type="radio" name="user-payment-method" value="nequi"> Nequi
                    </label>
                    <div id="user-nequi-info" style="display:none; margin-bottom: 20px;">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="text" name="user_nequi_number" placeholder="Número de Nequi">
                    </div>
                    <label style="display: block; margin-bottom: 10px; color: black;">
                        <input type="radio" name="user-payment-method" value="daviplata"> Daviplata
                    </label>
                    <div id="user-daviplata-info" style="display:none; margin-bottom: 20px;">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="text" name="user_daviplata_number" placeholder="Número de Daviplata">
                    </div>
                    <footer style="text-align: center;">
                        <button style="width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;" type="submit">Suscribirse</button>
                    </footer>
                </div>
            </form>
        </div>
    </section>

    <!-- Formulario de Suscripción para Empresas -->
    <section id="company-subscription-form" class="subscription-form" style="display: none; margin-top: 20px; background-color: #f2f2f2; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div class="form-header" style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #800080;">Suscripción Premium para Empresas</h2>
        </div>
        <div class="form-content">
            <p style="text-align: center; color: #555;">Suscríbete para que tu empresa aparezca en nuestra plataforma. Esta suscripción tiene un costo mensual de $200,000.</p>
            <form action="subscribe_company.php" method="post" id="company-subscribe-form" style="max-width: 400px; margin: 0 auto;" enctype="multipart/form-data">

                <div style="margin-bottom: 20px;">
                    <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="text" name="company_name" placeholder="Ingrese el nombre de la empresa" required>
                </div>
                <div style="margin-bottom: 20px;">
                    <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="email" name="email" placeholder="Ingrese su correo electrónico" required>
                </div>
                <div style="margin-bottom: 20px;">
                <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="file" name="icono_empresa" accept="image/*" required>
                <small style="color: gray;">* Suba el logo de la empresa en formato de imagen (PNG, JPG, JPEG).</small>
            </div>

                <div style="margin-bottom: 20px;">
                    <button style="width: 100%; padding: 10px; background-color: #800080; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;" type="button" onclick="togglePaymentOptions('company')">Pagar</button>
                </div>
                <div id="company-payment-options" style="display:none; padding: 20px; background-color: white; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <p style="margin-bottom: 10px; font-weight: bold; color: black;">Seleccione un método de pago:</p>
                    <label style="display: block; margin-bottom: 10px; color: black;">
                        <input type="radio" name="company-payment-method" value="tarjeta" required> Tarjeta de Crédito
                    </label>
                    <div id="company-card-info" style="display:none; margin-bottom: 20px;">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; color: black;" type="text" name="company_card_number" placeholder="Número de Tarjeta">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; color: black;" type="text" name="company_card_name" placeholder="Nombre en la Tarjeta">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="text" name="company_card_expiration" placeholder="Fecha de Expiración (MM/AA)">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="text" name="company_card_cvv" placeholder="CVV">
                    </div>
                    <label style="display: block; margin-bottom: 10px; color: black;">
                        <input type="radio" name="company-payment-method" value="nequi"> Nequi
                    </label>
                    <div id="company-nequi-info" style="display:none; margin-bottom: 20px;">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="text" name="company_nequi_number" placeholder="Número de Nequi">
                    </div>
                    <label style="display: block; margin-bottom: 10px; color: black;">
                        <input type="radio" name="company-payment-method" value="daviplata"> Daviplata
                    </label>
                    <div id="company-daviplata-info" style="display:none; margin-bottom: 20px;">
                        <input style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; color: black;" type="text" name="company_daviplata_number" placeholder="Número de Daviplata">
                    </div>
                    <footer style="text-align: center;">
                        <button style="width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;" type="submit">Suscribirse</button>
                    </footer>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
function toggleUserSubscriptionForm() {
    const form = document.getElementById('user-subscription-form');
    const companyForm = document.getElementById('company-subscription-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
    companyForm.style.display = 'none'; // Ocultar el formulario de empresa
}

function toggleCompanySubscriptionForm() {
    const form = document.getElementById('company-subscription-form');
    const userForm = document.getElementById('user-subscription-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
    userForm.style.display = 'none'; // Ocultar el formulario de usuario
}

function togglePaymentOptions(type) {
    const userOptions = document.getElementById('user-payment-options');
    const companyOptions = document.getElementById('company-payment-options');
    
    if (type === 'user') {
        userOptions.style.display = userOptions.style.display === 'none' ? 'block' : 'none';
    } else {
        companyOptions.style.display = companyOptions.style.display === 'none' ? 'block' : 'none';
    }
}

// Manejo de los campos de métodos de pago
document.querySelectorAll('input[name="user-payment-method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('user-card-info').style.display = this.value === 'tarjeta' ? 'block' : 'none';
        document.getElementById('user-nequi-info').style.display = this.value === 'nequi' ? 'block' : 'none';
        document.getElementById('user-daviplata-info').style.display = this.value === 'daviplata' ? 'block' : 'none';
    });
});

document.querySelectorAll('input[name="company-payment-method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('company-card-info').style.display = this.value === 'tarjeta' ? 'block' : 'none';
        document.getElementById('company-nequi-info').style.display = this.value === 'nequi' ? 'block' : 'none';
        document.getElementById('company-daviplata-info').style.display = this.value === 'daviplata' ? 'block' : 'none';
    });
});
</script>





    <div class="footer">
        <div class="box">
            <div class="left">
                <div class="categories">
                <p>Categorías</p>
             <div>
              <p>General</p>
              </div>
               <div>
             <p>Deportes</p>
              </div>
            <div>
           <p>Negocios</p>
             </div>
             <div>
          <p>Tecnología</p>
            </div>

                </div>
                <div class="contactUs">
                    <div class="contact">
                        <p>Contacto</p>
                        <div>Telefono - + <span>57 3005750852</span></div>
                        <div>Email - <span>accionbogota@outlook.com</span></div>
                        <div>Dirección - <span>Bogotá, Colombia</span></div>
                        <div>Administrador- <span><a href="admin/admin.php">Admin</a></span></div>
                    </div>
                    <div class="icon">
                    <a href="https://www.facebook.com/profile.php?id=61565421462360" target="_blank">
                    <i class="fa-brands fa-square-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/accionbogota/" target="_blank">
                   <i class="fa-brands fa-instagram"></i>
                    </a>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="newsletter">
                    <p>Únete a nuestra comunidad</p>
                    <div class="email">
                        <button onclick="window.location.href='unete.php'">Únete</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyrights">
            Copyrights &copy; 2024 Acción Bogotá
        </div>
    </div>


    



</body>
</html>

<style>
    .logo {
    font-size: 35px;
    font-weight: 700;
    cursor: pointer;
    display: flex; /* Permite alinear el logo y el texto en línea */
    align-items: center; /* Centra verticalmente */
}

.logo img { /* Suponiendo que la imagen del logo esté dentro de un elemento con la clase .logo */
    width: 80px; /* Ajusta el ancho a tu gusto */
}


.logo-text {
    margin-left: 10px; /* Espacio entre el logo y el texto */
}



.header {
    display: flex; /* Alinea logo, nav e input en línea */
    justify-content: space-between; /* Distribuye el espacio entre los elementos */
    align-items: center; /* Centra verticalmente */
    padding: 10px 20px; /* Espaciado alrededor del header */
}






.profile-pic {
    width: 40px; /* Ancho de la imagen de perfil */
    height: 40px; /* Alto de la imagen de perfil */
    border-radius: 50%; /* Hace que la imagen sea circular */
    object-fit: cover; /* Cubre el área del círculo sin deformar la imagen */
}





.user-section {
    display: flex;
    align-items: center; /* Alinea verticalmente el contenido */
    gap: 10px; /* Espacio entre el texto y la imagen */
    margin-right: 20px; /* Espacio a la derecha del contenedor */
}

.logout-link, .login-link {
    text-decoration: none; /* Quita el subrayado del enlace */
    color: #2c3e50; /* Color del texto */
    font-weight: bold; /* Estilo de texto en negrita */
    transition: color 0.3s; /* Transición suave para el color */
}

.logout-link:hover, .login-link:hover {
    color: #16a085; /* Color al pasar el mouse sobre el enlace */
}

.profile-pic {
    border-radius: 50%; /* Hace que la imagen sea circular */
    width: 40px; /* Ancho de la foto de perfil */
    height: 40px; /* Alto de la foto de perfil */
    border: 2px solid #16a085; /* Borde verde alrededor de la foto */
}


.user-section {
    display: flex;
    align-items: center; /* Alinea verticalmente el contenido */
    gap: 10px; /* Espacio entre el texto y la imagen */
    margin-right: 20px; /* Espacio a la derecha del contenedor */
}

.logout-link, .login-link {
    text-decoration: none; /* Quita el subrayado del enlace */
    color: #2c3e50; /* Color del texto */
    font-weight: bold; /* Estilo de texto en negrita */
    transition: color 0.3s; /* Transición suave para el color */
}

.logout-link:hover, .login-link:hover {
    color: #16a085; /* Color al pasar el mouse sobre el enlace */
}

.profile-pic {
    border-radius: 50%; /* Hace que la imagen sea circular */
    width: 40px; /* Ancho de la foto de perfil */
    height: 40px; /* Alto de la foto de perfil */
    border: 2px solid #16a085; /* Borde verde alrededor de la foto */
}




.input-container {
    display: flex; 
    align-items: center;
    position: relative;
}

#search-input {
    width: 200px; 
    padding: 10px 15px;
    border: 1px solid #ccc; 
    border-radius: 25px; 
    transition: all 0.3s ease; 
    display: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
}

#search-button {
    background: none; /* Sin fondo */
    border: none; /* Sin borde */
    cursor: pointer; /* Cambia el cursor al pasar el mouse */
    font-size: 20px; /* Tamaño del ícono */
    outline: none; /* Elimina el contorno al hacer clic */
    margin-right: 10px; /* Espacio entre el ícono y la barra de búsqueda */
    transition: transform 0.2s; /* Transición para el efecto de hover */
}

#search-button:hover {
    transform: scale(1.1); /* Efecto de aumentar el tamaño al pasar el mouse */
}

#search-icon {
    color: gray; /* Color gris por defecto */
    transition: color 0.3s ease; /* Transición suave para el color */
}

#search-button:hover #search-icon {
    color: green; /* Color verde al pasar el mouse */
}



</style>


<script>
function toggleSearchInput() {
        const searchInput = document.getElementById('search-input');
        // Alternar la visibilidad del input
        searchInput.style.display = searchInput.style.display === 'none' ? 'block' : 'none';
        // Focar el input al abrirlo
        if (searchInput.style.display === 'block') {
            searchInput.focus();
        }
    }

    function handleEnter(event) {
        if (event.key === 'Enter') {
            const query = document.getElementById('search-input').value;
            window.location.href = `search.php?query=${encodeURIComponent(query)}`;
        }
    }
</script>
