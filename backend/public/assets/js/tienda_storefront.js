const tvsf = {
  btns_abrir_carrito : document.querySelectorAll(`.js_btn_abrir_carrito`),
  overlay_carrito    : document.getElementById(`tv_overlay_carrito`),
  drawer_carrito     : document.getElementById(`tv_drawer_carrito`),
  btn_cerrar_drawer  : document.getElementById(`tv_btn_cerrar_drawer`),
  drawer_items       : document.getElementById(`tv_drawer_items`),
  drawer_resumen     : document.getElementById(`tv_drawer_resumen`),
  drawer_resumen_txt : document.getElementById(`tv_drawer_resumen_items`),
  contador_carrito   : document.getElementById(`tv_contador_carrito`),
  toast_wrap         : document.getElementById(`tv_toast_wrap`),
  temporizador_toast : null,
};

document.addEventListener(`DOMContentLoaded`, async function() {
  if (!tvsf.drawer_carrito) {
    return;
  }

  eventos_tienda_storefront();
  await cargar_resumen_tienda_storefront(false);
});

function eventos_tienda_storefront() {
  tvsf.btns_abrir_carrito.forEach(function(boton) {
    boton.addEventListener(`click`, function() {
      alternar_drawer_tienda_storefront(true);
    });
  });

  if (tvsf.btn_cerrar_drawer) {
    tvsf.btn_cerrar_drawer.addEventListener(`click`, function() {
      alternar_drawer_tienda_storefront(false);
    });
  }

  if (tvsf.overlay_carrito) {
    tvsf.overlay_carrito.addEventListener(`click`, function() {
      alternar_drawer_tienda_storefront(false);
    });
  }

  document.addEventListener(`submit`, async function(event) {
    const formulario = event.target.closest(`.js_form_carrito`);

    if (!formulario) {
      return;
    }

    event.preventDefault();

    const formularioDatos = new FormData(formulario);
    const acción = formularioDatos.get(`accion`) || ``;
    let petición = null;

    if (acción === `agregar`) {
      petición = await tienda_storefront_agregar_producto_peticiones(formularioDatos);
    }

    if (acción === `actualizar`) {
      petición = await tienda_storefront_agregar_producto_peticiones(formularioDatos);
    }

    if (acción === `eliminar`) {
      petición = await tienda_storefront_agregar_producto_peticiones(formularioDatos);
    }

    procesar_respuesta_tienda_storefront(petición, acción === `agregar`);
  });

  document.addEventListener(`click`, async function(event) {
    const btnCantidad = event.target.closest(`.js_btn_cantidad_drawer`);
    const btnEliminar = event.target.closest(`.js_btn_eliminar_drawer`);
    const btnCerrarToast = event.target.closest(`[data-cerrar-toast="true"]`);

    if (btnCerrarToast) {
      limpiar_toast_tienda_storefront();
      return;
    }

    if (btnCantidad) {
      const slug = btnCantidad.dataset.slug || ``;
      const cantidad = btnCantidad.dataset.cantidad || `0`;
      const petición = await tienda_storefront_actualizar_producto_peticiones(slug, cantidad);
      procesar_respuesta_tienda_storefront(petición, false);
      return;
    }

    if (btnEliminar) {
      const slug = btnEliminar.dataset.slug || ``;
      const petición = await tienda_storefront_eliminar_producto_peticiones(slug);
      procesar_respuesta_tienda_storefront(petición, false);
    }
  });
}

async function cargar_resumen_tienda_storefront(abrirDrawer = false) {
  const petición = await tienda_storefront_consultar_resumen_peticiones();
  procesar_respuesta_tienda_storefront(petición, abrirDrawer);
}

function procesar_respuesta_tienda_storefront(petición, abrirDrawer) {
  if (!petición || petición.estado !== true) {
    mostrar_toast_tienda_storefront(`error`, petición?.mensaje || `No fue posible procesar el carrito.`);
    return;
  }

  renderizar_drawer_tienda_storefront(petición.carrito || {});

  if (petición.mensaje) {
    mostrar_toast_tienda_storefront(`ok`, petición.mensaje);
  }

  if (abrirDrawer === true) {
    alternar_drawer_tienda_storefront(true);
  }
}

function renderizar_drawer_tienda_storefront(carrito) {
  const items = carrito.items || [];
  const cantidad = Number(carrito.cantidad || 0);

  if (tvsf.contador_carrito) {
    tvsf.contador_carrito.textContent = `${cantidad}`;
  }

  if (tvsf.drawer_resumen_txt) {
    tvsf.drawer_resumen_txt.textContent = cantidad > 0
      ? `${cantidad} producto(s) agregado(s)`
      : `Sin productos agregados`;
  }

  if (tvsf.drawer_items) {
    tvsf.drawer_items.innerHTML = items.length > 0
      ? items.map(function(item) {
          return template_item_drawer_tienda_storefront(item);
        }).join(``)
      : template_vacio_drawer_tienda_storefront();
  }

  if (tvsf.drawer_resumen) {
    tvsf.drawer_resumen.innerHTML = items.length > 0
      ? template_resumen_drawer_tienda_storefront(carrito)
      : ``;
  }
}

function alternar_drawer_tienda_storefront(visible) {
  if (!tvsf.drawer_carrito || !tvsf.overlay_carrito) {
    return;
  }

  if (visible === true) {
    tvsf.drawer_carrito.classList.add(`tv_drawer_carrito_activo`);
    tvsf.overlay_carrito.classList.add(`tv_overlay_carrito_activo`);
    return;
  }

  tvsf.drawer_carrito.classList.remove(`tv_drawer_carrito_activo`);
  tvsf.overlay_carrito.classList.remove(`tv_overlay_carrito_activo`);
}

function mostrar_toast_tienda_storefront(tipo, mensaje) {
  if (!tvsf.toast_wrap) {
    return;
  }

  limpiar_toast_tienda_storefront();
  tvsf.toast_wrap.innerHTML = template_toast_tienda_storefront(tipo, mensaje);
  tvsf.toast_wrap.classList.add(`tv_toast_wrap_activo`);
  tvsf.temporizador_toast = window.setTimeout(function() {
    limpiar_toast_tienda_storefront();
  }, 3500);
}

function limpiar_toast_tienda_storefront() {
  if (!tvsf.toast_wrap) {
    return;
  }

  if (tvsf.temporizador_toast) {
    window.clearTimeout(tvsf.temporizador_toast);
    tvsf.temporizador_toast = null;
  }

  tvsf.toast_wrap.classList.remove(`tv_toast_wrap_activo`);
  tvsf.toast_wrap.innerHTML = ``;
}
