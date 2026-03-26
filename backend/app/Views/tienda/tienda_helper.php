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

    case 'CHECKOUT':
      return '/checkout/';

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

function tienda_texto_es_placeholder_venus_publico($texto = '') {
  $texto = strtolower(trim((string) $texto));

  if ($texto === '') {
    return true;
  }

  $marcadores = [
    'parametriz',
    'bloques',
    'catálogo real',
    'catalogo real',
    'panel administrativo',
    'módulo',
    'modulo',
    'estructura comercial',
    'datos visibles',
    'portada',
    'reutil',
    'continuidad',
    'tema venus',
    'vista separada',
    'vista dedicada',
    'soporte comercial',
    'flujo de compra',
    'identidad visual',
    'preparad',
    'panel tienda',
    'tienda pública',
    'tienda publica',
  ];

  foreach ($marcadores as $marcador) {
    if (strpos($texto, $marcador) !== false) {
      return true;
    }
  }

  return false;
}

function tienda_texto_comercial_venus_publico($tema = [], $texto = '', $valor_defecto = '') {
  $texto = trim((string) $texto);

  if (tienda_tema_es_venus_publico($tema) === false) {
    return $texto !== '' ? $texto : $valor_defecto;
  }

  if (tienda_texto_es_placeholder_venus_publico($texto) === true) {
    return $valor_defecto;
  }

  return $texto !== '' ? $texto : $valor_defecto;
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
  $header = $componentes['header'] ?? [];
  $home_hero = $componentes['home.hero'] ?? [];
  $home_surface = $componentes['home.surface'] ?? [];
  $home_card = $componentes['home.card'] ?? [];
  $cta = $componentes['home.cta'] ?? [];

  $color_primary = $tema_tokens['color.primary'] ?? '#BFAFD0';
  $color_secondary = $tema_tokens['color.secondary'] ?? '#DDD4E7';
  $color_accent = $tema_tokens['color.accent'] ?? '#D4B6CA';
  $color_background = $tema_tokens['color.background'] ?? '#F5F2F8';
  $color_surface = $tema_tokens['color.surface'] ?? '#FFFFFF';
  $color_text = $tema_tokens['color.text'] ?? '#685666';
  $color_text_soft = $tema_tokens['color.text.soft'] ?? '#8A7A88';
  $color_border = $tema_tokens['color.border'] ?? '#BFAFD0';
  $shadow_card = $tema_tokens['shadow.card'] ?? '0 18px 42px rgba(104, 86, 102, 0.10)';
  $button_background = $button_primary['background'] ?? '#BFAFD0';
  $header_background = $header['background'] ?? 'rgba(245, 242, 248, 0.96)';
  $header_border = $header['border_color'] ?? $color_border;
  $nav_link = $header['link_color'] ?? $color_text;
  $nav_link_hover = $header['link_hover_color'] ?? '#685666';
  $search_background = $input_search['background'] ?? $color_surface;
  $search_border = $input_search['border_color'] ?? $color_border;
  $shell_background = $home_surface['background'] ?? 'rgba(255, 255, 255, 0.86)';
  $shell_border = $home_surface['border_color'] ?? $color_border;
  $card_background = $home_card['background'] ?? $card_product['background'] ?? '#FFFFFF';
  $card_border = $home_card['border_color'] ?? $card_product['border_color'] ?? $color_border;
  $hero_panel_background = $home_hero['background'] ?? 'linear-gradient(180deg, #F3D6D3 0%, #F5CFC6 100%)';
  $hero_visual_background = $home_hero['background_alt'] ?? 'linear-gradient(180deg, #DDD4E7 0%, #F5F2F8 100%)';
  $hero_title = $home_hero['title_color'] ?? $color_text;
  $hero_text = $home_hero['text_color'] ?? $color_text_soft;
  $cta_background = $cta['background'] ?? 'linear-gradient(135deg, #DDD4E7 0%, #F3D6D3 100%)';
  $footer_background = $footer['background'] ?? '#685666';
  $footer_border = $footer['border_color'] ?? '#685666';
  $footer_title = $footer['title_color'] ?? '#FFFFFF';
  $footer_text = $footer['color'] ?? '#F5F2F8';
  $footer_copy_border = $footer['copy_border_color'] ?? 'rgba(255, 255, 255, 0.18)';

  $variables = [
    '--tv-color-primary'                => $color_primary,
    '--tv-color-secondary'              => $color_secondary,
    '--tv-color-accent'                 => $color_accent,
    '--tv-color-background'             => $color_background,
    '--tv-color-surface'                => $color_surface,
    '--tv-color-text'                   => $color_text,
    '--tv-color-text-soft'              => $color_text_soft,
    '--tv-color-border'                 => $color_border,
    '--tv-font-heading'                 => '"' . ($tema_tokens['font.family.heading'] ?? 'Playfair Display') . '", serif',
    '--tv-font-base'                    => '"' . ($tema_tokens['font.family.body'] ?? 'Inter') . '", sans-serif',
    '--tv-font-size-base'               => $tema_tokens['font.size.base'] ?? '16px',
    '--tv-radius-sm'                    => $tema_tokens['border.radius.sm'] ?? '8px',
    '--tv-radius-md'                    => $tema_tokens['border.radius.md'] ?? '14px',
    '--tv-radius-lg'                    => $tema_tokens['border.radius.lg'] ?? '22px',
    '--tv-shadow-card'                  => $shadow_card,
    '--tv-badge-background'             => $badge['background'] ?? '#F5CFC6',
    '--tv-badge-text'                   => $badge['color'] ?? '#685666',
    '--tv-badge-border'                 => $badge['border_color'] ?? '#BFAFD0',
    '--tv-footer-background'            => $footer_background,
    '--tv-footer-background-alt'        => $footer['background_alt'] ?? '#5E4E5C',
    '--tv-footer-text'                  => $footer_text,
    '--tv-footer-link'                  => $footer['link_color'] ?? '#FFFFFF',
    '--tv-footer-link-hover-background' => $footer['link_hover_background'] ?? 'rgba(255, 255, 255, 0.12)',
    '--tv-footer-border'                => $footer_border,
    '--tv-topbar-background'            => $topbar['background'] ?? 'linear-gradient(90deg, #DDD4E7 0%, #F3D6D3 100%)',
    '--tv-topbar-text'                  => $topbar['color'] ?? '#685666',
    '--tv-button-primary-background'    => $button_background,
    '--tv-button-primary-text'          => $button_primary['color'] ?? '#FFFFFF',
    '--tv-button-primary-radius'        => $button_primary['border_radius'] ?? '999px',
    '--tv-card-product-background'      => $card_product['background'] ?? '#FFFFFF',
    '--tv-card-product-border'          => $card_product['border_color'] ?? '#BFAFD0',
    '--tv-card-product-shadow'          => $card_product['box_shadow'] ?? '0 16px 36px rgba(104, 86, 102, 0.10)',
    '--tv-input-search-background'      => $search_background,
    '--tv-input-search-border'          => $search_border,
    '--tv-collection-background'        => $collection_card['background'] ?? '#F5F2F8',
    '--tv-collection-border'            => $collection_card['border_color'] ?? '#BFAFD0',
    '--tv-contact-highlight-background' => $contact_highlight['background'] ?? '#F3D6D3',
    '--tv-contact-highlight-border'     => $contact_highlight['border_color'] ?? '#BFAFD0',
    '--tv-venus-page-background'        => 'linear-gradient(180deg, ' . $color_background . ' 0%, #fff8f6 100%)',
    '--tv-venus-header-background'      => $header_background,
    '--tv-venus-header-border'          => $header_border,
    '--tv-venus-nav-link'               => $nav_link,
    '--tv-venus-nav-link-hover'         => $nav_link_hover,
    '--tv-venus-search-background'      => $search_background,
    '--tv-venus-search-border'          => $search_border,
    '--tv-venus-shell-background'       => $shell_background,
    '--tv-venus-shell-border'           => $shell_border,
    '--tv-venus-card-background'        => $card_background,
    '--tv-venus-card-border'            => $card_border,
    '--tv-venus-card-shadow'            => $home_card['box_shadow'] ?? $shadow_card,
    '--tv-venus-hero-panel-background'  => $hero_panel_background,
    '--tv-venus-hero-visual-background' => $hero_visual_background,
    '--tv-venus-hero-title'             => $hero_title,
    '--tv-venus-hero-text'              => $hero_text,
    '--tv-venus-cta-background'         => $cta_background,
    '--tv-venus-footer-background'      => $footer_background,
    '--tv-venus-footer-border'          => $footer_border,
    '--tv-venus-footer-title'           => $footer_title,
    '--tv-venus-footer-text'            => $footer_text,
    '--tv-venus-footer-copy-border'     => $footer_copy_border,
    '--tv-color-rose'                    => '#F3D6D3',
    '--tv-color-peach'                   => '#F5CFC6',
    '--tv-page-background'               => '#F5F2F8',
    '--tv-rating-color'                  => '#C9A24F',
    '--tv-input-background'              => $search_background,
    '--tv-stepper-background'            => '#FFFFFF',
    '--tv-stepper-active-background'     => '#BFAFD0',
    '--tv-stepper-active-text'           => '#FFFFFF',
    '--tv-footer-contrast'               => '#F5F2F8',
  ];

  $css = ":root {\n";

  foreach ($variables as $clave => $valor) {
    $css .= "  {$clave}: {$valor};\n";
  }

  $css .= "}\n";

  return $css;
}

