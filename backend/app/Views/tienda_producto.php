<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto   = $tv_datos['contexto'] ?? [];
$branding   = $contexto['branding'] ?? [];
$menus      = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$producto   = $tv_datos['producto'] ?? [];
$relacionados = $tv_datos['relacionados'] ?? [];

tienda_render_head(empty($producto) ? 'Producto' : $producto['nombre'], $tema_tokens, $componentes);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CATALOGO'); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo">
    <section class="tv_bloque">
      <?php if (empty($producto)) { ?>
        <div class="tv_vacio">No se encontró el producto solicitado.</div>
      <?php } else { ?>
        <div class="tv_breadcrumb">Inicio / Catálogo / <?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="tv_producto_detalle_store">
          <article class="tv_producto_detalle_media tv_producto_media_<?php echo htmlspecialchars($producto['media'], ENT_QUOTES, 'UTF-8'); ?>"></article>
          <article class="tv_producto_detalle_info">
            <span class="tv_etiqueta"><?php echo htmlspecialchars($producto['etiqueta'], ENT_QUOTES, 'UTF-8'); ?></span>
            <h1><?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="tv_producto_detalle_resumen"><?php echo htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="tv_producto_meta tv_producto_meta_detalle">
              <strong>$<?php echo number_format((int) $producto['precio'], 0, ',', '.'); ?></strong>
              <?php if ((int) $producto['precio_anterior'] > (int) $producto['precio']) { ?>
                <span>$<?php echo number_format((int) $producto['precio_anterior'], 0, ',', '.'); ?></span>
              <?php } ?>
            </div>
            <ul class="tv_lista_beneficios">
              <?php foreach (($producto['beneficios'] ?? []) as $beneficio) { ?>
                <li><?php echo htmlspecialchars($beneficio, ENT_QUOTES, 'UTF-8'); ?></li>
              <?php } ?>
            </ul>
            <form action="/carrito/" method="post" class="tv_form_detalle">
              <input type="hidden" name="accion" value="agregar">
              <input type="hidden" name="slug" value="<?php echo htmlspecialchars($producto['slug'], ENT_QUOTES, 'UTF-8'); ?>">
              <input type="hidden" name="redireccion" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/producto/', ENT_QUOTES, 'UTF-8'); ?>">
              <label>Cantidad</label>
              <input type="number" name="cantidad" value="1" min="1" max="10">
              <button type="submit" class="tv_btn tv_btn_principal">Agregar al carrito</button>
            </form>
          </article>
        </div>
      <?php } ?>
    </section>

    <?php if (count($relacionados) > 0) { ?>
      <section class="tv_bloque tv_bloque_suave">
        <div class="tv_bloque_encabezado">
          <span class="tv_etiqueta">Relacionados</span>
          <h2>También podría interesarte</h2>
        </div>
        <div class="tv_grid_productos_store">
          <?php foreach ($relacionados as $item) { tienda_render_producto_card($item); } ?>
        </div>
      </section>
    <?php } ?>
  </main>

  <?php tienda_render_footer($branding, $menus); ?>
</body>
</html>
