<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Determinar si es usuario o empresa
    $isCompany = isset($_POST['tipo']) && $_POST['tipo'] === 'empresa';

    // Obtener los datos comunes
    $email = htmlspecialchars($_POST['email']);
    
    // Validar el formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Formato de correo electrónico inválido.");
    }

    if ($isCompany) {
        // Obtener el nombre de la empresa
        $nombre_empresa = htmlspecialchars($_POST['nombre_empresa']);
        
        // Preparar y ejecutar la inserción de datos en la tabla de empresas
        $stmt = $conn->prepare("INSERT INTO empresas (nombre_empresa, email) VALUES (?, ?)");
        if (!$stmt) {
            die("Fallo en la preparación: " . $conn->error);
        }

        $stmt->bind_param("ss", $nombre_empresa, $email);
    } else {
        // Preparar y ejecutar la inserción de datos en la tabla de suscriptores
        $stmt = $conn->prepare("INSERT INTO suscriptores (email) VALUES (?)");
        if (!$stmt) {
            die("Fallo en la preparación: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
    }

    if ($stmt->execute()) {
        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'accionbogota@outlook.com'; // Cambia a tu correo
            $mail->Password = 'AlenMessi10'; // Cambia a tu contraseña
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('accionbogota@outlook.com', 'Acción Bogotá');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a Acción Bogotá';

            if ($isCompany) {
                $mail->Body = '
                <div style="font-family: Arial, sans-serif; color: #333;">
                    <h1 style="color: #6a0dad;">¡Hola, ' . $nombre_empresa . '!</h1>
                    <p>Gracias por suscribirte a <strong style="color: #6a0dad;">Acción Bogotá</strong> como empresa. Te mantendremos informado con las últimas noticias.</p>
                    <p style="color: #28a745;">¡Esperamos que disfrutes de nuestro contenido!</p>
                </div>';
                $mail->AltBody = 'Gracias por suscribirte a Acción Bogotá como empresa. Te mantendremos informado con las últimas noticias.';
            } else {
                $mail->Body = '
                <div style="font-family: Arial, sans-serif; color: #333;">
                    <h1 style="color: #6a0dad;">¡Hola!</h1>
                    <p>Gracias por suscribirte a <strong style="color: #6a0dad;">Acción Bogotá</strong>. Te mantendremos informado con las últimas noticias.</p>
                    <p style="color: #28a745;">¡Esperamos que disfrutes de nuestro contenido!</p>
                </div>';
                $mail->AltBody = 'Gracias por suscribirte a Acción Bogotá. Te mantendremos informado con las últimas noticias.';
            }

            $mail->send();

            // Notificación HTML
            echo '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Registro Exitoso</title>
                <style>
                    body {
                        background-color: #f0f0f0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        font-family: Arial, sans-serif;
                    }
                    .notification {
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                        text-align: center;
                        max-width: 400px;
                        width: 100%;
                        margin: 20px;
                        animation: fadeIn 0.5s ease-in-out;
                    }
                    .notification h1 {
                        font-size: 24px;
                        margin-bottom: 10px;
                        color: #333;
                    }
                    .notification p {
                        font-size: 18px;
                        margin-bottom: 20px;
                        color: #666;
                    }
                    .notification button {
                        padding: 10px 20px;
                        background-color: #28a745;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        font-size: 16px;
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                    }
                    .notification button:hover {
                        background-color: #218838;
                    }
                    @keyframes fadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                </style>
            </head>
            <body>
                <div class="notification">
                    <h1>¡Gracias por registrarte!</h1>
                    <p>Te enviaremos noticias a <strong>' . $email . '</strong>.</p>
                    <button onclick="redirectToIndex()">OK</button>
                </div>
                <script>
                    function redirectToIndex() {
                        window.location.href = "index.php";
                    }
                </script>
            </body>
            </html>
            ';
        } catch (Exception $e) {
            echo "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
