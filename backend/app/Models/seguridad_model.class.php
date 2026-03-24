<?php
require_once __DIR__ . '/../../config/configdb.php';

class seguridad_model {
  private $dbh;
  private $configuracion;
  private $modulo = __FILE__;
  private $datos  = [];
  private $constante;
  private const CONSTANTE = true;
  public $usuario_id;

  public function __construct() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $this->configuracion = configdb_obtener_configuracion();
    $this->dbh           = configdb_obtener_conexion();
    $this->usuario_id    = $_SESSION['admin_usuario_id'] ?? 0;
  }

  public function seguridad_inicializar() {
    $this->constante = self::CONSTANTE;
    $this->datos     = [
      'usuario_id'          => (int) $this->usuario_id,
      'usuario_sesion_id'   => (int) ($_SESSION['admin_usuario_sesion_id'] ?? 0),
      'usuario_nombre'      => $_SESSION['admin_usuario_nombre_completo'] ?? '',
      'resumen'             => $this->consultar_resumen_seguridad(),
      'usuarios'            => $this->consultar_usuarios_seguridad(),
    ];

    return [
      'estado'    => true,
      'mensaje'   => 'Inicialización correcta.',
      'constante' => $this->constante,
      'datos'     => $this->datos,
    ];
  }

  public function seguridad_listar_panel() {
    return [
      'estado'  => true,
      'mensaje' => 'Consulta realizada correctamente.',
      'datos'   => [
        'resumen'          => $this->consultar_resumen_seguridad(),
        'sesiones_activas' => $this->consultar_sesiones_activas(),
        'historial'        => $this->consultar_historial_seguridad(),
        'usuarios'         => $this->consultar_usuarios_seguridad(),
      ],
    ];
  }

  public function seguridad_cerrar_sesion($usuario_sesion_id) {
    $stmt = null;

    try {
      if ($usuario_sesion_id <= 0) {
        return [
          'estado'  => false,
          'mensaje' => 'Debe indicar la sesión a cerrar.',
          'datos'   => [],
        ];
      }

      if ((int) ($_SESSION['admin_usuario_sesion_id'] ?? 0) === $usuario_sesion_id) {
        return [
          'estado'  => false,
          'mensaje' => 'No es posible cerrar la sesión actual desde esta opción.',
          'datos'   => [],
        ];
      }

      $sql = "UPDATE public.usuarios_sesiones
"
           . "SET
"
           . "  estado = B'0',
"
           . "  fecha_expiracion = NOW(),
"
           . "  usuario_modificacion = :usuario_modificacion,
"
           . "  fecha_modificacion = NOW()
"
           . "WHERE
"
           . "  usuario_sesion_id = :usuario_sesion_id
"
           . "  AND borrado = B'0'
"
           . "  AND estado = B'1';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_sesion_id', $usuario_sesion_id, PDO::PARAM_INT);
      $stmt->execute();

      if ($stmt->rowCount() <= 0) {
        return [
          'estado'  => false,
          'mensaje' => 'La sesión seleccionada ya no se encuentra activa.',
          'datos'   => [],
        ];
      }

      return [
        'estado'  => true,
        'mensaje' => 'La sesión fue cerrada correctamente.',
        'datos'   => [],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $usuario_sesion_id);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible cerrar la sesión seleccionada.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function seguridad_cerrar_otras_sesiones() {
    $stmt = null;

    try {
      $sql = "UPDATE public.usuarios_sesiones
"
           . "SET
"
           . "  estado = B'0',
"
           . "  fecha_expiracion = NOW(),
"
           . "  usuario_modificacion = :usuario_modificacion,
"
           . "  fecha_modificacion = NOW()
"
           . "WHERE
"
           . "  usuario_id = :usuario_id
"
           . "  AND usuario_sesion_id <> :usuario_sesion_id
"
           . "  AND borrado = B'0'
"
           . "  AND estado = B'1';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_id', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_sesion_id', (int) ($_SESSION['admin_usuario_sesion_id'] ?? 0), PDO::PARAM_INT);
      $stmt->execute();

      return [
        'estado'  => true,
        'mensaje' => 'Las demás sesiones del usuario actual fueron cerradas correctamente.',
        'datos'   => [],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $this->usuario_id);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible cerrar las demás sesiones activas.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function seguridad_cambiar_clave_usuario($usuario_id, $clave_nueva, $clave_confirmacion) {
    $stmt = null;

    try {
      $clave_nueva        = trim($clave_nueva);
      $clave_confirmacion = trim($clave_confirmacion);

      if ($usuario_id <= 0) {
        return [
          'estado'  => false,
          'mensaje' => 'Debe seleccionar el usuario a actualizar.',
          'datos'   => [],
        ];
      }

      if ($clave_nueva === '' || $clave_confirmacion === '') {
        return [
          'estado'  => false,
          'mensaje' => 'Debe ingresar y confirmar la nueva clave.',
          'datos'   => [],
        ];
      }

      if (strlen($clave_nueva) < 8) {
        return [
          'estado'  => false,
          'mensaje' => 'La nueva clave debe tener al menos 8 caracteres.',
          'datos'   => [],
        ];
      }

      if ($clave_nueva !== $clave_confirmacion) {
        return [
          'estado'  => false,
          'mensaje' => 'La confirmación de la clave no coincide.',
          'datos'   => [],
        ];
      }

      $sql = "UPDATE public.usuarios
"
           . "SET
"
           . "  clave = :clave,
"
           . "  usuario_modificacion = :usuario_modificacion,
"
           . "  fecha_modificacion = NOW()
"
           . "WHERE
"
           . "  usuario_id = :usuario_id
"
           . "  AND estado = B'1'
"
           . "  AND borrado = B'0';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':clave', password_hash($clave_nueva, PASSWORD_DEFAULT), PDO::PARAM_STR);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      if ($stmt->rowCount() <= 0) {
        return [
          'estado'  => false,
          'mensaje' => 'El usuario seleccionado no se encuentra disponible.',
          'datos'   => [],
        ];
      }

      $stmt = null;
      $this->cerrar_sesiones_por_cambio_clave($usuario_id);

      return [
        'estado'  => true,
        'mensaje' => 'La clave fue actualizada correctamente.',
        'datos'   => [],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $usuario_id);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible actualizar la clave del usuario.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_resumen_seguridad() {
    $stmt = null;

    try {
      $sql = "SELECT
"
           . "  COUNT(*) FILTER (
"
           . "    WHERE s.estado = B'1'
"
           . "      AND s.borrado = B'0'
"
           . "      AND s.fecha_expiracion >= NOW()
"
           . "  ) AS sesiones_activas,
"
           . "  COUNT(*) FILTER (
"
           . "    WHERE s.estado = B'1'
"
           . "      AND s.borrado = B'0'
"
           . "      AND s.fecha_expiracion >= NOW()
"
           . "      AND s.usuario_id = :usuario_id
"
           . "  ) AS sesiones_usuario_actual,
"
           . "  COUNT(DISTINCT s.usuario_id) FILTER (
"
           . "    WHERE s.estado = B'1'
"
           . "      AND s.borrado = B'0'
"
           . "      AND s.fecha_expiracion >= NOW()
"
           . "  ) AS usuarios_con_sesion,
"
           . "  COUNT(*) FILTER (
"
           . "    WHERE s.borrado = B'0'
"
           . "  ) AS accesos_registrados
"
           . "FROM
"
           . "  public.usuarios_sesiones s;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_id', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      return $stmt->fetch() ?: [];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $this->usuario_id);

      return [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_sesiones_activas() {
    $stmt  = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  s.usuario_sesion_id,
"
           . "  TRIM(u.nombres || ' ' || u.apellidos) AS usuario,
"
           . "  u.login,
"
           . "  COALESCE(s.ip, '--') AS ip,
"
           . "  COALESCE(s.user_agent, '--') AS user_agent,
"
           . "  s.fecha_inicio,
"
           . "  s.fecha_expiracion,
"
           . "  CASE
"
           . "    WHEN s.usuario_sesion_id = :usuario_sesion_id THEN 'Actual'
"
           . "    ELSE 'Activa'
"
           . "  END AS estado_sesion,
"
           . "  CASE
"
           . "    WHEN s.usuario_sesion_id = :usuario_sesion_id THEN '1'
"
           . "    ELSE '0'
"
           . "  END AS sesion_actual
"
           . "FROM
"
           . "  public.usuarios_sesiones s
"
           . "INNER JOIN public.usuarios u
"
           . "  ON u.usuario_id = s.usuario_id
"
           . "WHERE
"
           . "  s.estado = B'1'
"
           . "  AND s.borrado = B'0'
"
           . "  AND s.fecha_expiracion >= NOW()
"
           . "ORDER BY
"
           . "  s.fecha_inicio DESC,
"
           . "  s.usuario_sesion_id DESC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_sesion_id', (int) ($_SESSION['admin_usuario_sesion_id'] ?? 0), PDO::PARAM_INT);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $this->usuario_id);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function consultar_historial_seguridad() {
    $stmt  = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  s.usuario_sesion_id,
"
           . "  TRIM(u.nombres || ' ' || u.apellidos) AS usuario,
"
           . "  u.login,
"
           . "  COALESCE(s.ip, '--') AS ip,
"
           . "  s.fecha_inicio,
"
           . "  s.fecha_expiracion,
"
           . "  s.fecha_modificacion,
"
           . "  CASE
"
           . "    WHEN s.estado = B'1' AND s.fecha_expiracion >= NOW() THEN 'Activa'
"
           . "    WHEN s.estado = B'0' THEN 'Cerrada'
"
           . "    WHEN s.fecha_expiracion < NOW() THEN 'Expirada'
"
           . "    ELSE 'Inactiva'
"
           . "  END AS estado_sesion
"
           . "FROM
"
           . "  public.usuarios_sesiones s
"
           . "INNER JOIN public.usuarios u
"
           . "  ON u.usuario_id = s.usuario_id
"
           . "WHERE
"
           . "  s.borrado = B'0'
"
           . "ORDER BY
"
           . "  s.fecha_inicio DESC,
"
           . "  s.usuario_sesion_id DESC
"
           . "LIMIT 50;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $this->usuario_id);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function consultar_usuarios_seguridad() {
    $stmt  = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  u.usuario_id,
"
           . "  TRIM(u.nombres || ' ' || u.apellidos) AS usuario,
"
           . "  u.login,
"
           . "  u.correo,
"
           . "  u.ultimo_ingreso,
"
           . "  u.fecha_ultimo_cierre_sesion
"
           . "FROM
"
           . "  public.usuarios u
"
           . "WHERE
"
           . "  u.estado = B'1'
"
           . "  AND u.borrado = B'0'
"
           . "ORDER BY
"
           . "  u.nombres ASC,
"
           . "  u.apellidos ASC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $this->usuario_id);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function cerrar_sesiones_por_cambio_clave($usuario_id) {
    $stmt = null;

    try {
      $sql = "UPDATE public.usuarios_sesiones
"
           . "SET
"
           . "  estado = B'0',
"
           . "  fecha_expiracion = NOW(),
"
           . "  usuario_modificacion = :usuario_modificacion,
"
           . "  fecha_modificacion = NOW()
"
           . "WHERE
"
           . "  usuario_id = :usuario_id
"
           . "  AND borrado = B'0'
"
           . "  AND estado = B'1'";

      if ((int) $usuario_id === (int) $this->usuario_id) {
        $sql .= "
  AND usuario_sesion_id <> :usuario_sesion_id";
      }

      $sql .= ';';

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);

      if ((int) $usuario_id === (int) $this->usuario_id) {
        $stmt->bindValue(':usuario_sesion_id', (int) ($_SESSION['admin_usuario_sesion_id'] ?? 0), PDO::PARAM_INT);
      }

      $stmt->execute();
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $usuario_id);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }
}
?>
