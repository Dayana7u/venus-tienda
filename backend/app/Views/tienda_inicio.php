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

$lineas_home = array_slice(array_values($lineas), 0, 3);
$hero_visual = trim((string) tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.home_banner_principal_url', (string) ($branding['banner_principal'] ?? '')));

if ($hero_visual === '') {
  $hero_visual = (string) ($producto_hero['imagen_url'] ?? '/public/uploads/tienda/demo/categorias/general.jpg');
}

$promo_secundaria_visual = trim((string) tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.home_banner_secundario_url', ''));

if ($promo_secundaria_visual === '') {
  $promo_secundaria_visual = (string) ($ofertas[1]['imagen_url'] ?? $productos_home[1]['imagen_url'] ?? '/public/uploads/tienda/demo/categorias/general.jpg');
}

$hero_etiqueta = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_etiqueta', 'Skincare & Accessories');
$hero_titulo = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_titulo', 'Cosmética premium para una rutina más limpia y elegante');
$hero_descripcion = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_descripcion', 'La portada ahora toma producto real, imagen real y contenido parametrizable para evitar bloques quemados o tarjetas que no correspondan al catálogo.');
$hero_boton_primario = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_boton_primario', 'Comprar ahora');
$hero_boton_secundario = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_boton_secundario', 'Ver catálogo');
$hero_item_1 = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_item_1', 'Productos reales y activos');
$hero_item_2 = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_item_2', 'Identidad visual Venus consistente');
$hero_item_3 = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.hero_item_3', 'Portada preparada para parametrización');
$lineas_titulo = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.lineas_titulo', 'Explora por línea');
$lineas_descripcion = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.lineas_descripcion', 'Bloques apoyados en categorías reales, con imagen y navegación directa al catálogo.');
$destacados_titulo = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.mas_vendidos_titulo', 'Productos destacados');
$destacados_descripcion = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.mas_vendidos_descripcion', 'Las tarjetas del inicio toman referencias visibles del catálogo para evitar contenido que no corresponda con la tienda.');
$destacado_boton = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.destacado_boton', 'Ver detalle');
$colecciones_titulo = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.colecciones_titulo', 'Campañas visuales');
$colecciones_descripcion = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.colecciones_descripcion', 'Espacios listos para campañas, temporadas y cambios visuales sin romper la estructura comercial.');
$oferta_titulo = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.oferta_titulo', 'Promociones por temporada');
$oferta_descripcion = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.oferta_descripcion', 'Bloque visual preparado para hero secundarios, campañas y banners parametrizables.');
$oferta_secundaria_titulo = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.oferta_secundaria_titulo', 'Contenido comercial reusable');
$oferta_secundaria_descripcion = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.oferta_secundaria_descripcion', 'La página toma catálogo, precios, imágenes y branding desde los datos visibles de la tienda.');
$newsletter_titulo = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.newsletter_titulo', 'Un frente público más ordenado para seguir creciendo');
$newsletter_descripcion = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.newsletter_descripcion', 'Catálogo, checkout, pago y panel administrativo quedan listos para continuar sobre la misma identidad visual Venus.');
$mostrar_lineas = tienda_obtener_booleano_modulo_publico($modulo, 'tienda_publica.mostrar_lineas_producto', true);
$mostrar_destacados = tienda_obtener_booleano_modulo_publico($modulo, 'tienda_publica.mostrar_mas_vendidos', true);

tienda_render_head('VENUS', $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'INICIO', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo tv_pagina_inicio_venus">
    <section class="tv_bloque tv_venus_home_wrap">
      <div class="tv_venus_home_shell">
        <section class="tv_venus_home_hero">
          <article class="tv_venus_hero_texto_panel">
            <span class="tv_etiqueta tv_venus_etiqueta_inicio"><?php echo htmlspecialchars($hero_etiqueta, ENT_QUOTES, 'UTF-8'); ?></span>
            <h1><?php echo htmlspecialchars($hero_titulo, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p><?php echo htmlspecialchars($hero_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="tv_venus_hero_acciones">
              <a href="/catalogo/" class="tv_btn tv_btn_principal tv_btn_venus_hero"><?php echo htmlspecialchars($hero_boton_primario, ENT_QUOTES, 'UTF-8'); ?></a>
              <a href="/catalogo/" class="tv_btn tv_btn_secundario tv_btn_venus_outline"><?php echo htmlspecialchars($hero_boton_secundario, ENT_QUOTES, 'UTF-8'); ?></a>
            </div>
            <div class="tv_venus_hero_listado">
              <article><span>01</span><strong><?php echo htmlspecialchars($hero_item_1, ENT_QUOTES, 'UTF-8'); ?></strong></article>
              <article><span>02</span><strong><?php echo htmlspecialchars($hero_item_2, ENT_QUOTES, 'UTF-8'); ?></strong></article>
              <article><span>03</span><strong><?php echo htmlspecialchars($hero_item_3, ENT_QUOTES, 'UTF-8'); ?></strong></article>
            </div>
          </article>

          <article class="tv_venus_hero_visual_panel">
            <div class="tv_venus_hero_visual_marco">
              <img src="<?php echo htmlspecialchars($hero_visual, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($producto_hero['texto_alternativo'] ?? $hero_titulo), ENT_QUOTES, 'UTF-8'); ?>" class="tv_venus_hero_visual_imagen">
            </div>
            <?php if (count($producto_hero) > 0) { ?>
              <div class="tv_venus_hero_producto">
                <span class="tv_etiqueta"><?php echo htmlspecialchars((string) ($producto_hero['etiqueta'] ?? 'Destacado'), ENT_QUOTES, 'UTF-8'); ?></span>
                <h2><?php echo htmlspecialchars((string) ($producto_hero['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
                <p><?php echo htmlspecialchars((string) ($producto_hero['resumen'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="tv_venus_hero_producto_meta">
                  <strong>$<?php echo number_format((int) ($producto_hero['precio'] ?? 0), 0, ',', '.'); ?></strong>
                  <a href="/producto/?slug=<?php echo urlencode((string) ($producto_hero['slug'] ?? '')); ?>">Ver producto</a>
                </div>
              </div>
            <?php } ?>
          </article>
        </section>

        <?php if ($mostrar_lineas === true && count($lineas_home) > 0) { ?>
          <section class="tv_venus_home_bloque">
            <div class="tv_venus_home_encabezado">
              <div>
                <span class="tv_etiqueta">Catálogo</span>
                <h2><?php echo htmlspecialchars($lineas_titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
              </div>
              <p><?php echo htmlspecialchars($lineas_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="tv_venus_lineas_grid">
              <?php foreach ($lineas_home as $linea) { ?>
                <article class="tv_venus_linea_card">
                  <a href="<?php echo htmlspecialchars((string) ($linea['ruta'] ?? '/catalogo/'), ENT_QUOTES, 'UTF-8'); ?>" class="tv_venus_linea_media">
                    <img src="<?php echo htmlspecialchars((string) ($linea['imagen_url'] ?? '/public/uploads/tienda/demo/categorias/general.jpg'), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($linea['texto_alternativo'] ?? $linea['titulo'] ?? 'Línea'), ENT_QUOTES, 'UTF-8'); ?>">
                  </a>
                  <div class="tv_venus_linea_contenido">
                    <span class="tv_etiqueta"><?php echo htmlspecialchars((string) ($linea['titulo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                    <h3><?php echo htmlspecialchars((string) ($linea['titulo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars((string) ($linea['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <a href="<?php echo htmlspecialchars((string) ($linea['ruta'] ?? '/catalogo/'), ENT_QUOTES, 'UTF-8'); ?>" class="tv_venus_link_accion">Explorar línea</a>
                  </div>
                </article>
              <?php } ?>
            </div>
          </section>
        <?php } ?>

        <?php if ($mostrar_destacados === true && count($productos_home) > 0) { ?>
          <section class="tv_venus_home_bloque">
            <div class="tv_venus_home_encabezado">
              <div>
                <span class="tv_etiqueta">Selección Venus</span>
                <h2><?php echo htmlspecialchars($destacados_titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
              </div>
              <p><?php echo htmlspecialchars($destacados_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="tv_venus_destacados_grid">
              <?php foreach ($productos_home as $producto) { ?>
                <article class="tv_venus_destacado_card">
                  <a href="/producto/?slug=<?php echo urlencode((string) ($producto['slug'] ?? '')); ?>" class="tv_venus_destacado_media">
                    <img src="<?php echo htmlspecialchars((string) ($producto['imagen_url'] ?? '/public/uploads/tienda/demo/categorias/general.jpg'), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($producto['texto_alternativo'] ?? $producto['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                  </a>
                  <div class="tv_venus_destacado_contenido">
                    <span class="tv_etiqueta"><?php echo htmlspecialchars((string) ($producto['etiqueta'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                    <h3><?php echo htmlspecialchars((string) ($producto['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars((string) ($producto['resumen'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="tv_venus_destacado_meta">
                      <strong>$<?php echo number_format((int) ($producto['precio'] ?? 0), 0, ',', '.'); ?></strong>
                      <?php if ((int) ($producto['precio_anterior'] ?? 0) > (int) ($producto['precio'] ?? 0)) { ?>
                        <span>$<?php echo number_format((int) ($producto['precio_anterior'] ?? 0), 0, ',', '.'); ?></span>
                      <?php } ?>
                    </div>
                    <div class="tv_venus_destacado_acciones">
                      <a href="/producto/?slug=<?php echo urlencode((string) ($producto['slug'] ?? '')); ?>" class="tv_btn tv_btn_secundario tv_btn_venus_outline"><?php echo htmlspecialchars($destacado_boton, ENT_QUOTES, 'UTF-8'); ?></a>
                      <form action="/carrito/" method="post" class="tv_form_agregar_carrito">
                        <input type="hidden" name="accion" value="agregar">
                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars((string) ($producto['slug'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="cantidad" value="1">
                        <input type="hidden" name="redireccion" value="<?php echo htmlspecialchars((string) ($_SERVER['REQUEST_URI'] ?? '/'), ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="tv_btn tv_btn_principal tv_btn_venus_hero">Añadir</button>
                      </form>
                    </div>
                  </div>
                </article>
              <?php } ?>
            </div>
          </section>
        <?php } ?>

        <section class="tv_venus_home_bloque tv_venus_home_bloque_campanas">
          <div class="tv_venus_home_encabezado">
            <div>
              <span class="tv_etiqueta">Visual comercial</span>
              <h2><?php echo htmlspecialchars($colecciones_titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
            </div>
            <p><?php echo htmlspecialchars($colecciones_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="tv_venus_campanas_grid">
            <article class="tv_venus_campana_card tv_venus_campana_card_principal">
              <div class="tv_venus_campana_texto">
                <span class="tv_etiqueta">Campaña principal</span>
                <h3><?php echo htmlspecialchars($oferta_titulo, ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?php echo htmlspecialchars($oferta_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="/ofertas/" class="tv_btn tv_btn_principal tv_btn_venus_hero">Ver ofertas</a>
              </div>
              <div class="tv_venus_campana_media">
                <img src="<?php echo htmlspecialchars($hero_visual, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($oferta_titulo, ENT_QUOTES, 'UTF-8'); ?>">
              </div>
            </article>
            <article class="tv_venus_campana_card tv_venus_campana_card_secundaria">
              <div class="tv_venus_campana_media">
                <img src="<?php echo htmlspecialchars($promo_secundaria_visual, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($oferta_secundaria_titulo, ENT_QUOTES, 'UTF-8'); ?>">
              </div>
              <div class="tv_venus_campana_texto">
                <span class="tv_etiqueta">Soporte visual</span>
                <h3><?php echo htmlspecialchars($oferta_secundaria_titulo, ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?php echo htmlspecialchars($oferta_secundaria_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="/catalogo/" class="tv_venus_link_accion">Ir al catálogo</a>
              </div>
            </article>
          </div>
        </section>

        <section class="tv_venus_home_cta_final">
          <div>
            <span class="tv_etiqueta">Continuidad</span>
            <h2><?php echo htmlspecialchars($newsletter_titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
            <p><?php echo htmlspecialchars($newsletter_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="tv_venus_home_cta_botones">
            <a href="/catalogo/" class="tv_btn tv_btn_principal tv_btn_venus_hero">Ver catálogo</a>
            <a href="/contacto/" class="tv_btn tv_btn_secundario tv_btn_venus_outline">Hablar con asesoría</a>
          </div>
        </section>

        <?php tienda_render_footer($branding, $menus, $tema); ?>
      </div>
    </section>
  </main>

  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
