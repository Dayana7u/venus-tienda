let seg = {};
// Datos generales
  seg.token                     = document.getElementById(`token`).value;
  seg.usuario_actual_id         = Number(document.getElementById(`usuario_actual_id`).value || 0);
  seg.usuario_sesion_actual     = Number(document.getElementById(`usuario_sesion_actual`).value || 0);
  seg.estado_envio              = false;
  seg.temporizador_alerta       = null;
  seg.resumen                   = {};
  seg.listado_sesiones_activas  = [];
  seg.listado_historial         = [];
  seg.listado_usuarios          = [];
// Campos
  seg.buscar_seguridad              = document.getElementById(`buscar_seguridad`);
  seg.formulario_clave_seguridad    = document.getElementById(`formulario_clave_seguridad`);
  seg.seguridad_usuario_id          = document.getElementById(`seguridad_usuario_id`);
  seg.seguridad_clave_nueva         = document.getElementById(`seguridad_clave_nueva`);
  seg.seguridad_clave_confirmacion  = document.getElementById(`seguridad_clave_confirmacion`);
// Botones
  seg.btn_menu_seguridad            = document.getElementById(`btn_menu_seguridad`);
  seg.btn_recargar_seguridad        = document.getElementById(`btn_recargar_seguridad`);
  seg.btn_cerrar_otras_sesiones     = document.getElementById(`btn_cerrar_otras_sesiones`);
  seg.btn_guardar_clave_seguridad   = document.getElementById(`btn_guardar_clave_seguridad`);
  seg.btn_limpiar_clave_seguridad   = document.getElementById(`btn_limpiar_clave_seguridad`);
// Divs
  seg.div_mensaje_seguridad         = document.getElementById(`div_mensaje_seguridad`);
  seg.div_resumen_seguridad         = document.getElementById(`div_resumen_seguridad`);
  seg.div_sesiones_activas_seguridad = document.getElementById(`div_sesiones_activas_seguridad`);
  seg.div_historial_seguridad       = document.getElementById(`div_historial_seguridad`);
  seg.sidebar_seguridad             = document.getElementById(`dx_sidebar_seguridad`);
  seg.sidebar_backdrop_seguridad    = document.getElementById(`dx_sidebar_backdrop_seguridad`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_seguridad();
});
/**
 * Función encargada de inicializar el módulo seguridad.
 */
async function inicializar_seguridad() {
  eventos_seguridad();
  await seguridad_inicializar_peticiones(seg.token);
  await listar_panel_seguridad();
}
/**
 * Función encargada de registrar los eventos principales del módulo.
 */
function eventos_seguridad() {
  seg.btn_menu_seguridad.addEventListener(`click`, function() {
    alternar_menu_seguridad(true);
  });

  seg.sidebar_backdrop_seguridad.addEventListener(`click`, function() {
    alternar_menu_seguridad(false);
  });

  seg.btn_recargar_seguridad.addEventListener(`click`, async function() {
    await listar_panel_seguridad();
  });

  seg.btn_cerrar_otras_sesiones.addEventListener(`click`, async function() {
    await cerrar_otras_sesiones_seguridad();
  });

  seg.buscar_seguridad.addEventListener(`input`, function() {
    renderizar_listados_seguridad();
  });

  seg.formulario_clave_seguridad.addEventListener(`submit`, async function(event) {
    event.preventDefault();
    await guardar_clave_seguridad();
  });

  seg.btn_limpiar_clave_seguridad.addEventListener(`click`, function() {
    seg.formulario_clave_seguridad.reset();
    if (seg.listado_usuarios.length > 0) {
      seg.seguridad_usuario_id.innerHTML = template_opciones_usuarios_seguridad(seg.listado_usuarios, 0);
    }
  });

  seg.div_mensaje_seguridad.addEventListener(`click`, function(event) {
    if (event.target.matches(`[data-alerta-cerrar="true"]`)) {
      seg.div_mensaje_seguridad.innerHTML = ``;
    }
  });

  seg.div_sesiones_activas_seguridad.addEventListener(`click`, async function(event) {
    const boton = event.target.closest(`[data-accion="cerrar_sesion_seguridad"]`);

    if (!boton) {
      return;
    }

    await cerrar_sesion_seguridad(Number(boton.dataset.usuarioSesionId || 0));
  });

  document.querySelectorAll(`[data-menu-link="true"]`).forEach(function(enlace) {
    enlace.addEventListener(`click`, function() {
      if (window.innerWidth <= 960) {
        alternar_menu_seguridad(false);
      }
    });
  });
}
/**
 * Función encargada de consultar la información del módulo.
 */
async function listar_panel_seguridad() {
  const peticion = await seguridad_listar_panel_peticiones(seg.token);

  if (peticion.estado !== true) {
    mostrar_alerta_seguridad(`error`, peticion.mensaje);
    return;
  }

  seg.resumen                  = peticion.datos.resumen || {};
  seg.listado_sesiones_activas = peticion.datos.sesiones_activas || [];
  seg.listado_historial        = peticion.datos.historial || [];
  seg.listado_usuarios         = peticion.datos.usuarios || [];

  seg.div_resumen_seguridad.innerHTML = template_resumen_seguridad(seg.resumen);
  seg.seguridad_usuario_id.innerHTML  = template_opciones_usuarios_seguridad(seg.listado_usuarios, seg.usuario_actual_id || 0);

  renderizar_listados_seguridad();
}
/**
 * Función encargada de renderizar las tablas del módulo según el filtro actual.
 */
