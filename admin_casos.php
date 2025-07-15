<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Casos - Administrador</title>
  <style>
    body {
      font-family: Arial;
      margin: 20px;
      background: #e8f0fe;
    }
    h2 {
      color: #333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
    th {
      background: #125495;
      color: white;
    }
    button {
      padding: 5px 10px;
      margin-right: 5px;
      background: #125495;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background: #0e3e73;
    }
    .logo {
      margin-top: 40px;
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 60px;
      height: 60px;
      border-radius: 50%;
    }
    .modal {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 6px;
      width: 300px;
    }
    textarea {
      width: 100%;
      height: 80px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <h2>Gestión de Casos</h2>
  <div id="CasosAdminApp">Cargando React...</div>
  <img src="Logo.jpg" alt="Logo" class="logo" />

  <!-- React y Babel -->
  <script src="https://unpkg.com/react@17/umd/react.development.js"></script>
  <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js"></script>
  <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

  <!-- Componente React -->
  <script type="text/babel">
    const { useState, useEffect } = React;

    function CasosAdmin() {
      const [casos, setCasos] = useState([]);
      const [editando, setEditando] = useState(null);
      const [descripcionTemp, setDescripcionTemp] = useState("");

      // Obtener casos del backend
      useEffect(() => {
        fetch("auth/casos_admin.php")
          .then(res => res.json())
          .then(data => setCasos(data))
          .catch(() => alert("❌ Error al cargar los casos"));
      }, []);

      // Actualizar estado del caso
      const actualizarEstado = async (id_caso, estado) => {
        const res = await fetch("auth/actualizar_estado.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id_caso, estado }),
        });
        const data = await res.json();
        if (data.success) {
          setCasos(prev =>
            prev.map(c =>
              c.id_caso === id_caso ? { ...c, estado } : c
            )
          );
          alert("✅ Estado actualizado");
        } else {
          alert("❌ Error al actualizar estado");
        }
      };

      // Guardar nueva descripción
      const guardarDescripcion = async () => {
        const res = await fetch("auth/editar_caso.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            id_caso: editando.id_caso,
            descripcion: descripcionTemp
          }),
        });
        const data = await res.json();
        if (data.success) {
          setCasos(prev =>
            prev.map(c =>
              c.id_caso === editando.id_caso
                ? { ...c, descripcion: descripcionTemp }
                : c
            )
          );
          alert("✅ Descripción actualizada");
          setEditando(null);
        } else {
          alert("❌ Error al guardar");
        }
      };

      return (
        <div>
          <table>
            <thead>
              <tr>
                <th>ID Caso</th>
                <th>Cliente</th>
                <th>Agente</th>
                <th>Descripción</th>
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
                  <td>{caso.descripcion}</td>
                  <td>
                    <select
                      value={caso.estado}
                      onChange={(e) =>
                        actualizarEstado(caso.id_caso, e.target.value)
                      }
                    >
                      <option value="Abierto">Abierto</option>
                      <option value="En Progreso">En Progreso</option>
                      <option value="Resuelto">Resuelto</option>
                      <option value="Cerrado">Cerrado</option>
                    </select>
                  </td>
                  <td>{caso.fecha_creacion}</td>
                  <td>
                    <button onClick={() => alert(`Caso: ${caso.descripcion}`)}>Ver</button>
                    <button onClick={() => {
                      setEditando(caso);
                      setDescripcionTemp(caso.descripcion);
                    }}>Editar</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>

          {editando && (
            <div className="modal">
              <div className="modal-content">
                <h4>Editar Caso #{editando.id_caso}</h4>
                <textarea
                  value={descripcionTemp}
                  onChange={(e) => setDescripcionTemp(e.target.value)}
                ></textarea>
                <br />
                <button onClick={guardarDescripcion}>Guardar</button>
                <button onClick={() => setEditando(null)}>Cancelar</button>
              </div>
            </div>
          )}
        </div>
      );
    }

    ReactDOM.render(<CasosAdmin />, document.getElementById("CasosAdminApp"));
  </script>
</body>
</html>
