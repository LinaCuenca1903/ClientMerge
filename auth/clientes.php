<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");

if ($conn->connect_error) {
    echo json_encode(["error" => "Conexión fallida"]);
    exit;
}

$buscar = $_GET['buscar'] ?? '';

if ($buscar) {
    $sql = "SELECT * FROM clientelmcd WHERE id_cliente = ? OR nombre LIKE ?";
    $stmt = $conn->prepare($sql);
    $like = "%$buscar%";
    $stmt->bind_param("is", $buscar, $like);
    $stmt->execute();
    $res = $stmt->get_result();
    echo json_encode($res->fetch_assoc());
    exit;
}

echo json_encode(["error" => "No se especificó búsqueda"]);
