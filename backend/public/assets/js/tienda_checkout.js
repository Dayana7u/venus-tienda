let tcheckout = {};
// Datos generales
  tcheckout.token = document.getElementById(`token_checkout_tienda_publica`);
  tcheckout.controlador = document.getElementById(`controlador_checkout_tienda_publica`);
  tcheckout.formulario = document.getElementById(`form_checkout_tienda_publica`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_tienda_checkout();
});

async function inicializar_tienda_checkout() {
  if (!tcheckout.formulario || !tcheckout.controlador || !tcheckout.token) {
    return;
  }

  registrar_eventos_tienda_checkout();
}

function registrar_eventos_tienda_checkout() {
  tcheckout.formulario.addEventListener(`submit`, async function(event) {
    event.preventDefault();
    const primerError = validar_formulario_checkout_tienda();

    if (primerError) {
      abrir_modal_bloqueante_checkout_tienda({
        tipo: `error`,
        titulo: `Validación requerida`,
        mensaje: primerError.mensaje,
        textoAceptar: `Entendido`,
        onAceptar: function() {
          if (primerError.foco && typeof primerError.foco.focus === `function`) {
            primerError.foco.focus();
          }
        },
      });
      return;
    }

    abrir_modal_bloqueante_checkout_tienda({
      tipo: `confirmacion`,
      titulo: `Continuar al pago`,
      mensaje: `Se guardarán los datos del comprador y la entrega para pasar al paso de pago.`,
      textoAceptar: `Continuar`,
      textoCancelar: `Cancelar`,
      onAceptar: async function() {
        await guardar_datos_checkout_tienda();
      },
    });
  });
}

function validar_formulario_checkout_tienda() {
  const camposRequeridos = Array.from(tcheckout.formulario.querySelectorAll(`input[required], select[required], textarea[required]`));

  for (const campo of camposRequeridos) {
    if (campo.closest(`.tv_oculto`)) {
      continue;
    }

    if (campo.type === `checkbox` && campo.checked !== true) {
      return { mensaje: `Debes completar los campos obligatorios del formulario.`, foco: campo };
    }

    if (campo.type !== `checkbox` && !String(campo.value || ``).trim()) {
      const etiqueta = obtener_etiqueta_campo_checkout_tienda(campo);
      return { mensaje: `Debes completar ${etiqueta}.`, foco: campo };
    }
  }

  const correo = tcheckout.formulario.querySelector(`[name="correo"]`);
  if (correo && String(correo.value || ``).trim() !== `` && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.value.trim())) {
    return { mensaje: `El correo del comprador no es válido.`, foco: correo };
  }

  const celular = tcheckout.formulario.querySelector(`[name="celular"]`);
  if (celular && String(celular.value || ``).trim() !== `` && !/^[0-9]{10}$/.test(String(celular.value || ``).replace(/\D+/g, ``))) {
    return { mensaje: `El celular del comprador debe tener 10 dígitos.`, foco: celular };
  }

  const telefono = tcheckout.formulario.querySelector(`[name="telefono_direccion"]`);
  if (telefono && String(telefono.value || ``).trim() !== `` && !/^[0-9]{7,10}$/.test(String(telefono.value || ``).replace(/\D+/g, ``))) {
    return { mensaje: `El teléfono de la dirección no es válido.`, foco: telefono };
  }

  return null;
}

function obtener_etiqueta_campo_checkout_tienda(campo) {
  const contenedor = campo.closest(`label`);
  const etiqueta = contenedor ? contenedor.querySelector(`span`) : null;

  if (!etiqueta) {
    return `la información requerida`;
  }

  return etiqueta.textContent.replace(`*`, ``).trim().toLowerCase();
}

async function guardar_datos_checkout_tienda() {
  const token = tcheckout.token.value;
  const controlador = tcheckout.controlador.value;
  const formulario = new FormData(tcheckout.formulario);
  const btnGuardar = tcheckout.formulario.querySelector(`button[type="submit"]`);

  if (btnGuardar) {
    btnGuardar.disabled = true;
    btnGuardar.textContent = template_cargando_checkout_tienda();
  }

  try {
    const petición = await guardar_checkout_tienda_peticiones(token, controlador, formulario);

    if (petición.estado !== true) {
      abrir_modal_bloqueante_checkout_tienda({
        tipo: `error`,
        titulo: `No fue posible continuar`,
        mensaje: petición.mensaje || `No fue posible guardar los datos del pedido.`,
        textoAceptar: `Revisar`,
      });
      return;
    }

    window.location.href = petición.datos.redirect || `/checkout/pago/`;
  }
  catch (error) {
    abrir_modal_bloqueante_checkout_tienda({
      tipo: `error`,
      titulo: `Novedad en el checkout`,
      mensaje: `Se presentó una novedad al guardar los datos del pedido.`,
      textoAceptar: `Revisar`,
    });
  }
  finally {
    if (btnGuardar) {
      btnGuardar.disabled = false;
      btnGuardar.textContent = `Continuar al pago`;
    }
  }
}


document.addEventListener(`input`, function(event) {
  const campo = event.target;

  if (!campo || !campo.name) {
    return;
  }

  if ([`celular`, `telefono_direccion`, `codigo_postal`].includes(campo.name)) {
    campo.value = String(campo.value || ``).replace(/\D+/g, ``);
  }
});
