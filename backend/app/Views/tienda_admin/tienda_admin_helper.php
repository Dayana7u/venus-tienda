<?php
require_once __DIR__ . '/../../../config/configdb.php';

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

function tienda_admin_obtener_permisos_usuario() {
  $permisos = $_SESSION['tienda_admin_permisos'] ?? [];

  return is_array($permisos) ? $permisos : [];
}

function tienda_admin_usuario_tiene_permiso($codigo) {
  if (($_SESSION['tienda_admin_sw_superusuario'] ?? '0') === '1') {
    return true;
  }

  $permisos = tienda_admin_obtener_permisos_usuario();
  return in_array((string) $codigo, $permisos, true);
}

function tienda_admin_obtener_resumen_sidebar($pagina_activa) {
  $resumenes = [
    'DASHBOARD'  => 'Vista central para monitorear el comportamiento comercial de la tienda.',
    'PEDIDOS'    => 'Gestiona estados, pagos, seguimiento y acciones rápidas por pedido.',
    'CLIENTES'   => 'Consulta clientes, direcciones, contacto y actividad comercial.',
    'VENTAS'     => 'Revisa ingresos, descuentos, ticket promedio y comportamiento de venta.',
    'PAGOS'      => 'Consulta transacciones, referencias y estados generados por la pasarela base.',
    'CATEGORIAS' => 'Crea y organiza categorías del catálogo con imagen y orden visual.',
    'PRODUCTOS'  => 'Administra referencias, precios, stock, descuentos e imagen principal.',
    'IMAGENES'   => 'Carga galerías visuales por producto y define material para detalle.',
    'AUDITORIA'  => 'Consulta trazabilidad de acciones ejecutadas dentro del panel comercial.',
  ];

  return $resumenes[strtoupper((string) $pagina_activa)] ?? 'Panel comercial de administración de tienda.';
}


function tienda_admin_obtener_tema_activo() {
  static $tema = null;

  if ($tema !== null) {
    return $tema;
  }

  $tema = [
    'codigo' => 'PINK_NUDE',
    'nombre' => 'Pink Nude',
  ];

  try {
    $dbh = configdb_obtener_conexion();
    $sql = "SELECT
"
         . "  tem.codigo,
"
         . "  tem.nombre
"
         . "FROM
"
         . "  system.modulo_configuraciones mco
"
         . "  INNER JOIN system.temas tem
"
         . "    ON tem.codigo = mco.valor
"
         . "    AND tem.estado = B'1'
"
         . "    AND tem.borrado = B'0'
"
         . "WHERE
"
         . "  mco.codigo = 'tienda_publica.tema_activo'
"
         . "  AND mco.estado = B'1'
"
         . "  AND mco.borrado = B'0'
"
         . "LIMIT 1;";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
      $tema = $registro;
    }
  }
  catch (Throwable $e) {
    $tema = $tema;
  }

  return $tema;
}

function tienda_admin_obtener_css_tema() {
  $tema = tienda_admin_obtener_tema_activo();
  $codigo_tema = strtolower(trim((string) ($tema['codigo'] ?? '')));

  if ($codigo_tema === '') {
    return '';
  }

  $ruta_absoluta = $_SERVER['DOCUMENT_ROOT'] . '/public/assets/css/themes/admin/' . $codigo_tema . '.css';

  if (!file_exists($ruta_absoluta)) {
    return '';
  }

  return '/public/assets/css/themes/admin/' . $codigo_tema . '.css';
}

function tienda_admin_render_head($titulo = 'Admin tienda') {
  $css_tema = tienda_admin_obtener_css_tema();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/assets/css/admin_base.css">
  <link rel="stylesheet" href="/public/assets/css/tienda_admin.css">
  <?php if ($css_tema !== '') { ?>
  <link rel="stylesheet" href="<?php echo htmlspecialchars($css_tema, ENT_QUOTES, 'UTF-8'); ?>">
  <?php } ?>
</head>
<?php
}

