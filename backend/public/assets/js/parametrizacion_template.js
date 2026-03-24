/**
 * Función encargada de generar la alerta visual del módulo
 *
 * @param      string  tipo     Tipo de alerta
 * @param      string  mensaje  Mensaje a renderizar
 *
 * @return     string  Estructura HTML
 */
function template_alerta_parametrizacion(tipo, mensaje) {
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
 * Función encargada de generar las tarjetas de resumen
 *
 * @param      array  resumen  Resumen del módulo
 *
 * @return     string  Estructura HTML
 */
function template_resumen_parametrizacion(resumen) {
  let html = ``;

  resumen.forEach(function(item) {
    html += `
      <article class="dx_resumen_tarjeta">
        <p>${item.titulo}</p>
        <h3>${item.cantidad}</h3>
      </article>`;
  });

  return html;
}
/**
 * Función encargada de generar una sección CRUD
 *
 * @param      object  configuracion  Configuración visual de la sección
 * @param      array   listado        Listado de la sección
 *
 * @return     string  Estructura HTML
 */
function template_seccion_parametrizacion(configuracion, listado) {
  return `
    <article class="dx_seccion" id="bloque_${configuracion.seccion}">
      <div class="dx_seccion_encabezado">
        <div>
          <span class="dx_header_etiqueta">${configuracion.etiqueta}</span>
          <h3>${configuracion.titulo}</h3>
          <p>${configuracion.descripcion}</p>
        </div>

        <button
          type="button"
          class="dx_btn dx_btn_principal"
          data-accion="abrir_formulario"
          data-seccion="${configuracion.seccion}"
        >
          Nuevo
        </button>
      </div>

      ${template_tabla_parametrizacion(configuracion, listado)}
    </article>`;
}
/**
 * Función encargada de generar la tabla de una sección
 *
 * @param      object  configuracion  Configuración visual de la sección
 * @param      array   listado        Registros a renderizar
 *
 * @return     string  Estructura HTML
 */
function template_tabla_parametrizacion(configuracion, listado) {
  if (listado.length === 0) {
    return `<div class="dx_vacio">No hay registros para mostrar.</div>`;
  }

  let html = `
    <div class="dx_tabla_scroll">
      <table class="dx_tabla">
        <thead>
          <tr>`;

  configuracion.columnas.forEach(function(columna) {
    html += `
            <th>${columna.titulo}</th>`;
  });

  html += `
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>`;

  listado.forEach(function(registro) {
    html += `
          <tr>`;

    configuracion.columnas.forEach(function(columna) {
      html += `
            <td data-label="${columna.titulo}">${template_valor_columna_parametrizacion(registro[columna.campo], columna.tipo)}</td>`;
    });

    html += `
            <td data-label="Acciones">
              <div class="dx_acciones_tabla">
                <button
                  type="button"
                  class="dx_btn dx_btn_secundario dx_btn_tabla"
                  data-accion="editar_registro"
                  data-seccion="${configuracion.seccion}"
                  data-registro-id="${registro[configuracion.pk]}"
                >
                  Editar
                </button>
                <button
                  type="button"
                  class="dx_btn dx_btn_tabla dx_btn_estado"
                  data-accion="cambiar_estado"
                  data-seccion="${configuracion.seccion}"
                  data-registro-id="${registro[configuracion.pk]}"
                  data-estado="${template_estado_objetivo_parametrizacion(registro.estado)}"
                >
                  ${template_texto_estado_parametrizacion(registro.estado)}
                </button>
                <button
                  type="button"
                  class="dx_btn dx_btn_tabla dx_btn_peligro_tabla"
                  data-accion="borrar_registro"
                  data-seccion="${configuracion.seccion}"
                  data-registro-id="${registro[configuracion.pk]}"
                >
                  Eliminar
                </button>
              </div>
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
 * Función encargada de generar los campos del formulario
 *
 * @param      object  configuracion  Configuración de la sección
 * @param      object  datos          Datos del registro
 * @param      object  catalogos      Catálogos auxiliares
 *
 * @return     string  Estructura HTML
 */
function template_formulario_parametrizacion(configuracion, datos, catalogos) {
  let html = ``;

  configuracion.campos.forEach(function(campo) {
    html += template_campo_parametrizacion(campo, datos[campo.nombre], catalogos, datos);
  });

  return html;
}
/**
 * Función encargada de generar un campo del formulario
 *
 * @param      object  campo      Configuración del campo
 * @param      mixed   valor      Valor actual del campo
 * @param      object  catalogos  Catálogos auxiliares
 * @param      object  datos      Datos del registro
 *
 * @return     string  Estructura HTML
 */
function template_campo_parametrizacion(campo, valor, catalogos, datos) {
  const valor_campo = campo.nombre === `clave`
    ? ``
    : valor !== null && valor !== undefined ? valor : ``;
  const requerido   = campo.requerido === true ? `required` : ``;
  const ayuda       = campo.ayuda ? `<small>${campo.ayuda}</small>` : ``;
  const placeholder = obtener_placeholder_campo_parametrizacion(campo, datos);

  if (campo.tipo === `textarea`) {
    return `
      <div class="dx_campo dx_campo_completo">
        <label for="${campo.nombre}">${campo.etiqueta}</label>
        <textarea id="${campo.nombre}" name="${campo.nombre}" placeholder="${placeholder}" ${requerido}>${valor_campo}</textarea>
        ${ayuda}
      </div>`;
  }

  if (campo.tipo === `select`) {
    const opciones = (catalogos[campo.catalogo] ?? []).map(function(opcion) {
      const seleccionado = String(opcion[campo.valor]) === String(valor_campo) ? `selected` : ``;

      return `<option value="${opcion[campo.valor]}" ${seleccionado}>${opcion[campo.texto]}</option>`;
    }).join(``);

    return `
      <div class="dx_campo">
        <label for="${campo.nombre}">${campo.etiqueta}</label>
        <select id="${campo.nombre}" name="${campo.nombre}" ${requerido}>
          <option value="">Seleccione</option>
          ${opciones}
        </select>
        ${ayuda}
      </div>`;
  }

  return `
    <div class="dx_campo">
      <label for="${campo.nombre}">${campo.etiqueta}</label>
      <input
        type="${campo.tipo_html}"
        id="${campo.nombre}"
        name="${campo.nombre}"
        value="${valor_campo}"
        placeholder="${placeholder}"
        ${requerido}
      >
      ${ayuda}
    </div>`;
}
/**
 * Función encargada de obtener el placeholder del campo
 *
 * @param      object  campo  Configuración del campo
 * @param      object  datos  Datos del registro
 *
 * @return     string  Texto a mostrar
 */
function obtener_placeholder_campo_parametrizacion(campo, datos) {
  if (campo.nombre === `clave` && datos && datos.usuario_id) {
    return `Deje vacío para conservar la clave actual`;
  }

  return campo.placeholder ?? ``;
}
/**
 * Función encargada de transformar el valor visible de una columna
 *
 * @param      mixed   valor  Valor del registro
 * @param      string  tipo   Tipo visual de la columna
 *
 * @return     string  Valor transformado
 */
function template_valor_columna_parametrizacion(valor, tipo = `texto`) {
  if (tipo === `estado`) {
    const clase = valor === `1` || valor === ``
      ? `dx_badge_estado dx_badge_activo`
      : `dx_badge_estado dx_badge_inactivo`;
    const texto = valor === `1` || valor === `` ? `Activo` : `Inactivo`;

    return `<span class="${clase}">${texto}</span>`;
  }

  if (tipo === `fecha`) {
    if (valor === null || valor === undefined || valor === ``) {
      return `<span class="dx_texto_muted">--</span>`;
    }

    const fecha = new Date(valor);

    if (Number.isNaN(fecha.getTime())) {
      return `${valor}`;
    }

    return fecha.toLocaleString(`es-CO`);
  }

  if (valor === null || valor === undefined || valor === ``) {
    return `<span class="dx_texto_muted">--</span>`;
  }

  return `${valor}`;
}
/**
 * Función encargada de obtener el texto del botón de cambio de estado
 *
 * @param      string  estado  Estado actual del registro
 *
 * @return     string  Texto a mostrar
 */
function template_texto_estado_parametrizacion(estado) {
  return estado === `1` || estado === ``
    ? `Inactivar`
    : `Activar`;
}
/**
 * Función encargada de obtener el estado objetivo
 *
 * @param      string  estado  Estado actual del registro
 *
 * @return     string  Estado a aplicar
 */
function template_estado_objetivo_parametrizacion(estado) {
  return estado === `1` || estado === ``
    ? `0`
    : `1`;
}
