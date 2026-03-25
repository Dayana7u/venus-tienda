let par = {};
// Datos generales
  par.token                      = document.getElementById(`token`).value;
  par.estado_envio               = false;
  par.estado_carga               = false;
  par.temporizador_alerta        = null;
  par.listado_temas              = [];
  par.listado_branding           = [];
  par.listado_parametros         = [];
  par.listado_modulos            = [];
  par.listado_integraciones      = [];
  par.listado_menus              = [];
  par.listado_roles              = [];
  par.listado_usuarios           = [];
  par.listado_roles_permisos     = [];
  par.listado_parametro_grupos   = [];
  par.catalogos                  = {};
  par.seccion_actual             = ``;
  par.confirmacion_resolve       = null;
// Campos
  par.buscar_parametrizacion     = document.getElementById(`buscar_parametrizacion`);
  par.formulario_parametrizacion = document.getElementById(`formulario_parametrizacion`);
  par.form_seccion               = document.getElementById(`form_seccion`);
  par.form_registro_id           = document.getElementById(`form_registro_id`);
// Botones
  par.btn_menu_parametrizacion           = document.getElementById(`btn_menu_parametrizacion`);
  par.btn_recargar_parametrizacion       = document.getElementById(`btn_recargar_parametrizacion`);
  par.btn_guardar_parametrizacion        = document.getElementById(`btn_guardar_parametrizacion`);
  par.btn_limpiar_parametrizacion        = document.getElementById(`btn_limpiar_parametrizacion`);
  par.btn_cerrar_panel_parametrizacion   = document.getElementById(`btn_cerrar_panel_parametrizacion`);
  par.btn_cerrar_panel_backdrop          = document.getElementById(`btn_cerrar_panel_backdrop`);
  par.btn_confirmar_parametrizacion      = document.getElementById(`btn_confirmar_parametrizacion`);
  par.btn_cancelar_confirmacion          = document.getElementById(`btn_cancelar_confirmacion_parametrizacion`);
  par.btn_cancelar_confirmacion_backdrop = document.getElementById(`btn_cancelar_confirmacion_backdrop`);
// Divs
  par.div_mensaje_parametrizacion       = document.getElementById(`div_mensaje_parametrizacion`);
  par.div_resumen_parametrizacion       = document.getElementById(`div_resumen_parametrizacion`);
  par.div_campos_parametrizacion        = document.getElementById(`div_campos_parametrizacion`);
  par.div_secciones_parametrizacion     = document.getElementById(`div_secciones_parametrizacion`);
  par.panel_formulario_parametrizacion  = document.getElementById(`panel_formulario_parametrizacion`);
  par.titulo_panel_parametrizacion      = document.getElementById(`titulo_panel_parametrizacion`);
  par.texto_panel_seccion               = document.getElementById(`texto_panel_seccion`);
  par.modal_confirmacion_parametrizacion = document.getElementById(`modal_confirmacion_parametrizacion`);
  par.titulo_confirmacion_parametrizacion = document.getElementById(`titulo_confirmacion_parametrizacion`);
  par.texto_confirmacion_parametrizacion  = document.getElementById(`texto_confirmacion_parametrizacion`);
  par.sidebar_parametrizacion            = document.getElementById(`dx_sidebar_parametrizacion`);
  par.sidebar_backdrop                   = document.getElementById(`dx_sidebar_backdrop`);
  par.seccion_temas                      = document.getElementById(`seccion_temas`);
  par.seccion_branding                   = document.getElementById(`seccion_branding`);
  par.seccion_parametros                 = document.getElementById(`seccion_parametros`);
  par.seccion_modulos                    = document.getElementById(`seccion_modulos`);
  par.seccion_integraciones              = document.getElementById(`seccion_integraciones`);
  par.seccion_menus                      = document.getElementById(`seccion_menus`);
  par.seccion_roles                      = document.getElementById(`seccion_roles`);
  par.seccion_usuarios                   = document.getElementById(`seccion_usuarios`);
  par.seccion_roles_permisos             = document.getElementById(`seccion_roles_permisos`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_parametrizacion();
});
/**
 * Función encargada de inicializar el módulo parametrización
 */
async function inicializar_parametrizacion() {
  await listar_parametrizacion();
  eventos_parametrizacion();
}
/**
 * Función encargada de registrar los eventos principales del módulo
 */
