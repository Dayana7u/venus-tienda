function template_items_carrito_tienda_store(carrito) {
  const items = carrito.items || [];

  if (items.length === 0) {
    return `
      <div class="tv_drawer_vacio">
        <p>Tu carrito está vacío.</p>
        <a href="/catalogo/" class="tv_btn tv_btn_principal">Ir al catálogo</a>
      </div>`;
  }

  return items.map(function(item) {
    const precio = Number(item.precio || 0).toLocaleString(`es-CO`);
    const precioAnterior = Number(item.precio_anterior || 0).toLocaleString(`es-CO`);
    const total = Number(item.total || 0).toLocaleString(`es-CO`);
    const imagenUrl = item.imagen_url || ``;
    const alt = item.texto_alternativo || item.nombre || `Producto`;
    const descuento = Number(item.descuento_porcentaje || 0);

    return `
      <article class="tv_drawer_item">
        <div class="tv_drawer_item_media">
          ${imagenUrl !== ``
            ? `<img src="${imagenUrl}" alt="${alt}" class="tv_drawer_item_img">`
            : `<div class="tv_drawer_item_placeholder">${(item.nombre || `T`).charAt(0)}</div>`}
        </div>
        <div class="tv_drawer_item_info">
          <div class="tv_drawer_item_superior">
            <div>
              <h4>${item.nombre || ``}</h4>
              <span class="tv_drawer_item_etiqueta">${item.etiqueta || item.categoria_nombre || ``}</span>
            </div>
            ${descuento > 0 ? `<span class="tv_drawer_item_badge">-${descuento}%</span>` : ``}
          </div>
          <div class="tv_drawer_item_precios">
            <strong>$ ${precio}</strong>
            ${(item.precio_anterior || 0) > (item.precio || 0) ? `<span>$ ${precioAnterior}</span>` : ``}
          </div>
          <div class="tv_drawer_item_acciones">
            <div class="tv_drawer_cantidad" data-slug="${item.slug}">
              <button type="button" data-carrito-cantidad="menos" data-slug="${item.slug}">−</button>
              <span>${item.cantidad || 1}</span>
              <button type="button" data-carrito-cantidad="mas" data-slug="${item.slug}">+</button>
            </div>
            <button type="button" class="tv_drawer_eliminar" data-carrito-eliminar="${item.slug}">Eliminar</button>
          </div>
          <div class="tv_drawer_item_total">$ ${total}</div>
        </div>
      </article>`;
  }).join(``);
}

function template_resumen_carrito_tienda_store(carrito) {
  const subtotal = Number(carrito.subtotal || 0).toLocaleString(`es-CO`);
  const ahorro = Number(carrito.ahorro || 0).toLocaleString(`es-CO`);
  const envio = Number(carrito.envio || 0).toLocaleString(`es-CO`);
  const total = Number(carrito.total || 0).toLocaleString(`es-CO`);

  return `
    <div class="tv_drawer_resumen_beneficio">Compra segura · cálculo inicial de envío visible</div>
    <div class="tv_drawer_totales">
      <div><span>Subtotal</span><strong>$ ${subtotal}</strong></div>
      <div><span>Ahorro</span><strong>$ ${ahorro}</strong></div>
      <div><span>Envío</span><strong>$ ${envio}</strong></div>
      <div class="tv_drawer_total_general"><span>Total</span><strong>$ ${total}</strong></div>
    </div>
    <p class="tv_drawer_nota">Puedes seguir comprando o entrar al carrito completo para revisar cantidades, descuentos y resumen final.</p>
    <div class="tv_drawer_botones">
      <a href="/carrito/" class="tv_btn tv_btn_secundario">Ver carrito</a>
      <button type="button" class="tv_btn tv_btn_principal">Finalizar compra</button>
    </div>`;
}

function template_alerta_tienda_store(tipo, mensaje) {
  return `
    <div class="tv_toast tv_toast_${tipo}">
      <span>${mensaje}</span>
      <button type="button" data-toast-cerrar="true">×</button>
    </div>`;
}
