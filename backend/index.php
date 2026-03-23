<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!empty($_SESSION['admin_usuario_id'])) {
  header('Location: app/Views/parametrizacion.php');
  exit;
}

header('Location: app/Views/login.php');
exit;
?>
