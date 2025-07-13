<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");

$buscar = $_GET["buscar"] ?? '';

if (!$buscar) {
    echo json_encode(["error" => "No se proporcionó criterio de búsqueda"]);
    exit;
}

$buscar = $conn->real_escape_string($buscar);
$result = $conn->query("SELECT * FROM clientelmcd WHERE id_cliente = '$buscar' OR nombre LIKE '%$buscar%' LIMIT 1");

if ($result && $result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "Cliente no encontrado"]);
}
