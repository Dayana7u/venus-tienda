function template_tienda_admin_alerta(tipo, mensaje) {
  return `
    <div class="tda_admin_alerta ${tipo}">
      <span>${mensaje}</span>
      <button type="button" data-alerta-cerrar="true">×</button>
    </div>`;
}

function template_tienda_admin_metricas_dashboard(resumen, ventas) {
  return `
    <div class="tda_admin_metricas_grid">
      ${template_tienda_admin_metric_card(`◔`, `Ventas del día`, formatear_moneda_tienda_admin(ventas.total_ingresos || 0), `Ingresos comerciales registrados.`)}
      ${template_tienda_admin_metric_card(`◫`, `Productos activos`, resumen.productos || 0, `Catálogo visible para el frente público.`)}
      ${template_tienda_admin_metric_card(`☺`, `Clientes`, resumen.clientes || 0, `Base de compradores y registros.`)}
      ${template_tienda_admin_metric_card(`↺`, `Pedidos`, resumen.pedidos || 0, `Pedidos totales y seguimiento.`)}
    </div>`;
}

function template_tienda_admin_metric_card(icono, titulo, total, detalle) {
  return `
    <article class="tda_admin_metric_card">
      <div class="tda_admin_metric_icono">${icono}</div>
      <div>
        <strong>${total}</strong>
        <h4>${titulo}</h4>
        <p>${detalle}</p>
      </div>
    </article>`;
}

function template_tienda_admin_paneles_dashboard(resumen, ventas) {
  const pedidosTotales = Number(resumen.pedidos || 0);
  const pedidosPagados = Number(ventas.pedidos_pagados || 0);
  const porcentaje = pedidosTotales > 0 ? Math.min(100, Math.round((pedidosPagados * 100) / pedidosTotales)) : 0;

  return `
    <div class="tda_admin_paneles_dashboard">
      <article class="tda_admin_panel">
        <div class="tda_admin_panel_titulo">
          <h4>Movimiento comercial</h4>
          <span class="tda_admin_etiqueta">Beauty store</span>
        </div>
        <p class="tda_admin_panel_subtitulo">Lectura rápida del comportamiento general de ventas, pedidos y descuentos sin salir del dashboard principal.</p>
        <div class="tda_admin_chart">
          <div class="tda_admin_chart_labels">
            <span>08am</span>
            <span>10am</span>
            <span>12pm</span>
            <span>02pm</span>
            <span>04pm</span>
            <span>06pm</span>
            <span>08pm</span>
          </div>
        </div>
      </article>

      <article class="tda_admin_panel">
        <div class="tda_admin_panel_titulo">
          <h4>Estado de operación</h4>
          <span class="tda_admin_etiqueta">Resumen</span>
        </div>
        <p class="tda_admin_panel_subtitulo">Porcentaje estimado de pedidos pagados sobre el total de pedidos activos visibles en la tienda.</p>
        <div class="tda_admin_analitica_resumen">
          <div class="tda_admin_grafico_donut" style="--porcentaje:${porcentaje};">
            <div class="tda_admin_grafico_donut_contenido">
              <strong>${porcentaje}%</strong>
              <span>Pedidos pagados</span>
            </div>
          </div>
        </div>
        <div class="tda_admin_legend">
          <div class="tda_admin_legend_item">
            <div>
              <span class="tda_admin_legend_punto ventas"></span>
              <span>Total ingresos</span>
            </div>
            <strong>${formatear_moneda_tienda_admin(ventas.total_ingresos || 0)}</strong>
          </div>
          <div class="tda_admin_legend_item">
            <div>
              <span class="tda_admin_legend_punto descuentos"></span>
              <span>Descuentos aplicados</span>
            </div>
            <strong>${formatear_moneda_tienda_admin(ventas.total_descuentos || 0)}</strong>
          </div>
          <div class="tda_admin_legend_item">
            <div>
              <span class="tda_admin_legend_punto pedidos"></span>
              <span>Pedidos pagados</span>
            </div>
            <strong>${pedidosPagados}</strong>
          </div>
        </div>
      </article>
    </div>`;
}

function template_tienda_admin_resumen(resumen) {
  return `
    <div class="tda_admin_resumen_grid">
      ${template_tienda_admin_resumen_card(`Catálogo`, `Categorías`, resumen.categorias || 0, `Segmentos visibles para maquillaje, skincare y accesorios.`)}
      ${template_tienda_admin_resumen_card(`Catálogo`, `Productos`, resumen.productos || 0, `Referencias activas registradas en el catálogo.`)}
      ${template_tienda_admin_resumen_card(`Visual`, `Imágenes`, resumen.imagenes || 0, `Material visual cargado para portada y detalle.`)}
      ${template_tienda_admin_resumen_card(`Clientes`, `Clientes`, resumen.clientes || 0, `Base comercial acumulada.`)}
      ${template_tienda_admin_resumen_card(`Operación`, `Pedidos`, resumen.pedidos || 0, `Pedidos creados para seguimiento.`)}
      ${template_tienda_admin_resumen_card(`Operación`, `Pendientes`, resumen.pedidos_pendientes || 0, `Pedidos pendientes de cierre.`)}
    </div>`;
}

