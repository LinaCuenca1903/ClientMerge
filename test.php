<?php
$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$correo = $_POST['correo'] ?? '';
$clave = $_POST['clave'] ?? '';

if (empty($correo) || empty($clave)) {
    echo "❌ Campos vacíos.";
    exit;
}

$sql = "SELECT * FROM usuariolmcd WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($clave, $usuario['contrasena'])) {
        echo "✅ Bienvenido, " . $usuario['nombre'] . ". Rol: " . $usuario['rol'];
        // Aquí podrías redirigir según el rol
        // header("Location: admin.html"); exit;
    } else {
        echo "❌ Contraseña incorrecta.";
    }
} else {
    echo "❌ Usuario no encontrado.";
}

$stmt->close();
$conn->close();
?>
