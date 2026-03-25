let tcheckout_modal = {
  divModal: null,
  ultimoFoco: null,
  onAceptar: null,
  onCancelar: null,
};

function abrir_modal_bloqueante_checkout_tienda(configuracion = {}) {
  cerrar_modal_bloqueante_checkout_tienda();

  const titulo = configuracion.titulo || `Confirmación`;
  const mensaje = configuracion.mensaje || ``;
  const textoAceptar = configuracion.textoAceptar || `Aceptar`;
  const textoCancelar = configuracion.textoCancelar || ``;
  const mostrarCancelar = textoCancelar !== ``;
  const tipo = configuracion.tipo || `info`;

  tcheckout_modal.ultimoFoco = document.activeElement;
  tcheckout_modal.onAceptar = typeof configuracion.onAceptar === `function` ? configuracion.onAceptar : null;
  tcheckout_modal.onCancelar = typeof configuracion.onCancelar === `function` ? configuracion.onCancelar : null;

  const divModal = document.createElement(`div`);
  divModal.className = `tv_modal_checkout tv_modal_checkout_${tipo}`;
  divModal.innerHTML = `
    <div class="tv_modal_checkout_backdrop"></div>
    <div class="tv_modal_checkout_dialogo" role="dialog" aria-modal="true" aria-labelledby="tv_modal_checkout_titulo" tabindex="-1">
      <div class="tv_modal_checkout_cabecera">
        <span class="tv_etiqueta">Validación</span>
        <h3 id="tv_modal_checkout_titulo">${titulo}</h3>
      </div>
      <div class="tv_modal_checkout_cuerpo">${mensaje}</div>
      <div class="tv_modal_checkout_acciones">
        ${mostrarCancelar ? `<button type="button" class="tv_btn tv_btn_secundario" data-tv-modal-cancelar="1">${textoCancelar}</button>` : ``}
        <button type="button" class="tv_btn tv_btn_principal" data-tv-modal-aceptar="1">${textoAceptar}</button>
      </div>
    </div>`;

  document.body.appendChild(divModal);
  document.body.classList.add(`tv_body_modal_activo`);
  tcheckout_modal.divModal = divModal;

  const dialogo = divModal.querySelector(`.tv_modal_checkout_dialogo`);
  const btnAceptar = divModal.querySelector(`[data-tv-modal-aceptar="1"]`);
  const btnCancelar = divModal.querySelector(`[data-tv-modal-cancelar="1"]`);

  if (btnAceptar) {
    btnAceptar.addEventListener(`click`, async function() {
      const callback = tcheckout_modal.onAceptar;
      cerrar_modal_bloqueante_checkout_tienda();
      if (callback) {
        await callback();
      }
    });
  }

  if (btnCancelar) {
    btnCancelar.addEventListener(`click`, function() {
      const callback = tcheckout_modal.onCancelar;
      cerrar_modal_bloqueante_checkout_tienda();
      if (callback) {
        callback();
      }
    });
  }

  divModal.addEventListener(`keydown`, function(evento) {
    if (evento.key === `Escape`) {
      evento.preventDefault();
      evento.stopPropagation();
      return;
    }

    if (evento.key === `Tab`) {
      const focusables = Array.from(divModal.querySelectorAll(`button, a, input, select, textarea`)).filter((elemento) => !elemento.disabled);

      if (focusables.length === 0) {
        evento.preventDefault();
        return;
      }

      const primero = focusables[0];
      const ultimo = focusables[focusables.length - 1];

      if (evento.shiftKey && document.activeElement === primero) {
        evento.preventDefault();
        ultimo.focus();
      }
      else if (!evento.shiftKey && document.activeElement === ultimo) {
        evento.preventDefault();
        primero.focus();
      }
    }
  });

  setTimeout(function() {
    if (dialogo) {
      dialogo.focus();
    }

    if (btnAceptar) {
      btnAceptar.focus();
    }
  }, 20);
}

function cerrar_modal_bloqueante_checkout_tienda() {
  if (tcheckout_modal.divModal) {
    tcheckout_modal.divModal.remove();
    tcheckout_modal.divModal = null;
  }

  document.body.classList.remove(`tv_body_modal_activo`);

  if (tcheckout_modal.ultimoFoco && typeof tcheckout_modal.ultimoFoco.focus === `function`) {
    tcheckout_modal.ultimoFoco.focus();
  }

  tcheckout_modal.onAceptar = null;
  tcheckout_modal.onCancelar = null;
}
