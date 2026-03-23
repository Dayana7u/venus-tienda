const parametrizacion = {
  token: document.getElementById('token').value,
  constante: false,
  temas: [],
  branding: [],
  parametros: [],
  modulos: [],
  integraciones: [],
  menus: [],
  btn_recargar_parametrizacion: document.getElementById('btn_recargar_parametrizacion'),
  div_contenido_branding_parametrizacion: document.getElementById('div_contenido_branding_parametrizacion'),
  div_contenido_integraciones_parametrizacion: document.getElementById('div_contenido_integraciones_parametrizacion'),
  div_contenido_menus_parametrizacion: document.getElementById('div_contenido_menus_parametrizacion'),
  div_contenido_modulos_parametrizacion: document.getElementById('div_contenido_modulos_parametrizacion'),
  div_contenido_parametros_parametrizacion: document.getElementById('div_contenido_parametros_parametrizacion'),
  div_contenido_temas_parametrizacion: document.getElementById('div_contenido_temas_parametrizacion'),
  input_buscar_parametrizacion: document.getElementById('buscar_parametrizacion')
};

document.addEventListener('DOMContentLoaded', async () => {
  await inicializar_parametrizacion();
});

/**
 * Método encargado de inicializar el módulo de parametrización y cargar su información base.
 *
 * @returns {Promise<void>}
 */
const inicializar_parametrizacion = async function inicializar_parametrizacion() {
  const petición = await parametrizacion_inicializar_peticiones(parametrizacion.token);

  if (petición.estado)
    parametrizacion.constante = Boolean(petición.datos.constante);

  await listar_parametrizacion();
  eventos_parametrizacion();
};

/**
 * Método encargado de registrar los eventos principales del módulo.
 */
const eventos_parametrizacion = function eventos_parametrizacion() {
  parametrizacion.btn_recargar_parametrizacion.addEventListener('click', async () => {
    await listar_parametrizacion();
  });

  parametrizacion.input_buscar_parametrizacion.addEventListener('input', () => {
    renderizar_parametrizacion();
  });
};

/**
 * Método encargado de consultar los listados base del módulo de parametrización.
 *
 * @returns {Promise<void>}
 */
const listar_parametrizacion = async function listar_parametrizacion() {
  const [
    temas_resultado,
    branding_resultado,
    parametros_resultado,
    modulos_resultado,
    integraciones_resultado,
    menus_resultado
  ] = await Promise.all([
    parametrizacion_listar_temas_peticiones(parametrizacion.token),
    parametrizacion_listar_branding_peticiones(parametrizacion.token),
    parametrizacion_listar_parametros_peticiones(parametrizacion.token),
    parametrizacion_listar_modulos_peticiones(parametrizacion.token),
    parametrizacion_listar_integraciones_peticiones(parametrizacion.token),
    parametrizacion_listar_menus_peticiones(parametrizacion.token)
  ]);

  parametrizacion.temas         = temas_resultado.datos || [];
  parametrizacion.branding      = branding_resultado.datos || [];
  parametrizacion.parametros    = parametros_resultado.datos || [];
  parametrizacion.modulos       = modulos_resultado.datos || [];
  parametrizacion.integraciones = integraciones_resultado.datos || [];
  parametrizacion.menus         = menus_resultado.datos || [];

  renderizar_parametrizacion();
};

/**
 * Método encargado de renderizar la información del módulo de parametrización.
 */
const renderizar_parametrizacion = function renderizar_parametrizacion() {
  const texto_busqueda = parametrizacion.input_buscar_parametrizacion.value.trim().toLowerCase();

  parametrizacion.div_contenido_temas_parametrizacion.innerHTML = template_parametrizacion_tabla(
    filtrar_parametrizacion_datos(parametrizacion.temas, texto_busqueda),
    'No hay temas para mostrar.'
  );
  parametrizacion.div_contenido_branding_parametrizacion.innerHTML = template_parametrizacion_tabla(
    filtrar_parametrizacion_datos(parametrizacion.branding, texto_busqueda),
    'No hay branding para mostrar.'
  );
  parametrizacion.div_contenido_parametros_parametrizacion.innerHTML = template_parametrizacion_tabla(
    filtrar_parametrizacion_datos(parametrizacion.parametros, texto_busqueda),
    'No hay parámetros para mostrar.'
  );
  parametrizacion.div_contenido_modulos_parametrizacion.innerHTML = template_parametrizacion_tabla(
    filtrar_parametrizacion_datos(parametrizacion.modulos, texto_busqueda),
    'No hay módulos para mostrar.'
  );
  parametrizacion.div_contenido_integraciones_parametrizacion.innerHTML = template_parametrizacion_tabla(
    filtrar_parametrizacion_datos(parametrizacion.integraciones, texto_busqueda),
    'No hay integraciones para mostrar.'
  );
  parametrizacion.div_contenido_menus_parametrizacion.innerHTML = template_parametrizacion_tabla(
    filtrar_parametrizacion_datos(parametrizacion.menus, texto_busqueda),
    'No hay menús para mostrar.'
  );
};

/**
 * Método encargado de filtrar un listado según el texto recibido.
 *
 * @param      {Array}   datos            Datos a filtrar.
 * @param      {string}  texto_busqueda   Texto de búsqueda aplicado al listado.
 *
 * @returns    {Array}   Arreglo con los datos filtrados.
 */
const filtrar_parametrizacion_datos = function filtrar_parametrizacion_datos(
  datos,
  texto_busqueda
) {
  if (texto_busqueda === '')
    return datos;

  return datos.filter((item) => {
    const valor = JSON.stringify(item).toLowerCase();

    return valor.includes(texto_busqueda);
  });
};

/**
 * Método encargado de construir la tabla HTML de un listado.
 *
 * @param      {Array}   datos           Datos a renderizar.
 * @param      {string}  mensaje_vacio   Mensaje a mostrar cuando no existan datos.
 *
 * @returns    {string}  HTML del listado.
 */
const template_parametrizacion_tabla = function template_parametrizacion_tabla(
  datos,
  mensaje_vacio
) {
  if (datos.length === 0)
    return `<p>${mensaje_vacio}</p>`;

  const columnas   = Object.keys(datos[0]);
  const encabezado = columnas
    .map((columna) => `<th>${columna}</th>`)
    .join('');
  const filas      = datos
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
