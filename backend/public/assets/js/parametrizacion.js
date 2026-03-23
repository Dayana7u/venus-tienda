let par = {}; // Objeto general
// Datos generales
  par.token                    = document.getElementById(`token`).value;
  par.texto_busqueda           = ``;
  par.datos_iniciales          = {};
  par.listado_parametrizacion  = {
    temas                       : [],
    tema_tokens                 : [],
    tema_componentes            : [],
    branding                    : [],
    parametro_grupos            : [],
    parametros                  : [],
    parametro_valores           : [],
    modulos                     : [],
    modulo_configuraciones      : [],
    integraciones               : [],
    integracion_configuraciones : [],
    plantillas                  : [],
    menus                       : []
  };
  par.secciones_parametrizacion = {
    temas : {
      titulo         : `Temas`,
      mensaje_vacio  : `No hay temas para mostrar.`,
      div_contenido  : `div_contenido_temas_parametrizacion`,
      article        : `article_temas_parametrizacion`
    },
    tema_tokens : {
      titulo         : `Tokens de tema`,
      mensaje_vacio  : `No hay tokens de tema para mostrar.`,
      div_contenido  : `div_contenido_tema_tokens_parametrizacion`,
      article        : `article_tema_tokens_parametrizacion`
    },
    tema_componentes : {
      titulo         : `Componentes de tema`,
      mensaje_vacio  : `No hay componentes de tema para mostrar.`,
      div_contenido  : `div_contenido_tema_componentes_parametrizacion`,
      article        : `article_tema_componentes_parametrizacion`
    },
    branding : {
      titulo         : `Branding`,
      mensaje_vacio  : `No hay branding para mostrar.`,
      div_contenido  : `div_contenido_branding_parametrizacion`,
      article        : `article_branding_parametrizacion`
    },
    parametro_grupos : {
      titulo         : `Grupos de parámetros`,
      mensaje_vacio  : `No hay grupos de parámetros para mostrar.`,
      div_contenido  : `div_contenido_parametro_grupos_parametrizacion`,
      article        : `article_parametro_grupos_parametrizacion`
    },
    parametros : {
      titulo         : `Parámetros`,
      mensaje_vacio  : `No hay parámetros para mostrar.`,
      div_contenido  : `div_contenido_parametros_parametrizacion`,
      article        : `article_parametros_parametrizacion`
    },
    parametro_valores : {
      titulo         : `Valores de parámetros`,
      mensaje_vacio  : `No hay valores de parámetros para mostrar.`,
      div_contenido  : `div_contenido_parametro_valores_parametrizacion`,
      article        : `article_parametro_valores_parametrizacion`
    },
    modulos : {
      titulo         : `Módulos`,
      mensaje_vacio  : `No hay módulos para mostrar.`,
      div_contenido  : `div_contenido_modulos_parametrizacion`,
      article        : `article_modulos_parametrizacion`
    },
    modulo_configuraciones : {
      titulo         : `Configuraciones de módulos`,
      mensaje_vacio  : `No hay configuraciones de módulos para mostrar.`,
      div_contenido  : `div_contenido_modulo_configuraciones_parametrizacion`,
      article        : `article_modulo_configuraciones_parametrizacion`
    },
    integraciones : {
      titulo         : `Integraciones`,
      mensaje_vacio  : `No hay integraciones para mostrar.`,
      div_contenido  : `div_contenido_integraciones_parametrizacion`,
      article        : `article_integraciones_parametrizacion`
    },
    integracion_configuraciones : {
      titulo         : `Configuraciones de integraciones`,
      mensaje_vacio  : `No hay configuraciones de integraciones para mostrar.`,
      div_contenido  : `div_contenido_integracion_configuraciones_parametrizacion`,
      article        : `article_integracion_configuraciones_parametrizacion`
    },
    plantillas : {
      titulo         : `Plantillas`,
      mensaje_vacio  : `No hay plantillas para mostrar.`,
      div_contenido  : `div_contenido_plantillas_parametrizacion`,
      article        : `article_plantillas_parametrizacion`
    },
    menus : {
      titulo         : `Menús`,
      mensaje_vacio  : `No hay menús para mostrar.`,
      div_contenido  : `div_contenido_menus_parametrizacion`,
      article        : `article_menus_parametrizacion`
    }
  };
