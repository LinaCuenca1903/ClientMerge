<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "crm_callcenter");

$sql = "SELECT id_usuario, nombre, rol FROM usuariolmcd WHERE rol IN ('agente', 'supervisor')";
$result = $conn->query($sql);

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);
$conn->close();
