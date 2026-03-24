const CONTROLADOR_SEGURIDAD = '../../app/Controllers/seguridad_controller.php';
/**
 * Función encargada de ejecutar una petición POST al controlador seguridad.
 *
 * @param      string  accion      Acción a ejecutar.
 * @param      string  token       Token de seguridad.
 * @param      object  parametros  Parámetros adicionales.
 *
 * @return     object  Resultado de la petición.
 */
async function seguridad_peticion_post(accion, token, parametros = {}) {
  const form_data = new FormData();

  form_data.append(`accion`, accion);
  form_data.append(`token`, token);

  Object.keys(parametros).forEach(function(llave) {
    form_data.append(llave, parametros[llave]);
  });

  const peticion = await fetch(CONTROLADOR_SEGURIDAD, {
    method : `POST`,
    body   : form_data,
  });

  return await peticion.json();
}
/**
 * Función encargada de inicializar el módulo seguridad.
 *
 * @param      string  token  Token de seguridad.
 *
 * @return     object  Resultado de la petición.
 */
async function seguridad_inicializar_peticiones(token) {
  return await seguridad_peticion_post(`seguridad_inicializar`, token);
}
/**
 * Función encargada de consultar la información general del módulo.
 *
 * @param      string  token  Token de seguridad.
 *
 * @return     object  Resultado de la petición.
 */
async function seguridad_listar_panel_peticiones(token) {
  return await seguridad_peticion_post(`seguridad_listar_panel`, token);
}
/**
 * Función encargada de cerrar una sesión específica.
 *
 * @param      string   token              Token de seguridad.
 * @param      integer  usuario_sesion_id  Identificador de la sesión.
 *
 * @return     object  Resultado de la petición.
 */
async function seguridad_cerrar_sesion_peticiones(token, usuario_sesion_id) {
  return await seguridad_peticion_post(`seguridad_cerrar_sesion`, token, {
    usuario_sesion_id,
  });
}
/**
 * Función encargada de cerrar las demás sesiones del usuario actual.
 *
 * @param      string  token  Token de seguridad.
 *
 * @return     object  Resultado de la petición.
 */
async function seguridad_cerrar_otras_sesiones_peticiones(token) {
  return await seguridad_peticion_post(`seguridad_cerrar_otras_sesiones`, token);
}
/**
 * Función encargada de actualizar la clave de un usuario.
 *
 * @param      string   token               Token de seguridad.
 * @param      integer  usuario_id          Usuario a actualizar.
 * @param      string   clave_nueva         Nueva clave.
 * @param      string   clave_confirmacion  Confirmación de la clave.
 *
 * @return     object  Resultado de la petición.
 */
async function seguridad_cambiar_clave_usuario_peticiones(token, usuario_id, clave_nueva, clave_confirmacion) {
  return await seguridad_peticion_post(`seguridad_cambiar_clave_usuario`, token, {
    usuario_id,
    clave_nueva,
    clave_confirmacion,
  });
}
