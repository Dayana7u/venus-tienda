<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/configdb.php';

if (empty($_SESSION['admin_usuario_id']) || !configdb_validar_sesion_administrativa()) {
  configdb_limpiar_sesion_administrativa();
  header('Location: ../../app/Views/login.php');
  exit;
}

require_once __DIR__ . '/../../app/Controllers/tienda_admin_controller.php';
require_once __DIR__ . '/../../app/Views/tienda_admin.php';
?>
