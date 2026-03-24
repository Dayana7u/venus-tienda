async function tienda_storefront_peticion_post(url, formulario) {
  const petición = await fetch(url, {
    method: `POST`,
    body: formulario,
    headers: {
      'X-Requested-With': `XMLHttpRequest`
    },
    credentials: `same-origin`
  });

  return petición.json();
}

async function tienda_storefront_consultar_resumen_peticiones() {
  const formulario = new FormData();
  formulario.append(`accion`, `resumen`);
  return tienda_storefront_peticion_post(`/carrito/`, formulario);
}

async function tienda_storefront_agregar_producto_peticiones(formulario) {
  formulario.append(`ajax`, `1`);
  return tienda_storefront_peticion_post(`/carrito/`, formulario);
}

async function tienda_storefront_actualizar_producto_peticiones(slug, cantidad) {
  const formulario = new FormData();
  formulario.append(`accion`, `actualizar`);
  formulario.append(`slug`, slug);
  formulario.append(`cantidad`, cantidad);
  formulario.append(`ajax`, `1`);
  return tienda_storefront_peticion_post(`/carrito/`, formulario);
}

async function tienda_storefront_eliminar_producto_peticiones(slug) {
  const formulario = new FormData();
  formulario.append(`accion`, `eliminar`);
  formulario.append(`slug`, slug);
  formulario.append(`ajax`, `1`);
  return tienda_storefront_peticion_post(`/carrito/`, formulario);
}
