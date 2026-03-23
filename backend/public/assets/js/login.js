let log = {};
// Datos generales
  log.token               = document.getElementById(`token`).value;
  log.estado_envio        = false;
  log.temporizador_alerta = null;
// Formulario
  log.formulario_login    = document.getElementById(`formulario_login`);
  log.login_usuario       = document.getElementById(`login_usuario`);
  log.login_clave         = document.getElementById(`login_clave`);
// Botones
  log.btn_ingresar_login  = document.getElementById(`btn_ingresar_login`);
  log.btn_limpiar_login   = document.getElementById(`btn_limpiar_login`);
  log.btn_ver_clave_login = document.getElementById(`btn_ver_clave_login`);
// Divs
  log.div_mensaje_login   = document.getElementById(`div_mensaje_login`);
  log.div_resumen_login   = document.getElementById(`div_resumen_login`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_login();
});
/**
 * Función encargada de inicializar el módulo login
 */
async function inicializar_login() {
  await login_inicializar_peticiones(log.token);
  await validar_sesion_login();
  eventos_login();
}
/**
 * Función encargada de registrar los eventos del módulo login
 */
function eventos_login() {
  log.formulario_login.addEventListener(`submit`, async function(event) {
    event.preventDefault();
    await gestionar_envio_login();
  });

  log.btn_limpiar_login.addEventListener(`click`, async function() {
    await limpiar_formulario_login();
  });

  log.btn_ver_clave_login.addEventListener(`click`, function() {
    alternar_visualizacion_clave_login();
  });

  log.div_mensaje_login.addEventListener(`click`, function(event) {
    if (event.target.matches(`[data-alerta-cerrar="true"]`)) {
      limpiar_alerta_login();
    }
  });
}
/**
 * Función encargada de gestionar el envío del formulario login
 */
async function gestionar_envio_login() {
  if (log.estado_envio === true) {
    return;
  }

  const login_usuario = log.login_usuario.value.trim();
  const login_clave   = log.login_clave.value.trim();

  if (login_usuario === `` || login_clave === ``) {
    mostrar_alerta_login(`error`, `Debe ingresar usuario y clave.`);
    return;
  }

  await asignar_estado_envio_login(true);

  try {
    let peticion = await login_autenticar_peticiones(
      log.token,
      login_usuario,
      login_clave
    );

    if (peticion.estado !== true) {
      mostrar_alerta_login(`error`, peticion.mensaje);
      log.div_resumen_login.innerHTML = ``;
      return;
    }

    mostrar_alerta_login(`success`, peticion.mensaje);
    log.div_resumen_login.innerHTML = template_login_resumen_usuario(peticion.datos);

    if (peticion.datos.redireccion) {
      setTimeout(function() {
        window.location.href = peticion.datos.redireccion;
      }, 600);
    }
  }
  finally {
    await asignar_estado_envio_login(false);
  }
}
/**
 * Función encargada de validar si existe una sesión activa
 */
async function validar_sesion_login() {
  let peticion = await login_validar_sesion_peticiones(log.token);

  if (peticion.estado !== true) {
    return;
  }

  log.div_resumen_login.innerHTML = template_login_resumen_usuario(peticion.datos);
  mostrar_alerta_login(`info`, peticion.mensaje);
}
/**
 * Función encargada de limpiar el formulario login
 */
async function limpiar_formulario_login() {
  log.formulario_login.reset();
  log.login_clave.type = `password`;
  log.btn_ver_clave_login.textContent = `Ver`;
  limpiar_alerta_login();
  log.div_resumen_login.innerHTML = ``;
  log.login_usuario.focus();
}
/**
 * Función encargada de actualizar el estado del envío del formulario
 *
 * @param      boolean  estado  Estado actual del envío
 */
async function asignar_estado_envio_login(estado) {
  log.estado_envio                = estado;
  log.btn_ingresar_login.disabled = estado;
  log.btn_limpiar_login.disabled  = estado;
  log.btn_ver_clave_login.disabled = estado;
}
/**
 * Función encargada de alternar la visualización de la clave
 */
function alternar_visualizacion_clave_login() {
  const es_password = log.login_clave.type === `password`;

  log.login_clave.type = es_password ? `text` : `password`;
  log.btn_ver_clave_login.textContent = es_password ? `Ocultar` : `Ver`;
}
/**
 * Función encargada de mostrar mensajes en el módulo login
 *
 * @param      string  tipo     Tipo de alerta
 * @param      string  mensaje  Mensaje a renderizar
 */
function mostrar_alerta_login(tipo, mensaje) {
  limpiar_alerta_login();
  log.div_mensaje_login.innerHTML = template_login_alerta(tipo, mensaje);
  log.temporizador_alerta = setTimeout(function() {
    limpiar_alerta_login();
  }, 5000);
}
/**
 * Función encargada de limpiar la alerta visible
 */
function limpiar_alerta_login() {
  if (log.temporizador_alerta) {
    clearTimeout(log.temporizador_alerta);
    log.temporizador_alerta = null;
  }

  log.div_mensaje_login.innerHTML = ``;
}
