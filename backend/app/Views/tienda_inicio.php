<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$tema = $contexto['tema'] ?? [];
$modulo = $contexto['modulo'] ?? [];
$productos = $tv_datos['productos'] ?? [];
$destacados = $tv_datos['destacados'] ?? [];
$ofertas = $tv_datos['ofertas'] ?? [];
$lineas = $tv_datos['lineas'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];

$buscar_producto_home = function($slug) use ($productos) {
  $slug = trim((string) $slug);

  if ($slug === '') {
    return [];
  }

  foreach ($productos as $producto) {
    if ((string) ($producto['slug'] ?? '') === $slug) {
      return $producto;
    }
  }

  return [];
};

$hero_producto_slug = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.home_hero_producto_slug', '');
$producto_hero = $buscar_producto_home($hero_producto_slug);

if (count($producto_hero) === 0) {
  $producto_hero = $destacados[0] ?? $ofertas[0] ?? $productos[0] ?? [];
}

$productos_home = [];
$slugs_home = [];

for ($indice = 1; $indice <= 4; $indice += 1) {
  $slug_configurado = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.home_destacado_' . $indice . '_slug', '');
  $producto_configurado = $buscar_producto_home($slug_configurado);

  if (count($producto_configurado) > 0) {
    $slug_producto = (string) ($producto_configurado['slug'] ?? '');

    if ($slug_producto !== '' && !in_array($slug_producto, $slugs_home, true)) {
      $slugs_home[] = $slug_producto;
      $productos_home[] = $producto_configurado;
    }
  }
}

foreach ([$destacados, $ofertas, $productos] as $coleccion) {
  foreach ($coleccion as $producto) {
    $slug_producto = (string) ($producto['slug'] ?? '');

    if ($slug_producto === '' || in_array($slug_producto, $slugs_home, true)) {
      continue;
    }

    $slugs_home[] = $slug_producto;
    $productos_home[] = $producto;

    if (count($productos_home) >= 4) {
      break 2;
    }
  }
}