function renderizar_listados_seguridad() {
  const texto_busqueda = seg.buscar_seguridad.value.trim().toLowerCase();

  seg.div_sesiones_activas_seguridad.innerHTML = template_sesiones_activas_seguridad(
    filtrar_listado_seguridad(seg.listado_sesiones_activas, texto_busqueda)
  );
  seg.div_historial_seguridad.innerHTML = template_historial_seguridad(
    filtrar_listado_seguridad(seg.listado_historial, texto_busqueda)
  );
}
/**
 * Función encargada de filtrar un listado del módulo seguridad.
 *
 * @param      array   listado         Listado a filtrar.
 * @param      string  texto_busqueda  Texto de búsqueda.
 *
 * @return     array  Listado filtrado.
 */
function filtrar_listado_seguridad(listado, texto_busqueda) {
  if (texto_busqueda === ``) {
    return listado;
  }

  return listado.filter(function(item) {
    return JSON.stringify(item).toLowerCase().includes(texto_busqueda);
  });
}
/**
 * Función encargada de cerrar una sesión seleccionada.
 *
 * @param      integer  usuario_sesion_id  Identificador de la sesión.
 */
async function cerrar_sesion_seguridad(usuario_sesion_id) {
  if (usuario_sesion_id <= 0 || seg.estado_envio === true) {
    return;
  }

  seg.estado_envio = true;

  try {
    const peticion = await seguridad_cerrar_sesion_peticiones(seg.token, usuario_sesion_id);

    if (peticion.estado !== true) {
      mostrar_alerta_seguridad(`error`, peticion.mensaje);
      return;
    }

    mostrar_alerta_seguridad(`success`, peticion.mensaje);
    await listar_panel_seguridad();
  }
  finally {
    seg.estado_envio = false;
  }
}
/**
 * Función encargada de cerrar las demás sesiones del usuario actual.
 */
async function cerrar_otras_sesiones_seguridad() {
  if (seg.estado_envio === true) {
    return;
  }

  seg.estado_envio = true;
  seg.btn_cerrar_otras_sesiones.disabled = true;

  try {
    const peticion = await seguridad_cerrar_otras_sesiones_peticiones(seg.token);

    if (peticion.estado !== true) {
      mostrar_alerta_seguridad(`error`, peticion.mensaje);
      return;
    }

    mostrar_alerta_seguridad(`success`, peticion.mensaje);
    await listar_panel_seguridad();
  }
  finally {
    seg.estado_envio = false;
    seg.btn_cerrar_otras_sesiones.disabled = false;
  }
}
/**
 * Función encargada de actualizar la clave del usuario seleccionado.
 */
async function guardar_clave_seguridad() {
  if (seg.estado_envio === true) {
    return;
  }

  seg.estado_envio = true;
  seg.btn_guardar_clave_seguridad.disabled = true;

  try {
    const usuario_id         = Number(seg.seguridad_usuario_id.value || 0);
    const clave_nueva        = seg.seguridad_clave_nueva.value;
    const clave_confirmacion = seg.seguridad_clave_confirmacion.value;
    const peticion           = await seguridad_cambiar_clave_usuario_peticiones(
      seg.token,
      usuario_id,
      clave_nueva,
      clave_confirmacion
    );

    if (peticion.estado !== true) {
      mostrar_alerta_seguridad(`error`, peticion.mensaje);
      return;
    }

    mostrar_alerta_seguridad(`success`, peticion.mensaje);
    seg.formulario_clave_seguridad.reset();
    seg.seguridad_usuario_id.innerHTML = template_opciones_usuarios_seguridad(seg.listado_usuarios, 0);
    await listar_panel_seguridad();
  }
  finally {
    seg.estado_envio = false;
    seg.btn_guardar_clave_seguridad.disabled = false;
  }
}
/**
 * Función encargada de mostrar una alerta temporal del módulo.
 *
 * @param      string  tipo     Tipo de alerta.
 * @param      string  mensaje  Mensaje a mostrar.
 */
function mostrar_alerta_seguridad(tipo, mensaje) {
  seg.div_mensaje_seguridad.innerHTML = template_alerta_seguridad(tipo, mensaje);

  if (seg.temporizador_alerta) {
    clearTimeout(seg.temporizador_alerta);
  }

  seg.temporizador_alerta = setTimeout(function() {
    seg.div_mensaje_seguridad.innerHTML = ``;
  }, 5000);
}
/**
 * Función encargada de alternar el menú lateral del módulo.
 *
 * @param      bool  estado  Estado objetivo del menú.
 */
function alternar_menu_seguridad(estado) {
  if (window.innerWidth > 960) {
    return;
  }

  seg.sidebar_seguridad.classList.toggle(`dx_sidebar_abierto`, estado);
  seg.sidebar_backdrop_seguridad.classList.toggle(`dx_oculto`, !estado);
  document.body.classList.toggle(`dx_bloqueo_scroll`, estado);
}