function eventos_parametrizacion() {
  par.btn_menu_parametrizacion.addEventListener(`click`, function() {
    alternar_menu_parametrizacion(true);
  });

  par.sidebar_backdrop.addEventListener(`click`, function() {
    alternar_menu_parametrizacion(false);
  });

  par.btn_recargar_parametrizacion.addEventListener(`click`, async function() {
    await listar_parametrizacion();
    mostrar_alerta_parametrizacion(`info`, `Información recargada correctamente.`);
  });

  par.buscar_parametrizacion.addEventListener(`input`, async function() {
    await renderizar_parametrizacion();
  });

  par.formulario_parametrizacion.addEventListener(`submit`, async function(event) {
    event.preventDefault();
    await guardar_registro_parametrizacion();
  });

  par.btn_limpiar_parametrizacion.addEventListener(`click`, async function() {
    await limpiar_formulario_parametrizacion();
  });


  par.btn_cerrar_panel_parametrizacion.addEventListener(`click`, async function() {
    await cerrar_formulario_parametrizacion();
  });

  par.btn_cerrar_panel_backdrop.addEventListener(`click`, async function() {
    await cerrar_formulario_parametrizacion();
  });

  par.btn_confirmar_parametrizacion.addEventListener(`click`, function() {
    resolver_confirmacion_parametrizacion(true);
  });

  par.btn_cancelar_confirmacion.addEventListener(`click`, function() {
    resolver_confirmacion_parametrizacion(false);
  });

  par.btn_cancelar_confirmacion_backdrop.addEventListener(`click`, function() {
    resolver_confirmacion_parametrizacion(false);
  });

  par.div_mensaje_parametrizacion.addEventListener(`click`, function(event) {
    if (event.target.matches(`[data-alerta-cerrar="true"]`)) {
      limpiar_alerta_parametrizacion();
    }
  });

  par.div_secciones_parametrizacion.addEventListener(`click`, async function(event) {
    await gestionar_click_parametrizacion(event);
  });

  document.querySelectorAll(`[data-menu-link="true"]`).forEach(function(item) {
    item.addEventListener(`click`, function() {
      alternar_menu_parametrizacion(false);
    });
  });
}
/**
 * Función encargada de gestionar los clics del módulo
 *
 * @param      object  event  Evento del navegador
 */
async function gestionar_click_parametrizacion(event) {
  const boton = event.target.closest(`[data-accion]`);

  if (!boton) {
    return;
  }

  const accion      = boton.dataset.accion;
  const seccion     = boton.dataset.seccion ?? ``;
  const registro_id = Number(boton.dataset.registroId ?? 0);
  const estado      = boton.dataset.estado ?? ``;

  if (accion === `abrir_formulario`) {
    await abrir_formulario_parametrizacion(seccion);
    return;
  }

  if (accion === `editar_registro`) {
    await editar_registro_parametrizacion(seccion, registro_id);
    return;
  }

  if (accion === `cambiar_estado`) {
    await gestionar_estado_registro_parametrizacion(seccion, registro_id, estado);
    return;
  }

  if (accion === `borrar_registro`) {
    await borrar_registro_parametrizacion(seccion, registro_id);
  }
}
/**
 * Función encargada de consultar la información inicial del módulo
 */
async function listar_parametrizacion() {
  par.estado_carga = true;

  let peticion = await parametrizacion_inicializar_peticiones(par.token);

  par.estado_carga = false;

  if (peticion.estado !== true) {
    mostrar_alerta_parametrizacion(`error`, peticion.mensaje);
    return;
  }

  par.listado_temas            = peticion.datos.temas ?? [];
  par.listado_branding         = peticion.datos.branding ?? [];
  par.listado_parametros       = peticion.datos.parametros ?? [];
  par.listado_modulos          = peticion.datos.modulos ?? [];
  par.listado_integraciones    = peticion.datos.integraciones ?? [];
  par.listado_menus            = peticion.datos.menus ?? [];
  par.listado_roles            = peticion.datos.roles ?? [];
  par.listado_usuarios         = peticion.datos.usuarios ?? [];
  par.listado_roles_permisos   = peticion.datos.roles_permisos ?? [];
  par.listado_parametro_grupos = peticion.datos.parametro_grupos ?? [];
  par.catalogos               = peticion.datos.catalogos ?? {};
  par.catalogos.estados_binarios = [
    {valor : `1`, texto : `Activo`},
    {valor : `0`, texto : `Inactivo`}
  ];
  par.catalogos.roles = (par.catalogos.roles ?? []).filter(function(rol) {
    return rol.estado === `1` || rol.estado === `` || rol.estado === 1;
  });
  par.catalogos.permisos = (par.catalogos.permisos ?? []).filter(function(permiso) {
    return permiso.estado === `1` || permiso.estado === `` || permiso.estado === 1;
  });
  par.catalogos.menus_padre = [{menu_id : ``, nombre : `Sin padre`}].concat(par.listado_menus);

  await renderizar_parametrizacion();
}
/**
 * Función encargada de renderizar el módulo parametrización
 */
