<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto   = $tv_datos['contexto'] ?? [];
$branding   = $contexto['branding'] ?? [];
$menus      = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$carrito    = $tv_datos['carrito'] ?? ['items' => [], 'subtotal' => 0, 'envio' => 0, 'total' => 0];

tienda_render_head('Carrito', $tema_tokens, $componentes);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CARRITO'); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo">
    <section class="tv_bloque">
      <div class="tv_breadcrumb">Inicio / Carrito</div>
      <div class="tv_bloque_encabezado">
        <span class="tv_etiqueta">Carrito</span>
        <h2>Revisa tu compra</h2>
      </div>
      <?php if (count($carrito['items']) === 0) { ?>
        <div class="tv_vacio">Todavía no has agregado productos. Ve al <a href="/catalogo/">catálogo</a>.</div>
      <?php } else { ?>
        <div class="tv_carrito_layout">
          <div class="tv_carrito_items">
            <?php foreach ($carrito['items'] as $item) { ?>
              <article class="tv_carrito_item">
                <div class="tv_carrito_media tv_producto_media_<?php echo htmlspecialchars($item['media'], ENT_QUOTES, 'UTF-8'); ?>"></div>
                <div class="tv_carrito_info">
                  <h3><?php echo htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?></h3>
                  <p><?php echo htmlspecialchars($item['resumen'], ENT_QUOTES, 'UTF-8'); ?></p>
                  <strong>$<?php echo number_format((int) $item['precio'], 0, ',', '.'); ?></strong>
                </div>
                <div class="tv_carrito_acciones">
                  <form action="/carrito/" method="post" class="tv_form_carrito_linea">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars($item['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="redireccion" value="/carrito/">
                    <input type="number" name="cantidad" value="<?php echo (int) $item['cantidad']; ?>" min="0" max="10">
                    <button type="submit" class="tv_btn tv_btn_secundario">Actualizar</button>
                  </form>
                  <form action="/carrito/" method="post">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars($item['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="redireccion" value="/carrito/">
                    <button type="submit" class="tv_btn tv_btn_secundario">Eliminar</button>
                  </form>
                  <span class="tv_carrito_total_linea">$<?php echo number_format((int) $item['total'], 0, ',', '.'); ?></span>
                </div>
              </article>
            <?php } ?>
          </div>
          <aside class="tv_resumen_compra">
            <h3>Resumen</h3>
            <div><span>Subtotal</span><strong>$<?php echo number_format((int) $carrito['subtotal'], 0, ',', '.'); ?></strong></div>
            <div><span>Envío</span><strong>$<?php echo number_format((int) $carrito['envio'], 0, ',', '.'); ?></strong></div>
            <div class="tv_resumen_total"><span>Total</span><strong>$<?php echo number_format((int) $carrito['total'], 0, ',', '.'); ?></strong></div>
            <a href="/contacto/" class="tv_btn tv_btn_principal">Continuar por asesoría</a>
          </aside>
        </div>
      <?php } ?>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus); ?>
</body>
</html>
