async function listar_carrito_tienda_store_peticiones(token, controlador) {
  const url = `${controlador}?ajax=1&token=${encodeURIComponent(token)}`;
  const petición = await fetch(url, {
    method: `GET`,
    headers: {
      'Accept': `application/json`
    }
  });

  return petición.json();
}

async function guardar_carrito_tienda_store_peticiones(token, controlador, params) {
  const formulario = new FormData();
  formulario.append(`ajax`, `1`);
  formulario.append(`token`, token);

  Object.keys(params).forEach(function(clave) {
    formulario.append(clave, params[clave]);
  });

  const petición = await fetch(controlador, {
    method: `POST`,
    body: formulario,
    headers: {
      'Accept': `application/json`
    }
  });

  return petición.json();
}
