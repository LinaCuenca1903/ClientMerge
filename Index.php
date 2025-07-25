<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - Inicio</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Pantalla de Bienvenida -->
    <div class="welcome-screen" id="welcomeScreen">
        <img src="Logo.jpg" alt="Logo del CRM" class="logo-small">
        <button onclick="showLogin()" class="btn">Ingresar</button>
    </div>

    <!-- Contenedor Principal -->
    <div class="container" id="mainContent" style="display: none;">
        <nav class="navbar">
            <ul>
                <li><a href="help.html">Help</a></li>
            </ul>
        </nav>

        <!-- Formulario de Inicio de Sesión -->
        <div class="form-container" id="loginForm">
            <form id="loginFormHtml">
                <img src="Logo.jpg" alt="Logo del CRM" class="logo">
                <h2>Iniciar Sesión</h2>
                <input type="email" name="correo" id="correoLogin" placeholder="Correo" required>
                <input type="password" name="clave" id="claveLogin" placeholder="Contraseña" required>
                <button class="btn1" type="submit">Ingresar</button>
                <a href="#" onclick="showRecover()">¿Olvidaste tu contraseña?</a>
                <a href="#" onclick="showRegister()">Registrarse</a>
                <div id="react-root"></div>
            </form>
        </div>

        <!-- Formulario de Registro -->
        <div class="form-container" id="registerForm" style="display: none;">
            <form action="auth/register.php" method="POST">
                <h2>Registro de Usuario</h2>
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="email" name="correo" placeholder="Correo" required>
                <select name="rol" required>
                    <option value="">Seleccione su Rol</option>
                    <option value="agente">Agente</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="admin">Administrador</option>
                </select>
                <input type="password" name="clave" placeholder="Contraseña" required>
                <input type="password" name="confirmar" placeholder="Confirmar Contraseña" required>
                <button class="btn1" type="submit">Registrar</button>
            </form>
            <button class="btn1 cancel" onclick="showLogin()">Cancelar</button>
        </div>

        <!-- Formulario de Recuperación de Contraseña -->
        <div class="form-container" id="recoverForm" style="display: none;">
            <form action="#" method="POST">
                <h2>Recuperar Contraseña</h2>
                <input type="email" name="correo" placeholder="Correo" required>
                <button class="btn1" type="submit">Enviar</button>
                <button class="btn1 cancel" onclick="showLogin()">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="script.js"></script>
    <script type="module" src="react-login/dist/assets/index-D8b4DHJx.css"></script>

    <script>
        const params = new URLSearchParams(window.location.search);
        if (params.get("error") === "clave") {
            alert("⚠️ Las contraseñas no coinciden.");
            showRegister();
        }
    </script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const loginForm = document.getElementById("loginFormHtml");

                if (loginForm) {
                    loginForm.addEventListener("submit", async function (e) {
                        e.preventDefault(); // Evita que recargue

                        const formData = new FormData(loginForm);

                        try {
                            const response = await fetch("auth/login.php", {
                                method: "POST",
                                body: formData,
                            });

                            const result = await response.text();

                    try {
                        const data = JSON.parse(result);
                        const rol = data.rol?.trim();
                        const id = data.id_usuario;

                        if (rol && id) {
                            sessionStorage.setItem("id_usuario", id); // Guarda el ID del usuario

                if (rol === "agente") {
                    window.location.href = "agente.php";
                } else if (rol === "supervisor") {
                    window.location.href = "supervisor.php";
                } else if (rol === "Administrador") {
                    window.location.href = "admin.php";
                } else {
                    alert("⚠️ Rol no válido.");
                }
            } else {
                alert("⚠️ Datos de usuario incompletos.");
            }
        } catch (e) {
            alert("⚠️ Correo o contraseña incorrectos.");
        }

                        } catch (error) {
                            alert("❌ Error de conexión al servidor.");
                            console.error(error);
                        }
                    });
                }
            });
</script>

    </script>

    <footer class="footer">
        ClientMerge® 2025
    </footer>
</body>
</html>

