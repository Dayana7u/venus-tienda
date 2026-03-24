let tadm_pet = {};
// Datos generales
  tadm_pet.controlador = document.getElementById(`controlador_tienda_admin`).value;

async function tienda_admin_peticion_post(accion, parametros = {}) {
  const form_data = parametros instanceof FormData ? parametros : new FormData();

  if (!(parametros instanceof FormData)) {
    Object.keys(parametros).forEach(function(key) {
      form_data.append(key, parametros[key]);
    });
  }

  form_data.append(`accion`, accion);

  try {
    const peticion = await fetch(tadm_pet.controlador, {
      method : `POST`,
      body   : form_data
    });

    return await peticion.json();
  }
  catch (error) {
    return {
      estado  : false,
      mensaje : `No fue posible completar la petición del panel tienda.`,
      datos   : [],
    };
  }
}

async function tienda_admin_inicializar_peticiones(token) {
  return await tienda_admin_peticion_post(`tienda_admin_inicializar`, {token});
}

async function tienda_admin_listar_dashboard_peticiones(token) {
  return await tienda_admin_peticion_post(`tienda_admin_listar_dashboard`, {token});
}

async function tienda_admin_guardar_categoria_peticiones(formulario) {
  return await tienda_admin_peticion_post(`tienda_admin_guardar_categoria`, formulario);
}

async function tienda_admin_guardar_producto_peticiones(formulario) {
  return await tienda_admin_peticion_post(`tienda_admin_guardar_producto`, formulario);
}

async function tienda_admin_guardar_imagen_peticiones(formulario) {
  return await tienda_admin_peticion_post(`tienda_admin_guardar_imagen`, formulario);
}
