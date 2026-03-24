<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/config/configdb.php';

configdb_cerrar_sesion_tienda_admin();
configdb_limpiar_sesion_tienda_admin();

header('Location: /admin/tienda/');
exit;
?>
