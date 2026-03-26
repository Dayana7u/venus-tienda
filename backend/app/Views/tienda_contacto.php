<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$tema = $contexto['tema'] ?? [];
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

tienda_render_head('Contacto', $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? [], $tema); ?>
  <?php tienda_render_header($branding, $menus, 'CONTACTO', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_page">
    <section class="tv_section tv_shell">
      <div class="tv_breadcrumb">Inicio / Contacto</div>
      <div class="tv_section_head">
        <div>
          <span class="tv_etiqueta">Contacto</span>
          <h1 class="tv_page_title">Acompañamiento y postcompra</h1>
        </div>
        <p>Canales más claros para resolver dudas sobre productos, pagos, entregas o seguimiento de pedidos.</p>
      </div>

      <?php if (($soporte['activo'] ?? false) === true) { ?>
        <article class="tv_support_banner">
          <div>
            <span class="tv_etiqueta">Soporte</span>
            <h2>Seguimiento de tu pedido</h2>
            <p>Consulta rápida para acompañar pagos, entregas o soporte posterior a la compra.</p>
          </div>
          <div class="tv_support_actions">
            <?php if ($url_whatsapp_soporte !== '') { ?>
              <a href="<?php echo htmlspecialchars($url_whatsapp_soporte, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="tv_btn tv_btn_principal">Hablar por WhatsApp</a>
            <?php } ?>
            <?php if ($correo_soporte !== '') { ?>
              <a href="mailto:<?php echo htmlspecialchars($correo_soporte, ENT_QUOTES, 'UTF-8'); ?>" class="tv_btn tv_btn_secundario">Escribir por correo</a>
            <?php } ?>
          </div>
        </article>
      <?php } ?>

      <div class="tv_contact_grid">
        <?php foreach ($canales as $canal) { ?>
          <article class="tv_contact_card">
            <span class="tv_etiqueta">Canal</span>
            <h3><?php echo htmlspecialchars($canal['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars($canal['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
          </article>
        <?php } ?>
        <article class="tv_contact_card">
          <span class="tv_etiqueta">Correo</span>
          <h3><?php echo htmlspecialchars($branding['correo_contacto'] ?? 'hola@venusbeauty.co', ENT_QUOTES, 'UTF-8'); ?></h3>
        </article>
        <article class="tv_contact_card">
          <span class="tv_etiqueta">Teléfono</span>
          <h3><?php echo htmlspecialchars($branding['telefono_contacto'] ?? 'Disponible por parametrización', ENT_QUOTES, 'UTF-8'); ?></h3>
        </article>
        <article class="tv_contact_card">
          <span class="tv_etiqueta">Dirección</span>
          <h3><?php echo htmlspecialchars($branding['direccion'] ?? 'Disponible por parametrización', ENT_QUOTES, 'UTF-8'); ?></h3>
        </article>
      </div>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus, $tema); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
</body>
</html>
