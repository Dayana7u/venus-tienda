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
  <?php tienda_render_topbar($contexto['modulo'] ?? [], $tema); ?>
  <?php tienda_render_header($branding, $menus, 'CARRITO', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_page">
    <section class="tv_section tv_shell">
      <div class="tv_breadcrumb">Inicio / Carrito</div>
      <div class="tv_section_head">
        <div>
          <span class="tv_etiqueta">Carrito</span>
          <h1 class="tv_page_title">Tu selección actual</h1>
        </div>
        <p>Una vista más clara para revisar cantidades, subtotales y el paso a checkout desde cualquier tamaño de pantalla.</p>
      </div>
      <?php if (count($carrito['items']) === 0) { ?>
        <div class="tv_empty_state">Todavía no has agregado productos. Ve al <a href="/catalogo/">catálogo</a>.</div>
      <?php } else { ?>
        <div class="tv_cart_layout">
          <div class="tv_cart_items">
            <?php foreach ($carrito['items'] as $item) { ?>
              <article class="tv_cart_item">
                <div class="tv_cart_item_media">
                  <?php tienda_render_producto_media($item, 'tv_producto_media_drawer', true); ?>
                </div>
                <div class="tv_cart_item_content">
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
                <div class="tv_cart_item_actions">
                  <form action="/carrito/" method="post" class="tv_cart_line_form tv_form_actualizar_carrito">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars((string) ($item['slug'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="redireccion" value="/carrito/">
                    <label>Cantidad</label>
                    <input type="number" name="cantidad" value="<?php echo (int) ($item['cantidad'] ?? 1); ?>" min="0" max="10">
                    <button type="submit" class="tv_btn tv_btn_secundario">Actualizar</button>
                  </form>
                  <form action="/carrito/" method="post" class="tv_form_eliminar_carrito">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars((string) ($item['slug'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="redireccion" value="/carrito/">
                    <button type="submit" class="tv_btn tv_btn_secundario">Eliminar</button>
                  </form>
                  <strong class="tv_cart_line_total">$<?php echo number_format((int) ($item['total'] ?? 0), 0, ',', '.'); ?></strong>
                </div>
              </article>
            <?php } ?>
          </div>
          <aside class="tv_cart_summary">
            <span class="tv_etiqueta">Resumen</span>
            <div class="tv_cart_summary_badges"><span>Pago seguro</span><span>Entrega nacional</span></div>
            <h3>Pedido actual</h3>
            <div class="tv_cart_summary_rows">
              <div><span>Subtotal</span><strong>$<?php echo number_format((int) ($carrito['subtotal'] ?? 0), 0, ',', '.'); ?></strong></div>
              <div><span>Ahorro</span><strong>$<?php echo number_format((int) ($carrito['ahorro'] ?? 0), 0, ',', '.'); ?></strong></div>
              <div><span>Envío</span><strong>$<?php echo number_format((int) ($carrito['envio'] ?? 0), 0, ',', '.'); ?></strong></div>
              <div class="tv_cart_summary_total"><span>Total</span><strong>$<?php echo number_format((int) ($carrito['total'] ?? 0), 0, ',', '.'); ?></strong></div>
            </div>
            <div class="tv_cart_summary_actions">
              <a href="/catalogo/" class="tv_btn tv_btn_secundario">Seguir comprando</a>
              <a href="/checkout/" class="tv_btn tv_btn_principal">Ir al checkout</a>
            </div>
            <button type="button" id="btn_abrir_carrito_pagina" class="tv_link_button">Abrir carrito lateral</button>
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
