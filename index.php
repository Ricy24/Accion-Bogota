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
    <title>Acción Bogotá</title>
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
            <li><a href="#">Inicio</a></li>
            <li><a href="#generalNews">General</a></li>
            <li><a href="#sportsNews">Deportes</a></li>
            <li><a href="#businessNews">Negocios</a></li>
            <li><a href="#techNews">Tecnología</a></li>
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


    <br>
    <div class="topHeadlines">
    <div class="left">
        <div class="title">
            <h2>Noticias</h2>
        </div>
        <a href="#" id="breakingNewsLink" style="text-decoration: none; color: inherit;"> <!-- Enlace para Breaking News -->
            <div class="img" id="breakingImg">
                <?php
                // Consulta para obtener una noticia destacada (Breaking News)
                $sql_breaking = "SELECT id, imagen, titulo FROM noticias ORDER BY fecha_publicacion DESC LIMIT 1";
                $result_breaking = $conn->query($sql_breaking);
                if ($result_breaking->num_rows > 0) {
                    $row = $result_breaking->fetch_assoc();
                    if (!empty($row['imagen'])) {
                        $imagen_path = 'admin/uploads/' . htmlspecialchars($row['imagen']);
                        echo '<img src="' . $imagen_path . '" alt="' . htmlspecialchars($row['titulo']) . '" style="width: 100%; height: auto; object-fit: cover; clip-path: inset(0 0 20% 0);" id="breakingNewsImage">'; // Añadir id para el script
                    }
                } else {
                    echo '<p>No hay noticias de última hora disponibles.</p>';
                }
                ?>
            </div>
            <div class="text" id="breakingNews" style="position: relative; top: -20px; background-color: rgba(0, 0, 0, 0.5); padding: 10px; color: white;">
                <div class="title" id="breakingNewsTitle">
                    <?php
                    // Mostrar el título de la noticia
                    if (!empty($row['titulo'])) {
                        echo '<h3>' . htmlspecialchars($row['titulo']) . '</h3>';
                    }
                    ?>
                </div>
                <div class="description" id="breakingNewsDescription">
                    <p>Descripción breve de la noticia aquí.</p>
                </div>
            </div>
        </a>
    </div>

    <div class="right">
        <div class="title">
            <h2>Titulares principales</h2>
        </div>
        <div class="topNews">
            <?php
            // Consulta para obtener todas las noticias para la sección Top Headlines
            $sql_top = "SELECT id, titulo, imagen, resumen FROM noticias ORDER BY fecha_publicacion DESC"; // Asegúrate de incluir 'resumen' si la necesitas
            $result_top = $conn->query($sql_top);
            if ($result_top->num_rows > 0) {
                while ($row = $result_top->fetch_assoc()) {
                    echo '<div class="headline" style="display: flex; align-items: center; margin-bottom: 10px;">';
                    
                    // Mostrar imagen si está disponible
                    if (!empty($row['imagen'])) {
                        $imagen_path = 'admin/uploads/' . htmlspecialchars($row['imagen']);
                        echo '<img src="' . $imagen_path . '" style="width: 120px; height: 80px; object-fit: cover; margin-right: 15px; cursor: pointer;" alt="' . htmlspecialchars($row['titulo']) . '" onclick="showNews(' . $row['id'] . ', \'' . htmlspecialchars($row['titulo']) . '\', \'' . htmlspecialchars($row['imagen']) . '\', \'' . htmlspecialchars($row['resumen']) . '\')">'; // Modifica aquí
                    }
                    
                    // Mostrar título de la noticia con enlace y estilo
                    echo '<p style="flex: 1; font-size: 18px; color: white; text-decoration: none;">';
                    echo '<a href="detalle.php?noticia=' . $row['id'] . '" style="text-decoration: none; color: white;">' . htmlspecialchars($row['titulo']) . '</a></p>'; // Título con enlace
                    echo '</div>';
                }
            } else {
                echo '<p>No hay titulares disponibles en este momento.</p>';
            }
            ?>
        </div>     
    </div>
</div>

<script>
function showNews(id, title, image, summary) {
    // Cambiar la imagen de Breaking News
    document.getElementById('breakingNewsImage').src = 'admin/uploads/' + image; // Cambia la ruta según tu estructura
    // Cambiar el título de Breaking News
    document.getElementById('breakingNewsTitle').innerHTML = title;
    // Cambiar la descripción de Breaking News
    document.getElementById('breakingNewsDescription').innerHTML = summary;
    // Actualizar el enlace para que apunte a detalle.php
    document.getElementById('breakingNewsLink').href = 'detalle.php?noticia=' + id; // Cambia el enlace
}
</script>







    <div class="page2">
    <div class="news" id="generalNews" style="margin-top: 20px;">
    <div class="title">
        <h2>Noticias Generales</h2>
    </div>
    <div class="newsBox">
        <?php
        // Consulta para obtener las noticias generales
        $sql_general = "SELECT id, imagen, titulo FROM noticias WHERE categoria = 'general' ORDER BY fecha_publicacion DESC LIMIT 5"; // Ajustar según el nombre de la categoría
        $result_general = $conn->query($sql_general);
        if ($result_general->num_rows > 0) {
            while ($row = $result_general->fetch_assoc()) {
                echo '<div class="generalHeadline" style="margin-bottom: 20px; text-align: center;">'; // Alinear el texto al centro y agregar margen inferior

                // Mostrar imagen de la noticia
                if (!empty($row['imagen'])) {
                    $imagen_path = 'admin/uploads/' . htmlspecialchars($row['imagen']);
                    echo '<img src="' . $imagen_path . '" style="width: 200px; height: auto; object-fit: cover; margin: 0 10px; display: inline-block;" alt="' . htmlspecialchars($row['titulo']) . '">'; // Aumentar el tamaño de la imagen y agregar margen horizontal
                }

                // Mostrar título de la noticia con enlace
                echo '<a href="detalle.php?noticia=' . $row['id'] . '" style="display: block; text-decoration: none; color: inherit; font-size: 16px; width: 200px; margin-top: 5px; margin-left: auto; margin-right: auto;">' . htmlspecialchars($row['titulo']) . '</a>'; // Alinear el texto al centro y establecer el mismo ancho
                echo '</div>';
            }
        } else {
            echo '<p>No hay noticias generales disponibles en este momento.</p>';
        }
        ?>
    </div>