// Campos
  par.campo_buscar_parametrizacion = document.getElementById(`campo_buscar_parametrizacion`);
// Divs
  par.div_estado_parametrizacion   = document.getElementById(`div_estado_parametrizacion`);
  par.div_resumen_parametrizacion  = document.getElementById(`div_resumen_parametrizacion`);
// Botones
  par.btn_recargar_parametrizacion = document.getElementById(`btn_recargar_parametrizacion`);
// Inicializar módulo
  inicializar_modulo_parametrizacion();
// Funciones
  /**
   * Función encargada de inicializar el módulo de parametrización.
   */
  async function inicializar_modulo_parametrizacion() {
    await mostrar_estado_parametrizacion(`Cargando parametrización base...`, `info`);
    par.datos_iniciales = await parametrizacion_inicializar_peticiones(par.token);
    await listar_datos_parametrizacion();
    await asignar_eventos_parametrizacion();
    await mostrar_estado_parametrizacion(`Parametrización cargada correctamente.`, `success`);
  }
  /**
   * Función encargada de asignar los eventos principales del módulo.
   */
  async function asignar_eventos_parametrizacion() {
    if (par.btn_recargar_parametrizacion) {
      par.btn_recargar_parametrizacion.addEventListener(`click`, async function() {
        await mostrar_estado_parametrizacion(`Actualizando información de parametrización...`, `info`);
        await listar_datos_parametrizacion();
        await mostrar_estado_parametrizacion(`Información actualizada correctamente.`, `success`);
      });
    }
    if (par.campo_buscar_parametrizacion) {
      par.campo_buscar_parametrizacion.addEventListener(`input`, async function(evento) {
        par.texto_busqueda = evento.target.value.trim().toLowerCase();
        await renderizar_parametrizacion();
      });
    }
  }
  /**
   * Función encargada de listar la información base de parametrización.
   */
  async function listar_datos_parametrizacion() {
    try {
      const respuestas = await Promise.all([
        parametrizacion_listar_temas_peticiones(par.token),
        parametrizacion_listar_tema_tokens_peticiones(par.token),
        parametrizacion_listar_tema_componentes_peticiones(par.token),
        parametrizacion_listar_branding_peticiones(par.token),
        parametrizacion_listar_parametro_grupos_peticiones(par.token),
        parametrizacion_listar_parametros_peticiones(par.token),
        parametrizacion_listar_parametro_valores_peticiones(par.token),
        parametrizacion_listar_modulos_peticiones(par.token),
        parametrizacion_listar_modulo_configuraciones_peticiones(par.token),
        parametrizacion_listar_integraciones_peticiones(par.token),
        parametrizacion_listar_integracion_configuraciones_peticiones(par.token),
        parametrizacion_listar_plantillas_peticiones(par.token),
        parametrizacion_listar_menus_peticiones(par.token)
      ]);
      par.listado_parametrizacion.temas                       = await obtener_datos_respuesta_parametrizacion(respuestas[0]);
      par.listado_parametrizacion.tema_tokens                 = await obtener_datos_respuesta_parametrizacion(respuestas[1]);
      par.listado_parametrizacion.tema_componentes            = await obtener_datos_respuesta_parametrizacion(respuestas[2]);
      par.listado_parametrizacion.branding                    = await obtener_datos_respuesta_parametrizacion(respuestas[3]);
      par.listado_parametrizacion.parametro_grupos            = await obtener_datos_respuesta_parametrizacion(respuestas[4]);
      par.listado_parametrizacion.parametros                  = await obtener_datos_respuesta_parametrizacion(respuestas[5]);
      par.listado_parametrizacion.parametro_valores           = await obtener_datos_respuesta_parametrizacion(respuestas[6]);
      par.listado_parametrizacion.modulos                     = await obtener_datos_respuesta_parametrizacion(respuestas[7]);
      par.listado_parametrizacion.modulo_configuraciones      = await obtener_datos_respuesta_parametrizacion(respuestas[8]);
      par.listado_parametrizacion.integraciones               = await obtener_datos_respuesta_parametrizacion(respuestas[9]);
      par.listado_parametrizacion.integracion_configuraciones = await obtener_datos_respuesta_parametrizacion(respuestas[10]);
      par.listado_parametrizacion.plantillas                  = await obtener_datos_respuesta_parametrizacion(respuestas[11]);
      par.listado_parametrizacion.menus                       = await obtener_datos_respuesta_parametrizacion(respuestas[12]);
      await renderizar_parametrizacion();
    }
    catch (error) {
      await mostrar_estado_parametrizacion(`No fue posible cargar la parametrización.`, `error`);
      console.error(error);
    }
  }
  /**
   * Función encargada de obtener la lista de datos de una respuesta del controlador.
   *
   * @param      object  respuesta  Respuesta de la petición realizada.
   *
   * @return     array   Datos listados por el controlador.
   */
  async function obtener_datos_respuesta_parametrizacion(respuesta) {
    if (!respuesta || respuesta.estado !== true)
      return [];
    if (!Array.isArray(respuesta.datos))
      return [];
    return respuesta.datos;
  }
  /**
   * Función encargada de renderizar el módulo de parametrización.
   */
  async function renderizar_parametrizacion() {
    await renderizar_resumen_parametrizacion();
    for (let seccion in par.secciones_parametrizacion)
      await renderizar_bloque_parametrizacion(seccion);
  }
  /**
   * Función encargada de renderizar el resumen principal de parametrización.
   */
  async function renderizar_resumen_parametrizacion() {
    let template = await template_resumen_parametrizacion(
      par.listado_parametrizacion,
      par.secciones_parametrizacion,
      par.texto_busqueda
    );
    par.div_resumen_parametrizacion.innerHTML = ``;
    par.div_resumen_parametrizacion.innerHTML = template;
  }
  /**
   * Función encargada de renderizar una sección del módulo de parametrización.
   *
   * @param      string  seccion  Nombre de la sección a renderizar.
   */
  async function renderizar_bloque_parametrizacion(seccion) {
    let configuracion  = par.secciones_parametrizacion[seccion];
    let listado        = par.listado_parametrizacion[seccion] ? par.listado_parametrizacion[seccion] : [];
    let listado_filtra = await filtrar_listado_parametrizacion(listado, par.texto_busqueda);
    let contenedor     = document.getElementById(configuracion.div_contenido);
    if (!contenedor)
      return;
    contenedor.innerHTML = ``;
    contenedor.innerHTML = await template_bloque_parametrizacion(
      configuracion.titulo,
      listado_filtra,
      configuracion.mensaje_vacio,
      seccion
    );
  }
  /**
   * Función encargada de filtrar una sección de parametrización por texto.
   *
   * @param      array   listado         Datos de la sección.
   * @param      string  texto_busqueda  Texto buscado dentro del listado.
   *
   * @return     array   Datos filtrados.
   */
  async function filtrar_listado_parametrizacion(listado, texto_busqueda) {
    let datos = [];
    if (texto_busqueda === ``)
      return listado;
    for (let i = 0; i < listado.length; i++) {
      let valor = JSON.stringify(listado[i]).toLowerCase();
      if (valor.includes(texto_busqueda))
        datos.push(listado[i]);
    }
    return datos;
  }
  /**
   * Función encargada de ubicar visualmente una sección del módulo.
   *
   * @param      string  seccion  Nombre de la sección.
   */
  async function visualizar_seccion_parametrizacion(seccion) {
    let configuracion = par.secciones_parametrizacion[seccion];
    if (!configuracion)
      return;
    let contenedor = document.getElementById(configuracion.article);
    if (!contenedor)
      return;
    contenedor.scrollIntoView({ behavior : `smooth`, block : `start` });
  }
  /**
   * Función encargada de mostrar el estado general del módulo.
   *
   * @param      string  mensaje  Mensaje a visualizar.
   * @param      string  tipo     Tipo de estado a representar.
   */
  async function mostrar_estado_parametrizacion(mensaje, tipo) {
    if (!par.div_estado_parametrizacion)
      return;
    par.div_estado_parametrizacion.dataset.tipo = tipo;
    par.div_estado_parametrizacion.textContent  = mensaje;
  }
