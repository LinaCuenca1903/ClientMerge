<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}


$data = json_decode(file_get_contents("php://input"), true);
$id_caso = $conn->real_escape_string($data["id_caso"] ?? '');
$estado = $conn->real_escape_string($data["estado"] ?? '');

if (!$id_caso || !$estado) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$sql = "UPDATE casolmcd SET estado = '$estado' WHERE id_caso = $id_caso";
if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => $conn->error]);
}

$conn->close();