function tienda_obtener_configuracion_modulo_publico($modulo = [], $codigo = '', $valor_defecto = '') {
  $configuraciones = $modulo['configuraciones'] ?? [];

  if (!isset($configuraciones[$codigo])) {
    return $valor_defecto;
  }

  $valor = $configuraciones[$codigo]['valor'] ?? $valor_defecto;
  return $valor !== null && $valor !== '' ? $valor : $valor_defecto;
}

function tienda_obtener_booleano_modulo_publico($modulo = [], $codigo = '', $valor_defecto = true) {
  $valor = strtolower(trim((string) tienda_obtener_configuracion_modulo_publico($modulo, $codigo, $valor_defecto === true ? '1' : '0')));

  return in_array($valor, ['1', 'true', 'si', 'sí', 'yes', 'on'], true);
}

function tienda_obtener_parametro_publico($parametros = [], $codigo = '', $valor_defecto = '') {
  if (!isset($parametros[$codigo])) {
    return $valor_defecto;
  }

  $valor = $parametros[$codigo];
  return $valor !== null && $valor !== '' ? $valor : $valor_defecto;
}

function tienda_obtener_booleano_parametro_publico($parametros = [], $codigo = '', $valor_defecto = true) {
  $valor = strtolower(trim((string) tienda_obtener_parametro_publico($parametros, $codigo, $valor_defecto === true ? '1' : '0')));

  return in_array($valor, ['1', 'true', 'si', 'sí', 'yes', 'on'], true);
}

