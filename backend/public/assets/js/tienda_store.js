let tstore = {};
// Datos generales
  tstore.token = document.getElementById(`token_tienda_publica`);
  tstore.controlador = document.getElementById(`controlador_tienda_carrito_publica`);
  tstore.carrito = {};
  tstore.toastTemporizador = null;
// Botones
  tstore.btnAbrirCarrito = document.getElementById(`btn_abrir_carrito_tienda_publica`);
  tstore.btnAbrirCarritoPagina = document.getElementById(`btn_abrir_carrito_pagina`);
  tstore.btnCerrarCarrito = document.getElementById(`btn_cerrar_carrito_tienda_publica`);
// Divs
  tstore.divBackdropCarrito = document.getElementById(`div_backdrop_carrito_tienda_publica`);
  tstore.asideCarrito = document.getElementById(`aside_carrito_tienda_publica`);
  tstore.divItemsCarrito = document.getElementById(`div_items_carrito_tienda_publica`);
  tstore.divResumenCarrito = document.getElementById(`div_resumen_carrito_tienda_publica`);
  tstore.pResumenCarrito = document.getElementById(`p_resumen_carrito_tienda_publica`);
  tstore.spanContadorCarrito = document.getElementById(`span_contador_carrito_tienda_publica`);
  tstore.divToast = document.getElementById(`div_toast_tienda_publica`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_tienda_store();
});

async function inicializar_tienda_store() {
  if (!tstore.token || !tstore.controlador || !tstore.asideCarrito) {
    return;
  }

  registrar_eventos_tienda_store();
  await refrescar_carrito_tienda_store(false);
}

function registrar_eventos_tienda_store() {
  if (tstore.btnAbrirCarrito) {
    tstore.btnAbrirCarrito.addEventListener(`click`, function() {
      alternar_carrito_tienda_store(true);
    });
  }

  if (tstore.btnAbrirCarritoPagina) {
    tstore.btnAbrirCarritoPagina.addEventListener(`click`, function() {
      alternar_carrito_tienda_store(true);
    });
  }

  if (tstore.btnCerrarCarrito) {
    tstore.btnCerrarCarrito.addEventListener(`click`, function() {
      alternar_carrito_tienda_store(false);
    });
  }

  if (tstore.divBackdropCarrito) {
    tstore.divBackdropCarrito.addEventListener(`click`, function() {
      alternar_carrito_tienda_store(false);
    });
  }

  document.querySelectorAll(`.tv_form_agregar_carrito`).forEach(function(formulario) {
    formulario.addEventListener(`submit`, async function(event) {
      event.preventDefault();
      await guardar_agregar_carrito_tienda_store(formulario);
    });
  });

  document.querySelectorAll(`.tv_form_actualizar_carrito`).forEach(function(formulario) {
    formulario.addEventListener(`submit`, async function(event) {
      event.preventDefault();
      const cantidad = formulario.querySelector(`[name="cantidad"]`).value;
      const slug = formulario.querySelector(`[name="slug"]`).value;
      await actualizar_linea_carrito_tienda_store(slug, cantidad);
    });
  });

  document.querySelectorAll(`.tv_form_eliminar_carrito`).forEach(function(formulario) {
    formulario.addEventListener(`submit`, async function(event) {
      event.preventDefault();
      const slug = formulario.querySelector(`[name="slug"]`).value;
      await eliminar_linea_carrito_tienda_store(slug);
    });
  });

  if (tstore.divItemsCarrito) {
    tstore.divItemsCarrito.addEventListener(`click`, async function(event) {
      const botonEliminar = event.target.closest(`[data-carrito-eliminar]`);
      const botonCantidad = event.target.closest(`[data-carrito-cantidad]`);

      if (botonEliminar) {
        await eliminar_linea_carrito_tienda_store(botonEliminar.dataset.carritoEliminar);
        return;
      }

      if (botonCantidad) {
        const slug = botonCantidad.dataset.slug;
        const accion = botonCantidad.dataset.carritoCantidad;
        const item = (tstore.carrito.items || []).find(function(registro) {
          return registro.slug === slug;
        });
        const cantidadActual = Number(item ? item.cantidad : 1);
        const nuevaCantidad = accion === `mas` ? cantidadActual + 1 : cantidadActual - 1;
        await actualizar_linea_carrito_tienda_store(slug, nuevaCantidad);
      }
    });
  }

  if (tstore.divToast) {
    tstore.divToast.addEventListener(`click`, function(event) {
      if (event.target.matches(`[data-toast-cerrar="true"]`)) {
        limpiar_alerta_tienda_store();
      }
    });
  }
}

