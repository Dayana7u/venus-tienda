<?php
function tienda_admin_mbstring_disponible() {
  return extension_loaded('mbstring');
}

function tienda_admin_texto_substring($texto, $inicio, $longitud = null) {
  $texto = (string) $texto;

  if (tienda_admin_mbstring_disponible()) {
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

function tienda_admin_texto_mayuscula($texto) {
  $texto = (string) $texto;

  if (tienda_admin_mbstring_disponible()) {
    return mb_strtoupper($texto, 'UTF-8');
  }

  return strtoupper($texto);
}

function tienda_admin_texto_longitud($texto) {
  $texto = (string) $texto;

  if (tienda_admin_mbstring_disponible()) {
    return mb_strlen($texto, 'UTF-8');
  }

  if (function_exists('iconv_strlen')) {
    return (int) iconv_strlen($texto, 'UTF-8');
  }

  return strlen($texto);
}

function tienda_admin_clase_nav($codigo_actual, $codigo_item) {
  return strtoupper((string) $codigo_actual) === strtoupper((string) $codigo_item) ? ' tda_admin_nav_link_activo' : '';
}

function tienda_admin_obtener_iniciales_usuario($nombre) {
  $palabras = preg_split('/\s+/', trim((string) $nombre));
  $iniciales = '';

  foreach ($palabras as $palabra) {
    if ($palabra === '') {
      continue;
    }

    $iniciales .= tienda_admin_texto_mayuscula(tienda_admin_texto_substring($palabra, 0, 1));

    if (tienda_admin_texto_longitud($iniciales) >= 2) {
      break;
    }
  }

  return $iniciales !== '' ? $iniciales : 'TA';
}

function tienda_admin_render_head($titulo = 'Admin tienda') {
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="stylesheet" href="/public/assets/css/admin_base.css">
  <link rel="stylesheet" href="/public/assets/css/tienda_admin.css">
</head>
<?php
}

function tienda_admin_render_layout_inicio($pagina_activa, $titulo, $subtitulo) {
  $usuario_nombre = $_SESSION['tienda_admin_usuario_nombre_completo'] ?? 'Administrador tienda';
  $usuario_iniciales = tienda_admin_obtener_iniciales_usuario($usuario_nombre);
?>
<body class="tda_admin_body" data-pagina-activa="<?php echo htmlspecialchars($pagina_activa, ENT_QUOTES, 'UTF-8'); ?>">
  <input type="hidden" id="token" value="<?php echo htmlspecialchars($_SESSION['tienda_admin_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
  <input type="hidden" id="controlador_tienda_admin" value="/app/Controllers/tienda_admin_controller.php">

  <main class="tda_admin_layout">
    <aside class="tda_admin_sidebar">
      <div class="tda_admin_brand">
        <div class="tda_admin_brand_logo">B</div>
        <div>
          <span class="tda_admin_badge">Tienda</span>
          <h1><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>
      </div>

      <p class="tda_admin_sidebar_texto"><?php echo htmlspecialchars($subtitulo, ENT_QUOTES, 'UTF-8'); ?></p>

      <nav class="tda_admin_nav">
        <span class="tda_admin_nav_titulo">Comercial</span>
        <a href="/admin/tienda/dashboard/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'DASHBOARD'); ?>">
          <span class="tda_admin_nav_icono">▣</span>
          <span>Dashboard</span>
        </a>
        <a href="/admin/tienda/pedidos/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'PEDIDOS'); ?>">
          <span class="tda_admin_nav_icono">↺</span>
          <span>Pedidos</span>
        </a>
        <a href="/admin/tienda/clientes/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'CLIENTES'); ?>">
          <span class="tda_admin_nav_icono">☺</span>
          <span>Clientes</span>
        </a>
        <a href="/admin/tienda/ventas/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'VENTAS'); ?>">
          <span class="tda_admin_nav_icono">◔</span>
          <span>Ventas</span>
        </a>

        <span class="tda_admin_nav_titulo tda_admin_nav_titulo_mt">Catálogo</span>
        <a href="/admin/tienda/categorias/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'CATEGORIAS'); ?>">
          <span class="tda_admin_nav_icono">◫</span>
          <span>Categorías</span>
        </a>
        <a href="/admin/tienda/productos/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'PRODUCTOS'); ?>">
          <span class="tda_admin_nav_icono">◎</span>
          <span>Productos</span>
        </a>
        <a href="/admin/tienda/imagenes/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'IMAGENES'); ?>">
          <span class="tda_admin_nav_icono">▤</span>
          <span>Imágenes</span>
        </a>
      </nav>

      <div class="tda_admin_sidebar_footer">
        <span class="tda_admin_etiqueta">Tema</span>
        <strong>PINK_NUDE</strong>
        <p>Panel comercial inspirado en ecommerce beauty, ajustado a la identidad parametrizable actual.</p>
      </div>
    </aside>

    <section class="tda_admin_contenido">
      <header class="tda_admin_topbar">
        <div class="tda_admin_topbar_busqueda">
          <span class="tda_admin_topbar_icono">⌕</span>
          <input type="text" id="tda_admin_busqueda_general" placeholder="Buscar en el módulo actual">
        </div>

        <div class="tda_admin_topbar_acciones">
          <a href="/" class="tda_btn tda_btn_secundario">Ver tienda</a>
          <a href="/cerrar_sesion_tienda_admin.php" class="tda_btn tda_btn_principal">Cerrar sesión</a>
          <div class="tda_admin_usuario_chip">
            <div class="tda_admin_usuario_avatar"><?php echo htmlspecialchars($usuario_iniciales, ENT_QUOTES, 'UTF-8'); ?></div>
            <div>
              <strong><?php echo htmlspecialchars($usuario_nombre, ENT_QUOTES, 'UTF-8'); ?></strong>
              <span>Sesión activa</span>
            </div>
          </div>
        </div>
      </header>

      <section class="tda_admin_pagina_encabezado">
        <div>
          <span class="tda_admin_badge">Panel tienda</span>
          <h2><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></h2>
          <p><?php echo htmlspecialchars($subtitulo, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      </section>

      <section id="div_mensaje_tienda_admin" class="tda_admin_mensajes" aria-live="polite"></section>
<?php
}

function tienda_admin_render_layout_fin() {
?>
    </section>
  </main>

  <script src="/public/assets/js/tienda_admin_template.js"></script>
  <script src="/public/assets/js/tienda_admin_peticiones.js"></script>
  <script src="/public/assets/js/tienda_admin.js"></script>
</body>
</html>
<?php
}
