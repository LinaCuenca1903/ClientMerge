<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE");

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "crm_callcenter");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    // Obtener todos los artículos
    $res = $conn->query("SELECT * FROM articulolmcd ORDER BY fecha_creacion DESC");

    $articulos = [];
    while ($row = $res->fetch_assoc()) {
        $articulos[] = $row;
    }

    echo json_encode($articulos);

} elseif ($method === "POST") {
    // Agregar o editar artículo
    $data = json_decode(file_get_contents("php://input"), true);

    $titulo = $conn->real_escape_string($data["titulo"] ?? "");
    $categoria = $conn->real_escape_string($data["categoria"] ?? "");
    $contenido = $conn->real_escape_string($data["contenido"] ?? "");
    $fecha = date("Y-m-d");

    session_start();
    $autor_id = $_SESSION["id_usuario"] ?? 0;


    if (empty($titulo) || empty($categoria) || empty($contenido)) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan campos obligatorios"]);
        exit;
    }

    if (isset($data["id_articulo"])) {
        // Editar artículo
        $id = (int)$data["id_articulo"];
        $sql = "UPDATE articulolmcd 
                SET titulo='$titulo', categoria='$categoria', contenido='$contenido' 
                WHERE id_articulo=$id";
    } else {
        // Insertar nuevo artículo
        $sql = "INSERT INTO articulolmcd (titulo, categoria, contenido, fecha_creacion, autor_id)
                VALUES ('$titulo', '$categoria', '$contenido', '$fecha', $autor_id)";
    }

    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => $conn->error]);
    }

} elseif ($method === "DELETE") {
    // Eliminar artículo
    $data = json_decode(file_get_contents("php://input"), true);
    $id = (int)($data["id_articulo"] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "ID inválido"]);
        exit;
    }

    $sql = "DELETE FROM articulolmcd WHERE id_articulo = $id";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => $conn->error]);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Método no permitido"]);
}

$conn->close();
