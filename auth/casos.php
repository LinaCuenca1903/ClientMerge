<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $conn->real_escape_string($data['cliente']['nombre'] ?? '');
$correo = $conn->real_escape_string($data['cliente']['correo'] ?? '');
$telefono = $conn->real_escape_string($data['cliente']['telefono'] ?? '');
$descripcion = $conn->real_escape_string($data['descripcion'] ?? '');
$estado = $conn->real_escape_string($data['estado'] ?? 'Abierto');
$fecha = date('Y-m-d');

$id_agente = $_SESSION['id_usuario'] ?? null;

if (!$id_agente) {
    echo json_encode(["error" => "Sesión no iniciada"]);
    exit;
}

// Verificar si el cliente existe
$res = $conn->query("SELECT id_cliente FROM clientelmcd WHERE correo = '$correo'");
if ($res->num_rows > 0) {
    $cliente = $res->fetch_assoc();
    $id_cliente = $cliente['id_cliente'];
} else {
    $conn->query("INSERT INTO clientelmcd (nombre, correo, telefono) VALUES ('$nombre', '$correo', '$telefono')");
    $id_cliente = $conn->insert_id;
}

// Insertar el caso
$sql = "INSERT INTO casolmcd (descripcion, estado, fecha_creacion, id_cliente, id_agente) 
        VALUES ('$descripcion', '$estado', '$fecha', $id_cliente, $id_agente)";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => $conn->error]);
}

