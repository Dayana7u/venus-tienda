let par_pet = {};
// Datos generales
  par_pet.controlador = `../../app/Controllers/parametrizacion_controller.php`;
/**
 * Función encargada de realizar peticiones POST al controlador parametrización
 *
 * @param      string  accion      Acción a ejecutar
 * @param      object  parametros  Parámetros de la petición
 *
 * @return     object  Resultado de la petición
 */
async function parametrizacion_peticion_post(accion, parametros = {}) {
  const form_data = new FormData();

  form_data.append(`accion`, accion);

  Object.keys(parametros).forEach(function(key) {
    form_data.append(key, parametros[key]);
  });

  try {
    const peticion = await fetch(par_pet.controlador, {
      method : `POST`,
      body   : form_data
    });

    return await peticion.json();
  }
  catch (error) {
    return {
      estado  : false,
      mensaje : `No fue posible completar la petición de parametrización.`,
      datos   : [],
    };
  }
}
/**
 * Función encargada de inicializar el módulo parametrización
 *
 * @param      string  token  Token de la sesión
 *
 * @return     object  Resultado de la petición
 */
async function parametrizacion_inicializar_peticiones(token) {
  const params = {token};
  let peticion = await parametrizacion_peticion_post(`parametrizacion_inicializar`, params);
  return peticion;
}
/**
 * Función encargada de consultar un registro puntual
 *
 * @param      string   token       Token de la sesión
 * @param      string   seccion     Sección del registro
 * @param      integer  registro_id Identificador del registro
 *
 * @return     object  Resultado de la petición
 */
async function parametrizacion_consultar_registro_peticiones(token, seccion, registro_id) {
  const params = {
    token,
    seccion,
    registro_id
  };
  let peticion = await parametrizacion_peticion_post(`parametrizacion_consultar_registro`, params);
  return peticion;
}
/**
 * Función encargada de guardar un registro
 *
 * @param      string  token       Token de la sesión
 * @param      object  parametros  Datos del formulario
 *
 * @return     object  Resultado de la petición
 */
async function parametrizacion_guardar_registro_peticiones(token, parametros) {
  const params = {
    token,
    ...parametros
  };
  let peticion = await parametrizacion_peticion_post(`parametrizacion_guardar_registro`, params);
  return peticion;
}
/**
 * Función encargada de cambiar el estado de un registro
 *
 * @param      string   token       Token de la sesión
 * @param      string   seccion     Sección del registro
 * @param      integer  registro_id Identificador del registro
 * @param      string   estado      Estado a aplicar
 *
 * @return     object  Resultado de la petición
 */
async function parametrizacion_cambiar_estado_registro_peticiones(token, seccion, registro_id, estado) {
  const params = {
    token,
    seccion,
    registro_id,
    estado
  };
  let peticion = await parametrizacion_peticion_post(`parametrizacion_cambiar_estado_registro`, params);
  return peticion;
}

/**
 * Función encargada de borrar un registro
 *
 * @param      string   token       Token de la sesión
 * @param      string   seccion     Sección del registro
 * @param      integer  registro_id Identificador del registro
 *
 * @return     object  Resultado de la petición
 */
async function parametrizacion_borrar_registro_peticiones(token, seccion, registro_id) {
  const params = {
    token,
    seccion,
    registro_id
  };
  let peticion = await parametrizacion_peticion_post(`parametrizacion_borrar_registro`, params);
  return peticion;
}
