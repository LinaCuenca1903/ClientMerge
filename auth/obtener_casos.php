<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "crm_callcenter");

$sql = "SELECT 
            c.id_caso,
            c.estado,
            cli.nombre AS cliente,
            u.nombre AS agente
        FROM casolmcd c
        JOIN clientelmcd cli ON c.id_cliente = cli.id_cliente
        LEFT JOIN usuariolmcd u ON c.id_agente = u.id_usuario";

$result = $conn->query($sql);
$casos = [];

while ($row = $result->fetch_assoc()) {
    $casos[] = $row;
}

echo json_encode($casos);
$conn->close();
