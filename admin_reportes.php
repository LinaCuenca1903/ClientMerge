<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Reporte de Casos por Agente</title>
  <style>
    body { font-family: Arial; margin: 20px; background-color: #f9f9f9; }
    h2 { color: #222; }
    .reporte {
      background: #fff;
      padding: 15px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
      border-radius: 5px;
    }
    .reporte h3 { margin-top: 0; color: #125495; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #125495;
      color: white;
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
    .btn-container {
      margin: 15px 0;
    }
    .btn-export {
      padding: 8px 14px;
      background: #125495;
      color: white;
      border: none;
      border-radius: 4px;
      margin-right: 10px;
      cursor: pointer;
    }
    .btn-export:hover {
      background: #0e3e73;
    }
  </style>
</head>
<body>
  <h2>Reporte de Casos por Agente</h2>

  <div class="btn-container">
    <button class="btn-export" onclick="exportarPDF()">Exportar PDF</button>
    <button class="btn-export" onclick="exportarExcel()">Exportar Excel</button>
  </div>

  <div id="ReporteApp">Cargando React…</div>
  <img src="Logo.jpg" alt="Logo" class="logo" />

  <!-- React, Babel -->
  <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
  <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
  <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

  <!-- Librerías exportación -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

  <script type="text/babel">
    const { useState, useEffect } = React;

    function ReporteDetallado() {
      const [datos, setDatos] = useState([]);
      const [error, setError] = useState(null);

      useEffect(() => {
        fetch("auth/reporte_detallado.php")
          .then(res => res.json())
          .then(data => setDatos(data))
          .catch(() => setError("Error al cargar los reportes"));
      }, []);

      if (error) return <p style={{ color: "red" }}>{error}</p>;

      const agrupado = datos.reduce((acc, caso) => {
        if (!acc[caso.agente]) acc[caso.agente] = [];
        acc[caso.agente].push(caso);
        return acc;
      }, {});

      return (
        <div id="reporte-container">
          {Object.keys(agrupado).map(agente => (
            <div key={agente} className="reporte">
              <h3>Agente: {agente}</h3>
              <table>
                <thead>
                  <tr>
                    <th>ID Caso</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                  </tr>
                </thead>
                <tbody>
                  {agrupado[agente].map(caso => (
                    <tr key={caso.id_caso}>
                      <td>{caso.id_caso}</td>
                      <td>{caso.cliente}</td>
                      <td>{caso.estado}</td>
                      <td>{caso.descripcion}</td>
                      <td>{caso.fecha_creacion}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          ))}
        </div>
      );
    }

    ReactDOM.createRoot(document.getElementById("ReporteApp")).render(<ReporteDetallado />);

    // Exportar PDF
    function exportarPDF() {
      const elemento = document.getElementById("reporte-container");
      html2pdf().set({
        margin: 0.5,
        filename: "reporte_casos.pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "in", format: "letter", orientation: "portrait" }
      }).from(elemento).save();
    }

    // Exportar Excel
    function exportarExcel() {
      const tabla = document.querySelectorAll("#reporte-container table");
      const wb = XLSX.utils.book_new();

      tabla.forEach((table, index) => {
        const hoja = XLSX.utils.table_to_sheet(table);
        XLSX.utils.book_append_sheet(wb, hoja, `Agente_${index + 1}`);
      });

      XLSX.writeFile(wb, "reporte_casos.xlsx");
    }
  </script>
</body>
</html>

