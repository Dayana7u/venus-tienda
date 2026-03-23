const token = document.getElementById('token').value;

const seguridad = {
  btn_recargar_seguridad: document.getElementById('btn_recargar_seguridad'),
  div_contenido_permisos_seguridad: document.getElementById('div_contenido_permisos_seguridad'),
  div_contenido_roles_seguridad: document.getElementById('div_contenido_roles_seguridad'),
  div_contenido_usuarios_seguridad: document.getElementById('div_contenido_usuarios_seguridad'),
  input_buscar_seguridad: document.getElementById('buscar_seguridad'),
  permisos: [],
  roles: [],
  usuarios: [],
};

document.addEventListener('DOMContentLoaded', async () => {
  await inicializar_seguridad();
});

/**
 * Inicializa el módulo seguridad y carga su información base.
 *
 * @returns {Promise<void>}
 */
const inicializar_seguridad = async function inicializar_seguridad() {
  await seguridad_inicializar_peticiones(token);
  await listar_seguridad();
  eventos_seguridad();
};

/**
 * Registra los eventos principales del módulo.
 *
 * @returns {void}
 */
const eventos_seguridad = function eventos_seguridad() {
  seguridad.btn_recargar_seguridad.addEventListener('click', async () => {
    await listar_seguridad();
  });

  seguridad.input_buscar_seguridad.addEventListener('input', () => {
    renderizar_seguridad();
  });
};

/**
 * Consulta los listados base del módulo seguridad.
 *
 * @returns {Promise<void>}
 */
const listar_seguridad = async function listar_seguridad() {
  const [
    usuarios_resultado,
    roles_resultado,
    permisos_resultado,
  ] = await Promise.all([
    seguridad_listar_usuarios_peticiones(token),
    seguridad_listar_roles_peticiones(token),
    seguridad_listar_permisos_peticiones(token),
  ]);

  seguridad.usuarios = usuarios_resultado.datos || [];
  seguridad.roles = roles_resultado.datos || [];
  seguridad.permisos = permisos_resultado.datos || [];

  renderizar_seguridad();
};

/**
 * Renderiza la información del módulo seguridad.
 *
 * @returns {void}
 */
const renderizar_seguridad = function renderizar_seguridad() {
  const texto_busqueda = seguridad.input_buscar_seguridad.value.trim().toLowerCase();

  seguridad.div_contenido_usuarios_seguridad.innerHTML = template_seguridad_tabla(
    filtrar_seguridad_datos(seguridad.usuarios, texto_busqueda),
    'No hay usuarios para mostrar.'
  );
  seguridad.div_contenido_roles_seguridad.innerHTML = template_seguridad_tabla(
    filtrar_seguridad_datos(seguridad.roles, texto_busqueda),
    'No hay roles para mostrar.'
  );
  seguridad.div_contenido_permisos_seguridad.innerHTML = template_seguridad_tabla(
    filtrar_seguridad_datos(seguridad.permisos, texto_busqueda),
    'No hay permisos para mostrar.'
  );
};

/**
 * Filtra un listado de datos según el texto ingresado.
 *
 * @param array datos Datos a filtrar.
 * @param string texto_busqueda Texto de búsqueda aplicado al listado.
 * @returns {array}
 */
const filtrar_seguridad_datos = function filtrar_seguridad_datos(
  datos,
  texto_busqueda
) {
  if (texto_busqueda === '') {
    return datos;
  }

  return datos.filter((item) => {
    const valor = JSON.stringify(item).toLowerCase();

    return valor.includes(texto_busqueda);
  });
};

/**
 * Construye la tabla HTML para un listado.
 *
 * @param array datos Datos a renderizar.
 * @param string mensaje_vacio Mensaje a mostrar cuando no existan datos.
 * @returns {string}
 */
const template_seguridad_tabla = function template_seguridad_tabla(
  datos,
  mensaje_vacio
) {
  if (datos.length === 0) {
    return `<p>${mensaje_vacio}</p>`;
  }

  const columnas = Object.keys(datos[0]);
  const encabezado = columnas
    .map((columna) => `<th>${columna}</th>`)
    .join('');
  const filas = datos
    .map((item) => {
      const celdas = columnas
        .map((columna) => `<td>${item[columna] ?? ''}</td>`)
        .join('');

      return `<tr>${celdas}</tr>`;
    })
    .join('');

  return `
    <table border="1" cellpadding="6" cellspacing="0">
      <thead>
        <tr>${encabezado}</tr>
      </thead>
      <tbody>
        ${filas}
      </tbody>
    </table>
  `;
};
