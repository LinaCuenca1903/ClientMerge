const { useEffect, useState } = React;

function BaseConocimientoAdmin() {
  const [articulos, setArticulos] = useState([]);
  const [titulo, setTitulo] = useState("");
  const [categoria, setCategoria] = useState("");
  const [contenido, setContenido] = useState("");
  const [editando, setEditando] = useState(null);

  useEffect(() => {
    cargarArticulos();
  }, []);

  const cargarArticulos = async () => {
    try {
      const res = await fetch("auth/articulos.php");
      const data = await res.json();
      setArticulos(data);
    } catch (err) {
      console.error("Error al cargar artículos:", err);
    }
  };

  const guardarArticulo = async (e) => {
    e.preventDefault();
    const datos = { titulo, categoria, contenido };
    if (editando) datos.id_articulo = editando;

    try {
      const res = await fetch("auth/articulos.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos),
      });

      if (res.ok) {
        setTitulo("");
        setCategoria("");
        setContenido("");
        setEditando(null);
        cargarArticulos();
      } else {
        const error = await res.json();
        alert("Error al guardar: " + error.error);
      }
    } catch (err) {
      console.error("Error al guardar:", err);
    }
  };

  const eliminarArticulo = async (id) => {
    if (!confirm("¿Eliminar este artículo?")) return;

    try {
      const res = await fetch("auth/articulos.php", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_articulo: id }),
      });

      if (res.ok) cargarArticulos();
      else alert("Error al eliminar el artículo.");
    } catch (err) {
      console.error("Error al eliminar:", err);
    }
  };

  const editarArticulo = (art) => {
    setTitulo(art.titulo);
    setCategoria(art.categoria);
    setContenido(art.contenido);
    setEditando(art.id_articulo);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return (
    <div>
      <section className="form-section">
        <h2>{editando ? "Editar Artículo" : "Agregar Artículo"}</h2>
        <form onSubmit={guardarArticulo}>
          <input
            type="text"
            placeholder="Título del artículo"
            value={titulo}
            onChange={(e) => setTitulo(e.target.value)}
            required
          />
          <input
            type="text"
            placeholder="Categoría"
            value={categoria}
            onChange={(e) => setCategoria(e.target.value)}
            required
          />
          <textarea
            placeholder="Contenido del artículo..."
            rows="6"
            value={contenido}
            onChange={(e) => setContenido(e.target.value)}
            required
          ></textarea>
          <button type="submit">{editando ? "Actualizar" : "Agregar"}</button>
        </form>
      </section>

      <section className="list-section">
        <h2>Artículos Existentes</h2>
        <table>
          <thead>
            <tr>
              <th>Título</th>
              <th>Categoría</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {articulos.map((a) => (
              <tr key={a.id_articulo}>
                <td>{a.titulo}</td>
                <td>{a.categoria}</td>
                <td>{a.fecha_creacion}</td>
                <td>
                  <button onClick={() => editarArticulo(a)}>Editar</button>
                  <button onClick={() => eliminarArticulo(a.id_articulo)}>Eliminar</button>
                </td>
              </tr>
            ))}
            {articulos.length === 0 && (
              <tr>
                <td colSpan="4" style={{ textAlign: "center" }}>
                  No hay artículos registrados.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </section>
    </div>
  );
}

ReactDOM.createRoot(document.getElementById("BaseConocimientoAdmin")).render(<BaseConocimientoAdmin />);
