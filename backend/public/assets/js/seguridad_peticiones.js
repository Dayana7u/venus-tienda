const CONTROLADOR_SEGURIDAD = '../../app/Controllers/seguridad_controller.php';

/**
 * Método encargado de realizar una petición POST al controlador del módulo seguridad.
 *
 * @param      {string}  accion  Acción que se ejecutará en el controlador.
 * @param      {string}  token   Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const seguridad_peticion_post = async function seguridad_peticion_post(
  accion,
  token
) {
  const params = {
    accion,
    token
  };

  try {
    if (typeof petición_fetch === 'function') {
      const petición = await petición_fetch(CONTROLADOR_SEGURIDAD, params);

      if (typeof validar_estado_peticion_ajax === 'function')
        validar_estado_peticion_ajax(petición);

      return petición;
    }

    const form_data = new FormData();

    Object.keys(params).forEach((key) => {
      form_data.append(key, params[key]);
    });

    const petición = await fetch(CONTROLADOR_SEGURIDAD, {
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
 * Método encargado de consultar la información base del módulo seguridad.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const seguridad_inicializar_peticiones = async function seguridad_inicializar_peticiones(
  token
) {
  const petición = await seguridad_peticion_post(
    'seguridad_inicializar',
    token
  );

  return petición;
};

/**
 * Método encargado de consultar el listado de usuarios.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const seguridad_listar_usuarios_peticiones = async function seguridad_listar_usuarios_peticiones(
  token
) {
  const petición = await seguridad_peticion_post(
    'seguridad_listar_usuarios',
    token
  );

  return petición;
};

/**
 * Método encargado de consultar el listado de roles.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const seguridad_listar_roles_peticiones = async function seguridad_listar_roles_peticiones(
  token
) {
  const petición = await seguridad_peticion_post(
    'seguridad_listar_roles',
    token
  );

  return petición;
};

/**
 * Método encargado de consultar el listado de permisos.
 *
 * @param      {string}  token  Token de seguridad de la sesión.
 *
 * @returns    {Promise<object>}  Respuesta del controlador.
 */
const seguridad_listar_permisos_peticiones = async function seguridad_listar_permisos_peticiones(
  token
) {
  const petición = await seguridad_peticion_post(
    'seguridad_listar_permisos',
    token
  );

  return petición;
};