$lineas_home = array_slice(array_values($lineas), 0, 4);
$hero_visual = trim((string) tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.home_banner_principal_url', (string) ($branding['banner_principal'] ?? '')));

if ($hero_visual === '') {
  $hero_visual = (string) ($producto_hero['imagen_url'] ?? '/public/uploads/tienda/demo/categorias/general.jpg');
}

$promo_secundaria_visual = trim((string) tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.home_banner_secundario_url', ''));

if ($promo_secundaria_visual === '') {
  $promo_secundaria_visual = (string) ($ofertas[1]['imagen_url'] ?? $productos_home[1]['imagen_url'] ?? '/public/uploads/tienda/demo/categorias/general.jpg');
}

$hero_etiqueta = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_etiqueta', ''), 'Edición Venus');
$hero_titulo = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_titulo', ''), 'Belleza premium con una presencia más limpia y contemporánea');
$hero_descripcion = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_descripcion', ''), 'Un frente comercial renovado para descubrir maquillaje, skincare y accesorios con una lectura más clara, elegante y responsive.');
$hero_boton_primario = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_boton_primario', ''), 'Explorar catálogo');
$hero_boton_secundario = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_boton_secundario', ''), 'Ver favoritos');
$lineas_titulo = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.lineas_titulo', ''), 'Compra por categoría');
$lineas_descripcion = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.lineas_descripcion', ''), 'Categorías visibles, ligeras y rápidas de recorrer, con protagonismo real de la imagen y el nombre de cada línea.');
$destacados_titulo = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.mas_vendidos_titulo', ''), 'Selección destacada');
$destacados_descripcion = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.mas_vendidos_descripcion', ''), 'Tarjetas más limpias, mejor jerarquía y acciones claras para compra directa.');
$colecciones_titulo = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.colecciones_titulo', ''), 'Campañas y temporada');
$colecciones_descripcion = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.colecciones_descripcion', ''), 'Bloques editoriales para resaltar lanzamientos, regalos y promociones activas.');
$newsletter_titulo = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.newsletter_titulo', ''), 'Compra con una experiencia coherente en desktop y móvil');
$newsletter_descripcion = tienda_texto_comercial_venus_publico($tema, tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.newsletter_descripcion', ''), 'Cada vista se rehízo para que el recorrido completo se sienta moderno, respirado y funcional.');
$hero_tags = [];

foreach ($lineas_home as $linea) {
  $titulo_linea = trim((string) ($linea['titulo'] ?? ''));

  if ($titulo_linea !== '' && !in_array($titulo_linea, $hero_tags, true)) {
    $hero_tags[] = $titulo_linea;
  }

  if (count($hero_tags) >= 4) {
    break;
  }
}

if (count($hero_tags) === 0) {
  $hero_tags = ['Maquillaje', 'Skincare', 'Accesorios'];
}

tienda_render_head('VENUS', $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? [], $tema); ?>
  <?php tienda_render_header($branding, $menus, 'INICIO', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_page tv_page_home">
    <section class="tv_home_hero tv_shell">
      <article class="tv_home_hero_copy">
        <span class="tv_etiqueta"><?php echo htmlspecialchars($hero_etiqueta, ENT_QUOTES, 'UTF-8'); ?></span>
        <h1><?php echo htmlspecialchars($hero_titulo, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars($hero_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="tv_home_hero_tags">
          <?php foreach ($hero_tags as $hero_tag) { ?>
            <span><?php echo htmlspecialchars((string) $hero_tag, ENT_QUOTES, 'UTF-8'); ?></span>
          <?php } ?>
        </div>
        <div class="tv_home_hero_actions">
          <a href="/catalogo/" class="tv_btn tv_btn_principal"><?php echo htmlspecialchars($hero_boton_primario, ENT_QUOTES, 'UTF-8'); ?></a>
          <a href="/ofertas/" class="tv_btn tv_btn_secundario"><?php echo htmlspecialchars($hero_boton_secundario, ENT_QUOTES, 'UTF-8'); ?></a>
        </div>
        <div class="tv_home_hero_metrics">
          <article><strong>Envíos</strong><span>Cobertura nacional y seguimiento de pedido.</span></article>
          <article><strong>Compra segura</strong><span>PSE, tarjeta y contra entrega según disponibilidad.</span></article>
          <article><strong>Selección Venus</strong><span>Maquillaje, skincare y accesorios con mejor lectura visual.</span></article>
        </div>
      </article>
      <article class="tv_home_hero_visual">
        <img src="<?php echo htmlspecialchars($hero_visual, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($producto_hero['texto_alternativo'] ?? $hero_titulo), ENT_QUOTES, 'UTF-8'); ?>">
        <?php if (count($producto_hero) > 0) { ?>
          <div class="tv_home_hero_product">
            <span class="tv_etiqueta"><?php echo htmlspecialchars((string) ($producto_hero['etiqueta'] ?? 'Destacado'), ENT_QUOTES, 'UTF-8'); ?></span>
            <h2><?php echo htmlspecialchars((string) ($producto_hero['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
            <p><?php echo htmlspecialchars((string) ($producto_hero['resumen'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="tv_home_hero_product_meta">
              <strong>$<?php echo number_format((int) ($producto_hero['precio'] ?? 0), 0, ',', '.'); ?></strong>
              <a href="/producto/?slug=<?php echo urlencode((string) ($producto_hero['slug'] ?? '')); ?>">Ver producto</a>
            </div>
          </div>
        <?php } ?>
      </article>
    </section>

    <?php if (count($lineas_home) > 0) { ?>
      <section class="tv_section tv_shell">
        <div class="tv_section_head">
          <div>
            <span class="tv_etiqueta">Categorías</span>
            <h2><?php echo htmlspecialchars($lineas_titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
          </div>
          <p><?php echo htmlspecialchars($lineas_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="tv_category_grid">
          <?php foreach ($lineas_home as $linea) { ?>
            <a href="<?php echo htmlspecialchars((string) ($linea['ruta'] ?? '/catalogo/'), ENT_QUOTES, 'UTF-8'); ?>" class="tv_category_card">
              <span class="tv_category_media">
                <img src="<?php echo htmlspecialchars((string) ($linea['imagen_url'] ?? '/public/uploads/tienda/demo/categorias/general.jpg'), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($linea['texto_alternativo'] ?? $linea['titulo'] ?? 'Línea'), ENT_QUOTES, 'UTF-8'); ?>">
              </span>
              <strong><?php echo htmlspecialchars((string) ($linea['titulo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
            </a>
          <?php } ?>
        </div>
      </section>
    <?php } ?>

    <?php if (count($productos_home) > 0) { ?>
      <section class="tv_section tv_shell">
        <div class="tv_section_head">
          <div>
            <span class="tv_etiqueta">Selección</span>
            <h2><?php echo htmlspecialchars($destacados_titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
          </div>
          <p><?php echo htmlspecialchars($destacados_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="tv_product_grid tv_product_grid_home">
          <?php foreach ($productos_home as $producto) { tienda_render_producto_card($producto); } ?>
        </div>
      </section>
    <?php } ?>

    <section class="tv_section tv_shell tv_campaign_grid">
      <article class="tv_campaign_card tv_campaign_card_wide">
        <div class="tv_campaign_copy">
          <span class="tv_etiqueta">Campaña</span>
          <h2><?php echo htmlspecialchars($colecciones_titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
          <p><?php echo htmlspecialchars($colecciones_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
          <a href="/ofertas/" class="tv_btn tv_btn_principal">Ver campañas</a>
        </div>
        <div class="tv_campaign_media">
          <img src="<?php echo htmlspecialchars($hero_visual, ENT_QUOTES, 'UTF-8'); ?>" alt="Campaña VENUS">
        </div>
      </article>
      <article class="tv_campaign_card tv_campaign_card_tall">
        <div class="tv_campaign_media">
          <img src="<?php echo htmlspecialchars($promo_secundaria_visual, ENT_QUOTES, 'UTF-8'); ?>" alt="Selección Venus">
        </div>
        <div class="tv_campaign_copy">
          <span class="tv_etiqueta">Editorial</span>
          <h3>Sets, regalos y lanzamientos</h3>
          <p>Bloques visuales más sobrios para destacar oportunidades comerciales sin ensuciar la lectura general.</p>
        </div>
      </article>
    </section>

    <section class="tv_section tv_shell tv_home_cta_block">
      <div>
        <span class="tv_etiqueta">Experiencia</span>
        <h2><?php echo htmlspecialchars($newsletter_titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
        <p><?php echo htmlspecialchars($newsletter_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
      </div>
      <div class="tv_home_cta_actions">
        <a href="/catalogo/" class="tv_btn tv_btn_principal">Ir al catálogo</a>
        <a href="/contacto/" class="tv_btn tv_btn_secundario">Hablar con VENUS</a>
      </div>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus, $tema); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
