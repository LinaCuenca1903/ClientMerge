<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([]);
    exit;
}

$id_agente = $_SESSION['id_usuario'];

$conn = new mysqli("localhost", "root", "", "crm_callcenter");

$sql = "SELECT c.id_caso, c.descripcion, c.estado, cl.nombre 
        FROM casolmcd c
        JOIN clientelmcd cl ON c.id_cliente = cl.id_cliente
        WHERE c.id_agente = $id_agente";

$result = $conn->query($sql);
$casos = [];

while ($row = $result->fetch_assoc()) {
    $casos[] = $row;
}

echo json_encode($casos);
