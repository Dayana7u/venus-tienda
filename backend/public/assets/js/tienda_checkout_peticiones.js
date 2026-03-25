async function guardar_checkout_tienda_peticiones(token, controlador, formulario) {
  formulario.append(`ajax`, `1`);
  formulario.append(`token`, token);

  const petición = await fetch(controlador, {
    method: `POST`,
    body: formulario,
    headers: {
      'Accept': `application/json`
    }
  });

  return petición.json();
}

async function guardar_checkout_pago_tienda_peticiones(token, controlador, formulario) {
  formulario.append(`ajax`, `1`);
  formulario.append(`token`, token);

  const petición = await fetch(controlador, {
    method: `POST`,
    body: formulario,
    headers: {
      'Accept': `application/json`
    }
  });

  return petición.json();
}
