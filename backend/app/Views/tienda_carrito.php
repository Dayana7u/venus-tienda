<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto    = $tv_datos['contexto'] ?? [];
$branding    = $contexto['branding'] ?? [];
$menus       = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$carrito     = $tv_datos['carrito'] ?? ['items' => [], 'subtotal' => 0, 'envio' => 0, 'total' => 0, 'ahorro' => 0];

tienda_render_head('Carrito', $tema_tokens, $componentes);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CARRITO'); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo">
    <section class="tv_bloque">
      <div class="tv_breadcrumb">Inicio / Carrito</div>
      <div class="tv_bloque_encabezado tv_bloque_encabezado_inline">
        <div>
          <span class="tv_etiqueta">Carrito</span>
          <h2>Revisa tu compra antes de continuar</h2>
          <p>Este bloque ya muestra subtotal, ahorro, envío y total final con un diseño más cercano a una tienda real.</p>
        </div>
        <a href="/catalogo/" class="tv_btn tv_btn_secundario">Seguir comprando</a>
      </div>
      <?php if (count($carrito['items']) === 0) { ?>
        <div class="tv_vacio">Todavía no has agregado productos. Ve al <a href="/catalogo/">catálogo</a>.</div>
      <?php } else { ?>
        <div class="tv_carrito_layout tv_carrito_layout_v2">
          <div class="tv_carrito_items tv_carrito_items_v2">
            <?php foreach ($carrito['items'] as $item) {
              $style = trim((string) ($item['imagen_url'] ?? '')) !== '' ? ' style="background-image: url(' . htmlspecialchars($item['imagen_url'], ENT_QUOTES, 'UTF-8') . ');"' : '';
            ?>
              <article class="tv_carrito_item tv_carrito_item_v2">
                <a href="/producto/?slug=<?php echo urlencode($item['slug']); ?>" class="tv_carrito_media tv_producto_media_<?php echo htmlspecialchars($item['media'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo $style; ?>></a>
                <div class="tv_carrito_info tv_carrito_info_v2">
                  <div class="tv_carrito_info_superior">
                    <span class="tv_etiqueta"><?php echo htmlspecialchars($item['etiqueta'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php if ((int) ($item['porcentaje_descuento'] ?? 0) > 0) { ?>
                      <span class="tv_descuento_chip">-<?php echo (int) $item['porcentaje_descuento']; ?>%</span>
                    <?php } ?>
                  </div>
                  <h3><?php echo htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?></h3>
                  <p><?php echo htmlspecialchars($item['resumen'], ENT_QUOTES, 'UTF-8'); ?></p>
                  <div class="tv_producto_meta tv_producto_meta_detalle tv_producto_meta_carrito">
                    <strong><?php echo tienda_formatear_precio($item['precio']); ?></strong>
                    <?php if ((int) $item['precio_anterior'] > (int) $item['precio']) { ?>
                      <span><?php echo tienda_formatear_precio($item['precio_anterior']); ?></span>
                    <?php } ?>
                  </div>
                  <div class="tv_carrito_cantidad_row">
                    <form action="/carrito/" method="post" class="js_form_carrito tv_form_carrito_inline">
                      <input type="hidden" name="accion" value="actualizar">
                      <input type="hidden" name="slug" value="<?php echo htmlspecialchars($item['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                      <input type="hidden" name="redireccion" value="/carrito/">
                      <button type="submit" name="cantidad" value="<?php echo max(0, (int) $item['cantidad'] - 1); ?>" class="tv_btn_cantidad">−</button>
                      <span class="tv_cantidad_actual"><?php echo (int) $item['cantidad']; ?></span>
                      <button type="submit" name="cantidad" value="<?php echo (int) $item['cantidad'] + 1; ?>" class="tv_btn_cantidad">+</button>
                    </form>
                    <form action="/carrito/" method="post" class="js_form_carrito">
                      <input type="hidden" name="accion" value="eliminar">
                      <input type="hidden" name="slug" value="<?php echo htmlspecialchars($item['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                      <input type="hidden" name="redireccion" value="/carrito/">
                      <button type="submit" class="tv_btn_eliminar_linea">Eliminar</button>
                    </form>
                  </div>
                </div>
                <div class="tv_carrito_totales_v2">
                  <span>Total línea</span>
                  <strong><?php echo tienda_formatear_precio($item['total']); ?></strong>
                  <?php if ((int) $item['ahorro_total'] > 0) { ?>
                    <small>Ahorras <?php echo tienda_formatear_precio($item['ahorro_total']); ?></small>
                  <?php } ?>
                </div>
              </article>
            <?php } ?>
          </div>
          <aside class="tv_resumen_compra tv_resumen_compra_v2">
            <h3>Resumen de compra</h3>
            <div class="tv_resumen_linea"><span>Subtotal</span><strong><?php echo tienda_formatear_precio($carrito['subtotal']); ?></strong></div>
            <?php if ((int) $carrito['ahorro'] > 0) { ?>
              <div class="tv_resumen_linea tv_resumen_linea_ahorro"><span>Ahorro aplicado</span><strong>−<?php echo tienda_formatear_precio($carrito['ahorro']); ?></strong></div>
            <?php } ?>
            <div class="tv_resumen_linea"><span>Envío</span><strong><?php echo tienda_formatear_precio($carrito['envio']); ?></strong></div>
            <div class="tv_resumen_total"><span>Total</span><strong><?php echo tienda_formatear_precio($carrito['total']); ?></strong></div>
            <p class="tv_resumen_ayuda">Para conocer el costo final del envío y cerrar pago, el siguiente paso será conectar pasarela y checkout.</p>
            <div class="tv_resumen_botones">
              <a href="/catalogo/" class="tv_btn tv_btn_secundario">Seguir comprando</a>
              <a href="/contacto/" class="tv_btn tv_btn_principal">Finalizar por asesoría</a>
            </div>
          </aside>
        </div>
      <?php } ?>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus); ?>
</body>
</html>
