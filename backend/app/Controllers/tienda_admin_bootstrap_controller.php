<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../Models/tienda_admin_login_model.class.php';
require_once __DIR__ . '/../Models/tienda_admin_model.class.php';

$tienda_admin_login_model = new tienda_admin_login_model();
$tienda_admin_validacion  = $tienda_admin_login_model->tienda_admin_login_validar_sesion();

if (($tienda_admin_validacion['estado'] ?? false) !== true) {
  header('Location: /admin/tienda/');
  exit;
}

$tienda_admin_model = new tienda_admin_model();
$tienda_admin_datos = $tienda_admin_model->tienda_admin_listar_dashboard();
$tda_datos          = ($tienda_admin_datos['datos'] ?? []);
$tda_pagina_activa  = $tda_pagina_activa ?? 'DASHBOARD';
