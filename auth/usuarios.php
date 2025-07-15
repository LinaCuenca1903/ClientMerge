<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

// ✅ GET: Listar usuarios
if ($method === 'GET') {
    $sql = "SELECT id_usuario, nombre, correo, rol FROM usuariolmcd";
    $result = $conn->query($sql);
    $usuarios = [];

    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    echo json_encode($usuarios);
    exit;
}

// ✅ POST: Insertar, actualizar o eliminar
if ($method === 'POST') {
    // ✅ Eliminar si viene el flag 'eliminar' activado
    if (isset($input['eliminar']) && $input['eliminar'] === true) {
        $id_usuario = intval($input['id_usuario'] ?? 0);

        if ($id_usuario <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID inválido para eliminar"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM usuariolmcd WHERE id_usuario=?");
        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => $stmt->error]);
        }

        $stmt->close();
        exit;
    }

    // ✅ Insertar o actualizar
    $id = isset($input['id_usuario']) ? intval($input['id_usuario']) : 0;
    $nombre = $input['nombre'] ?? '';
    $correo = $input['correo'] ?? '';
    $rol = $input['rol'] ?? '';
    $password = isset($input['contrasena']) && $input['contrasena'] !== ''
        ? password_hash($input['contrasena'], PASSWORD_BCRYPT)
        : null;

    if ($id > 0) {
        // Actualizar usuario
        if ($password) {
            $stmt = $conn->prepare("UPDATE usuariolmcd SET nombre=?, correo=?, rol=?, contrasena=? WHERE id_usuario=?");
            $stmt->bind_param("ssssi", $nombre, $correo, $rol, $password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE usuariolmcd SET nombre=?, correo=?, rol=? WHERE id_usuario=?");
            $stmt->bind_param("sssi", $nombre, $correo, $rol, $id);
        }
    } else {
        // Insertar nuevo usuario
        if (!$password) {
            echo json_encode(["error" => "Falta la contraseña"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO usuariolmcd (nombre, correo, rol, contrasena) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $correo, $rol, $password);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => $stmt->error]);
    }

    $stmt->close();
    exit;
}

// ❌ Método no permitido
http_response_code(405);
echo json_encode(["error" => "Método no permitido"]);
?>
