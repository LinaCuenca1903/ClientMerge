<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");

$data = json_decode(file_get_contents("php://input"), true);

//Actualizar cliente en caso agente

$id_cliente = intval($data['id_cliente'] ?? 0);
$nombre = $conn->real_escape_string($data['nombre'] ?? '');
$correo = $conn->real_escape_string($data['correo'] ?? '');
$telefono = $conn->real_escape_string($data['telefono'] ?? '');

if ($id_cliente > 0 && $nombre && $correo && $telefono) {
    $sql = "UPDATE clientelmcd 
            SET nombre = '$nombre', correo = '$correo', telefono = '$telefono' 
            WHERE id_cliente = $id_cliente";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos"]);
}
