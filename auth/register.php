<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener y limpiar datos del formulario
$nombre = trim($_POST['nombre']);
$correo = trim($_POST['correo']);
$rol = $_POST['rol'];
$clave = $_POST['clave'];
$confirmar = $_POST['confirmar'];

// Validar que las contraseñas coincidan
if ($clave !== $confirmar) {
    // Redirigir al index con mensaje de error (o puedes usar alert con JS)
    header("Location: ../index.html?error=clave");
    exit();
}

// Hashear la contraseña
$clave_segura = password_hash($clave, PASSWORD_DEFAULT);

// Insertar en la base de datos
$sql = "INSERT INTO usuariolmcd (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $correo, $clave_segura, $rol);

if ($stmt->execute()) {
    // Registro exitoso
    header("Location: ../index.html");
} else {
    echo "❌ Error al registrar: " . $conn->error;
}

$conn->close();
?>
