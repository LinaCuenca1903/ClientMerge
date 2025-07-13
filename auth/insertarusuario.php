<?php
$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre = "UsuarioPrueba";
$correo = "usuario@crm.com";
$rol = "agente";
$clave = password_hash("1234", PASSWORD_DEFAULT); // Encriptar la contraseña

$sql = "INSERT INTO usuariolmcd (nombre, correo, rol, contrasena) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $correo, $rol, $clave);

if ($stmt->execute()) {
    echo "✅ Usuario insertado correctamente.";
} else {
    echo "❌ Error al insertar usuario: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
