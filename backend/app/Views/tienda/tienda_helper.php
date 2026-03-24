<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (empty($_SESSION['tienda_publica_token'])) {
  $_SESSION['tienda_publica_token'] = bin2hex(random_bytes(32));
}

function tienda_mbstring_disponible() {
  return extension_loaded('mbstring');
}

function tienda_texto_substring($texto, $inicio, $longitud = null) {
  $texto = (string) $texto;

  if (tienda_mbstring_disponible()) {
    return $longitud === null
      ? mb_substr($texto, (int) $inicio, null, 'UTF-8')
      : mb_substr($texto, (int) $inicio, (int) $longitud, 'UTF-8');
  }

  if (function_exists('iconv_substr')) {
    return $longitud === null
      ? iconv_substr($texto, (int) $inicio, iconv_strlen($texto, 'UTF-8'), 'UTF-8')
      : iconv_substr($texto, (int) $inicio, (int) $longitud, 'UTF-8');
  }

  return $longitud === null ? substr($texto, (int) $inicio) : substr($texto, (int) $inicio, (int) $longitud);
}

function tienda_texto_inicial($texto, $predeterminado = 'T') {
  $inicial = trim(tienda_texto_substring((string) $texto, 0, 1));

  return $inicial !== '' ? $inicial : $predeterminado;
}

function tienda_obtener_ruta_publica($codigo_menu = '', $ruta_defecto = '/') {
  $codigo_menu = strtoupper((string) $codigo_menu);

  switch ($codigo_menu) {
    case 'INICIO':
      return '/';

    case 'CATALOGO':
      return '/catalogo/';

    case 'OFERTAS':
      return '/ofertas/';

    case 'CONTACTO':
      return '/contacto/';

    case 'CARRITO':
      return '/carrito/';

    default:
      return $ruta_defecto !== '' ? $ruta_defecto : '/';
  }
}

function tienda_obtener_total_carrito() {
  $items = $_SESSION['tv_carrito'] ?? [];
  $total = 0;

  foreach ($items as $cantidad) {
    $total += (int) $cantidad;
  }

  return $total;
}

function tienda_consumir_mensaje_flash() {
  $mensaje = $_SESSION['tv_mensaje'] ?? '';
  unset($_SESSION['tv_mensaje']);
  return $mensaje;
}

