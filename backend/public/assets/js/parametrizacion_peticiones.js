const CONTROLADOR_PARAMETRIZACION = '../../app/Controllers/parametrizacion_controller.php';

/**
 * Método encargado de realizar una petición POST al controlador del módulo parametrizacion.
 *
 * @param      {string}  accion  Acción que se ejecutará en el controlador.
 * @param      {string}  token   Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const parametrizacion_peticion_post = async function parametrizacion_peticion_post(
  accion,
  token
) {
  const params = {
    accion,
    token
  };

  try {
    if (typeof petición_fetch === 'function') {
      const petición = await petición_fetch(CONTROLADOR_PARAMETRIZACION, params);

      if (typeof validar_estado_peticion_ajax === 'function')
        validar_estado_peticion_ajax(petición);

      return petición;
    }

    const form_data = new FormData();

    Object.keys(params).forEach((key) => {
      form_data.append(key, params[key]);
    });

    const petición = await fetch(CONTROLADOR_PARAMETRIZACION, {
      method: 'POST',
      body: form_data
    });

    return await petición.json();
  }
  catch (error) {
    return {
      estado: false,
      mensaje: 'No fue posible realizar la petición.',
      datos: [],
      error: error.message
    };
  }
};

/**
 * Método encargado de consultar la información base del módulo parametrizacion.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const parametrizacion_inicializar_peticiones = async function parametrizacion_inicializar_peticiones(
  token
) {
  const petición = await parametrizacion_peticion_post(
    'parametrizacion_inicializar',
    token
  );

  return petición;
};

/**
 * Método encargado de consultar el listado de temas.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const parametrizacion_listar_temas_peticiones = async function parametrizacion_listar_temas_peticiones(
  token
) {
  const petición = await parametrizacion_peticion_post(
    'parametrizacion_listar_temas',
    token
  );

  return petición;
};

/**
 * Método encargado de consultar el listado de branding.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const parametrizacion_listar_branding_peticiones = async function parametrizacion_listar_branding_peticiones(
  token
) {
  const petición = await parametrizacion_peticion_post(
    'parametrizacion_listar_branding',
    token
  );

  return petición;
};

/**
 * Método encargado de consultar el listado de parámetros.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const parametrizacion_listar_parametros_peticiones = async function parametrizacion_listar_parametros_peticiones(
  token
) {
  const petición = await parametrizacion_peticion_post(
    'parametrizacion_listar_parametros',
    token
  );

  return petición;
};

/**
 * Método encargado de consultar el listado de módulos.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const parametrizacion_listar_modulos_peticiones = async function parametrizacion_listar_modulos_peticiones(
  token
) {
  const petición = await parametrizacion_peticion_post(
    'parametrizacion_listar_modulos',
    token
  );

  return petición;
};

/**
 * Método encargado de consultar el listado de integraciones.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const parametrizacion_listar_integraciones_peticiones = async function parametrizacion_listar_integraciones_peticiones(
  token
) {
  const petición = await parametrizacion_peticion_post(
    'parametrizacion_listar_integraciones',
    token
  );

  return petición;
};

/**
 * Método encargado de consultar el listado de menús.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const parametrizacion_listar_menus_peticiones = async function parametrizacion_listar_menus_peticiones(
  token
) {
  const petición = await parametrizacion_peticion_post(
    'parametrizacion_listar_menus',
    token
  );

  return petición;
};
