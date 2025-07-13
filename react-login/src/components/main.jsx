import React from 'react';
import ReactDOM from 'react-dom/client';
import Usuarios from './components/Usuarios.jsx';
import BaseConocimientoAdmin from './components/BaseConocimientoAdmin.jsx';

// Función auxiliar para montar React en un div con un componente
function renderReactComponent(id, Component, props = {}) {
  const element = document.getElementById(id);
  if (element) {
    ReactDOM.createRoot(element).render(
      <React.StrictMode>
        <Component {...props} />
      </React.StrictMode>
    );
  }
}

// ✅ Módulo de usuarios
renderReactComponent('react-root', Usuarios);

// ✅ Base de conocimiento (formulario y tabla)
renderReactComponent('formArticuloReact', BaseConocimientoAdmin, { seccion: 'formulario' });
renderReactComponent('tablaArticulosReact', BaseConocimientoAdmin, { seccion: 'tabla' });
