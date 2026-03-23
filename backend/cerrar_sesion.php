<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/config/configdb.php';

$dbh  = null;
$stmt = null;

try {
  if (!empty($_SESSION['admin_usuario_sesion_id']) && !empty($_SESSION['admin_usuario_id'])) {
    $dbh = configdb_obtener_conexion();

    $sql = "UPDATE public.usuarios_sesiones
"
         . "SET
"
         . "  estado = B'0',
"
         . "  fecha_cierre = NOW(),
"
         . "  usuario_modificacion = :usuario_modificacion,
"
         . "  fecha_modificacion = NOW()
"
         . "WHERE
"
         . "  usuario_sesion_id = :usuario_sesion_id;";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':usuario_modificacion', $_SESSION['admin_usuario_id'], PDO::PARAM_INT);
    $stmt->bindValue(':usuario_sesion_id', $_SESSION['admin_usuario_sesion_id'], PDO::PARAM_INT);
    $stmt->execute();

    $stmt = null;

    $sql = "UPDATE public.usuarios
"
         . "SET
"
         . "  fecha_ultimo_cierre_sesion = NOW(),
"
         . "  usuario_modificacion = :usuario_modificacion,
"
         . "  fecha_modificacion = NOW()
"
         . "WHERE
"
         . "  usuario_id = :usuario_id;";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':usuario_modificacion', $_SESSION['admin_usuario_id'], PDO::PARAM_INT);
    $stmt->bindValue(':usuario_id', $_SESSION['admin_usuario_id'], PDO::PARAM_INT);
    $stmt->execute();
  }
}
catch (Throwable $throwable) {
  configdb_registrar_log(__FILE__, __FUNCTION__, $throwable->getMessage(), '');
}
finally {
  if ($stmt) {
    $stmt = null;
  }

  if ($dbh) {
    $dbh = null;
  }
}

unset(
  $_SESSION['admin_usuario_id'],
  $_SESSION['admin_usuario_login'],
  $_SESSION['admin_usuario_correo'],
  $_SESSION['admin_usuario_nombre_completo'],
  $_SESSION['admin_sw_superusuario'],
  $_SESSION['admin_roles'],
  $_SESSION['admin_token_sesion'],
  $_SESSION['admin_usuario_sesion_id']
);

$_SESSION['admin_token'] = bin2hex(random_bytes(32));
session_regenerate_id(true);

header('Location: app/Views/login.php');
exit;
?>