function template_tienda_admin_resumen_card(etiqueta, titulo, total, detalle) {
  return `
    <article class="tda_admin_resumen_card">
      <span class="tda_admin_etiqueta">${etiqueta}</span>
      <h4>${titulo}</h4>
      <strong>${total}</strong>
      <p>${detalle}</p>
    </article>`;
}

function template_tienda_admin_resumen_ventas(ventas) {
  return `
    <div class="tda_admin_resumen_grid tda_admin_resumen_grid_ventas">
      <article class="tda_admin_resumen_card tda_admin_resumen_card_destacado">
        <span class="tda_admin_etiqueta">Ventas</span>
        <h4>Total ingresos</h4>
        <strong>${formatear_moneda_tienda_admin(ventas.total_ingresos || 0)}</strong>
        <p>Pedidos pagados y activos registrados en la tienda.</p>
      </article>
      <article class="tda_admin_resumen_card">
        <span class="tda_admin_etiqueta">Ventas</span>
        <h4>Ticket promedio</h4>
        <strong>${formatear_moneda_tienda_admin(ventas.ticket_promedio || 0)}</strong>
        <p>Promedio por pedido confirmado.</p>
      </article>
      <article class="tda_admin_resumen_card">
        <span class="tda_admin_etiqueta">Ventas</span>
        <h4>Descuentos</h4>
        <strong>${formatear_moneda_tienda_admin(ventas.total_descuentos || 0)}</strong>
        <p>Ahorro acumulado visible en la operación.</p>
      </article>
      <article class="tda_admin_resumen_card">
        <span class="tda_admin_etiqueta">Operación</span>
        <h4>Pagados</h4>
        <strong>${ventas.pedidos_pagados || 0}</strong>
        <p>Pedidos con pago confirmado.</p>
      </article>
    </div>`;
}

function template_tienda_admin_categorias(categorias) {
  if (!categorias.length) {
    return `<p>No hay categorías registradas.</p>`;
  }

  return `<div class="tda_admin_listado_grid">${categorias.map(function(categoria) {
    return `
      <article class="tda_admin_card_categoria">
        ${categoria.imagen_url ? `<div class="tda_admin_media"><img src="${categoria.imagen_url}" alt="${categoria.texto_alternativo || categoria.nombre}"></div>` : `<div class="tda_admin_media"></div>`}
        <span class="tda_admin_etiqueta">${categoria.linea}</span>
        <h5>${categoria.nombre}</h5>
        <div class="tda_admin_card_lista_datos">
          <span><strong>Código:</strong> ${categoria.codigo}</span>
          <span><strong>Slug:</strong> ${categoria.slug}</span>
          <span><strong>Orden:</strong> ${categoria.orden}</span>
        </div>
        <p>${categoria.descripcion || `Sin descripción.`}</p>
      </article>`;
  }).join(``)}</div>`;
}

function template_tienda_admin_productos(productos) {
  if (!productos.length) {
    return `<p>No hay productos registrados.</p>`;
  }

  return `<div class="tda_admin_listado_grid">${productos.map(function(producto) {
    return `
      <article class="tda_admin_card_producto">
        ${producto.imagen_url ? `<div class="tda_admin_media"><img src="${producto.imagen_url}" alt="${producto.texto_alternativo || producto.nombre}"></div>` : `<div class="tda_admin_media"></div>`}
        <span class="tda_admin_etiqueta">${producto.categoria_nombre}</span>
        <h5>${producto.nombre}</h5>
        <div class="tda_admin_card_lista_datos">
          <span><strong>Código:</strong> ${producto.codigo}</span>
          <span><strong>Etiqueta:</strong> ${producto.etiqueta || `Sin etiqueta`}</span>
          <span><strong>Base:</strong> ${formatear_moneda_tienda_admin(producto.precio_base)}</span>
          <span><strong>Oferta:</strong> ${formatear_moneda_tienda_admin(producto.precio_oferta)}</span>
          <span><strong>Stock:</strong> ${producto.stock}</span>
          <span><strong>Rating:</strong> ${producto.rating_promedio}</span>
        </div>
        <p>${producto.resumen || `Sin resumen.`}</p>
      </article>`;
  }).join(``)}</div>`;
}