</div>

<div class="news" id="sportsNews" style="margin-top: 20px;">
    <div class="title">
        <h2>Noticias deportivas</h2>
    </div>
    <div class="newsBox">
        <?php
        // Consulta para obtener las noticias de deportes
        $sql_sports = "SELECT id, imagen, titulo FROM noticias WHERE categoria = 'deportes' ORDER BY fecha_publicacion DESC LIMIT 5"; // Ajustar según el nombre de la categoría
        $result_sports = $conn->query($sql_sports);
        if ($result_sports->num_rows > 0) {
            while ($row = $result_sports->fetch_assoc()) {
                echo '<div class="sportsHeadline" style="margin-bottom: 20px; text-align: center;">'; // Alinear el texto al centro y agregar margen inferior

                // Mostrar imagen de la noticia
                if (!empty($row['imagen'])) {
                    $imagen_path = 'admin/uploads/' . htmlspecialchars($row['imagen']);
                    echo '<img src="' . $imagen_path . '" style="width: 200px; height: auto; object-fit: cover; margin: 0 10px; display: inline-block;" alt="' . htmlspecialchars($row['titulo']) . '">'; // Aumentar el tamaño de la imagen y agregar margen horizontal
                }

                // Mostrar título de la noticia con enlace
                echo '<a href="detalle.php?noticia=' . $row['id'] . '" style="display: block; text-decoration: none; color: inherit; font-size: 16px; width: 200px; margin-top: 5px; margin-left: auto; margin-right: auto;">' . htmlspecialchars($row['titulo']) . '</a>'; // Alinear el texto al centro y establecer el mismo ancho
                echo '</div>';
            }
        } else {
            echo '<p>No hay noticias de deportes disponibles en este momento.</p>';
        }
        ?>
    </div>
</div>

<div class="news" id="businessNews" style="margin-top: 20px;">
    <div class="title">
        <h2>Noticias de Negocios</h2>
    </div>
    <div class="newsBox">
        <?php
        // Consulta para obtener las noticias de negocios
        $sql_business = "SELECT id, imagen, titulo FROM noticias WHERE categoria = 'negocios' ORDER BY fecha_publicacion DESC LIMIT 5"; // Ajustar según el nombre de la categoría
        $result_business = $conn->query($sql_business);
        if ($result_business->num_rows > 0) {
            while ($row = $result_business->fetch_assoc()) {
                echo '<div class="businessHeadline" style="margin-bottom: 20px; text-align: center;">'; // Alinear el texto al centro y agregar margen inferior

                // Mostrar imagen de la noticia
                if (!empty($row['imagen'])) {
                    $imagen_path = 'admin/uploads/' . htmlspecialchars($row['imagen']);
                    echo '<img src="' . $imagen_path . '" style="width: 200px; height: auto; object-fit: cover; margin: 0 10px; display: inline-block;" alt="' . htmlspecialchars($row['titulo']) . '">'; // Aumentar el tamaño de la imagen y agregar margen horizontal
                }

                // Mostrar título de la noticia con enlace
                echo '<a href="detalle.php?noticia=' . $row['id'] . '" style="display: block; text-decoration: none; color: inherit; font-size: 16px; width: 200px; margin-top: 5px; margin-left: auto; margin-right: auto;">' . htmlspecialchars($row['titulo']) . '</a>'; // Alinear el texto al centro y establecer el mismo ancho
                echo '</div>';
            }
        } else {
            echo '<p>No hay noticias de negocios disponibles en este momento.</p>';
        }
        ?>
    </div>
</div>

<div class="news" id="techNews" style="margin-top: 20px;">
    <div class="title">
        <h2>Empresas Suscritas</h2>
    </div>
    <div class="newsBox" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
        <?php
        // Consulta para obtener las empresas suscritas
        $sql_companies = "SELECT nombre_empresa, icono_empresa FROM suscripciones_empresas ORDER BY fecha_suscripcion DESC LIMIT 5"; 
        $result_companies = $conn->query($sql_companies);
        
        if ($result_companies->num_rows > 0) {
            while ($row = $result_companies->fetch_assoc()) {
                echo '<div class="companyCard" style="text-align: center; border: 1px solid #ccc; border-radius: 8px; padding: 10px; width: 200px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">'; // Card para cada empresa
                
// Mostrar logo de la empresa
if (!empty($row['icono_empresa'])) {
    // Asumimos que 'icono_empresa' almacena solo el nombre del archivo
    $logo_path = 'uploads/' . htmlspecialchars($row['icono_empresa']);
    echo '<img src="' . $logo_path . '" style="width: 100px; height: auto; object-fit: contain; margin-bottom: 10px;" alt="Logo de ' . htmlspecialchars($row['nombre_empresa']) . '">';
}


                // Mostrar nombre de la empresa
                echo '<h3 style="font-size: 18px; color: #333;">' . htmlspecialchars($row['nombre_empresa']) . '</h3>'; // Nombre de la empresa
                echo '</div>'; // Cierre de la tarjeta de la empresa
            }
        } else {
            echo '<p>No hay empresas suscritas disponibles en este momento.</p>';
        }
        ?>
    </div>
</div>





<br><br><br>


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
