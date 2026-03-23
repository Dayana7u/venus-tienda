let par_pet = {}; // Objeto general
// Datos generales
  par_pet.controlador = `../../app/Controllers/parametrizacion_controller.php`;
// Peticiones
  /**
   * Función encargada de realizar una petición POST al controlador del módulo.
   *
   * @param      string   accion      Acción a ejecutar en el controlador.
   * @param      string   token       Token de seguridad de la sesión.
   * @param      object   [params={}] Parámetros adicionales para la petición.
   *
   * @return     object   Respuesta del controlador.
   */
  async function parametrizacion_peticion_post(accion, token, params = {}) {
    const form_data = new FormData();
    const parametros = {
      accion : accion,
      token  : token,
      ...params
    };
    for (let key in parametros)
      form_data.append(key, parametros[key]);
    let peticion = await fetch(par_pet.controlador, {
      method : `POST`,
      body   : form_data
    });
    peticion = await peticion.json();
    if (typeof validar_estado_peticion_ajax === `function`)
      validar_estado_peticion_ajax(peticion);
    return peticion;
  }
  /**
   * Función encargada de inicializar la parametrización.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_inicializar_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_inicializar`, token);
    return peticion;
  }
  /**
   * Función encargada de listar los temas.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_temas_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_temas`, token);
    return peticion;
  }
  /**
   * Función encargada de listar los tokens de tema.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_tema_tokens_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_tema_tokens`, token);
    return peticion;
  }
  /**
   * Función encargada de listar los componentes de tema.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_tema_componentes_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_tema_componentes`, token);
    return peticion;
  }
  /**
   * Función encargada de listar el branding.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_branding_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_branding`, token);
    return peticion;
  }
  /**
   * Función encargada de listar los grupos de parámetros.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_parametro_grupos_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_parametro_grupos`, token);
    return peticion;
  }
  /**
   * Función encargada de listar los parámetros.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_parametros_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_parametros`, token);
    return peticion;
  }
  /**
   * Función encargada de listar los valores de parámetros.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_parametro_valores_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_parametro_valores`, token);
    return peticion;
  }
  /**
   * Función encargada de listar los módulos.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_modulos_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_modulos`, token);
    return peticion;
  }
  /**
   * Función encargada de listar las configuraciones de módulos.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_modulo_configuraciones_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_modulo_configuraciones`, token);
    return peticion;
  }
  /**
   * Función encargada de listar las integraciones.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_integraciones_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_integraciones`, token);
    return peticion;
  }
  /**
   * Función encargada de listar las configuraciones de integraciones.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_integracion_configuraciones_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_integracion_configuraciones`, token);
    return peticion;
  }
  /**
   * Función encargada de listar las plantillas.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_plantillas_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_plantillas`, token);
    return peticion;
  }
  /**
   * Función encargada de listar los menús.
   *
   * @param      string  token  Token de seguridad de la sesión.
   *
   * @return     object  Respuesta de la petición.
   */
  async function parametrizacion_listar_menus_peticiones(token) {
    let peticion = await parametrizacion_peticion_post(`parametrizacion_listar_menus`, token);
    return peticion;
  }
