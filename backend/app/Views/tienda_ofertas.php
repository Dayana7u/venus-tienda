<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$tema = $contexto['tema'] ?? [];
$productos = $tv_datos['productos'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];

tienda_render_head('Ofertas', $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? [], $tema); ?>
  <?php tienda_render_header($branding, $menus, 'OFERTAS', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_page">
    <section class="tv_section tv_shell">
      <div class="tv_breadcrumb">Inicio / Ofertas</div>
      <div class="tv_section_head">
        <div>
          <span class="tv_etiqueta">Ofertas</span>
          <h1 class="tv_page_title">Promociones y selecciones especiales</h1>
        </div>
        <p>Un escaparate de descuentos y campañas con la misma estética renovada del resto del ecommerce.</p>
      </div>
      <?php if (count($productos) > 0) { ?>
        <div class="tv_product_grid">
          <?php foreach ($productos as $producto) { tienda_render_producto_card($producto); } ?>
        </div>
      <?php } else { ?>
        <div class="tv_empty_state">No hay ofertas activas para mostrar en este momento.</div>
      <?php } ?>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus, $tema); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
