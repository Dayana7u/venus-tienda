<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/config/configdb.php';

if (!empty($_SESSION['admin_usuario_id']) && configdb_validar_sesion_administrativa()) {
  header('Location: app/Views/parametrizacion.php');
  exit;
}

if (!empty($_SESSION['admin_usuario_id'])) {
  configdb_limpiar_sesion_administrativa();
}

header('Location: app/Views/login.php');
exit;
?>
