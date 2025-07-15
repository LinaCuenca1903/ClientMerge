const { useState, useEffect } = React;

function CasosAdmin() {
  const [casos, setCasos] = useState([]);

  useEffect(() => {
    cargarCasos();
  }, []);

  const cargarCasos = async () => {
    try {
      const res = await fetch("auth/casos_admin.php");
      const data = await res.json();
      setCasos(data);
    } catch (err) {
      alert("Error al cargar casos");
      console.error(err);
    }
  };

  const actualizarEstado = async (id_caso, nuevoEstado) => {
    try {
      const res = await fetch("auth/actualizar_estado.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_caso, estado: nuevoEstado }),
      });
      const data = await res.json();
      if (data.success) {
        alert("Estado actualizado");
        cargarCasos();
      } else {
        alert("Error: " + data.error);
      }
    } catch (err) {
      alert("Error de conexión");
    }
  };

  return (
    <table>
      <thead>
        <tr>
          <th>ID Caso</th>
          <th>Cliente</th>
          <th>Agente</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        {casos.map(caso => (
          <tr key={caso.id_caso}>
            <td>{caso.id_caso}</td>
            <td>{caso.cliente}</td>
            <td>{caso.agente}</td>
            <td>
              <select
                value={caso.estado}
                onChange={(e) => actualizarEstado(caso.id_caso, e.target.value)}
              >
                <option value="Abierto">Abierto</option>
                <option value="En Progreso">En Progreso</option>
                <option value="Resuelto">Resuelto</option>
                <option value="Cerrado">Cerrado</option>
              </select>
            </td>
            <td>{caso.fecha}</td>
            <td>
              <button onClick={() => alert(JSON.stringify(caso, null, 2))}>Ver</button>
            </td>
          </tr>
        ))}
      </tbody>
    </table>
  );
}

ReactDOM.createRoot(document.getElementById("CasosAdminApp")).render(<CasosAdmin />);
