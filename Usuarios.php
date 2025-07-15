<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel de Usuarios</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      text-align: center;
    }
    h2 { color: #333; }
    #userTable {
      width: 80%;
      margin: auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #125495;
      color: white;
    }
    button {
      padding: 8px 12px;
      margin: 5px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }
    .btn-delete { background-color: #de1212; color: white; }
    .btn-edit { background-color: green; color: white; }
    .btn-add { background-color: #125495; color: white; }
    .btn-close { background-color: gray; }

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      background-color: white;
      margin: 10% auto;
      padding: 20px;
      width: 40%;
      border-radius: 8px;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
    }
    input, select {
      width: 90%;
      padding: 8px;
      margin: 5px 0;
    }

    footer {
      background: #125495;
      color: white;
      padding: 10px;
      margin-top: 20px;
      text-align: center;
    }
    .logo {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>

<h2>Panel de Usuarios</h2>
<button onclick="location.href='admin.php'">🔙 Volver</button>
<button class="btn-add" onclick="openUserForm()">➕ Agregar Usuario</button>

<table id="userTable">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Email</th>
      <th>Rol</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<div id="userModal" class="modal">
  <div class="modal-content">
    <h3 id="modalTitle">Agregar Usuario</h3>
    <input type="hidden" id="idEditar">
    <label>Nombre:</label>
    <input type="text" id="nombre">
    <label>Email:</label>
    <input type="email" id="email">
    <label>Rol:</label>
    <select id="rol">
      <option value="Agente">Agente</option>
      <option value="Administrador">Administrador</option>
      <option value="Supervisor">Supervisor</option>
    </select>
    
    <div id="passwordGroup">
      <label>Contraseña:</label>
      <input type="password" id="password">
    </div>

    <button onclick="guardarUsuario()">Guardar</button>
    <button class="btn-close" onclick="closeUserForm()">Cancelar</button>
  </div>
</div>

<footer>
  <img src="Logo.jpg" alt="Logo" class="logo">
  <p>ClientMerge</p>
</footer>

<script>
async function loadUsers() {
  const response = await fetch("auth/usuarios.php");
  const users = await response.json();
  const tableBody = document.querySelector("#userTable tbody");
  tableBody.innerHTML = "";

  users.forEach(user => {
    const row = `<tr>
      <td>${user.id_usuario}</td>
      <td>${user.nombre}</td>
      <td>${user.correo}</td>
      <td>${user.rol}</td>
      <td>
        <button class="btn-edit" onclick="cargarUsuario(${user.id_usuario}, '${user.nombre}', '${user.correo}', '${user.rol}')">✏️ Editar</button>
        <button class="btn-delete" onclick="deleteUser(${user.id_usuario})">🗑️ Eliminar</button>
      </td>
    </tr>`;
    tableBody.innerHTML += row;
  });
}

function openUserForm() {
  document.getElementById("modalTitle").innerText = "Agregar Usuario";
  document.getElementById("userModal").style.display = "block";
  document.getElementById("idEditar").value = "";
  document.getElementById("nombre").value = "";
  document.getElementById("email").value = "";
  document.getElementById("rol").value = "";
  document.getElementById("password").value = "";

  // Mostrar campo de contraseña al agregar
  document.getElementById("passwordGroup").style.display = "block";
}

function closeUserForm() {
  document.getElementById("userModal").style.display = "none";
}

function cargarUsuario(id, nombre, correo, rol) {
  document.getElementById("modalTitle").innerText = "Editar Usuario";
  document.getElementById("idEditar").value = id;
  document.getElementById("nombre").value = nombre;
  document.getElementById("email").value = correo;
  document.getElementById("rol").value = rol;
  document.getElementById("password").value = "";

  // Ocultar campo de contraseña al editar
  document.getElementById("passwordGroup").style.display = "none";

  document.getElementById("userModal").style.display = "block";
}

async function guardarUsuario() {
  const id = document.getElementById("idEditar").value;
  const nombre = document.getElementById("nombre").value;
  const correo = document.getElementById("email").value;
  const rol = document.getElementById("rol").value;
  const contrasena = document.getElementById("password").value;

  const datos = { nombre, correo, rol };
  if (contrasena !== "") datos.contrasena = contrasena;
  if (id !== "") datos.id_usuario = id;

  const response = await fetch("auth/usuarios.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(datos)
  });

  const resData = await response.json();

  if (response.ok) {
    alert("✅ Usuario guardado correctamente");
    closeUserForm();
    loadUsers();
  } else {
    alert("❌ Error: " + resData.error);
  }
}
// Funcion eliminar

async function deleteUser(id) {
  if (!confirm("¿Estás seguro de eliminar este usuario?")) return;

  const response = await fetch("auth/usuarios.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ eliminar: true, id_usuario: id }) // usa POST
  });

  const resData = await response.json();

  if (response.ok && resData.success) {
    alert("🗑️ Usuario eliminado");
    loadUsers(); // recarga la tabla
  } else {
    alert("❌ Error al eliminar: " + (resData.error || "Error desconocido"));
  }
}


loadUsers();
</script>

</body>
</html>