function template_tienda_admin_imagenes(imagenes) {
  if (!imagenes.length) {
    return `<p>No hay imágenes registradas.</p>`;
  }

  return `<div class="tda_admin_listado_grid">${imagenes.map(function(imagen) {
    return `
      <article class="tda_admin_card_imagen">
        ${imagen.imagen_url ? `<div class="tda_admin_media"><img src="${imagen.imagen_url}" alt="${imagen.texto_alternativo || imagen.producto_nombre}"></div>` : `<div class="tda_admin_media"></div>`}
        <span class="tda_admin_etiqueta">Imagen</span>
        <h5>${imagen.producto_nombre}</h5>
        <div class="tda_admin_card_lista_datos">
          <span><strong>ID producto:</strong> ${imagen.producto_id}</span>
          <span class="tda_admin_card_imagen_url"><strong>URL:</strong> ${imagen.imagen_url}</span>
        </div>
        <p>${imagen.texto_alternativo || `Sin texto alternativo.`}</p>
      </article>`;
  }).join(``)}</div>`;
}

function template_tienda_admin_clientes(clientes) {
  if (!clientes.length) {
    return `<p>No hay clientes registrados.</p>`;
  }

  return `<div class="tda_admin_listado_stack">${clientes.map(function(cliente) {
    return `
      <article class="tda_admin_card_fila">
        <div>
          <span class="tda_admin_etiqueta">Cliente</span>
          <h5>${cliente.nombre_completo}</h5>
          <p>${cliente.correo}</p>
        </div>
        <div class="tda_admin_card_lista_datos tda_admin_card_lista_datos_inline">
          <span><strong>Teléfono:</strong> ${cliente.celular || `Sin dato`}</span>
          <span><strong>Ciudad:</strong> ${cliente.ciudad || `Sin ciudad`}</span>
          <span><strong>Pedidos:</strong> ${cliente.total_pedidos || 0}</span>
          <span><strong>Total:</strong> ${formatear_moneda_tienda_admin(cliente.total_compras || 0)}</span>
        </div>
      </article>`;
  }).join(``)}</div>`;
}

function template_tienda_admin_pedidos(pedidos) {
  if (!pedidos.length) {
    return `<p>No hay pedidos registrados.</p>`;
  }

  return `<div class="tda_admin_listado_stack">${pedidos.map(function(pedido) {
    return `
      <article class="tda_admin_card_fila">
        <div>
          <span class="tda_admin_etiqueta">${pedido.estado_pedido}</span>
          <h5>${pedido.codigo}</h5>
          <p>${pedido.cliente_nombre_completo || `Cliente no disponible`} · ${pedido.fecha_pedido_texto || ``}</p>
        </div>
        <div class="tda_admin_card_lista_datos tda_admin_card_lista_datos_inline">
          <span><strong>Pago:</strong> ${pedido.estado_pago}</span>
          <span><strong>Items:</strong> ${pedido.cantidad_items}</span>
          <span><strong>Método:</strong> ${pedido.metodo_pago || `Sin definir`}</span>
          <span><strong>Total:</strong> ${formatear_moneda_tienda_admin(pedido.total || 0)}</span>
        </div>
      </article>`;
  }).join(``)}</div>`;
}

function template_tienda_admin_productos_top(productos_top) {
  if (!productos_top.length) {
    return `<p>No hay ventas suficientes para calcular productos top.</p>`;
  }

  return `<div class="tda_admin_listado_stack">${productos_top.map(function(producto, indice) {
    return `
      <article class="tda_admin_card_fila tda_admin_card_fila_top">
        <div class="tda_admin_top_posicion">${indice + 1}</div>
        <div>
          <span class="tda_admin_etiqueta">Top ventas</span>
          <h5>${producto.producto_nombre}</h5>
          <p>${producto.producto_codigo || `Sin código`} · ${producto.producto_slug || `Sin slug`}</p>
        </div>
        <div class="tda_admin_card_lista_datos tda_admin_card_lista_datos_inline">
          <span><strong>Unidades:</strong> ${producto.unidades_vendidas || 0}</span>
          <span><strong>Ingresos:</strong> ${formatear_moneda_tienda_admin(producto.total_vendido || 0)}</span>
        </div>
      </article>`;
  }).join(``)}</div>`;
}

function template_tienda_admin_select_categorias(categorias) {
  const opciones = categorias.map(function(categoria) {
    return `<option value="${categoria.categoria_id}">${categoria.nombre}</option>`;
  }).join(``);

  return `<option value="">Seleccione</option>${opciones}`;
}

function template_tienda_admin_select_productos(productos) {
  const opciones = productos.map(function(producto) {
    return `<option value="${producto.producto_id}">${producto.nombre}</option>`;
  }).join(``);

  return `<option value="">Seleccione</option>${opciones}`;
}

function formatear_moneda_tienda_admin(valor) {
  const numero = Number(valor || 0);
  return new Intl.NumberFormat(`es-CO`, {
    style                 : `currency`,
    currency              : `COP`,
    minimumFractionDigits : 0,
    maximumFractionDigits : 0
  }).format(numero);
}
