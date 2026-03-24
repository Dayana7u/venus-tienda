function template_item_drawer_tienda_storefront(item) {
  const precioAnterior = Number(item.precio_anterior || 0);
  const precioActual = Number(item.precio || 0);
  const mediaClass = item.media ? `tv_producto_media_${item.media}` : `tv_producto_media_default`;
  const imageStyle = item.imagen_url ? `style="background-image: url(${item.imagen_url});"` : ``;
  const bloqueAnterior = precioAnterior > precioActual
    ? `<span class="tv_drawer_precio_anterior">${formatear_precio_tienda_storefront(precioAnterior)}</span>`
    : ``;

  return `
    <article class="tv_drawer_item">
      <a href="/producto/?slug=${item.slug}" class="tv_drawer_media ${mediaClass}" ${imageStyle}></a>
      <div class="tv_drawer_info">
        <a href="/producto/?slug=${item.slug}" class="tv_drawer_nombre">${item.nombre}</a>
        <div class="tv_drawer_precios">
          <strong>${formatear_precio_tienda_storefront(precioActual)}</strong>
          ${bloqueAnterior}
        </div>
        <div class="tv_drawer_cantidad">
          <button type="button" class="js_btn_cantidad_drawer" data-slug="${item.slug}" data-cantidad="${Math.max(0, Number(item.cantidad) - 1)}">−</button>
          <span>${item.cantidad}</span>
          <button type="button" class="js_btn_cantidad_drawer" data-slug="${item.slug}" data-cantidad="${Number(item.cantidad) + 1}">+</button>
          <button type="button" class="js_btn_eliminar_drawer" data-slug="${item.slug}">Eliminar</button>
        </div>
      </div>
      <div class="tv_drawer_total_linea">${formatear_precio_tienda_storefront(item.total)}</div>
    </article>
  `;
}

function template_resumen_drawer_tienda_storefront(carrito) {
  const ahorro = Number(carrito.ahorro || 0);
  const bloqueAhorro = ahorro > 0
    ? `
      <div class="tv_drawer_linea tv_drawer_linea_ahorro">
        <span>Ahorro</span>
        <strong>−${formatear_precio_tienda_storefront(ahorro)}</strong>
      </div>
    `
    : ``;

  return `
    <div class="tv_drawer_linea">
      <span>Subtotal</span>
      <strong>${formatear_precio_tienda_storefront(carrito.subtotal || 0)}</strong>
    </div>
    ${bloqueAhorro}
    <div class="tv_drawer_linea">
      <span>Envío</span>
      <strong>${formatear_precio_tienda_storefront(carrito.envio || 0)}</strong>
    </div>
    <div class="tv_drawer_linea tv_drawer_linea_total">
      <span>Total</span>
      <strong>${formatear_precio_tienda_storefront(carrito.total || 0)}</strong>
    </div>
    <div class="tv_drawer_botones">
      <a href="/carrito/" class="tv_btn tv_btn_secundario">Ver carrito</a>
      <a href="/contacto/" class="tv_btn tv_btn_principal">Finalizar compra</a>
    </div>
  `;
}

function template_vacio_drawer_tienda_storefront() {
  return `
    <div class="tv_drawer_vacio">
      <p>Tu carrito está vacío por ahora.</p>
      <a href="/catalogo/" class="tv_btn tv_btn_principal">Ir al catálogo</a>
    </div>
  `;
}

function template_toast_tienda_storefront(tipo, mensaje) {
  return `
    <div class="tv_toast tv_toast_${tipo}">
      <span>${mensaje}</span>
      <button type="button" data-cerrar-toast="true">×</button>
    </div>
  `;
}

function formatear_precio_tienda_storefront(valor) {
  return new Intl.NumberFormat(`es-CO`, {
    style: `currency`,
    currency: `COP`,
    maximumFractionDigits: 0,
  }).format(Number(valor || 0));
}