function tienda_obtener_definicion_campo_publico($modulo = [], $prefijo = '', $definicion = []) {
  return [
    'label'       => tienda_obtener_configuracion_modulo_publico($modulo, $prefijo . '.label', $definicion['label'] ?? ''),
    'placeholder' => tienda_obtener_configuracion_modulo_publico($modulo, $prefijo . '.placeholder', $definicion['placeholder'] ?? ''),
    'visible'     => tienda_obtener_booleano_modulo_publico($modulo, $prefijo . '.visible', $definicion['visible'] ?? true),
    'requerido'   => tienda_obtener_booleano_modulo_publico($modulo, $prefijo . '.required', $definicion['requerido'] ?? true),
  ];
}

function tienda_formatear_metodo_pago_publico($metodo_pago = '') {
  switch ((string) $metodo_pago) {
    case 'tarjeta':
      return 'Tarjeta';

    case 'pse':
      return 'PSE';

    case 'contra_entrega':
      return 'Contra entrega';

    default:
      return ucfirst(str_replace('_', ' ', (string) $metodo_pago));
  }
}

function tienda_formatear_telefono_whatsapp_publico($telefono = '') {
  $numero = preg_replace('/\D+/', '', (string) $telefono);

  if ($numero === '') {
    return '';
  }

  if (strlen($numero) == 10) {
    return '57' . $numero;
  }

  if (strpos($numero, '57') === 0) {
    return $numero;
  }

  return $numero;
}

