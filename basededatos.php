<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Base de Conocimiento</title>
  <link rel="stylesheet" href="basededatos.css" />
</head>
<body>
  <header>
    <h1>Base de Conocimiento</h1>
  </header>

  <div class="container">
    <input type="text" id="buscador" placeholder="Buscar artículo..." />
    <table>
      <thead>
        <tr>
          <th>Título</th>
          <th>Categoría</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody id="tablaArticulos">
        <tr><td colspan="3">Cargando artículos...</td></tr>
      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div id="articleModal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 id="modalTitle"></h2>
      <p id="modalContent"></p>
    </div>
  </div>

  <footer>
    <img src="Logo.jpg" class="logo" alt="Logo redondo" />
    <p>Client Merge © 2025</p>
  </footer>

  <script>
    const tabla = document.getElementById("tablaArticulos");
    const buscador = document.getElementById("buscador");
    let articulosOriginales = [];

    // Cargar los artículos desde PHP
    fetch("auth/articulos.php")
      .then(res => res.json())
      .then(data => {
        articulosOriginales = data;
        mostrarArticulos(data);
      })
      .catch(error => {
        console.error("Error cargando artículos:", error);
        tabla.innerHTML = `<tr><td colspan="3">Error al cargar artículos</td></tr>`;
      });

    // Mostrar artículos
    function mostrarArticulos(lista) {
      tabla.innerHTML = "";
      if (lista.length === 0) {
        tabla.innerHTML = `<tr><td colspan="3">No se encontraron artículos</td></tr>`;
        return;
      }

      lista.forEach(articulo => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${articulo.titulo}</td>
          <td>${articulo.categoria}</td>
          <td>${articulo.fecha_creacion}</td>
        `;
        tr.onclick = () => openModal(articulo.titulo, articulo.contenido);
        tabla.appendChild(tr);
      });
    }

    // Filtro de artículos
    buscador.addEventListener("keyup", () => {
      const texto = buscador.value.toLowerCase();
      const filtrados = articulosOriginales.filter(a =>
        a.titulo.toLowerCase().includes(texto) ||
        a.categoria.toLowerCase().includes(texto) ||
        a.fecha_creacion.toLowerCase().includes(texto)
      );
      mostrarArticulos(filtrados);
    });

    // Abrir modal
    function openModal(titulo, contenido) {
      document.getElementById("modalTitle").textContent = titulo;
      document.getElementById("modalContent").textContent = contenido;
      document.getElementById("articleModal").style.display = "block";
    }

    // Cerrar modal
    function closeModal() {
      document.getElementById("articleModal").style.display = "none";
    }
  </script>
</body>
</html>

