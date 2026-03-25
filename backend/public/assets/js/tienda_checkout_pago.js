let tcheckout_pago = {};
// Datos generales
  tcheckout_pago.token = document.getElementById(`token_checkout_pago_tienda_publica`);
  tcheckout_pago.controlador = document.getElementById(`controlador_checkout_pago_tienda_publica`);
  tcheckout_pago.formulario = document.getElementById(`form_checkout_pago_tienda_publica`);
  tcheckout_pago.inputFranquicia = document.getElementById(`input_franquicia_tarjeta_checkout_pago`);
  tcheckout_pago.inputTokenTarjeta = document.getElementById(`input_token_tarjeta_checkout_pago`);
  tcheckout_pago.inputUltimosCuatro = document.getElementById(`input_ultimos_cuatro_checkout_pago`);
  tcheckout_pago.inputPublicKey = document.getElementById(`input_public_key_checkout_pago`);
  tcheckout_pago.inputPasarelaHabilitada = document.getElementById(`input_pasarela_habilitada_checkout_pago`);
  tcheckout_pago.textoFranquicia = document.getElementById(`strong_franquicia_checkout_pago`);
// Divs
  tcheckout_pago.divPse = document.getElementById(`div_checkout_pago_pse`);
  tcheckout_pago.divTarjeta = document.getElementById(`div_checkout_pago_tarjeta`);
  tcheckout_pago.divContraEntrega = document.getElementById(`div_checkout_pago_contra_entrega`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_tienda_checkout_pago();
});

async function inicializar_tienda_checkout_pago() {
  if (!tcheckout_pago.formulario || !tcheckout_pago.controlador || !tcheckout_pago.token) {
    return;
  }

  registrar_eventos_tienda_checkout_pago();
  cambiar_metodo_pago_tienda_checkout_pago();
  actualizar_tarjetas_metodo_checkout_pago();
  mostrar_toast_informativo_checkout_pago(mensaje_metodo_pago_checkout_pago());
}

function registrar_eventos_tienda_checkout_pago() {
  tcheckout_pago.formulario.addEventListener(`submit`, async function(event) {
    event.preventDefault();
    await confirmar_envio_checkout_pago_tienda();
  });

  tcheckout_pago.formulario.querySelectorAll(`[name="metodo_pago"]`).forEach(function(campo) {
    campo.addEventListener(`change`, function() {
      limpiar_token_tarjeta_checkout_pago();
      cambiar_metodo_pago_tienda_checkout_pago();
      actualizar_tarjetas_metodo_checkout_pago();
      mostrar_toast_informativo_checkout_pago(mensaje_metodo_pago_checkout_pago());
    });
  });

  const inputNumeroTarjeta = tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="numero"]`);
  const inputFechaTarjeta = tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="fecha"]`);

  if (inputNumeroTarjeta) {
    inputNumeroTarjeta.addEventListener(`input`, function() {
      inputNumeroTarjeta.value = formatear_numero_tarjeta_tienda_checkout_pago(inputNumeroTarjeta.value);
      detectar_franquicia_tienda_checkout_pago(inputNumeroTarjeta.value);
      limpiar_token_tarjeta_checkout_pago();
    });
  }

  if (inputFechaTarjeta) {
    inputFechaTarjeta.addEventListener(`input`, function() {
      inputFechaTarjeta.value = formatear_fecha_tarjeta_tienda_checkout_pago(inputFechaTarjeta.value);
      limpiar_token_tarjeta_checkout_pago();
    });
  }
}

function confirmar_envio_checkout_pago_tienda() {
  abrir_modal_bloqueante_checkout_tienda({
    tipo: `confirmacion`,
    titulo: `Confirmar pago`,
    mensaje: `¿Deseas continuar con el registro del pago del pedido actual?`,
    textoAceptar: `Continuar`,
    textoCancelar: `Cancelar`,
    onAceptar: async function() {
      await guardar_checkout_pago_tienda();
    },
  });
}

