<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$producto = $tv_datos['producto'] ?? [];
$relacionados = $tv_datos['relacionados'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];
$imagenes = $producto['imagenes'] ?? [];
$precio = (int) ($producto['precio'] ?? 0);
$precio_anterior = (int) ($producto['precio_anterior'] ?? 0);

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
        <div class="tv_producto_detalle_store tv_producto_detalle_store_real">
          <article class="tv_producto_detalle_columna_media">
            <?php tienda_render_producto_media($producto, 'tv_producto_media_detalle', false); ?>
            <?php if (count($imagenes) > 1) { ?>
              <div class="tv_producto_galeria_miniaturas">
                <?php foreach ($imagenes as $imagen) { ?>
                  <div class="tv_producto_galeria_item">
                    <img src="<?php echo htmlspecialchars((string) ($imagen['imagen_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($imagen['texto_alternativo'] ?? $producto['nombre']), ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                  </div>
                <?php } ?>
              </div>
            <?php } ?>
          </article>
          <article class="tv_producto_detalle_info tv_producto_detalle_info_real">
            <span class="tv_etiqueta"><?php echo htmlspecialchars((string) ($producto['etiqueta'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
            <h1><?php echo htmlspecialchars((string) ($producto['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="tv_producto_detalle_resumen"><?php echo htmlspecialchars((string) ($producto['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="tv_producto_meta tv_producto_meta_detalle">
              <strong>$<?php echo number_format($precio, 0, ',', '.'); ?></strong>
              <?php if ($precio_anterior > $precio) { ?>
                <span>$<?php echo number_format($precio_anterior, 0, ',', '.'); ?></span>
              <?php } ?>
            </div>
            <div class="tv_producto_extra tv_producto_extra_detalle">
              <span>★ <?php echo number_format((float) ($producto['rating'] ?? 0), 1, ',', '.'); ?></span>
              <span><?php echo (int) ($producto['stock'] ?? 0) > 0 ? 'Disponible en stock' : 'Sin stock'; ?></span>
              <span>Línea <?php echo htmlspecialchars((string) ($producto['linea'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <ul class="tv_lista_beneficios">
              <?php foreach (($producto['beneficios'] ?? []) as $beneficio) { ?>
                <li><?php echo htmlspecialchars($beneficio, ENT_QUOTES, 'UTF-8'); ?></li>
              <?php } ?>
            </ul>
            <form action="/carrito/" method="post" class="tv_form_detalle tv_form_agregar_carrito">
              <input type="hidden" name="accion" value="agregar">
              <input type="hidden" name="slug" value="<?php echo htmlspecialchars((string) ($producto['slug'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
              <input type="hidden" name="redireccion" value="<?php echo htmlspecialchars((string) ($_SERVER['REQUEST_URI'] ?? '/producto/'), ENT_QUOTES, 'UTF-8'); ?>">
              <label for="cantidad_producto_detalle">Cantidad</label>
              <input type="number" id="cantidad_producto_detalle" name="cantidad" value="1" min="1" max="10">
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
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
