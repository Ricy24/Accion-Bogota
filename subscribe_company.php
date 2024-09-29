<?php
// Conexión a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'bogota';

$mysqli = new mysqli($host, $user, $password, $dbname);

// Verifica la conexión
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'];
    $email = $_POST['email'];

    // Manejo de la subida de archivos
    if (isset($_FILES['icono_empresa']) && $_FILES['icono_empresa']['error'] == 0) {
        $file_tmp = $_FILES['icono_empresa']['tmp_name'];
        $file_name = basename($_FILES['icono_empresa']['name']);
        $upload_dir = 'uploads/'; // Asegúrate de que esta carpeta exista y tenga los permisos adecuados

        // Mueve el archivo a la carpeta deseada
        if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            // Insertar datos en la base de datos
            $stmt = $mysqli->prepare("INSERT INTO suscripciones_empresas (nombre_empresa, email, icono_empresa) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $company_name, $email, $file_name);

            if ($stmt->execute()) {
                echo "Suscripción exitosa.";
            } else {
                echo "Error al suscribirse: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        echo "Error en la subida del archivo.";
    }
}

$mysqli->close();
?>