async function renderizar_parametrizacion() {
  const texto_busqueda = par.buscar_parametrizacion.value.trim().toLowerCase();
  const resumen        = obtener_resumen_parametrizacion();

  par.div_resumen_parametrizacion.innerHTML = template_resumen_parametrizacion(resumen);
  par.seccion_temas.innerHTML = template_seccion_parametrizacion(
    obtener_configuracion_seccion_parametrizacion(`temas`),
    filtrar_parametrizacion_listado(par.listado_temas, texto_busqueda)
  );
  par.seccion_branding.innerHTML = template_seccion_parametrizacion(
    obtener_configuracion_seccion_parametrizacion(`branding`),
    filtrar_parametrizacion_listado(par.listado_branding, texto_busqueda)
  );
  par.seccion_parametros.innerHTML = template_seccion_parametrizacion(
    obtener_configuracion_seccion_parametrizacion(`parametros`),
    filtrar_parametrizacion_listado(par.listado_parametros, texto_busqueda)
  );
  par.seccion_modulos.innerHTML = template_seccion_parametrizacion(
    obtener_configuracion_seccion_parametrizacion(`modulos`),
    filtrar_parametrizacion_listado(par.listado_modulos, texto_busqueda)
  );
  par.seccion_integraciones.innerHTML = template_seccion_parametrizacion(
    obtener_configuracion_seccion_parametrizacion(`integraciones`),
    filtrar_parametrizacion_listado(par.listado_integraciones, texto_busqueda)
  );
  par.seccion_menus.innerHTML = template_seccion_parametrizacion(
    obtener_configuracion_seccion_parametrizacion(`menus`),
    filtrar_parametrizacion_listado(par.listado_menus, texto_busqueda)
  );
  par.seccion_roles.innerHTML = template_seccion_parametrizacion(
    obtener_configuracion_seccion_parametrizacion(`roles`),
    filtrar_parametrizacion_listado(par.listado_roles, texto_busqueda)
  );
  par.seccion_usuarios.innerHTML = template_seccion_parametrizacion(
    obtener_configuracion_seccion_parametrizacion(`usuarios`),
    filtrar_parametrizacion_listado(par.listado_usuarios, texto_busqueda)
  );
  par.seccion_roles_permisos.innerHTML = template_seccion_parametrizacion(
    obtener_configuracion_seccion_parametrizacion(`roles_permisos`),
    filtrar_parametrizacion_listado(par.listado_roles_permisos, texto_busqueda)
  );
}
/**
 * Función encargada de abrir el formulario por sección
 *
 * @param      string  seccion  Sección a gestionar
 */
async function abrir_formulario_parametrizacion(seccion) {
  await renderizar_panel_parametrizacion(seccion, 0, {});
}
/**
 * Función encargada de editar un registro del módulo
 *
 * @param      string   seccion      Sección del registro
 * @param      integer  registro_id  Identificador del registro
 */
async function editar_registro_parametrizacion(seccion, registro_id) {
  let peticion = await parametrizacion_consultar_registro_peticiones(par.token, seccion, registro_id);

  if (peticion.estado !== true) {
    mostrar_alerta_parametrizacion(`error`, peticion.mensaje);
    return;
  }

  await renderizar_panel_parametrizacion(seccion, registro_id, peticion.datos);
}
/**
 * Función encargada de guardar un registro del módulo
 */
async function guardar_registro_parametrizacion() {
  if (par.estado_envio === true) {
    return;
  }

  par.estado_envio = true;
  par.btn_guardar_parametrizacion.disabled = true;

  try {
    const form_data   = new FormData(par.formulario_parametrizacion);
    const parametros  = {};

    form_data.forEach(function(valor, llave) {
      parametros[llave] = valor;
    });

    parametros.borrado = `0`;

    let peticion = await parametrizacion_guardar_registro_peticiones(par.token, parametros);

    if (peticion.estado !== true) {
      mostrar_alerta_parametrizacion(`error`, peticion.mensaje);
      return;
    }

    mostrar_alerta_parametrizacion(`success`, peticion.mensaje);
    await cerrar_formulario_parametrizacion();
    await listar_parametrizacion();
  }
  finally {
    par.estado_envio = false;
    par.btn_guardar_parametrizacion.disabled = false;
  }
}
/**
 * Función encargada de gestionar el cambio de estado de un registro
 *
 * @param      string   seccion      Sección del registro
 * @param      integer  registro_id  Identificador del registro
 * @param      string   estado       Estado objetivo
 */