function tienda_generar_variables_css($tema_tokens = [], $componentes = []) {
  $badge = $componentes['badge'] ?? [];
  $footer = $componentes['footer'] ?? [];
  $button_primary = $componentes['button.primary'] ?? [];
  $card_product = $componentes['card.product'] ?? [];
  $input_search = $componentes['input.search'] ?? [];
  $topbar = $componentes['topbar'] ?? [];
  $collection_card = $componentes['collection.card'] ?? [];
  $contact_highlight = $componentes['contact.highlight'] ?? [];

  $variables = [
    '--tv-color-primary'                => $tema_tokens['color.primary'] ?? '#E8B4BC',
    '--tv-color-secondary'              => $tema_tokens['color.secondary'] ?? '#D98F9D',
    '--tv-color-accent'                 => $tema_tokens['color.accent'] ?? '#C46A7A',
    '--tv-color-background'             => $tema_tokens['color.background'] ?? '#FFF7F8',
    '--tv-color-surface'                => $tema_tokens['color.surface'] ?? '#FFFFFF',
    '--tv-color-text'                   => $tema_tokens['color.text'] ?? '#4A3A3F',
    '--tv-color-text-soft'              => $tema_tokens['color.text.soft'] ?? '#7A666C',
    '--tv-color-border'                 => $tema_tokens['color.border'] ?? '#F1D7DC',
    '--tv-font-heading'                 => '"' . ($tema_tokens['font.family.heading'] ?? 'Playfair Display') . '", serif',
    '--tv-font-base'                    => '"' . ($tema_tokens['font.family.body'] ?? 'Poppins') . '", sans-serif',
    '--tv-font-size-base'               => $tema_tokens['font.size.base'] ?? '16px',
    '--tv-radius-sm'                    => $tema_tokens['border.radius.sm'] ?? '8px',
    '--tv-radius-md'                    => $tema_tokens['border.radius.md'] ?? '14px',
    '--tv-radius-lg'                    => $tema_tokens['border.radius.lg'] ?? '22px',
    '--tv-shadow-card'                  => $tema_tokens['shadow.card'] ?? '0 10px 30px rgba(212, 166, 176, 0.18)',
    '--tv-badge-background'             => $badge['background'] ?? '#F7E5E9',
    '--tv-badge-text'                   => $badge['color'] ?? '#8D5D68',
    '--tv-badge-border'                 => $badge['border_color'] ?? '#E8C6CE',
    '--tv-footer-background'            => $footer['background'] ?? '#E9D7DD',
    '--tv-footer-background-alt'        => $footer['background_alt'] ?? '#E2CCD4',
    '--tv-footer-text'                  => $footer['color'] ?? '#5E454D',
    '--tv-footer-link'                  => $footer['link_color'] ?? '#6F525B',
    '--tv-footer-link-hover-background' => $footer['link_hover_background'] ?? 'rgba(255, 255, 255, 0.45)',
    '--tv-footer-border'                => $footer['border_color'] ?? '#E7CAD2',
    '--tv-topbar-background'            => $topbar['background'] ?? '#DCA3AF',
    '--tv-topbar-text'                  => $topbar['color'] ?? '#FFFAF9',
    '--tv-button-primary-background'    => $button_primary['background'] ?? '#D58C9E',
    '--tv-button-primary-text'          => $button_primary['color'] ?? '#FFFFFF',
    '--tv-button-primary-radius'        => $button_primary['border_radius'] ?? '999px',
    '--tv-card-product-background'      => $card_product['background'] ?? '#FFFFFF',
    '--tv-card-product-border'          => $card_product['border_color'] ?? '#F1D7DC',
    '--tv-card-product-shadow'          => $card_product['box_shadow'] ?? '0 14px 32px rgba(17, 17, 17, 0.08)',
    '--tv-input-search-background'      => $input_search['background'] ?? '#FFFFFF',
    '--tv-input-search-border'          => $input_search['border_color'] ?? '#E8C6CE',
    '--tv-collection-background'        => $collection_card['background'] ?? '#FFF5F7',
    '--tv-collection-border'            => $collection_card['border_color'] ?? '#ECD1D8',
    '--tv-contact-highlight-background' => $contact_highlight['background'] ?? '#FFF2F5',
    '--tv-contact-highlight-border'     => $contact_highlight['border_color'] ?? '#EAD1D8',
  ];

  $css = ":root {\n";

  foreach ($variables as $clave => $valor) {
    $css .= "  {$clave}: {$valor};\n";
  }

  $css .= "}\n";

  return $css;
}

function tienda_render_head($titulo, $tema_tokens = [], $componentes = []) {
  $css_variables = tienda_generar_variables_css($tema_tokens, $componentes);

  echo '<!DOCTYPE html>';
  echo '<html lang="es">';
  echo '<head>';
  echo '  <meta charset="UTF-8">';
  echo '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
  echo '  <title>' . htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') . '</title>';
  echo '  <link rel="preconnect" href="https://fonts.googleapis.com">';
  echo '  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
  echo '  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">';
  echo '  <link rel="stylesheet" href="/public/assets/css/tienda_publica.css">';
  echo '  <style>' . PHP_EOL . $css_variables . '  </style>';
  echo '</head>';
}

function tienda_render_topbar($modulo = []) {
  $configuraciones = $modulo['configuraciones'] ?? [];
  $mensaje = $configuraciones['tienda_publica.topbar_texto']['valor'] ?? 'Envíos nacionales · Compra segura · Atención por WhatsApp';

  echo '<section class="tv_topbar">';
  echo '  <div class="tv_topbar_contenido">' . htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</div>';
  echo '</section>';
}

function tienda_render_header($branding = [], $menus = [], $pagina_activa = '') {
  $nombre = $branding['nombre_comercial'] ?? 'Tienda Pública Base';
  $total_carrito = tienda_obtener_total_carrito();

  echo '<header class="tv_header tv_header_publico">';
  echo '  <div class="tv_header_superior">';
  echo '    <a href="/" class="tv_logo">' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '</a>';
  echo '    <nav class="tv_nav">';

  foreach ($menus as $menu) {
    $codigo = $menu['codigo'] ?? '';
    $ruta = tienda_obtener_ruta_publica($codigo, $menu['ruta'] ?? '/');
    $clase = $pagina_activa === $codigo ? ' tv_nav_link_activo' : '';
    echo '      <a href="' . htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8') . '" class="tv_nav_link' . $clase . '">' . htmlspecialchars($menu['nombre'], ENT_QUOTES, 'UTF-8') . '</a>';
  }

  echo '    </nav>';
  echo '    <div class="tv_header_acciones">';
  echo '      <form action="/catalogo/" method="get" class="tv_buscador_campo">';
  echo '        <input type="text" name="buscar" placeholder="Buscar productos">';
  echo '      </form>';
  echo '      <button type="button" id="btn_abrir_carrito_tienda_publica" class="tv_btn tv_btn_secundario tv_btn_carrito_encabezado">';
  echo '        Carrito';
  echo '        <span id="span_contador_carrito_tienda_publica" class="tv_contador_inline">' . (int) $total_carrito . '</span>';
  echo '      </button>';
  echo '    </div>';
  echo '  </div>';
  echo '</header>';
}

