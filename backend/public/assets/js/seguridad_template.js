/**
 * Función encargada de generar la alerta visual del módulo seguridad.
 *
 * @param      string  tipo     Tipo de alerta.
 * @param      string  mensaje  Mensaje a mostrar.
 *
 * @return     string  Estructura HTML.
 */
function template_alerta_seguridad(tipo, mensaje) {
  const iconos = {
    success : '✓',
    error   : '!',
    info    : 'i',
    warning : '·'
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
 * Función encargada de generar las tarjetas de resumen del módulo seguridad.
 *
 * @param      object  resumen  Totales del módulo.
 *
 * @return     string  Estructura HTML.
 */
function template_resumen_seguridad(resumen) {
  const tarjetas = [
    {titulo : `Sesiones activas`, cantidad : resumen.sesiones_activas ?? 0},
    {titulo : `Mis sesiones activas`, cantidad : resumen.sesiones_usuario_actual ?? 0},
    {titulo : `Usuarios con sesión`, cantidad : resumen.usuarios_con_sesion ?? 0},
    {titulo : `Accesos registrados`, cantidad : resumen.accesos_registrados ?? 0},
  ];

  let html = ``;

  tarjetas.forEach(function(item) {
    html += `
      <article class="dx_resumen_tarjeta">
        <p>${item.titulo}</p>
        <h3>${item.cantidad}</h3>
      </article>`;
  });

  return html;
}
/**
 * Función encargada de generar la tabla de sesiones activas.
 *
 * @param      array  listado  Sesiones activas a renderizar.
 *
 * @return     string  Estructura HTML.
 */
function template_sesiones_activas_seguridad(listado) {
  if (!Array.isArray(listado) || listado.length === 0) {
    return `<div class="dx_vacio">No hay sesiones activas para mostrar.</div>`;
  }

  let html = `
    <div class="dx_tabla_scroll">
      <table class="dx_tabla">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Login</th>
            <th>IP</th>
            <th>Navegador</th>
            <th>Inicio</th>
            <th>Expiración</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>`;

  listado.forEach(function(registro) {
    const deshabilitado = registro.sesion_actual === `1` ? `disabled` : ``;
    const texto_boton   = registro.sesion_actual === `1` ? `Sesión actual` : `Cerrar sesión`;

    html += `
          <tr>
            <td data-label="Usuario">${registro.usuario}</td>
            <td data-label="Login">${registro.login}</td>
            <td data-label="IP">${registro.ip}</td>
            <td data-label="Navegador">${registro.user_agent}</td>
            <td data-label="Inicio">${template_fecha_seguridad(registro.fecha_inicio)}</td>
            <td data-label="Expiración">${template_fecha_seguridad(registro.fecha_expiracion)}</td>
            <td data-label="Estado">${template_badge_seguridad(registro.estado_sesion)}</td>
            <td data-label="Acciones">
              <button
                type="button"
                class="dx_btn dx_btn_tabla dx_btn_secundario"
                data-accion="cerrar_sesion_seguridad"
                data-usuario-sesion-id="${registro.usuario_sesion_id}"
                ${deshabilitado}
              >
                ${texto_boton}
              </button>
            </td>
          </tr>`;
  });

  html += `
        </tbody>
      </table>
    </div>`;

  return html;
}
/**
 * Función encargada de generar la tabla del historial de accesos.
 *
 * @param      array  listado  Historial a renderizar.
 *
 * @return     string  Estructura HTML.
 */
function template_historial_seguridad(listado) {
  if (!Array.isArray(listado) || listado.length === 0) {
    return `<div class="dx_vacio">No hay accesos recientes para mostrar.</div>`;
  }

  let html = `
    <div class="dx_tabla_scroll">
      <table class="dx_tabla">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Login</th>
            <th>IP</th>
            <th>Inicio</th>
            <th>Expiración</th>
            <th>Último cambio</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>`;

  listado.forEach(function(registro) {
    html += `
          <tr>
            <td data-label="Usuario">${registro.usuario}</td>
            <td data-label="Login">${registro.login}</td>
            <td data-label="IP">${registro.ip}</td>
            <td data-label="Inicio">${template_fecha_seguridad(registro.fecha_inicio)}</td>
            <td data-label="Expiración">${template_fecha_seguridad(registro.fecha_expiracion)}</td>
            <td data-label="Último cambio">${template_fecha_seguridad(registro.fecha_modificacion)}</td>
            <td data-label="Estado">${template_badge_seguridad(registro.estado_sesion)}</td>
          </tr>`;
  });

  html += `
        </tbody>
      </table>
    </div>`;

  return html;
}
/**
 * Función encargada de generar las opciones del select de usuarios.
 *
 * @param      array     listado     Listado de usuarios.
 * @param      integer   usuario_id  Usuario seleccionado.
 *
 * @return     string  Estructura HTML.
 */
function template_opciones_usuarios_seguridad(listado, usuario_id = 0) {
  let html = `<option value="">Seleccione</option>`;

  (listado || []).forEach(function(registro) {
    const seleccionado = Number(usuario_id) === Number(registro.usuario_id) ? `selected` : ``;

    html += `<option value="${registro.usuario_id}" ${seleccionado}>${registro.usuario} (${registro.login})</option>`;
  });

  return html;
}
/**
 * Función encargada de formatear fechas visibles del módulo.
 *
 * @param      string  valor  Fecha recibida.
 *
 * @return     string  Fecha visible.
 */
function template_fecha_seguridad(valor) {
  if (valor === null || valor === undefined || valor === ``) {
    return `<span class="dx_texto_muted">--</span>`;
  }

  const fecha = new Date(valor);

  if (Number.isNaN(fecha.getTime())) {
    return valor;
  }

  return fecha.toLocaleString(`es-CO`);
}
/**
 * Función encargada de generar badges de estado.
 *
 * @param      string  estado  Estado visible.
 *
 * @return     string  Estructura HTML.
 */
function template_badge_seguridad(estado) {
  const clase = estado === `Activa` || estado === `Actual`
    ? `dx_badge_estado dx_badge_activo`
    : `dx_badge_estado dx_badge_inactivo`;

  return `<span class="${clase}">${estado}</span>`;
}