function tienda_admin_render_layout_inicio($pagina_activa, $titulo, $subtitulo) {
  $usuario_nombre = $_SESSION['tienda_admin_usuario_nombre_completo'] ?? 'Administrador tienda';
  $usuario_iniciales = tienda_admin_obtener_iniciales_usuario($usuario_nombre);
  $resumen_sidebar = tienda_admin_obtener_resumen_sidebar($pagina_activa);
  $tema_activo = tienda_admin_obtener_tema_activo();
  $tema_codigo = (string) ($tema_activo['codigo'] ?? 'PINK_NUDE');
  $tema_nombre = (string) ($tema_activo['nombre'] ?? 'Pink Nude');
  $logo_inicial = tienda_admin_texto_substring($tema_nombre, 0, 1);
?>
<body class="tda_admin_body" data-pagina-activa="<?php echo htmlspecialchars($pagina_activa, ENT_QUOTES, 'UTF-8'); ?>">
  <input type="hidden" id="token" value="<?php echo htmlspecialchars($_SESSION['tienda_admin_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
  <input type="hidden" id="controlador_tienda_admin" value="/app/Controllers/tienda_admin_controller.php">
  <input type="hidden" id="tienda_admin_permisos_json" value='<?php echo htmlspecialchars(json_encode(tienda_admin_obtener_permisos_usuario()), ENT_QUOTES, 'UTF-8'); ?>'>
  <input type="hidden" id="tienda_admin_superusuario" value="<?php echo htmlspecialchars((string) ($_SESSION['tienda_admin_sw_superusuario'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?>">

  <main class="tda_admin_layout">
    <aside class="tda_admin_sidebar">
      <div class="tda_admin_sidebar_superior">
        <div class="tda_admin_brand tda_admin_brand_stack">
          <div class="tda_admin_brand_logo"><?php echo htmlspecialchars($logo_inicial !== '' ? $logo_inicial : 'T', ENT_QUOTES, 'UTF-8'); ?></div>
          <div>
            <span class="tda_admin_badge">Tienda</span>
            <h1><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></h1>
          </div>
        </div>

        <p class="tda_admin_sidebar_texto"><?php echo htmlspecialchars($resumen_sidebar, ENT_QUOTES, 'UTF-8'); ?></p>

        <nav class="tda_admin_nav">
          <span class="tda_admin_nav_titulo">Comercial</span>
          <?php if (tienda_admin_usuario_tiene_permiso('TIENDA_DASHBOARD_VER')) { ?>
          <a href="/admin/tienda/dashboard/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'DASHBOARD'); ?>">
            <span class="tda_admin_nav_icono">▣</span>
            <span>Dashboard</span>
          </a>
          <?php } ?>
          <?php if (tienda_admin_usuario_tiene_permiso('TIENDA_PEDIDOS_VER')) { ?>
          <a href="/admin/tienda/pedidos/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'PEDIDOS'); ?>">
            <span class="tda_admin_nav_icono">↺</span>
            <span>Pedidos</span>
          </a>
          <?php } ?>
          <?php if (tienda_admin_usuario_tiene_permiso('TIENDA_CLIENTES_VER')) { ?>
          <a href="/admin/tienda/clientes/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'CLIENTES'); ?>">
            <span class="tda_admin_nav_icono">☺</span>
            <span>Clientes</span>
          </a>
          <?php } ?>
          <?php if (tienda_admin_usuario_tiene_permiso('TIENDA_VENTAS_VER')) { ?>
          <a href="/admin/tienda/ventas/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'VENTAS'); ?>">
            <span class="tda_admin_nav_icono">◔</span>
            <span>Ventas</span>
          </a>
          <?php } ?>
          <?php if (tienda_admin_usuario_tiene_permiso('TIENDA_PAGOS_VER')) { ?>
          <a href="/admin/tienda/pagos/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'PAGOS'); ?>">
            <span class="tda_admin_nav_icono">◌</span>
            <span>Pagos</span>
          </a>
          <?php } ?>

          <span class="tda_admin_nav_titulo tda_admin_nav_titulo_mt">Catálogo</span>
          <?php if (tienda_admin_usuario_tiene_permiso('TIENDA_CATEGORIAS_VER')) { ?>
          <a href="/admin/tienda/categorias/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'CATEGORIAS'); ?>">
            <span class="tda_admin_nav_icono">◫</span>
            <span>Categorías</span>
          </a>
          <?php } ?>
          <?php if (tienda_admin_usuario_tiene_permiso('TIENDA_PRODUCTOS_VER')) { ?>
          <a href="/admin/tienda/productos/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'PRODUCTOS'); ?>">
            <span class="tda_admin_nav_icono">◎</span>
            <span>Productos</span>
          </a>
          <?php } ?>
          <?php if (tienda_admin_usuario_tiene_permiso('TIENDA_IMAGENES_VER')) { ?>
          <a href="/admin/tienda/imagenes/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'IMAGENES'); ?>">
            <span class="tda_admin_nav_icono">▤</span>
            <span>Imágenes</span>
          </a>
          <?php } ?>
          <?php if (tienda_admin_usuario_tiene_permiso('TIENDA_AUDITORIA_VER')) { ?>
          <a href="/admin/tienda/auditoria/" class="tda_admin_nav_link<?php echo tienda_admin_clase_nav($pagina_activa, 'AUDITORIA'); ?>">
            <span class="tda_admin_nav_icono">◷</span>
            <span>Auditoría</span>
          </a>
          <?php } ?>
        </nav>
      </div>

      <div class="tda_admin_sidebar_footer">
        <span class="tda_admin_etiqueta">Tema</span>
        <strong><?php echo htmlspecialchars($tema_codigo, ENT_QUOTES, 'UTF-8'); ?></strong>
        <p>Identidad activa para el catálogo y la operación de la tienda. Tema visible: <?php echo htmlspecialchars($tema_nombre, ENT_QUOTES, 'UTF-8'); ?>.</p>
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

      <div id="tda_admin_modal_confirmacion" class="tda_admin_modal tda_admin_modal_oculto" aria-hidden="true">
        <div class="tda_admin_modal_backdrop" data-modal-cerrar="true"></div>
        <div class="tda_admin_modal_dialogo">
          <div class="tda_admin_modal_encabezado">
            <h3 id="tda_admin_modal_confirmacion_titulo">Confirmar acción</h3>
            <button type="button" class="tda_btn_icono tda_btn_icono_modal" data-modal-cerrar="true">×</button>
          </div>
          <div class="tda_admin_modal_contenido">
            <p id="tda_admin_modal_confirmacion_mensaje">¿Desea continuar?</p>
          </div>
          <div class="tda_admin_modal_acciones">
            <button type="button" id="tda_admin_modal_confirmacion_cancelar" class="tda_btn tda_btn_secundario">Cancelar</button>
            <button type="button" id="tda_admin_modal_confirmacion_aceptar" class="tda_btn tda_btn_principal">Aceptar</button>
          </div>
        </div>
      </div>

      <div id="tda_admin_modal_detalle_pedido" class="tda_admin_modal tda_admin_modal_oculto" aria-hidden="true">
        <div class="tda_admin_modal_backdrop" data-modal-detalle-cerrar="true"></div>
        <div class="tda_admin_modal_dialogo tda_admin_modal_dialogo_lg">
          <div class="tda_admin_modal_encabezado">
            <h3>Detalle de pedido</h3>
            <button type="button" class="tda_btn_icono tda_btn_icono_modal" data-modal-detalle-cerrar="true">×</button>
          </div>
          <div id="tda_admin_modal_detalle_pedido_contenido" class="tda_admin_modal_contenido"></div>
          <div class="tda_admin_modal_acciones">
            <button type="button" class="tda_btn tda_btn_secundario" data-modal-detalle-cerrar="true">Cerrar</button>
          </div>
        </div>
      </div>
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