function tienda_render_flash() {
  $mensaje = tienda_consumir_mensaje_flash();

  if ($mensaje === '') {
    return;
  }

  echo '<section class="tv_flash_wrap">';
  echo '  <div class="tv_flash_ok">' . htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</div>';
  echo '</section>';
}

function tienda_render_footer($branding = [], $menus = []) {
  $nombre = $branding['nombre_comercial'] ?? 'Tienda Pública Base';
  $texto = $branding['texto_footer'] ?? 'Compra fácil, rápida y segura.';

  echo '<footer class="tv_footer">';
  echo '  <div class="tv_footer_contenido">';
  echo '    <div class="tv_footer_columnas">';
  echo '      <article class="tv_footer_bloque">';
  echo '        <h3>' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '</h3>';
  echo '        <p>' . htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') . '</p>';
  echo '      </article>';
  echo '      <article class="tv_footer_bloque">';
  echo '        <h3>Categorías</h3>';
  echo '        <ul class="tv_footer_lista">';
  echo '          <li><a href="/catalogo/?linea=maquillaje">Maquillaje</a></li>';
  echo '          <li><a href="/catalogo/?linea=skincare">Skincare</a></li>';
  echo '          <li><a href="/catalogo/?linea=accesorios">Accesorios</a></li>';
  echo '        </ul>';
  echo '      </article>';
  echo '      <article class="tv_footer_bloque">';
  echo '        <h3>Navegación</h3>';
  echo '        <ul class="tv_footer_lista">';

  foreach ($menus as $menu) {
    $ruta = tienda_obtener_ruta_publica($menu['codigo'] ?? '', $menu['ruta'] ?? '/');
    echo '          <li><a href="' . htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($menu['nombre'], ENT_QUOTES, 'UTF-8') . '</a></li>';
  }

  echo '        </ul>';
  echo '      </article>';
  echo '      <article class="tv_footer_bloque">';
  echo '        <h3>Atención</h3>';
  echo '        <ul class="tv_footer_lista">';
  echo '          <li>' . htmlspecialchars($branding['correo_contacto'] ?? 'contacto@tiendapublica.com', ENT_QUOTES, 'UTF-8') . '</li>';
  echo '          <li>' . htmlspecialchars($branding['telefono_contacto'] ?? 'Pendiente por parametrizar', ENT_QUOTES, 'UTF-8') . '</li>';
  echo '          <li>' . htmlspecialchars($branding['direccion'] ?? 'Pendiente por parametrizar', ENT_QUOTES, 'UTF-8') . '</li>';
  echo '        </ul>';
  echo '      </article>';
  echo '    </div>';
  echo '  </div>';
  echo '</footer>';
}

