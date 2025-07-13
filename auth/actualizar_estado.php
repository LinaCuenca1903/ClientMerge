<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");

$data = json_decode(file_get_contents("php://input"), true);

$id_caso = $conn->real_escape_string($data['id_caso'] ?? '');
$estado = $conn->real_escape_string($data['estado'] ?? '');

if (!$id_caso || !$estado) {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

$sql = "UPDATE casolmcd SET estado = ? WHERE id_caso = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $estado, $id_caso);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}