async function gestionar_estado_registro_parametrizacion(seccion, registro_id, estado) {
  const texto_accion = estado === `1` ? `activar` : `inactivar`;
  const confirmacion = await confirmar_accion_parametrizacion(
    `Confirmar cambio`,
    `¿Desea ${texto_accion} el registro seleccionado en ${seccion}?`
  );

  if (confirmacion !== true) {
    return;
  }

  let peticion = await parametrizacion_cambiar_estado_registro_peticiones(
    par.token,
    seccion,
    registro_id,
    estado
  );

  if (peticion.estado !== true) {
    mostrar_alerta_parametrizacion(`error`, peticion.mensaje);
    return;
  }

  mostrar_alerta_parametrizacion(`success`, peticion.mensaje);
  await listar_parametrizacion();
}
/**
 * Función encargada de cerrar el panel del formulario
 */
async function cerrar_formulario_parametrizacion() {
  par.panel_formulario_parametrizacion.classList.add(`dx_oculto`);
  par.panel_formulario_parametrizacion.setAttribute(`aria-hidden`, `true`);
  par.formulario_parametrizacion.reset();
  par.form_seccion.value = ``;
  par.form_registro_id.value = ``;
  par.seccion_actual = ``;
  par.div_campos_parametrizacion.innerHTML = ``;
}
/**
 * Función encargada de limpiar el formulario del módulo
 */
async function limpiar_formulario_parametrizacion() {
  const seccion = par.seccion_actual || par.form_seccion.value;
  const registro_id = Number(par.form_registro_id.value || 0);

  if (seccion === ``) {
    par.formulario_parametrizacion.reset();
    par.div_campos_parametrizacion.innerHTML = ``;
    return;
  }

  if (registro_id > 0) {
    await editar_registro_parametrizacion(seccion, registro_id);
    return;
  }

  await abrir_formulario_parametrizacion(seccion);
}

/**
 * Función encargada de renderizar el panel del formulario
 *
 * @param      string   seccion      Sección del formulario
 * @param      integer  registro_id  Identificador del registro
 * @param      object   datos        Datos a renderizar
 */
async function renderizar_panel_parametrizacion(seccion, registro_id, datos) {
  const configuracion = obtener_configuracion_seccion_parametrizacion(seccion);

  par.seccion_actual = seccion;
  par.form_seccion.value = seccion;
  par.form_registro_id.value = registro_id > 0 ? String(registro_id) : ``;
  par.texto_panel_seccion.textContent = configuracion.etiqueta;
  par.titulo_panel_parametrizacion.textContent = registro_id > 0
    ? `Editar registro`
    : `Nuevo registro`;
  par.div_campos_parametrizacion.innerHTML = template_formulario_parametrizacion(
    configuracion,
    datos,
    par.catalogos
  );
  par.panel_formulario_parametrizacion.classList.remove(`dx_oculto`);
  par.panel_formulario_parametrizacion.setAttribute(`aria-hidden`, `false`);
}


/**
 * Función encargada de borrar un registro del módulo
 *
 * @param      string   seccion      Sección del registro
 * @param      integer  registro_id  Identificador del registro
 */
async function borrar_registro_parametrizacion(seccion, registro_id) {
  const confirmacion = await confirmar_accion_parametrizacion(
    `Confirmar eliminación`,
    `¿Desea eliminar el registro seleccionado en ${seccion}?`
  );

  if (confirmacion !== true) {
    return;
  }

  let peticion = await parametrizacion_borrar_registro_peticiones(par.token, seccion, registro_id);

  if (peticion.estado !== true) {
    mostrar_alerta_parametrizacion(`error`, peticion.mensaje);
    return;
  }

  mostrar_alerta_parametrizacion(`success`, peticion.mensaje);

  if (Number(par.form_registro_id.value || 0) === registro_id && (par.seccion_actual || par.form_seccion.value) === seccion) {
    await cerrar_formulario_parametrizacion();
  }

  await listar_parametrizacion();
}
/**
 * Función encargada de filtrar un listado por texto
 *
 * @param      array   listado         Registros del listado
 * @param      string  texto_busqueda  Texto de búsqueda
 *
 * @return     array  Registros filtrados
 */
function filtrar_parametrizacion_listado(listado, texto_busqueda) {
  if (texto_busqueda === ``) {
    return listado;
  }

  return listado.filter(function(registro) {
    const valor = Object.values(registro).join(` `).toLowerCase();

    return valor.includes(texto_busqueda);
  });
}
/**
 * Función encargada de obtener el resumen general del módulo
 *
 * @return     array  Resumen visual
 */
