<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$tema = $contexto['tema'] ?? [];
$producto = $tv_datos['producto'] ?? [];
$relacionados = $tv_datos['relacionados'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];
$imagenes = $producto['imagenes'] ?? [];
$precio = (int) ($producto['precio'] ?? 0);
$precio_anterior = (int) ($producto['precio_anterior'] ?? 0);

$variantes_demo = ['Clásico', 'Glow', 'Soft'];

tienda_render_head(empty($producto) ? 'Producto' : $producto['nombre'], $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? [], $tema); ?>
  <?php tienda_render_header($branding, $menus, 'CATALOGO', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_page">
    <section class="tv_section tv_shell">
      <?php if (empty($producto)) { ?>
        <div class="tv_empty_state">No se encontró el producto solicitado.</div>
      <?php } else { ?>
        <div class="tv_breadcrumb">Inicio / Catálogo / <?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="tv_product_layout">
          <article class="tv_product_gallery">
            <div class="tv_product_gallery_main">
              <?php tienda_render_producto_media($producto, 'tv_producto_media_detalle', false); ?>
            </div>
            <?php if (count($imagenes) > 1) { ?>
              <div class="tv_product_gallery_strip">
                <?php foreach ($imagenes as $imagen) { ?>
                  <div class="tv_product_gallery_thumb">
                    <img src="<?php echo htmlspecialchars((string) ($imagen['imagen_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($imagen['texto_alternativo'] ?? $producto['nombre']), ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                  </div>
                <?php } ?>
              </div>
            <?php } ?>
          </article>

          <article class="tv_product_summary">
            <span class="tv_etiqueta"><?php echo htmlspecialchars((string) ($producto['etiqueta'] ?? 'Producto'), ENT_QUOTES, 'UTF-8'); ?></span>
            <h1><?php echo htmlspecialchars((string) ($producto['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="tv_product_description"><?php echo htmlspecialchars((string) ($producto['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="tv_product_price">
              <strong>$<?php echo number_format($precio, 0, ',', '.'); ?></strong>
              <?php if ($precio_anterior > $precio) { ?>
                <span>$<?php echo number_format($precio_anterior, 0, ',', '.'); ?></span>
              <?php } ?>
            </div>
            <div class="tv_product_meta_row">
              <span class="tv_rating">★★★★★ <b><?php echo number_format((float) ($producto['rating'] ?? 0), 1, ',', '.'); ?></b></span>
              <span class="tv_stock"><?php echo (int) ($producto['stock'] ?? 0) > 0 ? 'Disponible' : 'Sin stock'; ?></span>
              <span class="tv_stock">Línea <?php echo htmlspecialchars((string) ($producto['linea'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
            </div>

            <div class="tv_product_trust">
              <span>Pago seguro</span>
              <span>Despacho nacional</span>
              <span>Compra fácil</span>
            </div>

            <div class="tv_variant_group">
              <span>Acabado</span>
              <div class="tv_variant_chips">
                <?php foreach ($variantes_demo as $indice => $variante) { ?>
                  <button type="button" class="tv_chip<?php echo $indice === 0 ? ' tv_chip_active' : ''; ?>"><?php echo htmlspecialchars($variante, ENT_QUOTES, 'UTF-8'); ?></button>
                <?php } ?>
              </div>
            </div>

            <?php if (count($producto['beneficios'] ?? []) > 0) { ?>
              <ul class="tv_benefit_list">
                <?php foreach (($producto['beneficios'] ?? []) as $beneficio) { ?>
                  <li><?php echo htmlspecialchars($beneficio, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php } ?>
              </ul>
            <?php } ?>

            <form action="/carrito/" method="post" class="tv_product_buy_box tv_form_agregar_carrito">
              <input type="hidden" name="accion" value="agregar">
              <input type="hidden" name="slug" value="<?php echo htmlspecialchars((string) ($producto['slug'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
              <input type="hidden" name="redireccion" value="<?php echo htmlspecialchars((string) ($_SERVER['REQUEST_URI'] ?? '/producto/'), ENT_QUOTES, 'UTF-8'); ?>">
              <label for="cantidad_producto_detalle">Cantidad</label>
              <input type="number" id="cantidad_producto_detalle" name="cantidad" value="1" min="1" max="10">
              <button type="submit" class="tv_btn tv_btn_principal">Agregar al carrito</button>
              <a href="/catalogo/" class="tv_btn tv_btn_secundario">Seguir explorando</a>
            </form>
          </article>
        </div>
      <?php } ?>
    </section>

    <?php if (count($relacionados) > 0) { ?>
      <section class="tv_section tv_shell">
        <div class="tv_section_head">
          <div>
            <span class="tv_etiqueta">Relacionados</span>
            <h2>También te puede gustar</h2>
          </div>
          <p>Más referencias con la misma línea visual y una presentación más uniforme dentro del flujo público.</p>
        </div>
        <div class="tv_product_grid">
          <?php foreach ($relacionados as $item) { tienda_render_producto_card($item); } ?>
        </div>
      </section>
    <?php } ?>
  </main>

  <?php tienda_render_footer($branding, $menus, $tema); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
