<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$productos = $tv_datos['productos'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];

tienda_render_head('Ofertas', $tema_tokens, $componentes, $contexto['tema'] ?? []);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'OFERTAS'); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo">
    <section class="tv_bloque">
      <div class="tv_breadcrumb">Inicio / Ofertas</div>
      <div class="tv_bloque_encabezado">
        <span class="tv_etiqueta">Ofertas</span>
        <h2>Campañas y descuentos visibles por separado</h2>
        <p>Vista dedicada para promociones activas en maquillaje, skincare y accesorios.</p>
      </div>
      <div class="tv_grid_productos_store">
        <?php foreach ($productos as $producto) { tienda_render_producto_card($producto); } ?>
      </div>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
