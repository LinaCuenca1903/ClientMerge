<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "crm_callcenter");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Total usuarios
$sql = "SELECT COUNT(*) AS total FROM usuariolmcd";
$result = $conn->query($sql);
$total_usuarios = ($result && $row = $result->fetch_assoc()) ? $row['total'] : 0;

// Casos Activos (estado = 'Abierto')
$sql = "SELECT COUNT(*) AS activos FROM casolmcd WHERE estado = 'Abierto'";
$result = $conn->query($sql);
$casos_activos = ($result && $row = $result->fetch_assoc()) ? $row['activos'] : 0;

// Solicitudes Pendientes (estado = 'En Progreso')
$sql = "SELECT COUNT(*) AS pendientes FROM casolmcd WHERE estado = 'En Progreso'";
$result = $conn->query($sql);
$casos_pendientes = ($result && $row = $result->fetch_assoc()) ? $row['pendientes'] : 0;

// Artículos en base de conocimiento
$sql = "SELECT COUNT(*) AS total FROM articulolmcd";
$result = $conn->query($sql);
$total_articulos = ($result && $row = $result->fetch_assoc()) ? $row['total'] : 0;

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
                <li><a href="gestionarcasosadmin.php">Casos</a></li>
                <li><a href="baseconocimientoadmin.php">Base de Conocimiento</a></li>
                <li><a href="admin_reportes.php">Reportes</a></li>
                <li><a href="Index.php">Cerrar Sesión</a></li>
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
                    <p><?php echo $casos_activos; ?></p>
                </div>
                <div class="card">
                    <h3>Solicitudes Pendientes</h3>
                    <p><?php echo $casos_pendientes; ?></p>
                </div>
                <div class="card">
                    <h3>Base de Conocimiento</h3>
                    <p><?php echo $total_articulos; ?> Artículos</p>
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
