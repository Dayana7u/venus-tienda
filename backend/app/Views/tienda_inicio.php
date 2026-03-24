<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$destacados = $tv_datos['destacados'] ?? [];
$ofertas = $tv_datos['ofertas'] ?? [];
$lineas = $tv_datos['lineas'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];

$producto_hero = $destacados[0] ?? [];
$producto_secundario = $ofertas[0] ?? [];

$items_linea = [
  'maquillaje' => ['titulo' => 'Maquillaje', 'descripcion' => 'Rostro, labios y kits para looks diarios.'],
  'skincare' => ['titulo' => 'Skincare', 'descripcion' => 'Rutinas, hidratación y glow de día y noche.'],
  'accesorios' => ['titulo' => 'Accesorios', 'descripcion' => 'Brochas, cosmetiqueras y organizadores.'],
  'cabello' => ['titulo' => 'Cabello', 'descripcion' => 'Tratamientos y apoyo para styling.'],
];

tienda_render_head('Tienda pública', $tema_tokens, $componentes);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'INICIO'); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo tv_pagina_inicio_store">
    <section class="tv_bloque tv_bloque_hero_store">
      <div class="tv_hero_store_layout">
        <article class="tv_hero_store_principal">
          <div class="tv_hero_store_texto">
            <span class="tv_etiqueta">Beauty · skincare · accesorios</span>
            <h1><?php echo htmlspecialchars($branding['mensaje_bienvenida'] ?? 'Una tienda pensada para compra real, no solo una landing.', ENT_QUOTES, 'UTF-8'); ?></h1>
            <p>La portada se mantiene separada del catálogo, detalle, carrito y contacto, pero ya empieza a verse más comercial y usable para maquillaje, skincare y accesorios.</p>
            <div class="tv_hero_acciones">
              <a href="/catalogo/" class="tv_btn tv_btn_principal">Ir al catálogo</a>
              <a href="/ofertas/" class="tv_btn tv_btn_secundario">Ver ofertas</a>
            </div>
          </div>
          <div class="tv_hero_store_media">
            <?php tienda_render_producto_media($producto_hero, 'tv_producto_media_hero', false); ?>
          </div>
        </article>

        <article class="tv_hero_store_secundario">
          <span class="tv_etiqueta">Compra rápida</span>
          <h3><?php echo htmlspecialchars($producto_secundario['nombre'] ?? 'Campañas activas', ENT_QUOTES, 'UTF-8'); ?></h3>
          <p><?php echo htmlspecialchars($producto_secundario['resumen'] ?? 'Productos destacados y ofertas visibles desde la portada.', ENT_QUOTES, 'UTF-8'); ?></p>
          <div class="tv_hero_store_precio">
            <?php if (!empty($producto_secundario)) { ?>
              <strong>$<?php echo number_format((int) ($producto_secundario['precio'] ?? 0), 0, ',', '.'); ?></strong>
              <?php if ((int) ($producto_secundario['precio_anterior'] ?? 0) > (int) ($producto_secundario['precio'] ?? 0)) { ?>
                <span>$<?php echo number_format((int) ($producto_secundario['precio_anterior'] ?? 0), 0, ',', '.'); ?></span>
              <?php } ?>
            <?php } ?>
          </div>
          <div class="tv_hero_store_tarjetas">
            <div class="tv_hero_store_mini_card">
              <strong>Carrito lateral</strong>
              <span>Compra sin perder el contexto.</span>
            </div>
            <div class="tv_hero_store_mini_card">
              <strong>Admin tienda</strong>
              <span>Productos, imágenes y descuentos por separado.</span>
            </div>
          </div>
        </article>
      </div>
    </section>

    <section class="tv_bloque">
      <div class="tv_bloque_encabezado tv_bloque_encabezado_inline">
        <div>
          <span class="tv_etiqueta">Categorías</span>
          <h2>Compra por línea principal</h2>
          <p>Bloques rápidos para navegar la tienda desde el inicio.</p>
        </div>
        <a href="/catalogo/" class="tv_btn tv_btn_secundario">Ver todo</a>
      </div>
      <div class="tv_grid_lineas_store tv_grid_lineas_inicio">
        <?php foreach ($items_linea as $codigo_linea => $linea) { ?>
          <article class="tv_tarjeta_info tv_linea_store">
            <span class="tv_etiqueta"><?php echo htmlspecialchars(strtoupper($codigo_linea), ENT_QUOTES, 'UTF-8'); ?></span>
            <h3><?php echo htmlspecialchars($linea['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars($linea['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="/catalogo/?linea=<?php echo urlencode($codigo_linea); ?>" class="tv_btn tv_btn_secundario">Explorar</a>
          </article>
        <?php } ?>
      </div>
    </section>

    <section class="tv_bloque tv_bloque_suave">
      <div class="tv_bloque_encabezado tv_bloque_encabezado_inline">
        <div>
          <span class="tv_etiqueta">Destacados</span>
          <h2>Productos visibles desde la portada</h2>
          <p>Cards preparadas para funcionar con detalle, carrito y descuentos reales.</p>
        </div>
      </div>
      <div class="tv_grid_productos_store">
        <?php foreach ($destacados as $producto) { tienda_render_producto_card($producto); } ?>
      </div>
    </section>

    <section class="tv_bloque">
      <div class="tv_bloque_encabezado tv_bloque_encabezado_inline">
        <div>
          <span class="tv_etiqueta">Ofertas</span>
          <h2>Campañas activas para mover compra</h2>
          <p>Este bloque sigue apuntando a la vista separada de ofertas, sin volver la tienda una sola página.</p>
        </div>
        <a href="/ofertas/" class="tv_btn tv_btn_secundario">Ir a ofertas</a>
      </div>
      <div class="tv_grid_productos_store">
        <?php foreach ($ofertas as $producto) { tienda_render_producto_card($producto); } ?>
      </div>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
