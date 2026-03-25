<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$tema = $contexto['tema'] ?? [];
$carrito = $tv_datos['carrito'] ?? ['items' => [], 'subtotal' => 0, 'envio' => 0, 'total' => 0, 'ahorro' => 0];

tienda_render_head('Carrito', $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CARRITO', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo">
    <section class="tv_bloque">
      <div class="tv_breadcrumb">Inicio / Carrito</div>
      <div class="tv_bloque_encabezado tv_bloque_encabezado_inline">
        <div>
          <span class="tv_etiqueta">Carrito</span>
          <h2>Revisa tu compra</h2>
          <p>Vista dedicada para el paso previo al checkout, manteniendo también el carrito lateral en toda la tienda.</p>
        </div>
        <button type="button" id="btn_abrir_carrito_pagina" class="tv_btn tv_btn_secundario">Abrir carrito lateral</button>
      </div>
      <?php if (count($carrito['items']) === 0) { ?>
        <div class="tv_vacio">Todavía no has agregado productos. Ve al <a href="/catalogo/">catálogo</a>.</div>
      <?php } else { ?>
        <div class="tv_carrito_layout tv_carrito_layout_real">
          <div class="tv_carrito_items tv_carrito_items_real">
            <?php foreach ($carrito['items'] as $item) { ?>
              <article class="tv_carrito_item tv_carrito_item_real">
                <div class="tv_carrito_item_media_wrap">
                  <?php tienda_render_producto_media($item, 'tv_producto_media_drawer', true); ?>
                </div>
                <div class="tv_carrito_info tv_carrito_info_real">
                  <span class="tv_etiqueta"><?php echo htmlspecialchars((string) ($item['etiqueta'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                  <h3><?php echo htmlspecialchars((string) ($item['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
                  <p><?php echo htmlspecialchars((string) ($item['resumen'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                  <div class="tv_producto_meta">
                    <strong>$<?php echo number_format((int) ($item['precio'] ?? 0), 0, ',', '.'); ?></strong>
                    <?php if ((int) ($item['precio_anterior'] ?? 0) > (int) ($item['precio'] ?? 0)) { ?>
                      <span>$<?php echo number_format((int) ($item['precio_anterior'] ?? 0), 0, ',', '.'); ?></span>
                    <?php } ?>
                  </div>
                </div>
                <div class="tv_carrito_acciones tv_carrito_acciones_real">
                  <form action="/carrito/" method="post" class="tv_form_carrito_linea tv_form_actualizar_carrito">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars((string) ($item['slug'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="redireccion" value="/carrito/">
                    <input type="number" name="cantidad" value="<?php echo (int) ($item['cantidad'] ?? 1); ?>" min="0" max="10">
                    <button type="submit" class="tv_btn tv_btn_secundario">Actualizar</button>
                  </form>
                  <form action="/carrito/" method="post" class="tv_form_eliminar_carrito">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars((string) ($item['slug'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="redireccion" value="/carrito/">
                    <button type="submit" class="tv_btn tv_btn_secundario">Eliminar</button>
                  </form>
                  <span class="tv_carrito_total_linea">$<?php echo number_format((int) ($item['total'] ?? 0), 0, ',', '.'); ?></span>
                </div>
              </article>
            <?php } ?>
          </div>
          <aside class="tv_resumen_compra tv_resumen_compra_real">
            <h3>Resumen</h3>
            <div><span>Subtotal</span><strong>$<?php echo number_format((int) ($carrito['subtotal'] ?? 0), 0, ',', '.'); ?></strong></div>
            <div><span>Ahorro</span><strong>$<?php echo number_format((int) ($carrito['ahorro'] ?? 0), 0, ',', '.'); ?></strong></div>
            <div><span>Envío</span><strong>$<?php echo number_format((int) ($carrito['envio'] ?? 0), 0, ',', '.'); ?></strong></div>
            <div class="tv_resumen_total"><span>Total</span><strong>$<?php echo number_format((int) ($carrito['total'] ?? 0), 0, ',', '.'); ?></strong></div>
            <a href="/contacto/" class="tv_btn tv_btn_secundario">Continuar por asesoría</a>
            <a href="/checkout/" class="tv_btn tv_btn_principal">Finalizar compra</a>
          </aside>
        </div>
      <?php } ?>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus, $tema); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
