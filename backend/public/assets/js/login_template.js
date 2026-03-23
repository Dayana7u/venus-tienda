/**
 * Función encargada de generar la alerta del login
 *
 * @param      string  tipo     Tipo de alerta
 * @param      string  mensaje  Mensaje a renderizar
 *
 * @return     string  Estructura HTML
 */
function template_login_alerta(tipo, mensaje) {
  const iconos = {
    success : '✓',
    error   : '!',
    info    : 'i'
  };
  const icono = iconos[tipo] ?? 'i';

  return `
    <article class="dx_alerta dx_alerta_${tipo}">
      <span class="dx_alerta_icono">${icono}</span>
      <div class="dx_alerta_contenido">
        <p>${mensaje}</p>
      </div>
      <button type="button" class="dx_alerta_cerrar" data-alerta-cerrar="true" aria-label="Cerrar alerta">×</button>
    </article>`;
}
/**
 * Función encargada de generar el resumen de la sesión actual
 *
 * @param      object  datos  Información del usuario autenticado
 *
 * @return     string  Estructura HTML
 */
function template_login_resumen_usuario(datos) {
  const nombre = datos.usuario_nombre_completo ? datos.usuario_nombre_completo : 'Usuario autenticado';
  const login  = datos.usuario_login ? datos.usuario_login : '--';
  const roles  = Array.isArray(datos.roles) && datos.roles.length > 0
    ? datos.roles.map(function(rol) {
      return rol.nombre;
    }).join(', ')
    : 'Sin rol asignado';

  return `
    <article class="dx_tarjeta_resumen">
      <p class="dx_login_etiqueta">Sesión activa</p>
      <h3>${nombre}</h3>
      <ul class="dx_resumen_lista">
        <li><strong>Usuario:</strong> ${login}</li>
        <li><strong>Rol:</strong> ${roles}</li>
      </ul>
    </article>`;
}
