<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Conexión fallida"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id_caso = $data["id_caso"] ?? null;

if (!$id_caso) {
    echo json_encode(["success" => false, "error" => "ID inválido"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM casolmcd WHERE id_caso = ?");
$stmt->bind_param("i", $id_caso);
$success = $stmt->execute();

echo json_encode(["success" => $success]);
$conn->close();