function tienda_tema_es_venus_publico($tema = []) {
  $codigo_tema = is_array($tema) ? (string) ($tema['codigo'] ?? '') : (string) $tema;
  return strtolower(trim($codigo_tema)) === 'venus';
}

function tienda_obtener_logo_tema_publico($branding = [], $tema = []) {
  $logo_principal = trim((string) ($branding['logo_principal'] ?? ''));

  if (tienda_tema_es_venus_publico($tema)) {
    return '/public/assets/img/themes/venus/logo_venus_principal.png';
  }

  if ($logo_principal !== '') {
    return $logo_principal;
  }

  return '';
}

function tienda_render_logo_publico($branding = [], $tema = [], $clase = 'tv_logo') {
  $nombre = trim((string) ($branding['nombre_comercial'] ?? 'Tienda Pública Base'));
  $logo = tienda_obtener_logo_tema_publico($branding, $tema);

  if ($logo !== '') {
    return '<span class="' . htmlspecialchars($clase, ENT_QUOTES, 'UTF-8') . ' tv_logo_marca"><img src="' . htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '" class="tv_logo_imagen"><span class="tv_sr_only">' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '</span></span>';
  }

  return '<span class="' . htmlspecialchars($clase, ENT_QUOTES, 'UTF-8') . ' tv_logo_texto">' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '</span>';
}