function tienda_render_producto_media($producto, $clase_adicional = '', $lazy = true) {
  $imagen_url = trim((string) ($producto['imagen_url'] ?? ''));
  $texto_alternativo = trim((string) ($producto['texto_alternativo'] ?? ($producto['nombre'] ?? 'Producto')));
  $clase_media = trim('tv_producto_media ' . $clase_adicional);

  echo '<div class="' . htmlspecialchars($clase_media, ENT_QUOTES, 'UTF-8') . '">';

  if ($imagen_url !== '') {
    echo '  <img src="' . htmlspecialchars($imagen_url, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($texto_alternativo, ENT_QUOTES, 'UTF-8') . '" class="tv_producto_imagen"' . ($lazy ? ' loading="lazy"' : '') . '>';
  }
  else {
    echo '  <div class="tv_producto_media_placeholder tv_producto_media_' . htmlspecialchars((string) ($producto['media'] ?? 'base'), ENT_QUOTES, 'UTF-8') . '">';
    echo '    <span>' . htmlspecialchars(tienda_texto_inicial((string) ($producto['nombre'] ?? 'TV'), 'T'), ENT_QUOTES, 'UTF-8') . '</span>';
    echo '  </div>';
  }

  echo '</div>';
}

function tienda_render_producto_card($producto) {
  $url = '/producto/?slug=' . urlencode((string) $producto['slug']);
  $descuento = (int) ($producto['descuento_porcentaje'] ?? 0);

  echo '<article class="tv_producto_card tv_producto_card_tienda">';

  if ($descuento > 0) {
    echo '  <span class="tv_producto_descuento">-' . $descuento . '%</span>';
  }

  echo '  <a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" class="tv_producto_media_enlace">';
  tienda_render_producto_media($producto, 'tv_producto_media_card');
  echo '  </a>';
  echo '  <div class="tv_producto_contenido">';
  echo '    <span class="tv_etiqueta">' . htmlspecialchars((string) ($producto['etiqueta'] ?? ''), ENT_QUOTES, 'UTF-8') . '</span>';
  echo '    <h3><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars((string) ($producto['nombre'] ?? ''), ENT_QUOTES, 'UTF-8') . '</a></h3>';
  echo '    <p>' . htmlspecialchars((string) ($producto['resumen'] ?? ''), ENT_QUOTES, 'UTF-8') . '</p>';
  echo '    <div class="tv_producto_meta">';
  echo '      <strong>$' . number_format((int) ($producto['precio'] ?? 0), 0, ',', '.') . '</strong>';

  if ((int) ($producto['precio_anterior'] ?? 0) > (int) ($producto['precio'] ?? 0)) {
    echo '      <span>$' . number_format((int) ($producto['precio_anterior'] ?? 0), 0, ',', '.') . '</span>';
  }

  echo '    </div>';
  echo '    <div class="tv_producto_extra">';
  echo '      <span>★ ' . number_format((float) ($producto['rating'] ?? 0), 1, ',', '.') . '</span>';
  echo '      <span>' . ((int) ($producto['stock'] ?? 0) > 0 ? 'Disponible' : 'Sin stock') . '</span>';
  echo '    </div>';
  echo '    <div class="tv_producto_acciones">';
  echo '      <a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" class="tv_btn tv_btn_secundario">Ver detalle</a>';
  echo '      <form action="/carrito/" method="post" class="tv_form_agregar_carrito">';
  echo '        <input type="hidden" name="accion" value="agregar">';
  echo '        <input type="hidden" name="slug" value="' . htmlspecialchars((string) ($producto['slug'] ?? ''), ENT_QUOTES, 'UTF-8') . '">';
  echo '        <input type="hidden" name="cantidad" value="1">';
  echo '        <input type="hidden" name="redireccion" value="' . htmlspecialchars((string) ($_SERVER['REQUEST_URI'] ?? '/'), ENT_QUOTES, 'UTF-8') . '">';
  echo '        <button type="submit" class="tv_btn tv_btn_principal">Agregar</button>';
  echo '      </form>';
  echo '    </div>';
  echo '  </div>';
  echo '</article>';
}

function tienda_render_carrito_drawer($carrito = []) {
  echo '<input type="hidden" id="token_tienda_publica" value="' . htmlspecialchars($_SESSION['tienda_publica_token'], ENT_QUOTES, 'UTF-8') . '">';
  echo '<input type="hidden" id="controlador_tienda_carrito_publica" value="/app/Controllers/tienda_carrito_controller.php">';
  echo '<div id="div_toast_tienda_publica" class="tv_toast_contenedor" aria-live="polite"></div>';
  echo '<div id="div_backdrop_carrito_tienda_publica" class="tv_drawer_backdrop tv_oculto"></div>';
  echo '<aside id="aside_carrito_tienda_publica" class="tv_drawer_carrito" aria-hidden="true">';
  echo '  <div class="tv_drawer_encabezado">';
  echo '    <div>';
  echo '      <h3>Tu carrito</h3>';
  echo '      <p id="p_resumen_carrito_tienda_publica">' . (int) ($carrito['cantidad'] ?? 0) . ' producto(s) agregado(s)</p>';
  echo '    </div>';
  echo '    <button type="button" id="btn_cerrar_carrito_tienda_publica" class="tv_btn_icono tv_btn_icono_cerrar">×</button>';
  echo '  </div>';
  echo '  <div id="div_items_carrito_tienda_publica" class="tv_drawer_items"></div>';
  echo '  <div id="div_resumen_carrito_tienda_publica" class="tv_drawer_resumen"></div>';
  echo '</aside>';
}

function tienda_render_public_scripts() {
  echo '<script src="/public/assets/js/tienda_store_template.js"></script>';
  echo '<script src="/public/assets/js/tienda_store_peticiones.js"></script>';
  echo '<script src="/public/assets/js/tienda_store.js"></script>';
}
?>
