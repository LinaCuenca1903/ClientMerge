<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida"]);
    exit;
}

$sql = "
  SELECT 
    u.id_usuario AS id_agente,
    u.nombre AS agente,
    SUM(CASE WHEN c.estado = 'Abierto' THEN 1 ELSE 0 END) AS estado_abierto,
    SUM(CASE WHEN c.estado = 'Cerrado' THEN 1 ELSE 0 END) AS estado_cerrado,
    COUNT(*) AS total_casos
  FROM casolmcd c
  JOIN usuariolmcd u ON c.id_agente = u.id_usuario
  GROUP BY u.id_usuario, u.nombre
";

$result = $conn->query($sql);
$datos = [];

while ($row = $result->fetch_assoc()) {
  $datos[] = $row;
}

echo json_encode($datos);
$conn->close();
