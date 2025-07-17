<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Iniciar Nuevo Caso</title>
  <link rel="stylesheet" href="nuevocaso.css" />
</head>
<body>
  <header>
    <h1>Iniciar Nuevo Caso</h1>
  </header>

  <div id="NuevoCasoApp"></div>

  <footer>
    <img src="Logo.jpg" class="logo" alt="Logo redondo" />
    <p>Client Merge © 2025</p>
  </footer>

  <!-- React y Babel -->
  <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
  <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
  <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

  <!-- Componente JSX -->
  <script type="text/babel">
    const { useState } = React;

    function NuevoCaso() {
      const [clienteExistente, setClienteExistente] = useState("");
      const [cliente, setCliente] = useState({ nombre: "", correo: "", telefono: "" });
      const [descripcion, setDescripcion] = useState("");
      const [estado, setEstado] = useState("Abierto");
      const [correoVisible, setCorreoVisible] = useState(false);

      const buscarCliente = async () => {
        if (!clienteExistente.trim()) return alert("Ingrese un nombre o ID para buscar.");
        try {
          const res = await fetch("auth/clientes.php?buscar=" + encodeURIComponent(clienteExistente));
          const data = await res.json();
          if (data && data.id_cliente) {
            setCliente(data);
          } else {
            alert("Cliente no encontrado.");
          }
        } catch (err) {
          alert("Error buscando cliente: " + err.message);
        }
      };

      const crearCaso = async () => {
        if (!cliente.nombre || !cliente.correo || !cliente.telefono || !descripcion) {
          return alert("Complete todos los campos requeridos.");
        }

        try {
          const res = await fetch("auth/casos.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              cliente: cliente,
              descripcion,
              estado
            })
          });

          const text = await res.text();
          let data;

          try {
            data = JSON.parse(text);
          } catch (e) {
            alert("Respuesta inesperada del servidor:\n" + text);
            return;
          }

          if (data.success) {
            alert("✅ Caso creado con éxito");
            // Limpiar campos
            setClienteExistente("");
            setCliente({ nombre: "", correo: "", telefono: "" });
            setDescripcion("");
            setEstado("Abierto");
            setCorreoVisible(true);
          } else {
            alert("❌ Error del servidor: " + data.error);
          }
        } catch (err) {
          alert("Error al crear el caso: " + err.message);
        }
      };

      const cancelarFormulario = () => {
        setClienteExistente("");
        setCliente({ nombre: "", correo: "", telefono: "" });
        setDescripcion("");
        setEstado("Abierto");
        setCorreoVisible(false);
      };

      const enviarCorreo = async () => {
        try {
          const res = await fetch("auth/enviar_correo.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(cliente)
          });
          const data = await res.json();
          if (data.success) {
            alert("📧 Correo enviado al cliente");
          } else {
            alert("❌ Error al enviar correo: " + data.error);
          }
        } catch (err) {
          alert("❌ Fallo al conectar con el servidor");
        }
      };

      return (
        <div className="container">
          <div className="panel">
            <h2>Información del Cliente</h2>
            <label>Buscar Cliente:</label>
            <input
              type="text"
              value={clienteExistente}
              onChange={(e) => setClienteExistente(e.target.value)}
              placeholder="Ingrese nombre o ID"
            />
            <button onClick={buscarCliente}>Buscar</button>

            <h3>O Ingrese un Nuevo Cliente:</h3>
            <label>Nombre:</label>
            <input
              type="text"
              value={cliente.nombre}
              onChange={(e) => setCliente({ ...cliente, nombre: e.target.value })}
              placeholder="Nombre del Cliente"
            />
            <label>Correo Electrónico:</label>
            <input
              type="email"
              value={cliente.correo}
              onChange={(e) => setCliente({ ...cliente, correo: e.target.value })}
              placeholder="Correo del Cliente"
            />
            <label>Teléfono:</label>
            <input
              type="text"
              value={cliente.telefono}
              onChange={(e) => setCliente({ ...cliente, telefono: e.target.value })}
              placeholder="Teléfono del Cliente"
            />
          </div>

          <div className="panel">
            <h2>Detalles del Caso</h2>
            <label>Descripción del Caso:</label>
            <textarea
              value={descripcion}
              onChange={(e) => setDescripcion(e.target.value)}
              placeholder="Detalles del caso"
            ></textarea>
            <label>Estado:</label>
            <select value={estado} onChange={(e) => setEstado(e.target.value)}>
              <option value="Abierto">Abierto</option>
              <option value="En Progreso">En Progreso</option>
              <option value="Resuelto">Resuelto</option>
              <option value="Cerrado">Cerrado</option>
            </select>

            <button onClick={crearCaso}>Crear Caso</button>
            <button onClick={cancelarFormulario}>Cancelar</button>
          </div>

          {correoVisible && (
            <div className="panel">
              <h2>Enviar Correo</h2>
              <p>En construccion</p>
              
            </div>
          )}
        </div>
      );
    }

    ReactDOM.createRoot(document.getElementById("NuevoCasoApp")).render(<NuevoCaso />);
  </script>
</body>
</html>
