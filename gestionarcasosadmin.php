<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Casos - Admin</title>
  <link rel="stylesheet" href="gestionarcasosadmin.css" />
</head>
<body>
  <header>
    <h1>Gestión de Casos</h1>
    <nav>
      <ul>
        <li><a href="admin.php">Inicio</a></li>
        <li><a href="admin_casos.php">Revisar Casos</a></li>
        <li><a href="admin_reportes.php">Reportes</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section class="casos">
      <h2>Casos Actuales</h2>
      <div id="ReasignarCasosApp">Cargando...</div>
    </section>
  </main>

  <footer>
    <img src="Logo.jpg" class="logo" alt="Logo redondo" />
    <p>Client Merge © 2025</p>
  </footer>

  <!-- React y Babel -->
  <script src="https://unpkg.com/react@17/umd/react.development.js"></script>
  <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js"></script>
  <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

  <!-- Script React -->
  <script type="text/babel">
    const { useEffect, useState } = React;

    function ReasignarCasos() {
      const [casos, setCasos] = useState([]);
      const [usuarios, setUsuarios] = useState([]);

      useEffect(() => {
        fetch("auth/obtener_casos.php")
          .then(res => res.json())
          .then(setCasos);

        fetch("auth/obtener_usuarios.php")
          .then(res => res.json())
          .then(setUsuarios);
      }, []);

      const reasignarCaso = async (id_caso, id_usuario) => {
        const res = await fetch("auth/reasignar_caso.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id_caso, id_usuario }),
        });
        const data = await res.json();
        if (data.success) {
          alert("✅ Caso reasignado");
          setCasos(prev =>
            prev.map(c =>
              c.id_caso === id_caso
                ? { ...c, agente: usuarios.find(u => u.id_usuario == id_usuario).nombre }
                : c
            )
          );
        } else {
          alert("❌ Error al reasignar");
        }
      };

      return (
        <table>
          <thead>
            <tr>
              <th>ID Caso</th>
              <th>Cliente</th>
              <th>Estado</th>
              <th>Agente Asignado</th>
              <th>Reasignar A</th>
            </tr>
          </thead>
          <tbody>
            {casos.map(caso => (
              <tr key={caso.id_caso}>
                <td>{caso.id_caso}</td>
                <td>{caso.cliente}</td>
                <td>{caso.estado}</td>
                <td>{caso.agente}</td>
                <td>
                  <select onChange={(e) => reasignarCaso(caso.id_caso, e.target.value)}>
                    <option value="">-- Seleccionar --</option>
                    {usuarios.map(u => (
                      <option key={u.id_usuario} value={u.id_usuario}>
                        {u.nombre} ({u.rol})
                      </option>
                    ))}
                  </select>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      );
    }

    ReactDOM.render(<ReasignarCasos />, document.getElementById("ReasignarCasosApp"));
  </script>
</body>
</html>
