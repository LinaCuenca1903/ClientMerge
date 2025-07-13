const { useState } = React;

function NuevoCaso() {
  const [clienteExistente, setClienteExistente] = useState("");
  const [cliente, setCliente] = useState({ nombre: "", correo: "", telefono: "" });
  const [descripcion, setDescripcion] = useState("");
  const [prioridad, setPrioridad] = useState("Baja");
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
      console.error("Error al buscar cliente", err);
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
          prioridad
        })
      });

      const data = await res.json();
      if (data.success) {
        alert("✅ Caso creado con éxito");
        setCorreoVisible(true);
      } else {
        alert("Error: " + data.error);
      }
    } catch (err) {
      console.error("Error al crear caso", err);
    }
  };

  return (
    <>
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
          <label>Prioridad:</label>
          <select value={prioridad} onChange={(e) => setPrioridad(e.target.value)}>
            <option value="Baja">Baja</option>
            <option value="Media">Media</option>
            <option value="Alta">Alta</option>
          </select>
          <button onClick={crearCaso}>Crear Caso</button>
        </div>
      </div>

      {correoVisible && (
        <div className="container">
          <div className="panel">
            <h2>Enviar Correo</h2>
            <p>El caso ha sido creado con éxito. ¿Desea enviar un correo al cliente?</p>
            <button onClick={() => alert("Correo enviado al cliente")}>Enviar Correo</button>
          </div>
        </div>
      )}
    </>
  );
}

ReactDOM.createRoot(document.getElementById("NuevoCasoApp")).render(<NuevoCaso />);
