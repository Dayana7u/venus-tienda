let tcheckout = {};
// Datos generales
  tcheckout.token = document.getElementById(`token_checkout_tienda_publica`);
  tcheckout.controlador = document.getElementById(`controlador_checkout_tienda_publica`);
  tcheckout.formulario = document.getElementById(`form_checkout_tienda_publica`);
  tcheckout.inputTitular = document.getElementById(`input_titular_pagador_checkout`);
// Divs
  tcheckout.divPse = document.getElementById(`div_checkout_pse`);
  tcheckout.divTarjeta = document.getElementById(`div_checkout_tarjeta`);
  tcheckout.divContraEntrega = document.getElementById(`div_checkout_contra_entrega`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_tienda_checkout();
});

async function inicializar_tienda_checkout() {
  if (!tcheckout.formulario || !tcheckout.controlador || !tcheckout.token) {
    return;
  }

  registrar_eventos_tienda_checkout();
  cambiar_metodo_pago_tienda_checkout();
}

function registrar_eventos_tienda_checkout() {
  tcheckout.formulario.addEventListener(`submit`, async function(event) {
    event.preventDefault();
    await guardar_checkout_tienda_checkout();
  });

  tcheckout.formulario.querySelectorAll(`[name="metodo_pago"]`).forEach(function(campo) {
    campo.addEventListener(`change`, function() {
      cambiar_metodo_pago_tienda_checkout();
    });
  });
}

function cambiar_metodo_pago_tienda_checkout() {
  const metodoPago = obtener_metodo_pago_tienda_checkout();
  const inputEntidad = tcheckout.formulario.querySelector(`[name="entidad_pse"]`);
  const inputTipoPersonaPse = tcheckout.formulario.querySelector(`[name="tipo_persona_pse"]`);
  const inputTipoCuentaPse = tcheckout.formulario.querySelector(`[name="tipo_cuenta_pse"]`);
  const inputTarjeta = tcheckout.formulario.querySelector(`[name="numero_tarjeta"]`);
  const inputFecha = tcheckout.formulario.querySelector(`[name="fecha_expiracion"]`);
  const inputCvv = tcheckout.formulario.querySelector(`[data-checkout-cvv="true"]`);
  const inputTitularTarjeta = tcheckout.formulario.querySelector(`[name="titular_pagador_tarjeta"]`);
  const inputTitularPse = tcheckout.formulario.querySelector(`#div_checkout_pse [name="titular_pagador_pse"]`);

  if (tcheckout.divPse) {
    tcheckout.divPse.classList.toggle(`tv_oculto`, metodoPago !== `pse`);
  }

  if (tcheckout.divTarjeta) {
    tcheckout.divTarjeta.classList.toggle(`tv_oculto`, metodoPago !== `tarjeta`);
  }

  if (tcheckout.divContraEntrega) {
    tcheckout.divContraEntrega.classList.toggle(`tv_oculto`, metodoPago !== `contra_entrega`);
  }

  if (inputEntidad) {
    inputEntidad.required = metodoPago === `pse`;
  }

  if (inputTipoPersonaPse) {
    inputTipoPersonaPse.required = metodoPago === `pse`;
  }

  if (inputTipoCuentaPse) {
    inputTipoCuentaPse.required = metodoPago === `pse`;
  }

  if (inputTarjeta) {
    inputTarjeta.required = metodoPago === `tarjeta`;
  }

  if (inputFecha) {
    inputFecha.required = metodoPago === `tarjeta`;
  }

  if (inputTitularTarjeta) {
    inputTitularTarjeta.required = metodoPago === `tarjeta`;
  }

  if (inputCvv) {
    inputCvv.required = metodoPago === `tarjeta`;
  }

  if (tcheckout.inputTitular) {
    if (metodoPago === `tarjeta`) {
      tcheckout.inputTitular.value = inputTitularTarjeta ? inputTitularTarjeta.value.trim() : ``;
      return;
    }

    tcheckout.inputTitular.value = inputTitularPse ? inputTitularPse.value.trim() : ``;
  }
}

function obtener_metodo_pago_tienda_checkout() {
  const campo = tcheckout.formulario.querySelector(`[name="metodo_pago"]:checked`);
  return campo ? campo.value : `pse`;
}

async function guardar_checkout_tienda_checkout() {
  const token = tcheckout.token.value;
  const controlador = tcheckout.controlador.value;
  const metodoPago = obtener_metodo_pago_tienda_checkout();
  const inputTitularTarjeta = tcheckout.formulario.querySelector(`[name="titular_pagador_tarjeta"]`);
  const inputTitularPse = tcheckout.formulario.querySelector(`#div_checkout_pse [name="titular_pagador_pse"]`);
  const formulario = new FormData(tcheckout.formulario);
  const btnGuardar = tcheckout.formulario.querySelector(`button[type="submit"]`);
  const inputCvv = tcheckout.formulario.querySelector(`[data-checkout-cvv="true"]`);

  if (tcheckout.inputTitular) {
    tcheckout.inputTitular.value = metodoPago === `tarjeta`
      ? (inputTitularTarjeta ? inputTitularTarjeta.value.trim() : ``)
      : (inputTitularPse ? inputTitularPse.value.trim() : ``);
    formulario.set(`titular_pagador`, tcheckout.inputTitular.value);
  }

  if (metodoPago === `tarjeta` && inputCvv && String(inputCvv.value || ``).trim().length < 3) {
    mostrar_alerta_checkout_tienda(`error`, `Debes ingresar un CVV válido para la tarjeta.`);
    return;
  }

  if (btnGuardar) {
    btnGuardar.disabled = true;
    btnGuardar.textContent = template_cargando_checkout_tienda();
  }

  try {
    const petición = await guardar_checkout_tienda_peticiones(token, controlador, formulario);

    if (petición.estado !== true) {
      mostrar_alerta_checkout_tienda(`error`, petición.mensaje || `No fue posible procesar el pago.`);
      return;
    }

    mostrar_alerta_checkout_tienda(`ok`, petición.mensaje || `Pago registrado correctamente.`);
    window.location.href = petición.datos.redirect;
  }
  catch (error) {
    mostrar_alerta_checkout_tienda(`error`, `Se presentó una novedad al procesar la compra.`);
  }
  finally {
    if (metodoPago === `tarjeta` && inputCvv && String(inputCvv.value || ``).trim().length < 3) {
    mostrar_alerta_checkout_tienda(`error`, `Debes ingresar un CVV válido para la tarjeta.`);
    return;
  }

  if (btnGuardar) {
      btnGuardar.disabled = false;
      btnGuardar.textContent = `Pagar pedido`;
    }
  }
}

function mostrar_alerta_checkout_tienda(tipo, mensaje) {
  if (typeof mostrar_alerta_tienda_store === `function`) {
    mostrar_alerta_tienda_store(tipo, mensaje);
    return;
  }

  alert(mensaje);
}
