function template_cargando_checkout_tienda() {
  return `Guardando datos...`;
}

function template_cargando_checkout_pago_tienda(metodoPago = ``) {
  if (metodoPago === `pse`) {
    return `Preparando PSE...`;
  }

  if (metodoPago === `tarjeta`) {
    return `Autorizando tarjeta...`;
  }

  if (metodoPago === `contra_entrega`) {
    return `Registrando pedido...`;
  }

  return `Procesando pago...`;
}
