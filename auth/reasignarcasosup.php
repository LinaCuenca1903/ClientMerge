<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "crm_callcenter");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $conn->connect_error]);
    exit;
}

// Obtener datos enviados por POST en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

$id_caso = $data["id_caso"] ?? null;
$id_agente = $data["id_agente"] ?? null;

if (!$id_caso || !$id_agente) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$sql = "UPDATE casolmcd SET id_agente = ? WHERE id_caso = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_agente, $id_caso);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al reasignar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
