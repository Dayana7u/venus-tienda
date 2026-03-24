let tadm_log_pet = {};
// Datos generales
  tadm_log_pet.controlador = document.getElementById(`controlador_tienda_admin_login`).value;

async function tienda_admin_login_peticion_post(accion, parametros = {}) {
  const form_data = new FormData();

  form_data.append(`accion`, accion);

  Object.keys(parametros).forEach(function(key) {
    form_data.append(key, parametros[key]);
  });

  try {
    const peticion = await fetch(tadm_log_pet.controlador, {
      method : `POST`,
      body   : form_data
    });

    return await peticion.json();
  }
  catch (error) {
    return {
      estado  : false,
      mensaje : `No fue posible completar la petición del panel de tienda.`,
      datos   : [],
    };
  }
}

async function tienda_admin_login_inicializar_peticiones(token) {
  return await tienda_admin_login_peticion_post(`tienda_admin_login_inicializar`, {token});
}

async function tienda_admin_login_autenticar_peticiones(token, tienda_admin_login_usuario, tienda_admin_login_clave) {
  return await tienda_admin_login_peticion_post(`tienda_admin_login_autenticar`, {
    token,
    tienda_admin_login_usuario,
    tienda_admin_login_clave
  });
}

async function tienda_admin_login_validar_sesion_peticiones(token) {
  return await tienda_admin_login_peticion_post(`tienda_admin_login_validar_sesion`, {token});
}
