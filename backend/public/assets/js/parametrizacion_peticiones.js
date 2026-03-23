const URL_CONTROLADOR_PARAMETRIZACION = `../../app/Controllers/parametrizacion_controller.php`;
/**
 * Función encargada de realizar la petición POST al controlador del módulo
 *
 * @param      string  accion       Acción a ejecutar en el controlador
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta del controlador
 */
const parametrizacion_peticion_fetch = async function(accion, token_param) {
  let params = {
    accion : accion,
    token  : token_param,
  };
  let peticion = null;

  if (typeof petición_fetch === `function`) {
    peticion = await petición_fetch(URL_CONTROLADOR_PARAMETRIZACION, params);

    if (typeof validar_estado_peticion_ajax === `function`)
      validar_estado_peticion_ajax(peticion);

    return peticion;
  }

  const formulario = new FormData();
  Object.keys(params).forEach(function(parametro) {
    formulario.append(parametro, params[parametro]);
  });

  peticion = await fetch(URL_CONTROLADOR_PARAMETRIZACION, {
    method : `POST`,
    body   : formulario,
  });

  return await peticion.json();
};
/**
 * Función encargada de consultar la información inicial del módulo
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con la información inicial del módulo
 */
const parametrizacion_inicializar_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_inicializar`, token_param);
};
/**
 * Función encargada de consultar el listado de temas
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_temas_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_temas`, token_param);
};
/**
 * Función encargada de consultar el listado de tokens de temas
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_tema_tokens_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_tema_tokens`, token_param);
};
/**
 * Función encargada de consultar el listado de componentes de temas
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_tema_componentes_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_tema_componentes`, token_param);
};
/**
 * Función encargada de consultar el listado de branding
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_branding_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_branding`, token_param);
};
/**
 * Función encargada de consultar el listado de grupos de parámetros
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_parametro_grupos_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_parametro_grupos`, token_param);
};
/**
 * Función encargada de consultar el listado de parámetros
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_parametros_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_parametros`, token_param);
};
/**
 * Función encargada de consultar el listado de valores de parámetros
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_parametro_valores_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_parametro_valores`, token_param);
};
/**
 * Función encargada de consultar el listado de módulos
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_modulos_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_modulos`, token_param);
};
/**
 * Función encargada de consultar el listado de configuraciones de módulos
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_modulo_configuraciones_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_modulo_configuraciones`, token_param);
};
/**
 * Función encargada de consultar el listado de integraciones
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_integraciones_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_integraciones`, token_param);
};
/**
 * Función encargada de consultar el listado de configuraciones de integraciones
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_integracion_configuraciones_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_integracion_configuraciones`, token_param);
};
/**
 * Función encargada de consultar el listado de plantillas
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_plantillas_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_plantillas`, token_param);
};
/**
 * Función encargada de consultar el listado de menús
 *
 * @param      string  token_param  Token de seguridad de la sesión
 *
 * @return     object  Respuesta con el listado consultado
 */
const parametrizacion_listar_menus_peticiones = async function(token_param) {
  return await parametrizacion_peticion_fetch(`parametrizacion_listar_menus`, token_param);
};
