<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$productos = $tv_datos['productos'] ?? [];
$lineas = $tv_datos['lineas'] ?? [];
$filtros = $tv_datos['filtros'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];
$buscar = $filtros['buscar'] ?? '';
$linea_activa = $filtros['linea'] ?? '';

tienda_render_head('Catálogo', $tema_tokens, $componentes);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CATALOGO'); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo">
    <section class="tv_bloque">
      <div class="tv_breadcrumb">Inicio / Catálogo</div>
      <div class="tv_bloque_encabezado tv_bloque_encabezado_inline">
        <div>
          <span class="tv_etiqueta">Catálogo</span>
          <h2>Explora la tienda por línea y producto</h2>
          <p>Vista separada para catálogo real, búsqueda y navegación por línea.</p>
        </div>
        <form method="get" action="/catalogo/" class="tv_filtros_catalogo">
          <select name="linea">
            <option value="">Todas las líneas</option>
            <?php foreach ($lineas as $codigo_linea => $linea) { ?>
              <option value="<?php echo htmlspecialchars($codigo_linea, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $linea_activa === $codigo_linea ? ' selected' : ''; ?>><?php echo htmlspecialchars($linea['titulo'], ENT_QUOTES, 'UTF-8'); ?></option>
            <?php } ?>
          </select>
          <input type="text" name="buscar" value="<?php echo htmlspecialchars($buscar, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Buscar maquillaje, serum, kit...">
          <button type="submit" class="tv_btn tv_btn_principal">Filtrar</button>
        </form>
      </div>
      <div class="tv_grid_productos_store">
        <?php if (count($productos) > 0) { ?>
          <?php foreach ($productos as $producto) { tienda_render_producto_card($producto); } ?>
        <?php } else { ?>
          <div class="tv_vacio">No hay productos para el filtro aplicado.</div>
        <?php } ?>
      </div>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