function obtener_resumen_parametrizacion() {
  return [
    {titulo : `Temas`, cantidad : par.listado_temas.length},
    {titulo : `Branding`, cantidad : par.listado_branding.length},
    {titulo : `Parámetros`, cantidad : par.listado_parametros.length},
    {titulo : `Módulos`, cantidad : par.listado_modulos.length},
    {titulo : `Integraciones`, cantidad : par.listado_integraciones.length},
    {titulo : `Menús`, cantidad : par.listado_menus.length},
    {titulo : `Roles`, cantidad : par.listado_roles.length},
    {titulo : `Usuarios`, cantidad : par.listado_usuarios.length},
    {titulo : `Asignaciones`, cantidad : par.listado_roles_permisos.length}
  ];
}
/**
 * Función encargada de mostrar mensajes del módulo
 *
 * @param      string  tipo     Tipo de alerta
 * @param      string  mensaje  Mensaje a renderizar
 */
function mostrar_alerta_parametrizacion(tipo, mensaje) {
  limpiar_alerta_parametrizacion();
  par.div_mensaje_parametrizacion.innerHTML = template_alerta_parametrizacion(tipo, mensaje);
  par.temporizador_alerta = setTimeout(function() {
    limpiar_alerta_parametrizacion();
  }, 5000);
}
/**
 * Función encargada de limpiar la alerta visible
 */
function limpiar_alerta_parametrizacion() {
  if (par.temporizador_alerta) {
    clearTimeout(par.temporizador_alerta);
    par.temporizador_alerta = null;
  }

  par.div_mensaje_parametrizacion.innerHTML = ``;
}
/**
 * Función encargada de solicitar confirmación visual
 *
 * @param      string  titulo   Título de la confirmación
 * @param      string  mensaje  Texto principal
 *
 * @return     boolean  Resultado de la confirmación
 */
async function confirmar_accion_parametrizacion(titulo, mensaje) {
  par.titulo_confirmacion_parametrizacion.textContent = titulo;
  par.texto_confirmacion_parametrizacion.textContent = mensaje;
  par.modal_confirmacion_parametrizacion.classList.remove(`dx_oculto`);
  par.modal_confirmacion_parametrizacion.setAttribute(`aria-hidden`, `false`);

  return new Promise(function(resolve) {
    par.confirmacion_resolve = resolve;
  });
}
/**
 * Función encargada de resolver la confirmación abierta
 *
 * @param      boolean  respuesta  Respuesta del usuario
 */
function resolver_confirmacion_parametrizacion(respuesta) {
  par.modal_confirmacion_parametrizacion.classList.add(`dx_oculto`);
  par.modal_confirmacion_parametrizacion.setAttribute(`aria-hidden`, `true`);

  if (typeof par.confirmacion_resolve === `function`) {
    par.confirmacion_resolve(respuesta);
  }

  par.confirmacion_resolve = null;
}
/**
 * Función encargada de abrir o cerrar el menú móvil
 *
 * @param      boolean  estado  Estado del menú
 */
function alternar_menu_parametrizacion(estado) {
  if (window.innerWidth > 980) {
    return;
  }

  if (estado === true) {
    par.sidebar_parametrizacion.classList.add(`dx_sidebar_abierto`);
    par.sidebar_backdrop.classList.remove(`dx_oculto`);
    return;
  }

  par.sidebar_parametrizacion.classList.remove(`dx_sidebar_abierto`);
  par.sidebar_backdrop.classList.add(`dx_oculto`);
}
/**
 * Función encargada de obtener la configuración de una sección
 *
 * @param      string  seccion  Sección a consultar
 *
 * @return     object  Configuración de la sección
 */
