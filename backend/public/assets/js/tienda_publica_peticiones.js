/**
 * Función encargada de realizar peticiones POST del módulo tienda pública.
 *
 * @param      string  accion      Acción a ejecutar.
 * @param      object  parametros  Parámetros de la petición.
 *
 * @return     object  Respuesta procesada del controlador.
 */
async function tienda_publica_peticion_post(accion, parametros = {}) {
  const formulario = new FormData();
  formulario.append(`accion`, accion);

  Object.entries(parametros).forEach(function([clave, valor]) {
    formulario.append(clave, valor);
  });

  let petición = await fetch(`../Controllers/tienda_publica_controller.php`, {
    method      : `POST`,
    body        : formulario,
    credentials : `same-origin`
  });

  return petición.json();
}
/**
 * Función encargada de inicializar el módulo tienda pública.
 *
 * @param      string  token  Token de la vista pública.
 *
 * @return     object  Resultado de la petición.
 */
async function tienda_publica_inicializar_peticiones(token) {
  const params = {
    token
  };
  let petición = await tienda_publica_peticion_post(`tienda_publica_inicializar`, params);
  return petición;
}
/**
 * Función encargada de consultar la portada pública.
 *
 * @param      string  token  Token de la vista pública.
 *
 * @return     object  Resultado de la petición.
 */
async function tienda_publica_listar_portada_peticiones(token) {
  const params = {
    token
  };
  let petición = await tienda_publica_peticion_post(`tienda_publica_listar_portada`, params);
  return petición;
}
