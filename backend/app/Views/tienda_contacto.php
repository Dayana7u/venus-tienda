<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto   = $tv_datos['contexto'] ?? [];
$branding   = $contexto['branding'] ?? [];
$menus      = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$canales    = $tv_datos['canales'] ?? [];

tienda_render_head('Contacto', $tema_tokens, $componentes);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CONTACTO'); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo">
    <section class="tv_bloque">
      <div class="tv_breadcrumb">Inicio / Contacto</div>
      <div class="tv_bloque_encabezado">
        <span class="tv_etiqueta">Contacto</span>
        <h2>Canales para venta, asesoría y seguimiento</h2>
        <p>Sección separada del catálogo para soporte comercial y atención personalizada.</p>
      </div>
      <div class="tv_grid_lineas_store">
        <?php foreach ($canales as $canal) { ?>
          <article class="tv_tarjeta_info tv_linea_store">
            <h3><?php echo htmlspecialchars($canal['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars($canal['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
          </article>
        <?php } ?>
      </div>
      <div class="tv_contacto_cards_store">
        <article class="tv_tarjeta_info">
          <span class="tv_etiqueta">Correo</span>
          <h3><?php echo htmlspecialchars($branding['correo_contacto'] ?? 'contacto@tiendapublica.com', ENT_QUOTES, 'UTF-8'); ?></h3>
        </article>
        <article class="tv_tarjeta_info">
          <span class="tv_etiqueta">Teléfono</span>
          <h3><?php echo htmlspecialchars($branding['telefono_contacto'] ?? 'Pendiente por parametrizar', ENT_QUOTES, 'UTF-8'); ?></h3>
        </article>
        <article class="tv_tarjeta_info">
          <span class="tv_etiqueta">Dirección</span>
          <h3><?php echo htmlspecialchars($branding['direccion'] ?? 'Pendiente por parametrizar', ENT_QUOTES, 'UTF-8'); ?></h3>
        </article>
      </div>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus); ?>
</body>
</html>