function alternar_carrito_tienda_store(abrir) {
  if (!tstore.asideCarrito || !tstore.divBackdropCarrito) {
    return;
  }

  if (abrir === true) {
    tstore.asideCarrito.classList.add(`tv_drawer_carrito_abierto`);
    tstore.divBackdropCarrito.classList.remove(`tv_oculto`);
    tstore.asideCarrito.setAttribute(`aria-hidden`, `false`);
    return;
  }

  tstore.asideCarrito.classList.remove(`tv_drawer_carrito_abierto`);
  tstore.divBackdropCarrito.classList.add(`tv_oculto`);
  tstore.asideCarrito.setAttribute(`aria-hidden`, `true`);
}

async function refrescar_carrito_tienda_store(mostrarDrawer = false) {
  const token = tstore.token.value;
  const controlador = tstore.controlador.value;
  const petición = await listar_carrito_tienda_store_peticiones(token, controlador);

  if (petición.estado !== true) {
    return;
  }

  tstore.carrito = petición.datos.carrito || {};
  renderizar_carrito_tienda_store();

  if (mostrarDrawer === true) {
    alternar_carrito_tienda_store(true);
  }
}

function renderizar_carrito_tienda_store() {
  const carrito = tstore.carrito || {};
  const cantidad = Number(carrito.cantidad || 0);

  if (tstore.divItemsCarrito) {
    tstore.divItemsCarrito.innerHTML = template_items_carrito_tienda_store(carrito);
  }

  if (tstore.divResumenCarrito) {
    tstore.divResumenCarrito.innerHTML = template_resumen_carrito_tienda_store(carrito);
  }

  if (tstore.pResumenCarrito) {
    tstore.pResumenCarrito.textContent = `${cantidad} producto(s) agregado(s)`;
  }

  if (tstore.spanContadorCarrito) {
    tstore.spanContadorCarrito.textContent = cantidad;
    tstore.spanContadorCarrito.classList.toggle(`tv_contador_inline_vacio`, cantidad === 0);
  }
}

async function guardar_agregar_carrito_tienda_store(formulario) {
  const token = tstore.token.value;
  const controlador = tstore.controlador.value;
  const slug = formulario.querySelector(`[name="slug"]`).value;
  const cantidad = formulario.querySelector(`[name="cantidad"]`).value;
  const petición = await guardar_carrito_tienda_store_peticiones(token, controlador, {
    accion: `agregar`,
    slug: slug,
    cantidad: cantidad,
    redireccion: window.location.pathname
  });

  if (petición.estado !== true) {
    mostrar_alerta_tienda_store(`error`, petición.mensaje || `No fue posible agregar el producto.`);
    return;
  }

  tstore.carrito = petición.datos.carrito || {};
  renderizar_carrito_tienda_store();
  mostrar_alerta_tienda_store(`ok`, petición.mensaje || `Producto agregado al carrito.`);
  alternar_carrito_tienda_store(true);
}

async function actualizar_linea_carrito_tienda_store(slug, cantidad) {
  const token = tstore.token.value;
  const controlador = tstore.controlador.value;
  const petición = await guardar_carrito_tienda_store_peticiones(token, controlador, {
    accion: `actualizar`,
    slug: slug,
    cantidad: cantidad,
    redireccion: window.location.pathname
  });

  if (petición.estado !== true) {
    mostrar_alerta_tienda_store(`error`, petición.mensaje || `No fue posible actualizar el carrito.`);
    return;
  }

  tstore.carrito = petición.datos.carrito || {};
  renderizar_carrito_tienda_store();
  mostrar_alerta_tienda_store(`ok`, petición.mensaje || `Carrito actualizado correctamente.`);
}

async function eliminar_linea_carrito_tienda_store(slug) {
  const token = tstore.token.value;
  const controlador = tstore.controlador.value;
  const petición = await guardar_carrito_tienda_store_peticiones(token, controlador, {
    accion: `eliminar`,
    slug: slug,
    cantidad: 0,
    redireccion: window.location.pathname
  });

  if (petición.estado !== true) {
    mostrar_alerta_tienda_store(`error`, petición.mensaje || `No fue posible eliminar el producto.`);
    return;
  }

  tstore.carrito = petición.datos.carrito || {};
  renderizar_carrito_tienda_store();
  mostrar_alerta_tienda_store(`ok`, petición.mensaje || `Producto eliminado del carrito.`);
}

function mostrar_alerta_tienda_store(tipo, mensaje) {
  if (!tstore.divToast) {
    return;
  }

  limpiar_alerta_tienda_store();
  tstore.divToast.innerHTML = template_alerta_tienda_store(tipo, mensaje);

  tstore.toastTemporizador = setTimeout(function() {
    limpiar_alerta_tienda_store();
  }, 2600);
}

function limpiar_alerta_tienda_store() {
  if (!tstore.divToast) {
    return;
  }

  if (tstore.toastTemporizador) {
    clearTimeout(tstore.toastTemporizador);
    tstore.toastTemporizador = null;
  }

  tstore.divToast.innerHTML = ``;
}
