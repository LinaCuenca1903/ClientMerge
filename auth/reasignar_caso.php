<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$conn = new mysqli("localhost", "root", "", "crm_callcenter");
$id_caso = $data["id_caso"];
$id_usuario = $data["id_usuario"];

$stmt = $conn->prepare("UPDATE casolmcd SET id_agente = ? WHERE id_caso = ?");
$stmt->bind_param("ii", $id_usuario, $id_caso);

$response = [];
if ($stmt->execute()) {
    $response["success"] = true;
} else {
    $response["success"] = false;
}

echo json_encode($response);
$conn->close();