function cambiar_metodo_pago_tienda_checkout_pago() {
  const metodoPago = obtener_metodo_pago_tienda_checkout_pago();
  const camposPse = tcheckout_pago.divPse ? tcheckout_pago.divPse.querySelectorAll(`input, select`) : [];
  const camposTarjeta = tcheckout_pago.divTarjeta ? tcheckout_pago.divTarjeta.querySelectorAll(`input, select`) : [];
  const camposContraEntrega = tcheckout_pago.divContraEntrega ? tcheckout_pago.divContraEntrega.querySelectorAll(`input`) : [];

  if (tcheckout_pago.divPse) {
    tcheckout_pago.divPse.classList.toggle(`tv_oculto`, metodoPago !== `pse`);
  }

  if (tcheckout_pago.divTarjeta) {
    tcheckout_pago.divTarjeta.classList.toggle(`tv_oculto`, metodoPago !== `tarjeta`);
  }

  if (tcheckout_pago.divContraEntrega) {
    tcheckout_pago.divContraEntrega.classList.toggle(`tv_oculto`, metodoPago !== `contra_entrega`);
  }

  asignar_requeridos_tienda_checkout_pago(camposPse, metodoPago === `pse`, [`nombres_pagador`, `correo_pagador`, `documento_pagador`, `entidad_pse`]);
  asignar_requeridos_tienda_checkout_pago(camposTarjeta, metodoPago === `tarjeta`, [`titular_pagador`, `fecha_expiracion`]);
  asignar_requeridos_tienda_checkout_pago(camposContraEntrega, metodoPago === `contra_entrega`, [`contra_entrega_recibe`, `contra_entrega_documento`, `contra_entrega_confirma`]);
}

function actualizar_tarjetas_metodo_checkout_pago() {
  const metodoPago = obtener_metodo_pago_tienda_checkout_pago();

  tcheckout_pago.formulario.querySelectorAll(`.tv_checkout_metodo_pago_card`).forEach(function(card) {
    const radio = card.querySelector(`[name="metodo_pago"]`);
    card.classList.toggle(`tv_checkout_metodo_pago_card_activo`, radio && radio.value === metodoPago);
  });
}

function asignar_requeridos_tienda_checkout_pago(campos, requerido, nombres = []) {
  Array.from(campos).forEach(function(campo) {
    if (campo.type === `hidden`) {
      return;
    }

    if (campo.type === `checkbox`) {
      campo.required = requerido;
      return;
    }

    if (campo.name === `cuotas`) {
      campo.required = false;
      return;
    }

    if (campo.name && nombres.includes(campo.name)) {
      campo.required = requerido;
      return;
    }

    if (campo.dataset.checkoutTarjeta) {
      campo.required = requerido;
      return;
    }

    campo.required = false;
  });
}

function obtener_metodo_pago_tienda_checkout_pago() {
  const campo = tcheckout_pago.formulario.querySelector(`[name="metodo_pago"]:checked`);
  return campo ? campo.value : `tarjeta`;
}

function detectar_franquicia_tienda_checkout_pago(numeroTarjeta) {
  const numeroLimpio = String(numeroTarjeta || ``).replace(/\D+/g, ``);
  let franquicia = `Por detectar`;
  let valorFranquicia = ``;

  if (/^4/.test(numeroLimpio)) {
    franquicia = `Visa`;
    valorFranquicia = `VISA`;
  }
  else if (/^(5[1-5]|2[2-7])/.test(numeroLimpio)) {
    franquicia = `Mastercard`;
    valorFranquicia = `MASTERCARD`;
  }
  else if (/^3[47]/.test(numeroLimpio)) {
    franquicia = `Amex`;
    valorFranquicia = `AMEX`;
  }
  else if (/^6/.test(numeroLimpio)) {
    franquicia = `Discover`;
    valorFranquicia = `DISCOVER`;
  }

  if (tcheckout_pago.textoFranquicia) {
    tcheckout_pago.textoFranquicia.textContent = franquicia;
  }

  if (tcheckout_pago.inputFranquicia) {
    tcheckout_pago.inputFranquicia.value = valorFranquicia;
  }
}

function formatear_numero_tarjeta_tienda_checkout_pago(valor) {
  const numeroLimpio = String(valor || ``).replace(/\D+/g, ``).substring(0, 19);
  return numeroLimpio.replace(/(.{4})/g, `$1 `).trim();
}

