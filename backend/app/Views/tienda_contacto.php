<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$canales = $tv_datos['canales'] ?? [];
$carrito = $tv_datos['carrito'] ?? [];
$soporte = $tv_datos['soporte'] ?? [];
$pedido_soporte = trim((string) ($soporte['pedido'] ?? ''));
$cliente_soporte = trim((string) ($soporte['cliente'] ?? ''));
$total_soporte = trim((string) ($soporte['total'] ?? ''));
$telefono_soporte = $branding['telefono_contacto'] ?? '';
$correo_soporte = $branding['correo_contacto'] ?? '';
$mensaje_soporte = 'Hola, necesito apoyo con mi pedido';
if ($pedido_soporte !== '') {
  $mensaje_soporte .= ' ' . $pedido_soporte;
}
if ($cliente_soporte !== '') {
  $mensaje_soporte .= '. Cliente: ' . $cliente_soporte;
}
if ($total_soporte !== '') {
  $mensaje_soporte .= '. Total: ' . $total_soporte;
}
$url_whatsapp_soporte = tienda_generar_url_whatsapp_soporte_publico((string) $telefono_soporte, $mensaje_soporte);

tienda_render_head('Contacto', $tema_tokens, $componentes, $contexto['tema'] ?? []);
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
      <?php if (($soporte['activo'] ?? false) === true) { ?>
        <article class="tv_tarjeta_info tv_contacto_soporte_pedido">
          <span class="tv_etiqueta">Soporte de compra</span>
          <h3>Atención para seguimiento del pedido</h3>
          <p>Se dejó este bloque listo para dar continuidad a la compra y a la gestión comercial sin salir del frente público.</p>
          <div class="tv_contacto_soporte_resumen">
            <?php if ($pedido_soporte !== '') { ?>
              <article>
                <span>Pedido</span>
                <strong><?php echo htmlspecialchars($pedido_soporte, ENT_QUOTES, 'UTF-8'); ?></strong>
              </article>
            <?php } ?>
            <?php if ($cliente_soporte !== '') { ?>
              <article>
                <span>Cliente</span>
                <strong><?php echo htmlspecialchars($cliente_soporte, ENT_QUOTES, 'UTF-8'); ?></strong>
              </article>
            <?php } ?>
            <?php if ($total_soporte !== '') { ?>
              <article>
                <span>Total</span>
                <strong><?php echo htmlspecialchars($total_soporte, ENT_QUOTES, 'UTF-8'); ?></strong>
              </article>
            <?php } ?>
          </div>
          <div class="tv_checkout_exito_botones tv_contacto_soporte_acciones">
            <?php if ($url_whatsapp_soporte !== '') { ?>
              <a href="<?php echo htmlspecialchars($url_whatsapp_soporte, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="tv_btn tv_btn_principal">Hablar por WhatsApp</a>
            <?php } ?>
            <?php if ($correo_soporte !== '') { ?>
              <a href="mailto:<?php echo htmlspecialchars($correo_soporte, ENT_QUOTES, 'UTF-8'); ?>?subject=<?php echo rawurlencode('Soporte pedido ' . ($pedido_soporte !== '' ? $pedido_soporte : 'tienda')); ?>" class="tv_btn tv_btn_secundario">Escribir por correo</a>
            <?php } ?>
            <?php if ($telefono_soporte !== '') { ?>
              <a href="tel:<?php echo htmlspecialchars((string) $telefono_soporte, ENT_QUOTES, 'UTF-8'); ?>" class="tv_btn tv_btn_secundario">Llamar</a>
            <?php } ?>
          </div>
        </article>
      <?php } ?>
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
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
