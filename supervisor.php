<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel del Supervisor</title>
  <link rel="stylesheet" href="supervisor.css" />
</head>
<body>
  <nav class="navbar">
    <ul>
      <li><a href="#" onclick="toggleMetricas()">Métricas</a></li>
      <li><a href="index.php">Cerrar Sesión</a></li>
    </ul>
  </nav>

  <section class="container">
    <h1>Casos en Curso</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Agente</th>
          <th>Descripción</th>
          <th>Estado</th>
          <th>Reasignar</th>
        </tr>
      </thead>
      <tbody id="tablaCasos">
        <tr><td colspan="5">Cargando casos...</td></tr>
      </tbody>
    </table>
  </section>

  <!-- Métricas -->
  <section class="container" id="metricas" style="display: none;">
    <h2>Métricas de Casos</h2>
    <table>
      <thead>
        <tr>
          <th>Agente</th>
          <th>Casos Abiertos</th>
          <th>Casos Cerrados</th>
        </tr>
      </thead>
      <tbody id="tablaMetricas">
        <tr><td colspan="3">Cargando métricas...</td></tr>
      </tbody>
    </table>
  </section>

  <footer class="footer">
    <img src="logo.jpg" alt="Logo del CRM" class="footer-logo" />
    <p>&copy; Client Merge</p>
  </footer>

  <script>
    const tablaCasos = document.getElementById("tablaCasos");
    const tablaMetricas = document.getElementById("tablaMetricas");

    // Cargar Casos
    fetch("auth/casos_admin.php")
      .then(res => res.json())
      .then(data => {
        tablaCasos.innerHTML = "";
        data.forEach(caso => {
          const fila = document.createElement("tr");
          fila.innerHTML = `
            <td>${caso.id_caso}</td>
            <td>${caso.agente}</td>
            <td>${caso.descripcion}</td>
            <td>${caso.estado}</td>
            <td>
              <select onchange="reasignarCaso(${caso.id_caso}, this.value)">
                <option value="">-- Seleccionar --</option>
              </select>
            </td>
          `;
          tablaCasos.appendChild(fila);
        });

        // Cargar opciones de agentes
        fetch("auth/usuarios.php")
          .then(res => res.json())
          .then(usuarios => {
            const selects = document.querySelectorAll("select");
            selects.forEach(select => {
              usuarios.forEach(user => {
                if (user.rol === "agente" || user.rol === "supervisor") {
                  const option = document.createElement("option");
                  option.value = user.id_usuario;
                  option.textContent = user.nombre;
                  select.appendChild(option);
                }
              });
            });
          });
      })
      .catch(err => {
        tablaCasos.innerHTML = `<tr><td colspan="5">Error al cargar casos</td></tr>`;
      });

    function reasignarCaso(id_caso, nuevo_id) {
  if (!nuevo_id) return;

  fetch("auth/reasignarcasosup.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_caso: id_caso, id_agente: nuevo_id }) // ✅ corregido
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Caso reasignado correctamente");
        location.reload();
      } else {
        alert("Error al reasignar caso: " + (data.error || ""));
      }
    })
    .catch(() => alert("Error de conexión"));
}
    // Mostrar/Ocultar Métricas
    function toggleMetricas() {
      const met = document.getElementById("metricas");
      met.style.display = met.style.display === "none" ? "block" : "none";

      // Cargar métricas solo si están vacías
      if (tablaMetricas.innerHTML.includes("Cargando")) {
        fetch("auth/metricas.php")
          .then(res => res.json())
          .then(data => {
            tablaMetricas.innerHTML = "";
            data.forEach(m => {
              const fila = document.createElement("tr");
              fila.innerHTML = `
                <td>${m.nombre}</td>
                <td>${m.abiertos}</td>
                <td>${m.cerrados}</td>
              `;
              tablaMetricas.appendChild(fila);
            });
          })
          .catch(() => {
            tablaMetricas.innerHTML = `<tr><td colspan="3">Error al cargar métricas</td></tr>`;
          });
      }
    }
  </script>
</body>
</html>
