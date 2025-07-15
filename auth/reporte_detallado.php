<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    echo json_encode(["error" => "Conexión fallida"]);
    exit;
}

$sql = "
    SELECT 
        c.id_caso,
        cli.nombre AS cliente,
        c.estado,
        c.descripcion,
        c.fecha_creacion,
        u.nombre AS agente
    FROM casolmcd c
    INNER JOIN clientelmcd cli ON c.id_cliente = cli.id_cliente
    INNER JOIN usuariolmcd u ON c.id_agente = u.id_usuario
    ORDER BY u.nombre, c.fecha_creacion DESC
";

$result = $conn->query($sql);

$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

echo json_encode($datos);
$conn->close();