function tienda_render_icono_metodo_pago_publico($metodo = '', $icono = '') {
  $icono = trim((string) $icono);

  if ($icono !== '') {
    return '<span class="tv_checkout_metodo_icono_media"><img src="' . htmlspecialchars($icono, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars((string) $metodo, ENT_QUOTES, 'UTF-8') . '"></span>';
  }

  switch ((string) $metodo) {
    case 'tarjeta':
      return '<span class="tv_checkout_metodo_icono_svg" aria-hidden="true"><svg viewBox="0 0 64 64" focusable="false"><rect x="8" y="14" width="48" height="36" rx="10"></rect><path d="M8 24h48"></path><path d="M18 39h10"></path><path d="M34 39h12"></path></svg></span>';

    case 'pse':
      return '<span class="tv_checkout_metodo_icono_svg" aria-hidden="true"><svg viewBox="0 0 64 64" focusable="false"><path d="M14 24h36a6 6 0 0 1 6 6v10a6 6 0 0 1-6 6H14z"></path><path d="M20 20h24"></path><path d="M24 34h12"></path><path d="M46 20l4 4-4 4"></path></svg></span>';

    case 'contra_entrega':
      return '<span class="tv_checkout_metodo_icono_svg" aria-hidden="true"><svg viewBox="0 0 64 64" focusable="false"><path d="M14 24h26v22H14z"></path><path d="M40 30h8l4 6v10H40z"></path><circle cx="24" cy="48" r="4"></circle><circle cx="46" cy="48" r="4"></circle></svg></span>';

    default:
      return '<span class="tv_checkout_metodo_icono_svg" aria-hidden="true"><svg viewBox="0 0 64 64" focusable="false"><circle cx="32" cy="32" r="18"></circle></svg></span>';
  }
}

function tienda_generar_url_whatsapp_soporte_publico($telefono = '', $mensaje = '') {
  $telefono = tienda_formatear_telefono_whatsapp_publico($telefono);

  if ($telefono === '') {
    return '';
  }

  return 'https://wa.me/' . rawurlencode($telefono) . '?text=' . rawurlencode((string) $mensaje);
}

function tienda_normalizar_codigo_tema_publico($tema = []) {
  $codigo_tema = is_array($tema) ? (string) ($tema['codigo'] ?? '') : (string) $tema;
  $codigo_tema = strtolower(trim($codigo_tema));
  $codigo_tema = preg_replace('/[^a-z0-9]+/', '_', $codigo_tema);
  return trim((string) $codigo_tema, '_');
}

function tienda_obtener_archivo_css_tema_publico($tema = []) {
  $codigo_tema = tienda_normalizar_codigo_tema_publico($tema);

  if ($codigo_tema === '') {
    return '';
  }

  $ruta_archivo = __DIR__ . '/../../../public/assets/css/themes/tienda/' . $codigo_tema . '.css';

  if (!is_file($ruta_archivo)) {
    return '';
  }

  return '/public/assets/css/themes/tienda/' . $codigo_tema . '.css';
}

function tienda_render_head($titulo, $tema_tokens = [], $componentes = [], $tema = []) {
  $css_variables = tienda_generar_variables_css($tema_tokens, $componentes);
  $archivo_css_tema = tienda_obtener_archivo_css_tema_publico($tema);

  echo '<!DOCTYPE html>';
  echo '<html lang="es">';
  echo '<head>';
  echo '  <meta charset="UTF-8">';
  echo '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
  echo '  <title>' . htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') . '</title>';
  echo '  <link rel="stylesheet" href="/public/assets/css/tienda_publica.css">';

  if ($archivo_css_tema !== '') {
    echo '  <link rel="stylesheet" href="' . htmlspecialchars($archivo_css_tema, ENT_QUOTES, 'UTF-8') . '">';
  }

  echo '  <style>' . PHP_EOL . $css_variables . '  </style>';
  echo '</head>';
}

function tienda_render_topbar($modulo = [], $tema = []) {
  $configuraciones = $modulo['configuraciones'] ?? [];
  $mensaje = tienda_texto_comercial_venus_publico(
    $tema,
    (string) ($configuraciones['tienda_publica.topbar_texto']['valor'] ?? ''),
    'Entrega nacional · Edición premium · Compra segura'
  );

  echo '<section class="tv_topbar">';
  echo '  <div class="tv_topbar_contenido">';
  echo '    <span class="tv_topbar_pill">Nuevo tema VENUS</span>';
  echo '    <p>' . htmlspecialchars((string) $mensaje, ENT_QUOTES, 'UTF-8') . '</p>';
  echo '  </div>';
  echo '</section>';
}

function tienda_render_header($branding = [], $menus = [], $pagina_activa = '', $tema = []) {
  $nombre = $branding['nombre_comercial'] ?? 'Tienda Pública Base';
  $total_carrito = tienda_obtener_total_carrito();
  $es_tema_venus = tienda_tema_es_venus_publico($tema);
  $subtitulo = $es_tema_venus ? 'Maquillaje · Skincare · Accesorios' : 'Compra en línea';

  echo '<header class="tv_header tv_header_publico' . ($es_tema_venus ? ' tv_header_venus' : '') . '">';
  echo '  <div class="tv_header_superior">';
  echo '    <div class="tv_header_marca">';
  echo '      <a href="/" class="tv_logo_link tv_logo_link_stack" aria-label="' . htmlspecialchars((string) $nombre, ENT_QUOTES, 'UTF-8') . '">';
  echo '        ' . tienda_render_logo_publico($branding, $tema, 'tv_logo') . '';
  echo '        <span class="tv_logo_caption">' . htmlspecialchars((string) $subtitulo, ENT_QUOTES, 'UTF-8') . '</span>';
  echo '      </a>';
  echo '    </div>';
  echo '    <div class="tv_header_centro">';
  echo '      <nav class="tv_nav">';

  foreach ($menus as $menu) {
    $codigo = $menu['codigo'] ?? '';
    $ruta = tienda_obtener_ruta_publica($codigo, $menu['ruta'] ?? '/');
    $clase = $pagina_activa === $codigo ? ' tv_nav_link_activo' : '';
    echo '        <a href="' . htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8') . '" class="tv_nav_link' . $clase . '">' . htmlspecialchars($menu['nombre'], ENT_QUOTES, 'UTF-8') . '</a>';
  }

  echo '      </nav>';
  echo '    </div>';
  echo '    <div class="tv_header_acciones">';
  echo '      <form action="/catalogo/" method="get" class="tv_buscador_campo">';
  echo '        <input type="text" name="buscar" placeholder="Buscar maquillaje, skincare o accesorios" aria-label="Buscar producto">';
  echo '        <button type="submit" class="tv_buscador_boton" aria-label="Buscar">';
  echo '          <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"></circle><path d="M16 16l4.5 4.5"></path></svg>';
  echo '        </button>';
  echo '      </form>';
  echo '      <a href="/contacto/" class="tv_btn_icono tv_btn_icono_header" aria-label="Mi cuenta y contacto">';
  echo '        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 19c1.5-3 4-4.5 7-4.5s5.5 1.5 7 4.5"></path></svg>';
  echo '      </a>';
  echo '      <button type="button" id="btn_abrir_carrito_tienda_publica" class="tv_btn_icono tv_btn_icono_header tv_btn_carrito_encabezado" aria-label="Carrito">';
  echo '        <span class="tv_btn_carrito_icono">';
  echo '          <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="19" r="1.5"></circle><circle cx="17" cy="19" r="1.5"></circle><path d="M4 5h2l2.2 9h8.8l2-6H8.2"></path></svg>';
  echo '        </span>';
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

function tienda_render_footer($branding = [], $menus = [], $tema = []) {
  $nombre = trim((string) ($branding['nombre_comercial'] ?? 'VENUS'));
  $texto_footer = tienda_texto_comercial_venus_publico(
    $tema,
    (string) ($branding['texto_footer'] ?? ''),
    'Cosmética premium con una estética editorial, femenina y limpia para cada momento de tu rutina.'
  );
  $correo = trim((string) ($branding['correo_contacto'] ?? 'contacto@venusbeauty.co'));
  $telefono = trim((string) ($branding['telefono_contacto'] ?? ''));
  $direccion = trim((string) ($branding['direccion'] ?? ''));
  $menus_footer = [];
  $badges_footer = ['Despacho nacional', 'Pago protegido', 'Atención personalizada'];

  foreach ($menus as $menu) {
    $codigo = strtoupper((string) ($menu['codigo'] ?? ''));

    if (!in_array($codigo, ['INICIO', 'CATALOGO', 'OFERTAS', 'CONTACTO'], true)) {
      continue;
    }

    $menus_footer[] = [
      'nombre' => (string) ($menu['nombre'] ?? $codigo),
      'ruta'   => tienda_obtener_ruta_publica($codigo, (string) ($menu['ruta'] ?? '/')),
    ];
  }

  echo '<footer class="tv_footer">';
  echo '  <div class="tv_footer_contenido">';
  echo '    <div class="tv_footer_top">';
  echo '      <div class="tv_footer_logo">' . tienda_render_logo_publico($branding, $tema, 'tv_logo tv_logo_footer') . '</div>';
  echo '      <p class="tv_footer_copy">' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . ' · Belleza curada para una compra más cómoda y elegante</p>';
  echo '    </div>';
  echo '    <div class="tv_footer_badges">';
  foreach ($badges_footer as $badge_footer) {
    echo '      <span>' . htmlspecialchars((string) $badge_footer, ENT_QUOTES, 'UTF-8') . '</span>';
  }
  echo '    </div>';
  echo '    <div class="tv_footer_columnas">';
  echo '      <article class="tv_footer_bloque tv_footer_bloque_marca">';
  echo '        <h3>Sobre VENUS</h3>';
  echo '        <p>' . htmlspecialchars($texto_footer, ENT_QUOTES, 'UTF-8') . '</p>';
  echo '      </article>';
  echo '      <article class="tv_footer_bloque">';
  echo '        <h3>Navegación</h3>';
  echo '        <ul class="tv_footer_lista">';
  foreach ($menus_footer as $menu_footer) {
    echo '          <li><a href="' . htmlspecialchars($menu_footer['ruta'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($menu_footer['nombre'], ENT_QUOTES, 'UTF-8') . '</a></li>';
  }
  echo '        </ul>';
  echo '      </article>';
  echo '      <article class="tv_footer_bloque">';
  echo '        <h3>Contacto</h3>';
  echo '        <ul class="tv_footer_lista">';
  if ($correo !== '') {
    echo '          <li><a href="mailto:' . htmlspecialchars($correo, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($correo, ENT_QUOTES, 'UTF-8') . '</a></li>';
  }
  if ($telefono !== '') {
    echo '          <li><a href="tel:' . htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8') . '</a></li>';
  }
  if ($direccion !== '') {
    echo '          <li><span>' . htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8') . '</span></li>';
  }
  echo '        </ul>';
  echo '      </article>';
  echo '      <article class="tv_footer_bloque">';
  echo '        <h3>Servicio</h3>';
  echo '        <ul class="tv_footer_lista">';
  echo '          <li><span>Entrega nacional</span></li>';
  echo '          <li><span>Pagos seguros</span></li>';
  echo '          <li><span>Atención postcompra</span></li>';
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
  $rating = number_format((float) ($producto['rating'] ?? 0), 1, ',', '.');
  $stock_texto = ((int) ($producto['stock'] ?? 0) > 0 ? 'Disponible' : 'Sin stock');

  echo '<article class="tv_producto_card tv_producto_card_tienda">';
  echo '  <a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" class="tv_producto_media_enlace">';
  tienda_render_producto_media($producto, 'tv_producto_media_card');
  echo '  </a>';
  echo '  <div class="tv_producto_contenido">';
  echo '    <div class="tv_producto_badges">';
  if ((string) ($producto['etiqueta'] ?? '') !== '') {
    echo '      <span class="tv_etiqueta">' . htmlspecialchars((string) ($producto['etiqueta'] ?? ''), ENT_QUOTES, 'UTF-8') . '</span>';
  }
  if ($descuento > 0) {
    echo '      <span class="tv_producto_descuento">-' . $descuento . '%</span>';
  }
  echo '    </div>';
  echo '    <h3><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars((string) ($producto['nombre'] ?? ''), ENT_QUOTES, 'UTF-8') . '</a></h3>';
  echo '    <p>' . htmlspecialchars((string) ($producto['resumen'] ?? ''), ENT_QUOTES, 'UTF-8') . '</p>';
  echo '    <div class="tv_producto_meta">';
  echo '      <strong>$' . number_format((int) ($producto['precio'] ?? 0), 0, ',', '.') . '</strong>';
  if ((int) ($producto['precio_anterior'] ?? 0) > (int) ($producto['precio'] ?? 0)) {
    echo '      <span>$' . number_format((int) ($producto['precio_anterior'] ?? 0), 0, ',', '.') . '</span>';
  }
  echo '    </div>';
  echo '    <div class="tv_producto_extra">';
  echo '      <span class="tv_rating">★★★★★ <b>' . $rating . '</b></span>';
  echo '      <span class="tv_stock">' . htmlspecialchars($stock_texto, ENT_QUOTES, 'UTF-8') . '</span>';
  echo '    </div>';
  echo '    <div class="tv_producto_acciones">';
  echo '      <a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" class="tv_btn tv_btn_secundario">Ver detalle</a>';
  echo '      <form action="/carrito/" method="post" class="tv_form_agregar_carrito">';
  echo '        <input type="hidden" name="accion" value="agregar">';
  echo '        <input type="hidden" name="slug" value="' . htmlspecialchars((string) ($producto['slug'] ?? ''), ENT_QUOTES, 'UTF-8') . '">';
  echo '        <input type="hidden" name="cantidad" value="1">';
  echo '        <input type="hidden" name="redireccion" value="' . htmlspecialchars((string) ($_SERVER['REQUEST_URI'] ?? '/'), ENT_QUOTES, 'UTF-8') . '">';
  echo '        <button type="submit" class="tv_btn tv_btn_principal">Añadir</button>';
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
