<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT 
            c.id_caso,
            c.descripcion,
            c.estado,
            c.fecha_creacion,
            cli.nombre AS cliente,
            u.nombre AS agente
        FROM casolmcd c
        JOIN clientelmcd cli ON c.id_cliente = cli.id_cliente
        JOIN usuariolmcd u ON c.id_agente = u.id_usuario
        ORDER BY c.fecha_creacion DESC";

$result = $conn->query($sql);

$casos = [];
while ($row = $result->fetch_assoc()) {
    $casos[] = $row;
}

echo json_encode($casos);
$conn->close();
