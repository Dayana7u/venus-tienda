function template_cargando_checkout_tienda(metodoPago = ``) {
  if (metodoPago === `pse`) {
    return `Procesando pago PSE...`;
  }

  if (metodoPago === `tarjeta`) {
    return `Autorizando tarjeta...`;
  }

  if (metodoPago === `contra_entrega`) {
    return `Registrando pedido...`;
  }

  return `Procesando pago...`;
}
