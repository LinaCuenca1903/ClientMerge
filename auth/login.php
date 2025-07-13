<?php
session_start(); // 👈 Necesario para usar $_SESSION

$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$correo = $_POST['correo'] ?? '';
$clave = $_POST['clave'] ?? '';

if (empty($correo) || empty($clave)) {
    echo "error";
    exit;
}

$sql = "SELECT * FROM usuariolmcd WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($clave, $usuario['contrasena'])) {
        // ✅ GUARDAR EN SESIÓN
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['rol'] = trim($usuario['rol']);

        echo json_encode([
            "rol" => $_SESSION['rol'],
            "id_usuario" => $_SESSION['id_usuario']
        ]);
    } else {
        echo "error"; // Contraseña incorrecta
    }
} else {
    echo "error"; // Usuario no encontrado
}

$stmt->close();
$conn->close();