function obtener_configuracion_seccion_parametrizacion(seccion) {
  const configuraciones = {
    temas : {
      seccion     : `temas`,
      etiqueta    : `Visual`,
      titulo      : `Temas`,
      descripcion : `CRUD base para la configuración visual principal.`,
      pk          : `tema_id`,
      columnas    : [
        {campo : `codigo`, titulo : `Código`},
        {campo : `nombre`, titulo : `Nombre`},
        {campo : `version`, titulo : `Versión`},
        {campo : `orden`, titulo : `Orden`},
        {campo : `estado`, titulo : `Estado`, tipo : `estado`}
      ],
      campos      : [
        {nombre : `codigo`, etiqueta : `Código`, tipo : `input`, tipo_html : `text`, placeholder : `Código interno del tema`, requerido : true},
        {nombre : `nombre`, etiqueta : `Nombre`, tipo : `input`, tipo_html : `text`, placeholder : `Nombre visible del tema`, requerido : true},
        {nombre : `descripcion`, etiqueta : `Descripción`, tipo : `textarea`, placeholder : `Descripción corta del tema`, requerido : false},
        {nombre : `version`, etiqueta : `Versión`, tipo : `input`, tipo_html : `text`, placeholder : `1.0.0`, requerido : true},
        {nombre : `sw_predeterminado`, etiqueta : `Predeterminado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `orden`, etiqueta : `Orden`, tipo : `input`, tipo_html : `number`, placeholder : `Orden de visualización`, requerido : true},
        {nombre : `estado`, etiqueta : `Estado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true}
      ]
    },
    branding : {
      seccion     : `branding`,
      etiqueta    : `Identidad`,
      titulo      : `Branding`,
      descripcion : `CRUD base para la identidad visual principal.`,
      pk          : `branding_id`,
      columnas    : [
        {campo : `codigo`, titulo : `Código`},
        {campo : `nombre_comercial`, titulo : `Nombre comercial`},
        {campo : `correo_contacto`, titulo : `Correo`},
        {campo : `telefono_contacto`, titulo : `Teléfono`},
        {campo : `estado`, titulo : `Estado`, tipo : `estado`}
      ],
      campos      : [
        {nombre : `codigo`, etiqueta : `Código`, tipo : `input`, tipo_html : `text`, placeholder : `Código del branding`, requerido : true},
        {nombre : `nombre_comercial`, etiqueta : `Nombre comercial`, tipo : `input`, tipo_html : `text`, placeholder : `Nombre visible`, requerido : true},
        {nombre : `razon_social`, etiqueta : `Razón social`, tipo : `input`, tipo_html : `text`, placeholder : `Razón social`, requerido : false},
        {nombre : `nit`, etiqueta : `NIT`, tipo : `input`, tipo_html : `text`, placeholder : `Identificación tributaria`, requerido : false},
        {nombre : `correo_contacto`, etiqueta : `Correo contacto`, tipo : `input`, tipo_html : `email`, placeholder : `correo@dominio.com`, requerido : false},
        {nombre : `telefono_contacto`, etiqueta : `Teléfono contacto`, tipo : `input`, tipo_html : `text`, placeholder : `Número de contacto`, requerido : false},
        {nombre : `direccion`, etiqueta : `Dirección`, tipo : `textarea`, placeholder : `Dirección principal`, requerido : false},
        {nombre : `mensaje_bienvenida`, etiqueta : `Mensaje bienvenida`, tipo : `textarea`, placeholder : `Texto de bienvenida`, requerido : false},
        {nombre : `texto_footer`, etiqueta : `Texto footer`, tipo : `textarea`, placeholder : `Texto visible al final`, requerido : false},
        {nombre : `estado`, etiqueta : `Estado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true}
      ]
    },
    parametros : {
      seccion     : `parametros`,
      etiqueta    : `Funcional`,
      titulo      : `Parámetros`,
      descripcion : `CRUD base para parámetros y comportamiento configurable.`,
      pk          : `parametro_id`,
      columnas    : [
        {campo : `codigo`, titulo : `Código`},
        {campo : `nombre`, titulo : `Nombre`},
        {campo : `parametro_grupo`, titulo : `Grupo`},
        {campo : `tipo_dato`, titulo : `Tipo dato`},
        {campo : `estado`, titulo : `Estado`, tipo : `estado`}
      ],
      campos      : [
        {nombre : `parametro_grupo_id`, etiqueta : `Grupo`, tipo : `select`, catalogo : `parametro_grupos`, valor : `parametro_grupo_id`, texto : `nombre`, requerido : true},
        {nombre : `codigo`, etiqueta : `Código`, tipo : `input`, tipo_html : `text`, placeholder : `Código del parámetro`, requerido : true},
        {nombre : `nombre`, etiqueta : `Nombre`, tipo : `input`, tipo_html : `text`, placeholder : `Nombre visible del parámetro`, requerido : true},
        {nombre : `descripcion`, etiqueta : `Descripción`, tipo : `textarea`, placeholder : `Descripción funcional`, requerido : false},
        {nombre : `tipo_dato`, etiqueta : `Tipo dato`, tipo : `input`, tipo_html : `text`, placeholder : `string, integer, boolean`, requerido : true},
        {nombre : `valor_defecto`, etiqueta : `Valor defecto`, tipo : `input`, tipo_html : `text`, placeholder : `Valor por defecto`, requerido : false},
        {nombre : `sw_requerido`, etiqueta : `Requerido`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `sw_publico`, etiqueta : `Público`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `orden`, etiqueta : `Orden`, tipo : `input`, tipo_html : `number`, placeholder : `Orden de salida`, requerido : true},
        {nombre : `estado`, etiqueta : `Estado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true}
      ]
    },
    modulos : {
      seccion     : `modulos`,
      etiqueta    : `Navegación`,
      titulo      : `Módulos`,
      descripcion : `CRUD base para módulos habilitados del proyecto.`,
      pk          : `modulo_id`,
      columnas    : [
        {campo : `codigo`, titulo : `Código`},
        {campo : `nombre`, titulo : `Nombre`},
        {campo : `ruta`, titulo : `Ruta`},
        {campo : `orden`, titulo : `Orden`},
        {campo : `estado`, titulo : `Estado`, tipo : `estado`}
      ],
      campos      : [
        {nombre : `codigo`, etiqueta : `Código`, tipo : `input`, tipo_html : `text`, placeholder : `Código del módulo`, requerido : true},
        {nombre : `nombre`, etiqueta : `Nombre`, tipo : `input`, tipo_html : `text`, placeholder : `Nombre visible`, requerido : true},
        {nombre : `descripcion`, etiqueta : `Descripción`, tipo : `textarea`, placeholder : `Descripción corta`, requerido : false},
        {nombre : `ruta`, etiqueta : `Ruta`, tipo : `input`, tipo_html : `text`, placeholder : `Ruta principal del módulo`, requerido : false},
        {nombre : `icono`, etiqueta : `Icono`, tipo : `input`, tipo_html : `text`, placeholder : `Nombre del icono`, requerido : false},
        {nombre : `orden`, etiqueta : `Orden`, tipo : `input`, tipo_html : `number`, placeholder : `Orden de salida`, requerido : true},
        {nombre : `sw_visible_menu`, etiqueta : `Visible menú`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `sw_requiere_login`, etiqueta : `Requiere login`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `estado`, etiqueta : `Estado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true}
      ]
    },
    integraciones : {
      seccion     : `integraciones`,
      etiqueta    : `Servicios`,
      titulo      : `Integraciones`,
      descripcion : `CRUD base para conexiones e integraciones externas.`,
      pk          : `integracion_id`,
      columnas    : [
        {campo : `codigo`, titulo : `Código`},
        {campo : `nombre`, titulo : `Nombre`},
        {campo : `tipo_autenticacion`, titulo : `Autenticación`},
        {campo : `base_url`, titulo : `Base url`},
        {campo : `estado`, titulo : `Estado`, tipo : `estado`}
      ],
      campos      : [
        {nombre : `codigo`, etiqueta : `Código`, tipo : `input`, tipo_html : `text`, placeholder : `Código de integración`, requerido : true},
        {nombre : `nombre`, etiqueta : `Nombre`, tipo : `input`, tipo_html : `text`, placeholder : `Nombre visible`, requerido : true},
        {nombre : `descripcion`, etiqueta : `Descripción`, tipo : `textarea`, placeholder : `Descripción de la integración`, requerido : false},
        {nombre : `tipo_autenticacion`, etiqueta : `Tipo autenticación`, tipo : `input`, tipo_html : `text`, placeholder : `Bearer, Basic, API Key`, requerido : false},
        {nombre : `base_url`, etiqueta : `Base url`, tipo : `input`, tipo_html : `text`, placeholder : `https://dominio.com`, requerido : false},
        {nombre : `sw_activa`, etiqueta : `Activa`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `estado`, etiqueta : `Estado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true}
      ]
    },
    menus : {
      seccion     : `menus`,
      etiqueta    : `Acceso`,
      titulo      : `Menús`,
      descripcion : `CRUD base para la navegación visible del módulo administrativo.`,
      pk          : `menu_id`,
      columnas    : [
        {campo : `codigo`, titulo : `Código`},
        {campo : `nombre`, titulo : `Nombre`},
        {campo : `modulo`, titulo : `Módulo`},
        {campo : `ruta`, titulo : `Ruta`},
        {campo : `estado`, titulo : `Estado`, tipo : `estado`}
      ],
      campos      : [
        {nombre : `modulo_id`, etiqueta : `Módulo`, tipo : `select`, catalogo : `modulos`, valor : `modulo_id`, texto : `nombre`, requerido : true},
        {nombre : `menu_padre_id`, etiqueta : `Menú padre`, tipo : `select`, catalogo : `menus_padre`, valor : `menu_id`, texto : `nombre`, requerido : false},
        {nombre : `codigo`, etiqueta : `Código`, tipo : `input`, tipo_html : `text`, placeholder : `Código del menú`, requerido : true},
        {nombre : `nombre`, etiqueta : `Nombre`, tipo : `input`, tipo_html : `text`, placeholder : `Nombre visible`, requerido : true},
        {nombre : `ruta`, etiqueta : `Ruta`, tipo : `input`, tipo_html : `text`, placeholder : `Ruta del menú`, requerido : false},
        {nombre : `icono`, etiqueta : `Icono`, tipo : `input`, tipo_html : `text`, placeholder : `Nombre del icono`, requerido : false},
        {nombre : `orden`, etiqueta : `Orden`, tipo : `input`, tipo_html : `number`, placeholder : `Orden del menú`, requerido : true},
        {nombre : `nivel`, etiqueta : `Nivel`, tipo : `input`, tipo_html : `number`, placeholder : `Nivel jerárquico`, requerido : true},
        {nombre : `sw_visible`, etiqueta : `Visible`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `sw_publico`, etiqueta : `Público`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `estado`, etiqueta : `Estado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true}
      ]
    },
    roles : {
      seccion     : `roles`,
      etiqueta    : `Seguridad`,
      titulo      : `Roles`,
      descripcion : `CRUD base para roles del panel administrativo.`,
      pk          : `rol_id`,
      columnas    : [
        {campo : `codigo`, titulo : `Código`},
        {campo : `nombre`, titulo : `Nombre`},
        {campo : `descripcion`, titulo : `Descripción`},
        {campo : `estado`, titulo : `Estado`, tipo : `estado`}
      ],
      campos      : [
        {nombre : `codigo`, etiqueta : `Código`, tipo : `input`, tipo_html : `text`, placeholder : `Código del rol`, requerido : true},
        {nombre : `nombre`, etiqueta : `Nombre`, tipo : `input`, tipo_html : `text`, placeholder : `Nombre visible del rol`, requerido : true},
        {nombre : `descripcion`, etiqueta : `Descripción`, tipo : `textarea`, placeholder : `Descripción del rol`, requerido : false},
        {nombre : `sw_predeterminado`, etiqueta : `Predeterminado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `estado`, etiqueta : `Estado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true}
      ]
    },
    usuarios : {
      seccion     : `usuarios`,
      etiqueta    : `Seguridad`,
      titulo      : `Usuarios`,
      descripcion : `CRUD base para administración de usuarios del acceso inicial.`,
      pk          : `usuario_id`,
      columnas    : [
        {campo : `nombres`, titulo : `Nombres`},
        {campo : `login`, titulo : `Login`},
        {campo : `correo`, titulo : `Correo`},
        {campo : `rol`, titulo : `Rol`},
        {campo : `ultimo_ingreso`, titulo : `Último ingreso`, tipo : `fecha`},
        {campo : `estado`, titulo : `Estado`, tipo : `estado`}
      ],
      campos      : [
        {nombre : `nombres`, etiqueta : `Nombres`, tipo : `input`, tipo_html : `text`, placeholder : `Nombres del usuario`, requerido : true},
        {nombre : `apellidos`, etiqueta : `Apellidos`, tipo : `input`, tipo_html : `text`, placeholder : `Apellidos del usuario`, requerido : true},
        {nombre : `login`, etiqueta : `Login`, tipo : `input`, tipo_html : `text`, placeholder : `Usuario de ingreso`, requerido : true},
        {nombre : `correo`, etiqueta : `Correo`, tipo : `input`, tipo_html : `email`, placeholder : `correo@dominio.com`, requerido : true},
        {nombre : `clave`, etiqueta : `Clave`, tipo : `input`, tipo_html : `password`, placeholder : `Clave del usuario`, ayuda : `En edición puede dejarla vacía para conservar la actual.`, requerido : false},
        {nombre : `rol_id`, etiqueta : `Rol`, tipo : `select`, catalogo : `roles`, valor : `rol_id`, texto : `nombre`, ayuda : `Obligatorio si no es superusuario.`, requerido : false},
        {nombre : `sw_superusuario`, etiqueta : `Superusuario`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true},
        {nombre : `estado`, etiqueta : `Estado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true}
      ]
    },
    roles_permisos : {
      seccion     : `roles_permisos`,
      etiqueta    : `Seguridad`,
      titulo      : `Asignación permisos`,
      descripcion : `CRUD base para asignar permisos a roles del acceso administrativo y comercial.`,
      pk          : `rol_permiso_id`,
      columnas    : [
        {campo : `rol`, titulo : `Rol`},
        {campo : `permiso`, titulo : `Permiso`},
        {campo : `modulo`, titulo : `Módulo`},
        {campo : `tipo_permiso`, titulo : `Tipo`},
        {campo : `estado`, titulo : `Estado`, tipo : `estado`}
      ],
      campos      : [
        {nombre : `rol_id`, etiqueta : `Rol`, tipo : `select`, catalogo : `roles`, valor : `rol_id`, texto : `nombre`, requerido : true},
        {nombre : `permiso_id`, etiqueta : `Permiso`, tipo : `select`, catalogo : `permisos`, valor : `permiso_id`, texto : `nombre`, requerido : true},
        {nombre : `estado`, etiqueta : `Estado`, tipo : `select`, catalogo : `estados_binarios`, valor : `valor`, texto : `texto`, requerido : true}
      ]
    }
  };

  return configuraciones[seccion];
}
