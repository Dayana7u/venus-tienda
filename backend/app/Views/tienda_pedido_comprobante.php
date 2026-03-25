<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$tema = $contexto['tema'] ?? [];
$pedido = $tv_datos['pedido'] ?? [];
$items = $tv_datos['items'] ?? [];
$pago = $tv_datos['pago'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];

$pedido_codigo = (string) ($pedido['codigo'] ?? '');
$titulo = $pedido_codigo !== '' ? 'Comprobante ' . $pedido_codigo : 'Comprobante de compra';

$telefono_soporte = $branding['telefono_contacto'] ?? '';
$mensaje_soporte = 'Hola, necesito apoyo con mi comprobante';
if ($pedido_codigo !== '') {
  $mensaje_soporte .= ' ' . $pedido_codigo;
}
$url_whatsapp_soporte = tienda_generar_url_whatsapp_soporte_publico((string) $telefono_soporte, $mensaje_soporte);

$subtotal = (float) ($pedido['subtotal'] ?? 0);
$descuento_total = (float) ($pedido['descuento_total'] ?? 0);
$envio_total = (float) ($pedido['envio_total'] ?? 0);
$total = (float) ($pedido['total'] ?? 0);

if ($subtotal <= 0 && count($items) > 0) {
  foreach ($items as $item) {
    $subtotal += ((float) ($item['precio'] ?? 0) * (int) ($item['cantidad'] ?? 0));
    $descuento_total += ((float) ($item['descuento'] ?? 0) * (int) ($item['cantidad'] ?? 0));
  }
}

if ($total <= 0) {
  $total = $subtotal - $descuento_total + $envio_total;
}

tienda_render_head($titulo, $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CHECKOUT', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo">
    <section class="tv_bloque tv_bloque_checkout_amplio tv_comprobante_bloque">
      <div class="tv_breadcrumb">Inicio / Comprobante</div>
      <div class="tv_bloque_encabezado tv_bloque_encabezado_inline tv_bloque_encabezado_comprobante">
        <div>
          <span class="tv_etiqueta">Comprobante</span>
          <h2><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
          <p>Vista lista para impresión y soporte comercial del pedido registrado en la tienda.</p>
        </div>
        <div class="tv_checkout_exito_botones tv_comprobante_acciones_top">
          <button type="button" class="tv_btn tv_btn_principal" onclick="window.print();">Imprimir</button>
          <a href="/catalogo/" class="tv_btn tv_btn_secundario">Seguir comprando</a>
        </div>
      </div>

      <article class="tv_checkout_panel tv_comprobante_panel">
        <div class="tv_comprobante_encabezado">
          <div>
            <span class="tv_etiqueta">Pedido</span>
            <h3><?php echo htmlspecialchars($pedido_codigo !== '' ? $pedido_codigo : 'Pendiente de generación', ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars((string) ($branding['nombre_comercial'] ?? 'Tienda Virtual'), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="tv_comprobante_estado">
            <article>
              <span>Estado pedido</span>
              <strong><?php echo htmlspecialchars(ucfirst((string) ($pedido['estado_pedido'] ?? 'pendiente')), ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
            <article>
              <span>Estado pago</span>
              <strong><?php echo htmlspecialchars(ucfirst((string) ($pedido['estado_pago'] ?? 'pendiente')), ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
            <article>
              <span>Fecha</span>
              <strong><?php echo htmlspecialchars((string) ($pedido['fecha_pedido'] ?? date('Y-m-d H:i:s')), ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
          </div>
        </div>

        <div class="tv_comprobante_grid">
          <article class="tv_comprobante_card">
            <span class="tv_etiqueta">Cliente</span>
            <h3><?php echo htmlspecialchars((string) ($pedido['cliente'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars((string) ($pedido['correo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            <p><?php echo htmlspecialchars((string) ($pedido['celular'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </article>
          <article class="tv_comprobante_card">
            <span class="tv_etiqueta">Entrega</span>
            <h3>Dirección registrada</h3>
            <p><?php echo nl2br(htmlspecialchars((string) ($pedido['direccion_resumen'] ?? 'Pendiente de resumen de entrega.'), ENT_QUOTES, 'UTF-8')); ?></p>
          </article>
          <article class="tv_comprobante_card">
            <span class="tv_etiqueta">Pago</span>
            <h3><?php echo htmlspecialchars(tienda_formatear_metodo_pago_publico((string) ($pedido['metodo_pago'] ?? '')), ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars((string) ($pago['entidad_pse'] ?? $pago['franquicia_tarjeta'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            <p><?php echo htmlspecialchars((string) ($pago['referencia_pasarela'] ?? $pago['codigo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </article>
        </div>

        <div class="tv_comprobante_tabla">
          <div class="tv_comprobante_tabla_head">
            <span>Producto</span>
            <span>Cantidad</span>
            <span>Valor unitario</span>
            <span>Total</span>
          </div>
          <?php if (count($items) > 0) { ?>
            <?php foreach ($items as $item) { ?>
              <article class="tv_comprobante_tabla_fila">
                <div>
                  <strong><?php echo htmlspecialchars((string) ($item['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                  <span><?php echo htmlspecialchars((string) ($item['codigo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <span><?php echo (int) ($item['cantidad'] ?? 0); ?></span>
                <span>$<?php echo number_format((float) ($item['precio'] ?? 0), 0, ',', '.'); ?></span>
                <strong>$<?php echo number_format((float) ($item['total_linea'] ?? $item['total'] ?? 0), 0, ',', '.'); ?></strong>
              </article>
            <?php } ?>
          <?php } else { ?>
            <article class="tv_comprobante_tabla_fila tv_comprobante_tabla_fila_vacia">
              <div><strong>No hay líneas registradas para mostrar.</strong></div>
              <span>-</span>
              <span>-</span>
              <strong>-</strong>
            </article>
          <?php } ?>
        </div>

        <div class="tv_comprobante_totales">
          <div><span>Subtotal</span><strong>$<?php echo number_format($subtotal, 0, ',', '.'); ?></strong></div>
          <div><span>Descuento</span><strong>$<?php echo number_format($descuento_total, 0, ',', '.'); ?></strong></div>
          <div><span>Envío</span><strong>$<?php echo number_format($envio_total, 0, ',', '.'); ?></strong></div>
          <div class="tv_comprobante_total_final"><span>Total</span><strong>$<?php echo number_format($total, 0, ',', '.'); ?></strong></div>
        </div>

        <?php if (($pedido['observacion'] ?? '') !== '') { ?>
          <div class="tv_checkout_alerta tv_comprobante_observacion">
            <?php echo htmlspecialchars((string) $pedido['observacion'], ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php } ?>

        <div class="tv_checkout_exito_botones tv_comprobante_acciones_bottom">
          <button type="button" class="tv_btn tv_btn_principal" onclick="window.print();">Imprimir comprobante</button>
          <?php if ($url_whatsapp_soporte !== '') { ?>
            <a href="<?php echo htmlspecialchars($url_whatsapp_soporte, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="tv_btn tv_btn_secundario">Solicitar soporte</a>
          <?php } else { ?>
            <a href="/contacto/?pedido=<?php echo urlencode($pedido_codigo); ?>" class="tv_btn tv_btn_secundario">Solicitar soporte</a>
          <?php } ?>
        </div>
      </article>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus, $tema); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