function formatear_fecha_tarjeta_tienda_checkout_pago(valor) {
  const numeroLimpio = String(valor || ``).replace(/\D+/g, ``).substring(0, 4);

  if (numeroLimpio.length <= 2) {
    return numeroLimpio;
  }

  return `${numeroLimpio.substring(0, 2)}/${numeroLimpio.substring(2)}`;
}

function limpiar_token_tarjeta_checkout_pago() {
  if (tcheckout_pago.inputTokenTarjeta) {
    tcheckout_pago.inputTokenTarjeta.value = ``;
  }

  if (tcheckout_pago.inputUltimosCuatro) {
    tcheckout_pago.inputUltimosCuatro.value = ``;
  }
}

function validar_formulario_checkout_pago_tienda() {
  const metodoPago = obtener_metodo_pago_tienda_checkout_pago();

  if (tcheckout_pago.formulario.querySelector(`[name="acepta_pasarela"]`)?.checked !== true) {
    return {
      estado: false,
      mensaje: `Debes aceptar los términos del medio de pago para continuar.`,
      foco: tcheckout_pago.formulario.querySelector(`[name="acepta_pasarela"]`),
    };
  }

  if ((tcheckout_pago.inputPasarelaHabilitada?.value || `0`) !== `1` && [`pse`, `tarjeta`].includes(metodoPago)) {
    return {
      estado: false,
      mensaje: `La pasarela Wompi no está configurada todavía. Completa las llaves en el servidor para habilitar pagos reales.`,
      foco: null,
    };
  }

  if (metodoPago === `pse`) {
    const nombres = tcheckout_pago.formulario.querySelector(`[name="nombres_pagador"]`);
    const correo = tcheckout_pago.formulario.querySelector(`[name="correo_pagador"]`);
    const documento = tcheckout_pago.formulario.querySelector(`[name="documento_pagador"]`);
    const banco = tcheckout_pago.formulario.querySelector(`[name="entidad_pse"]`);

    if (!nombres?.value.trim()) {
      return {estado: false, mensaje: `Debes ingresar el nombre del pagador.`, foco: nombres};
    }

    if (!correo?.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.value.trim())) {
      return {estado: false, mensaje: `Debes ingresar un correo válido para PSE.`, foco: correo};
    }

    if (!documento?.value.trim()) {
      return {estado: false, mensaje: `Debes ingresar el documento del pagador.`, foco: documento};
    }

    if (!/^[0-9]{5,20}$/.test(String(documento.value || ``).replace(/\D+/g, ``))) {
      return {estado: false, mensaje: `El documento del pagador no es válido.`, foco: documento};
    }

    if (!banco?.value.trim()) {
      return {estado: false, mensaje: `Debes seleccionar el banco para continuar con PSE.`, foco: banco};
    }
  }

  if (metodoPago === `tarjeta`) {
    const titular = tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="titular"]`);
    const numero = tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="numero"]`);
    const fecha = tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="fecha"]`);
    const cvv = tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="cvv"]`);
    const numeroLimpio = String(numero?.value || ``).replace(/\D+/g, ``);
    const cvvLimpio = String(cvv?.value || ``).replace(/\D+/g, ``);

    if (!titular?.value.trim()) {
      return {estado: false, mensaje: `Debes ingresar el titular de la tarjeta.`, foco: titular};
    }

    if (String(titular.value || ``).trim().length < 5) {
      return {estado: false, mensaje: `El titular de la tarjeta debe tener al menos 5 caracteres.`, foco: titular};
    }

    if (!luhn_valido_checkout_pago(numeroLimpio)) {
      return {estado: false, mensaje: `El número de tarjeta no es válido.`, foco: numero};
    }

    if (!/^(0[1-9]|1[0-2])\/[0-9]{2}$/.test(String(fecha?.value || ``))) {
      return {estado: false, mensaje: `La fecha de expiración no es válida.`, foco: fecha};
    }

    if (!fecha_tarjeta_vigente_checkout_pago(String(fecha?.value || ``))) {
      return {estado: false, mensaje: `La tarjeta ya está vencida.`, foco: fecha};
    }

    if (!cvv_valido_checkout_pago(cvvLimpio, tcheckout_pago.inputFranquicia?.value || ``)) {
      return {estado: false, mensaje: `El código de seguridad no es válido.`, foco: cvv};
    }
  }

  if (metodoPago === `contra_entrega`) {
    const recibe = tcheckout_pago.formulario.querySelector(`[name="contra_entrega_recibe"]`);
    const documento = tcheckout_pago.formulario.querySelector(`[name="contra_entrega_documento"]`);
    const confirma = tcheckout_pago.formulario.querySelector(`[name="contra_entrega_confirma"]`);

    if (!recibe?.value.trim()) {
      return {estado: false, mensaje: `Debes indicar quién recibe el pedido.`, foco: recibe};
    }

    if (!documento?.value.trim()) {
      return {estado: false, mensaje: `Debes ingresar el documento de quien recibe.`, foco: documento};
    }

    if (!/^[0-9]{5,20}$/.test(String(documento.value || ``).replace(/\D+/g, ``))) {
      return {estado: false, mensaje: `El documento de quien recibe no es válido.`, foco: documento};
    }

    if (confirma?.checked !== true) {
      return {estado: false, mensaje: `Debes confirmar la información de entrega.`, foco: confirma};
    }
  }

  return {estado: true, mensaje: ``};
}

