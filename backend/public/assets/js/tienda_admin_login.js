let tadm_log = {};
// Datos generales
  tadm_log.token               = document.getElementById(`token`).value;
  tadm_log.estado_envio        = false;
  tadm_log.temporizador_alerta = null;
// Formulario
  tadm_log.formulario          = document.getElementById(`formulario_tienda_admin_login`);
  tadm_log.usuario             = document.getElementById(`tienda_admin_login_usuario`);
  tadm_log.clave               = document.getElementById(`tienda_admin_login_clave`);
// Botones
  tadm_log.btn_ingresar        = document.getElementById(`btn_ingresar_tienda_admin_login`);
  tadm_log.btn_limpiar         = document.getElementById(`btn_limpiar_tienda_admin_login`);
  tadm_log.btn_ver_clave       = document.getElementById(`btn_ver_clave_tienda_admin_login`);
// Divs
  tadm_log.div_mensaje         = document.getElementById(`div_mensaje_tienda_admin_login`);
  tadm_log.div_resumen         = document.getElementById(`div_resumen_tienda_admin_login`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_tienda_admin_login();
});

async function inicializar_tienda_admin_login() {
  await tienda_admin_login_inicializar_peticiones(tadm_log.token);
  eventos_tienda_admin_login();
  await validar_sesion_tienda_admin_login();
}

function eventos_tienda_admin_login() {
  tadm_log.formulario.addEventListener(`submit`, async function(event) {
    event.preventDefault();
    await gestionar_envio_tienda_admin_login();
  });

  tadm_log.btn_limpiar.addEventListener(`click`, function() {
    limpiar_formulario_tienda_admin_login();
  });

  tadm_log.btn_ver_clave.addEventListener(`click`, function() {
    alternar_visualizacion_clave_tienda_admin_login();
  });

  tadm_log.div_mensaje.addEventListener(`click`, function(event) {
    if (event.target.matches(`[data-alerta-cerrar="true"]`)) {
      limpiar_alerta_tienda_admin_login();
    }
  });
}

async function gestionar_envio_tienda_admin_login() {
  if (tadm_log.estado_envio === true) {
    return;
  }

  const tienda_admin_login_usuario = tadm_log.usuario.value.trim();
  const tienda_admin_login_clave   = tadm_log.clave.value.trim();

  if (tienda_admin_login_usuario === `` || tienda_admin_login_clave === ``) {
    mostrar_alerta_tienda_admin_login(`error`, `Debe ingresar usuario y clave.`);
    return;
  }

  await asignar_estado_envio_tienda_admin_login(true);

  try {
    let peticion = await tienda_admin_login_autenticar_peticiones(
      tadm_log.token,
      tienda_admin_login_usuario,
      tienda_admin_login_clave
    );

    if (peticion.estado !== true) {
      mostrar_alerta_tienda_admin_login(`error`, peticion.mensaje);
      tadm_log.div_resumen.innerHTML = ``;
      return;
    }

    mostrar_alerta_tienda_admin_login(`success`, peticion.mensaje);
    tadm_log.div_resumen.innerHTML = template_tienda_admin_login_resumen(peticion.datos);

    if (peticion.datos.redireccion) {
      setTimeout(function() {
        window.location.href = peticion.datos.redireccion;
      }, 500);
    }
  }
  finally {
    await asignar_estado_envio_tienda_admin_login(false);
  }
}

async function validar_sesion_tienda_admin_login() {
  let peticion = await tienda_admin_login_validar_sesion_peticiones(tadm_log.token);

  if (peticion.estado !== true) {
    return;
  }

  tadm_log.div_resumen.innerHTML = template_tienda_admin_login_resumen(peticion.datos);
  mostrar_alerta_tienda_admin_login(`info`, peticion.mensaje);
}

function limpiar_formulario_tienda_admin_login() {
  tadm_log.formulario.reset();
  tadm_log.clave.type = `password`;
  tadm_log.btn_ver_clave.textContent = `Ver`;
  limpiar_alerta_tienda_admin_login();
  tadm_log.div_resumen.innerHTML = ``;
  tadm_log.usuario.focus();
}

async function asignar_estado_envio_tienda_admin_login(estado) {
  tadm_log.estado_envio          = estado;
  tadm_log.btn_ingresar.disabled = estado;
  tadm_log.btn_limpiar.disabled  = estado;
  tadm_log.btn_ver_clave.disabled = estado;
}

function alternar_visualizacion_clave_tienda_admin_login() {
  const es_password = tadm_log.clave.type === `password`;

  tadm_log.clave.type = es_password ? `text` : `password`;
  tadm_log.btn_ver_clave.textContent = es_password ? `Ocultar` : `Ver`;
}

function mostrar_alerta_tienda_admin_login(tipo, mensaje) {
  limpiar_alerta_tienda_admin_login();
  tadm_log.div_mensaje.innerHTML = template_tienda_admin_login_alerta(tipo, mensaje);
  tadm_log.temporizador_alerta = setTimeout(function() {
    limpiar_alerta_tienda_admin_login();
  }, 5000);
}

function limpiar_alerta_tienda_admin_login() {
  if (tadm_log.temporizador_alerta) {
    clearTimeout(tadm_log.temporizador_alerta);
    tadm_log.temporizador_alerta = null;
  }

  tadm_log.div_mensaje.innerHTML = ``;
}
