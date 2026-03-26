<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$tema = $contexto['tema'] ?? [];
$productos = $tv_datos['productos'] ?? [];
$lineas = $tv_datos['lineas'] ?? [];
$filtros = $tv_datos['filtros'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];
$buscar = $filtros['buscar'] ?? '';
$linea_activa = $filtros['linea'] ?? '';

tienda_render_head('Catálogo', $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? [], $tema); ?>
  <?php tienda_render_header($branding, $menus, 'CATALOGO', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_page">
    <section class="tv_section tv_shell">
      <div class="tv_breadcrumb">Inicio / Catálogo</div>
      <div class="tv_section_head tv_section_head_catalog">
        <div>
          <span class="tv_etiqueta">Catálogo</span>
          <h1 class="tv_page_title">Explora VENUS por línea y producto</h1>
          <p>Un catálogo con filtros visibles, resultados claros y jerarquía editorial para navegar mejor en desktop y teléfono.</p>
        </div>
      </div>
      <div class="tv_catalog_layout">
        <aside class="tv_catalog_sidebar">
          <form method="get" action="/catalogo/" class="tv_catalog_filters">
            <div class="tv_filter_group">
              <label for="linea_catalogo">Línea principal</label>
              <select id="linea_catalogo" name="linea">
                <option value="">Todas las líneas</option>
                <?php foreach ($lineas as $codigo_linea => $linea) { ?>
                  <option value="<?php echo htmlspecialchars($codigo_linea, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $linea_activa === $codigo_linea ? ' selected' : ''; ?>><?php echo htmlspecialchars($linea['titulo'], ENT_QUOTES, 'UTF-8'); ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="tv_filter_group">
              <label for="buscar_catalogo">Buscar</label>
              <input id="buscar_catalogo" type="text" name="buscar" value="<?php echo htmlspecialchars($buscar, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Base, sérum, brochas...">
            </div>
            <button type="submit" class="tv_btn tv_btn_principal">Aplicar filtros</button>
            <a href="/catalogo/" class="tv_btn tv_btn_secundario">Limpiar</a>
          </form>
          <div class="tv_catalog_side_note">
            <span class="tv_etiqueta">Tip</span>
            <p>Usa la búsqueda para encontrar referencias puntuales y la línea para acotar la navegación.</p>
          </div>
        </aside>
        <div class="tv_catalog_content">
          <div class="tv_catalog_toolbar">
            <strong><?php echo count($productos); ?> resultado(s)</strong>
            <span><?php echo $linea_activa !== '' ? 'Filtro activo: ' . htmlspecialchars((string) ($lineas[$linea_activa]['titulo'] ?? $linea_activa), ENT_QUOTES, 'UTF-8') : 'Mostrando todo el catálogo'; ?></span>
          </div>
          <?php if (count($productos) > 0) { ?>
            <div class="tv_product_grid">
              <?php foreach ($productos as $producto) { tienda_render_producto_card($producto); } ?>
            </div>
          <?php } else { ?>
            <div class="tv_empty_state">No encontramos productos para el filtro aplicado.</div>
          <?php } ?>
        </div>
      </div>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus, $tema); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