function luhn_valido_checkout_pago(numero) {
  if (!/^[0-9]{13,19}$/.test(numero)) {
    return false;
  }

  let suma = 0;
  let duplicar = false;

  for (let i = numero.length - 1; i >= 0; i--) {
    let digito = parseInt(numero.charAt(i), 10);

    if (duplicar) {
      digito *= 2;
      if (digito > 9) {
        digito -= 9;
      }
    }

    suma += digito;
    duplicar = !duplicar;
  }

  return (suma % 10) === 0;
}

function fecha_tarjeta_vigente_checkout_pago(fecha) {
  const partes = String(fecha || ``).split(`/`);

  if (partes.length !== 2) {
    return false;
  }

  const mes = parseInt(partes[0], 10);
  const anio = parseInt(`20${partes[1]}`, 10);
  const ahora = new Date();
  const limite = new Date(anio, mes, 0, 23, 59, 59);

  return limite >= ahora;
}

function cvv_valido_checkout_pago(cvv, franquicia) {
  if ([`AMEX`].includes(String(franquicia || ``).toUpperCase())) {
    return /^[0-9]{4}$/.test(cvv);
  }

  return /^[0-9]{3}$/.test(cvv);
}

async function tokenizar_tarjeta_checkout_pago_tienda() {
  const publicKey = tcheckout_pago.inputPublicKey?.value || ``;
  const titular = tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="titular"]`)?.value.trim() || ``;
  const numero = (tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="numero"]`)?.value || ``).replace(/\D+/g, ``);
  const fecha = tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="fecha"]`)?.value || ``;
  const cvv = (tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="cvv"]`)?.value || ``).replace(/\D+/g, ``);
  const [expMonth, expYear] = fecha.split(`/`);

  const endpointBase = publicKey.startsWith(`pub_prod_`) ? `https://production.wompi.co/v1` : `https://sandbox.wompi.co/v1`;
  const respuesta = await fetch(`${endpointBase}/tokens/cards`, {
    method: `POST`,
    headers: {
      'Authorization': `Bearer ${publicKey}`,
      'Content-Type': `application/json`,
      'Accept': `application/json`,
    },
    body: JSON.stringify({
      number: numero,
      cvc: cvv,
      exp_month: expMonth,
      exp_year: expYear,
      card_holder: titular,
    }),
  });

  const json = await respuesta.json();

  if (!respuesta.ok || !json?.data?.id) {
    throw new Error(json?.error?.reason || `No fue posible tokenizar la tarjeta en la pasarela.`);
  }

  if (tcheckout_pago.inputTokenTarjeta) {
    tcheckout_pago.inputTokenTarjeta.value = json.data.id;
  }

  if (tcheckout_pago.inputUltimosCuatro) {
    tcheckout_pago.inputUltimosCuatro.value = json.data.last_four || numero.substring(numero.length - 4);
  }
}

