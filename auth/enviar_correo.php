<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

$data = json_decode(file_get_contents("php://input"), true);
$nombre = $data['nombre'] ?? '';
$correo = $data['correo'] ?? '';

$mail = new PHPMailer(true);

try {
    // Configuración SMTP para Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // 👉 Usa un correo real y activa "contraseñas de aplicaciones"
    $mail->Username = 'TUCORREO@gmail.com';
    $mail->Password = 'TU_CONTRASEÑA_DE_APP';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remitente y destinatario
    $mail->setFrom('TUCORREO@gmail.com', 'Client Merge');
    $mail->addAddress($correo, $nombre);

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Caso creado con éxito';
    $mail->Body = "Hola <b>$nombre</b>,<br>Tu caso ha sido registrado exitosamente.<br>Gracias por contactarnos.";

    $mail->send();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["error" => "No se pudo enviar el correo. " . $mail->ErrorInfo]);
}
