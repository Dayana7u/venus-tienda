/**
 * Función encargada de generar el resumen visual de parametrización.
 *
 * @param      object  listado         Listado general del módulo.
 * @param      object  secciones       Configuración de las secciones.
 * @param      string  texto_busqueda  Texto usado para filtrar.
 *
 * @return     string  Estructura HTML del resumen.
 */
async function template_resumen_parametrizacion(listado, secciones, texto_busqueda) {
  let html = ``;
  html     += ` <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">`;
  for (let seccion in secciones) {
    let cantidad = listado[seccion] ? listado[seccion].length : 0;
    let titulo   = secciones[seccion].titulo;
    html        += `   <button
                        type="button"
                        onclick="visualizar_seccion_parametrizacion('${seccion}')"
                        style="border:1px solid #d7dce2;border-radius:12px;background:#ffffff;padding:14px;text-align:left;cursor:pointer;box-shadow:0 2px 8px rgba(15,23,42,.06);"
                      >
                        <div style="font-size:13px;color:#475569;margin-bottom:6px;">${titulo}</div>
                        <div style="font-size:24px;font-weight:700;color:#0f172a;">${cantidad}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:4px;">${texto_busqueda !== `` ? `Filtro activo` : `Listado completo`}</div>
                      </button>`;
  }
  html += `     </div>`;
  return html;
}
/**
 * Función encargada de generar el bloque visual de una sección.
 *
 * @param      string  titulo          Título de la sección.
 * @param      array   listado         Datos de la sección.
 * @param      string  mensaje_vacio   Mensaje cuando no existan registros.
 * @param      string  seccion         Nombre técnico de la sección.
 *
 * @return     string  Estructura HTML del bloque.
 */
async function template_bloque_parametrizacion(titulo, listado, mensaje_vacio, seccion) {
  let html = ``;
  html     += ` <div style="border:1px solid #d7dce2;border-radius:14px;background:#ffffff;overflow:hidden;box-shadow:0 2px 8px rgba(15,23,42,.05);">
                  <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#f8fafc;border-bottom:1px solid #e2e8f0;gap:12px;">
                    <div>
                      <div style="font-size:17px;font-weight:700;color:#0f172a;">${titulo}</div>
                      <div style="font-size:12px;color:#64748b;">Sección: ${seccion}</div>
                    </div>
                    <div style="display:inline-flex;align-items:center;justify-content:center;min-width:42px;height:32px;padding:0 10px;border-radius:999px;background:#e2e8f0;color:#0f172a;font-size:12px;font-weight:700;">
                      ${listado.length}
                    </div>
                  </div>
                  <div style="padding:16px;">`;
  if (listado.length > 0)
    html += await template_tabla_parametrizacion(listado);
  else
    html += await template_sin_registros_parametrizacion(mensaje_vacio);
  html += `     </div>
                </div>`;
  return html;
}
/**
 * Función encargada de generar la tabla de una sección.
 *
 * @param      array   listado  Datos a visualizar.
 *
 * @return     string  Estructura HTML de la tabla.
 */
async function template_tabla_parametrizacion(listado) {
  let html     = ``;
  let columnas = Object.keys(listado[0]);
  html        += ` <div style="overflow:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:13px;min-width:920px;">
                      <thead>
                        <tr>`;
  for (let i = 0; i < columnas.length; i++)
    html += `           <th style="padding:10px 12px;border-bottom:1px solid #e2e8f0;text-align:left;background:#f8fafc;color:#334155;white-space:nowrap;">${columnas[i]}</th>`;
  html += `         </tr>
                      </thead>
                      <tbody>`;
  for (let i = 0; i < listado.length; i++) {
    let fila = listado[i];
    html    += ` <tr>`;
    for (let j = 0; j < columnas.length; j++) {
      let columna = columnas[j];
      let valor   = await template_valor_celda_parametrizacion(fila[columna], columna);
      html       += ` <td style="padding:10px 12px;border-bottom:1px solid #f1f5f9;vertical-align:top;color:#0f172a;">${valor}</td>`;
    }
    html += `     </tr>`;
  }
  html += `       </tbody>
                    </table>
                  </div>`;
  return html;
}
/**
 * Función encargada de transformar el valor de una celda para su visualización.
 *
 * @param      mixed   valor    Valor original de la celda.
 * @param      string  columna  Nombre de la columna.
 *
 * @return     string  Valor formateado para la celda.
 */
async function template_valor_celda_parametrizacion(valor, columna) {
  if (valor === null || valor === undefined || valor === ``)
    return `<span style="color:#94a3b8;">--</span>`;
  if (columna === `estado` || columna === `borrado` || columna === `sw_predeterminado` || columna === `sw_requerido` || columna === `sw_publico` || columna === `sw_visible_menu` || columna === `sw_requiere_login` || columna === `sw_activa` || columna === `sw_encriptado` || columna === `sw_activa` || columna === `sw_visible`) {
    return await template_badge_parametrizacion(valor);
  }
  return String(valor)
    .replaceAll(`&`, `&amp;`)
    .replaceAll(`<`, `&lt;`)
    .replaceAll(`>`, `&gt;`)
    .replaceAll(`"`, `&quot;`)
    .replaceAll(`'`, `&#039;`)
    .replaceAll(`\n`, `<br>`);
}
/**
 * Función encargada de construir un badge para campos lógicos.
 *
 * @param      mixed  valor  Valor del campo.
 *
 * @return     string  Estructura HTML del badge.
 */
async function template_badge_parametrizacion(valor) {
  let texto = `Inactivo`;
  let fondo = `#fee2e2`;
  let color = `#991b1b`;
  if (valor === `1` || valor === 1 || valor === true || valor === `t` || valor === `true` || valor === `b'1'`) {
    texto = `Activo`;
    fondo = `#dcfce7`;
    color = `#166534`;
  }
  return `<span style="display:inline-flex;align-items:center;justify-content:center;padding:4px 10px;border-radius:999px;background:${fondo};color:${color};font-size:12px;font-weight:700;">${texto}</span>`;
}
/**
 * Función encargada de generar el mensaje de tabla vacía.
 *
 * @param      string  mensaje  Mensaje a visualizar.
 *
 * @return     string  Estructura HTML del mensaje.
 */
async function template_sin_registros_parametrizacion(mensaje) {
  let html = ``;
  html    += ` <div style="padding:18px;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc;color:#475569;">${mensaje}</div>`;
  return html;
}