async function guardar_checkout_pago_tienda() {
  const validacion = validar_formulario_checkout_pago_tienda();

  if (validacion.estado !== true) {
    abrir_modal_bloqueante_checkout_tienda({
      tipo: `error`,
      titulo: `Validación requerida`,
      mensaje: validacion.mensaje,
      textoAceptar: `Entendido`,
      onAceptar: function() {
        if (validacion.foco && typeof validacion.foco.focus === `function`) {
          validacion.foco.focus();
        }
      },
    });
    return;
  }

  const token = tcheckout_pago.token.value;
  const controlador = tcheckout_pago.controlador.value;
  const formulario = new FormData(tcheckout_pago.formulario);
  const metodoPago = obtener_metodo_pago_tienda_checkout_pago();
  const btnGuardar = document.getElementById(`btn_guardar_checkout_pago`);
  const textoBase = btnGuardar?.dataset.textoBase || `Pagar pedido`;

  if (metodoPago === `tarjeta`) {
    formulario.set(`titular_pagador`, tcheckout_pago.formulario.querySelector(`[data-checkout-tarjeta="titular"]`)?.value.trim() || ``);
  }

  if (btnGuardar) {
    btnGuardar.disabled = true;
    btnGuardar.textContent = template_cargando_checkout_pago_tienda(metodoPago);
  }

  try {
    if (metodoPago === `tarjeta`) {
      await tokenizar_tarjeta_checkout_pago_tienda();
      formulario.set(`token_tarjeta_wompi`, tcheckout_pago.inputTokenTarjeta?.value || ``);
      formulario.set(`ultimos_cuatro_tarjeta`, tcheckout_pago.inputUltimosCuatro?.value || ``);
      formulario.set(`franquicia_tarjeta`, tcheckout_pago.inputFranquicia?.value || ``);
    }

    const petición = await guardar_checkout_pago_tienda_peticiones(token, controlador, formulario);

    if (!petición || petición.estado !== true) {
      abrir_modal_bloqueante_checkout_tienda({
        tipo: `error`,
        titulo: `No fue posible continuar`,
        mensaje: petición.mensaje || `No fue posible registrar el pago.`,
        textoAceptar: `Revisar`,
      });
      return;
    }

    const redirect = petición?.datos?.redirect || `/checkout/pago/`;
    window.location.href = redirect;
  }
  catch (error) {
    abrir_modal_bloqueante_checkout_tienda({
      tipo: `error`,
      titulo: `Novedad en el pago`,
      mensaje: error.message || `Se presentó una novedad al registrar el pago.`,
      textoAceptar: `Revisar`,
    });
  }
  finally {
    if (btnGuardar) {
      btnGuardar.disabled = false;
      btnGuardar.textContent = textoBase;
    }
  }
}

function mensaje_metodo_pago_checkout_pago() {
  const radio = tcheckout_pago.formulario.querySelector(`[name="metodo_pago"]:checked`);

  if (!radio) {
    return ``;
  }

  return radio.dataset.mensaje || ``;
}

function mostrar_toast_informativo_checkout_pago(mensaje) {
  if (!mensaje) {
    return;
  }

  let contenedor = document.getElementById(`tv_toast_checkout_pago`);

  if (!contenedor) {
    contenedor = document.createElement(`div`);
    contenedor.id = `tv_toast_checkout_pago`;
    contenedor.className = `tv_checkout_toast_info`;
    document.body.appendChild(contenedor);
  }

  contenedor.textContent = mensaje;
  contenedor.classList.add(`tv_checkout_toast_info_visible`);

  if (contenedor.dataset.timeoutId) {
    clearTimeout(parseInt(contenedor.dataset.timeoutId, 10));
  }

  const timeoutId = setTimeout(function() {
    contenedor.classList.remove(`tv_checkout_toast_info_visible`);
  }, 3200);
  contenedor.dataset.timeoutId = timeoutId;
}

document.addEventListener(`input`, function(event) {
  const campo = event.target;

  if (!campo) {
    return;
  }

  if ([`documento_pagador`, `contra_entrega_documento`].includes(campo.name)) {
    campo.value = String(campo.value || ``).replace(/\D+/g, ``);
  }
});
