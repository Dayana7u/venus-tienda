let log_pet = {};
// Datos generales
  log_pet.controlador = `../../app/Controllers/login_controller.php`;
/**
 * Función encargada de realizar peticiones POST al controlador login
 *
 * @param      string  accion      Acción a ejecutar
 * @param      object  parametros  Parámetros de la petición
 *
 * @return     object  Resultado de la petición
 */
async function login_peticion_post(accion, parametros = {}) {
  const form_data = new FormData();

  form_data.append(`accion`, accion);

  Object.keys(parametros).forEach(function(key) {
    form_data.append(key, parametros[key]);
  });

  try {
    const peticion = await fetch(log_pet.controlador, {
      method : `POST`,
      body   : form_data
    });

    return await peticion.json();
  }
  catch (error) {
    return {
      estado  : false,
      mensaje : `No fue posible completar la petición del login.`,
      datos   : [],
    };
  }
}
/**
 * Función encargada de inicializar el módulo login
 *
 * @param      string  token  Token de la sesión
 *
 * @return     object  Resultado de la petición
 */
async function login_inicializar_peticiones(token) {
  const params = {token};
  let peticion = await login_peticion_post(`login_inicializar`, params);
  return peticion;
}
/**
 * Función encargada de autenticar el usuario
 *
 * @param      string  token          Token de la sesión
 * @param      string  login_usuario  Login del usuario
 * @param      string  login_clave    Clave del usuario
 *
 * @return     object  Resultado de la petición
 */
async function login_autenticar_peticiones(token, login_usuario, login_clave) {
  const params = {
    token,
    login_usuario,
    login_clave
  };
  let peticion = await login_peticion_post(`login_autenticar`, params);
  return peticion;
}
/**
 * Función encargada de validar la sesión actual
 *
 * @param      string  token  Token de la sesión
 *
 * @return     object  Resultado de la petición
 */
async function login_validar_sesion_peticiones(token) {
  const params = {token};
  let peticion = await login_peticion_post(`login_validar_sesion`, params);
  return peticion;
}
