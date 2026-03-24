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

tienda_render_head('Tienda pública', $tema_tokens, $componentes);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'INICIO'); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_tienda tv_pagina_publica">
    <section class="tv_hero tv_hero_store">
      <div class="tv_hero_contenido">
        <article class="tv_hero_texto">
          <span class="tv_etiqueta">Beauty store</span>
          <h1>Una tienda pensada para maquillaje, skincare y accesorios</h1>
          <p>La portada ya deja de ser landing única y pasa a comportarse como una tienda con catálogo, detalle, carrito y vistas separadas.</p>
          <div class="tv_hero_acciones">
            <a href="/catalogo/" class="tv_btn tv_btn_principal">Ver catálogo</a>
            <a href="/ofertas/" class="tv_btn tv_btn_secundario">Ver ofertas</a>
          </div>
        </article>
        <article class="tv_hero_panel tv_hero_panel_store">
          <span class="tv_etiqueta">Tema activo <?php echo htmlspecialchars($contexto['tema']['codigo'] ?? 'PINK_NUDE', ENT_QUOTES, 'UTF-8'); ?></span>
          <h3>Vitrina comercial separada del panel</h3>
          <p>Este frente ya queda dividido por módulos para crecer como tienda virtual y no como una sola landing.</p>
          <div class="tv_grid_resumen">
            <div class="tv_resumen_item"><strong>Catálogo</strong><span>Productos por línea</span></div>
            <div class="tv_resumen_item"><strong>Carrito</strong><span>Compra paso a paso</span></div>
            <div class="tv_resumen_item"><strong>Detalle</strong><span>Vista individual</span></div>
          </div>
        </article>
      </div>
    </section>

    <section class="tv_bloque">
      <div class="tv_bloque_encabezado">
        <span class="tv_etiqueta">Líneas principales</span>
        <h2>Compra por tipo de producto</h2>
        <p>Bloques iniciales para que la tienda ya se vea más comercial y organizada.</p>
      </div>
      <div class="tv_grid_lineas_store">
        <?php foreach ($lineas as $codigo_linea => $linea) { ?>
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
      <div class="tv_bloque_encabezado">
        <span class="tv_etiqueta">Destacados</span>
        <h2>Selección inicial para la portada</h2>
        <p>Productos visibles desde la entrada principal para llevar al catálogo y al detalle.</p>
      </div>
      <div class="tv_grid_productos_store">
        <?php foreach ($destacados as $producto) { tienda_render_producto_card($producto); } ?>
      </div>
    </section>

    <section class="tv_bloque">
      <div class="tv_bloque_encabezado">
        <span class="tv_etiqueta">Ofertas</span>
        <h2>Campañas activas</h2>
        <p>Bloque comercial inicial con productos rebajados para empujar compra rápida.</p>
      </div>
      <div class="tv_grid_productos_store">
        <?php foreach ($ofertas as $producto) { tienda_render_producto_card($producto); } ?>
      </div>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus); ?>
</body>
</html>
