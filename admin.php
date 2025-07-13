<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para contar usuarios
$sql = "SELECT COUNT(*) AS total FROM usuariolmcd";
$result = $conn->query($sql);
$total_usuarios = 0;

if ($result && $row = $result->fetch_assoc()) {
    $total_usuarios = $row['total'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - Administrador</title>
    <link rel="stylesheet" href="admin.styles.css">
</head>
<body>
    <header>
        <h1>Panel de Administrador</h1>
        <nav>
            <ul>
                <li><a href="Usuarios.php">Usuarios</a></li>
                <li><a href="gestionarcasosadmin.html">Casos</a></li>
                <li><a href="baseconocimientoadmin.html">Base de Conocimiento</a></li>
                <li><a href="admin_reportes.html">Reportes</a></li>
                <li><a href="Index.html">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="dashboard">
            <h2>Bienvenido, Administrador</h2>
            <div class="grid">
                <div class="card">
                    <h3>Usuarios Registrados</h3>
                    <p><?php echo $total_usuarios; ?></p>
                </div>
                <div class="card">
                    <h3>Casos Activos</h3>
                    <p>35</p>
                </div>
                <div class="card">
                    <h3>Solicitudes Pendientes</h3>
                    <p>18</p>
                </div>
                <div class="card">
                    <h3>Base de Conocimiento</h3>
                    <p>55 Artículos</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>Client Merge</p>
        <img src="Logo.jpg" alt="Logo del CRM" class="logo-small">
    </footer>
</body>
</html>
