function template_tienda_admin_login_alerta(tipo, mensaje) {
  return `
    <div class="tda_login_alerta ${tipo}">
      <span>${mensaje}</span>
      <button type="button" data-alerta-cerrar="true">×</button>
    </div>`;
}

function template_tienda_admin_login_resumen(datos) {
  const roles = (datos.roles || []).map(function(rol) {
    return rol.nombre;
  }).join(`, `) || `Sin roles visibles`;

  return `
    <div class="tda_login_resumen_card">
      <strong>${datos.usuario_nombre_completo || ``}</strong>
      <div>Usuario: ${datos.usuario_login || ``}</div>
      <div>Roles: ${roles}</div>
    </div>`;
}
