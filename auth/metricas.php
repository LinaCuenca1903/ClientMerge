<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "crm_callcenter");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $conn->connect_error]);
    exit;
}

// Consulta para contar casos abiertos y cerrados por cada usuario (solo agentes y supervisores)
$sql = "
    SELECT u.nombre,
        SUM(c.estado = 'Abierto' OR c.estado = 'En Progreso') AS abiertos,
        SUM(c.estado = 'Resuelto' OR c.estado = 'Cerrado') AS cerrados
    FROM casolmcd c
    JOIN usuariolmcd u ON c.id_agente = u.id_usuario
    WHERE u.rol IN ('agente', 'supervisor')
    GROUP BY u.id_usuario
    ORDER BY u.nombre ASC
";

$res = $conn->query($sql);
$metricas = [];

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $metricas[] = $row;
    }
    echo json_encode($metricas);
} else {
    http_response_code(500);
    echo json_encode(["error" => $conn->error]);
}

$conn->close();

