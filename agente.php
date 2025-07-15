<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel de Agente</title>
  <link rel="stylesheet" href="agente.css" />
</head>
<body>
  <header>
    <h1>Panel de Agente</h1>
  </header>

  <nav>
    <ul>
      <li><a href="#"><img src="lupa.png" alt="Buscar" class="lupa"> Buscar Cliente</a></li>
      <li><a href="nuevocaso.php">Nuevo Caso</a></li>
      <li><a href="basededatos.php">Base de Conocimiento</a></li>
      <li><a href="#">Chatbot</a></li>
      <li><a href="Index.php">Cerrar Sesión</a></li>
    </ul>
  </nav>

  <div class="container">
    <div class="panel">
      <h2>Buscar Cliente</h2>
      <input type="text" id="buscarCliente" placeholder="Ingrese nombre o correo del cliente" />
      <button onclick="buscarCliente()">Buscar</button>
    </div>

    <div class="panel" id="infoCliente" style="display: none;">
      <h2>Información del Cliente</h2>
      <input type="hidden" id="idCliente" />
      <label>Nombre:</label>
      <input type="text" id="nombreCliente" />
      <label>Correo Electrónico:</label>
      <input type="email" id="emailCliente" />
      <label>Teléfono:</label>
      <input type="text" id="telefonoCliente" />
      <button onclick="actualizarCliente()">Actualizar Información</button>
    </div>

    <div class="panel">
      <h2>Historial de Casos</h2>
      <table id="tablaHistorial">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <footer class="footer">
    <img src="logo.jpg" alt="Logo del CRM" class="footer-logo" />
    <p>&copy; Client Merge</p>
  </footer>

  <script>
    async function buscarCliente() {
      const valor = document.getElementById("buscarCliente").value.trim();
      if (!valor) return alert("Ingrese nombre o correo");

      try {
        const res = await fetch(`auth/clientes.php?buscar=${encodeURIComponent(valor)}`);
        const data = await res.json();

        if (data && data.id_cliente) {
          document.getElementById("idCliente").value = data.id_cliente;
          document.getElementById("nombreCliente").value = data.nombre;
          document.getElementById("emailCliente").value = data.correo;
          document.getElementById("telefonoCliente").value = data.telefono;
          document.getElementById("infoCliente").style.display = "block";
        } else {
          alert("Cliente no encontrado.");
        }
      } catch (err) {
        alert("Error al buscar cliente");
        console.error(err);
      }
    }

    async function actualizarCliente() {
      const id = document.getElementById("idCliente").value;
      const nombre = document.getElementById("nombreCliente").value;
      const correo = document.getElementById("emailCliente").value;
      const telefono = document.getElementById("telefonoCliente").value;

      if (!id || !nombre || !correo || !telefono) {
        return alert("Complete todos los campos");
      }

      try {
        const res = await fetch("auth/actualizar_cliente.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id_cliente: id, nombre, correo, telefono })
        });

        const data = await res.json();
        if (data.success) {
          alert("Cliente actualizado");
        } else {
          alert("Error al actualizar: " + data.error);
        }
      } catch (err) {
        alert("Fallo de conexión");
      }
    }

    async function cargarHistorial() {
      try {
        const res = await fetch("auth/historial_agente.php"); // ✅ sin id por URL
        const casos = await res.json();
        const tbody = document.querySelector("#tablaHistorial tbody");
        tbody.innerHTML = "";

        if (casos.length === 0) {
          tbody.innerHTML = `<tr><td colspan="5">No hay casos registrados</td></tr>`;
          return;
        }

        casos.forEach(caso => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${caso.id_caso}</td>
            <td>${caso.nombre}</td>
            <td>${caso.descripcion}</td>
            <td>
              <select>
                <option ${caso.estado === "Abierto" ? "selected" : ""}>Abierto</option>
                <option ${caso.estado === "En Progreso" ? "selected" : ""}>En Progreso</option>
                <option ${caso.estado === "Resuelto" ? "selected" : ""}>Resuelto</option>
                <option ${caso.estado === "Cerrado" ? "selected" : ""}>Cerrado</option>
              </select>
            </td>
            <td><button onclick="actualizarEstado(${caso.id_caso}, this)">Actualizar</button></td>
          `;
          tbody.appendChild(tr);
        });
      } catch (err) {
        console.error("Error cargando historial", err);
      }
    }

    async function actualizarEstado(id_caso, btn) {
      const fila = btn.closest("tr");
      const estado = fila.querySelector("select").value;

      try {
        const res = await fetch("auth/actualizar_estado.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id_caso, estado })
        });

        const data = await res.json();
        if (data.success) {
          alert("✅ Estado actualizado");
          cargarHistorial();
        } else {
          alert("❌ Error: " + data.error);
        }
      } catch (err) {
        alert("Error de conexión");
      }
    }

    window.onload = cargarHistorial;
  </script>
</body>
</html>


