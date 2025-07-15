<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "crm_callcenter");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id_caso"] ?? null;
$descripcion = $data["descripcion"] ?? "";

if (!$id || $descripcion === "") {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

$stmt = $conn->prepare("UPDATE casolmcd SET descripcion = ? WHERE id_caso = ?");
$stmt->bind_param("si", $descripcion, $id);
$success = $stmt->execute();

echo json_encode(["success" => $success]);
$stmt->close();
$conn->close();
