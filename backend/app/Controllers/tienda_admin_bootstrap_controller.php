<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../Models/tienda_admin_login_model.class.php';
require_once __DIR__ . '/../Models/tienda_admin_model.class.php';
require_once __DIR__ . '/../Models/tienda_publica_model.class.php';

$tienda_admin_login_model = new tienda_admin_login_model();
$tienda_admin_validacion  = $tienda_admin_login_model->tienda_admin_login_validar_sesion();

if (($tienda_admin_validacion['estado'] ?? false) !== true) {
  header('Location: /admin/tienda/');
  exit;
}

function tienda_admin_bootstrap_tiene_permiso($codigo) {
  if (($_SESSION['tienda_admin_sw_superusuario'] ?? '0') === '1') {
    return true;
  }

  $permisos = $_SESSION['tienda_admin_permisos'] ?? [];
  return is_array($permisos) && in_array((string) $codigo, $permisos, true);
}

function tienda_admin_bootstrap_obtener_ruta_permitida() {
  $rutas = [
    'TIENDA_DASHBOARD_VER'  => '/admin/tienda/dashboard/',
    'TIENDA_PEDIDOS_VER'    => '/admin/tienda/pedidos/',
    'TIENDA_CLIENTES_VER'   => '/admin/tienda/clientes/',
    'TIENDA_VENTAS_VER'     => '/admin/tienda/ventas/',
    'TIENDA_PAGOS_VER'      => '/admin/tienda/pagos/',
    'TIENDA_CATEGORIAS_VER' => '/admin/tienda/categorias/',
    'TIENDA_PRODUCTOS_VER'  => '/admin/tienda/productos/',
    'TIENDA_IMAGENES_VER'   => '/admin/tienda/imagenes/',
    'TIENDA_AUDITORIA_VER'  => '/admin/tienda/auditoria/',
  ];

  foreach ($rutas as $permiso => $ruta) {
    if (tienda_admin_bootstrap_tiene_permiso($permiso)) {
      return $ruta;
    }
  }

  return '/admin/tienda/';
}

function tienda_admin_bootstrap_validar_pagina($pagina_activa) {
  $mapa = [
    'DASHBOARD'  => 'TIENDA_DASHBOARD_VER',
    'PEDIDOS'    => 'TIENDA_PEDIDOS_VER',
    'CLIENTES'   => 'TIENDA_CLIENTES_VER',
    'VENTAS'     => 'TIENDA_VENTAS_VER',
    'PAGOS'      => 'TIENDA_PAGOS_VER',
    'CATEGORIAS' => 'TIENDA_CATEGORIAS_VER',
    'PRODUCTOS'  => 'TIENDA_PRODUCTOS_VER',
    'IMAGENES'   => 'TIENDA_IMAGENES_VER',
    'AUDITORIA'  => 'TIENDA_AUDITORIA_VER',
  ];

  $permiso = $mapa[strtoupper((string) $pagina_activa)] ?? 'TIENDA_DASHBOARD_VER';
  return tienda_admin_bootstrap_tiene_permiso($permiso);
}

if (!tienda_admin_bootstrap_validar_pagina($tda_pagina_activa ?? 'DASHBOARD')) {
  header('Location: ' . tienda_admin_bootstrap_obtener_ruta_permitida());
  exit;
}

$tienda_admin_model   = new tienda_admin_model();
$tienda_publica_model = new tienda_publica_model();
$tienda_admin_datos   = $tienda_admin_model->tienda_admin_listar_dashboard();
$tda_datos            = ($tienda_admin_datos['datos'] ?? []);
$tda_tema             = $tienda_publica_model->consultar_tema_tienda_publica();
$tda_branding         = $tienda_publica_model->consultar_branding_tienda_publica();
$tda_pagina_activa    = $tda_pagina_activa ?? 'DASHBOARD';
