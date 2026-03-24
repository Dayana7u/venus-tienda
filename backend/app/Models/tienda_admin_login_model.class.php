<?php
require_once __DIR__ . '/../../config/configdb.php';

class tienda_admin_login_model {
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
    $this->usuario_id    = $_SESSION['tienda_admin_usuario_id'] ?? 0;
  }

  public function tienda_admin_login_inicializar() {
    $this->constante = self::CONSTANTE;
    $this->datos     = [
      'sesion_activa' => !empty($_SESSION['tienda_admin_usuario_id']),
      'token'         => $_SESSION['tienda_admin_token'] ?? '',
    ];

    return [
      'estado'    => true,
      'mensaje'   => 'Inicialización correcta.',
      'constante' => $this->constante,
      'datos'     => $this->datos,
    ];
  }

  public function tienda_admin_login_autenticar($login, $clave) {
    $stmt = null;

    try {
      $login = trim($login);
      $clave = trim($clave);

      if ($login === '' || $clave === '') {
        return [
          'estado'  => false,
          'mensaje' => 'Debe ingresar usuario y clave.',
          'datos'   => [],
        ];
      }

      $sql = "SELECT
"
           . "  usu.usuario_id,
"
           . "  usu.nombres,
"
           . "  usu.apellidos,
"
           . "  usu.login,
"
           . "  usu.correo,
"
           . "  usu.clave,
"
           . "  usu.sw_superusuario
"
           . "FROM
"
           . "  public.usuarios usu
"
           . "WHERE
"
           . "  usu.login = :login
"
           . "  AND usu.estado = B'1'
"
           . "  AND usu.borrado = B'0'
"
           . "LIMIT 1;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':login', $login, PDO::PARAM_STR);
      $stmt->execute();

      $usuario = $stmt->fetch();

      if (!$usuario || !$this->validar_clave_usuario($clave, (string) $usuario['clave'])) {
        return [
          'estado'  => false,
          'mensaje' => 'Usuario o clave incorrectos.',
          'datos'   => [],
        ];
      }

      $this->actualizar_hash_clave_usuario((int) $usuario['usuario_id'], $clave, (string) $usuario['clave']);

      if (!$this->usuario_puede_ingresar_tienda_admin((int) $usuario['usuario_id'], (string) $usuario['sw_superusuario'])) {
        return [
          'estado'  => false,
          'mensaje' => 'El usuario no tiene acceso al panel de tienda.',
          'datos'   => [],
        ];
      }

      session_regenerate_id(true);
      $_SESSION['tienda_admin_usuario_id']              = (int) $usuario['usuario_id'];
      $_SESSION['tienda_admin_usuario_login']           = $usuario['login'];
      $_SESSION['tienda_admin_usuario_correo']          = $usuario['correo'];
      $_SESSION['tienda_admin_usuario_nombre_completo'] = trim($usuario['nombres'] . ' ' . $usuario['apellidos']);
      $_SESSION['tienda_admin_roles']                   = $this->consultar_roles_usuario($usuario['usuario_id']);
      $_SESSION['tienda_admin_token_sesion']            = bin2hex(random_bytes(32));

      $this->actualizar_ultimo_ingreso($usuario['usuario_id']);
      $this->registrar_sesion_usuario_tienda((int) $usuario['usuario_id']);

      return [
        'estado'  => true,
        'mensaje' => 'Ingreso realizado correctamente.',
        'datos'   => [
          'usuario_id'              => (int) $usuario['usuario_id'],
          'usuario_login'           => $usuario['login'],
          'usuario_nombre_completo' => $_SESSION['tienda_admin_usuario_nombre_completo'],
          'roles'                   => $_SESSION['tienda_admin_roles'],
          'redireccion'             => '/admin/tienda/dashboard/',
        ],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $login);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible validar el acceso.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function tienda_admin_login_validar_sesion() {
    if (empty($_SESSION['tienda_admin_usuario_id'])) {
      return [
        'estado'  => false,
        'mensaje' => 'No existe una sesión activa.',
        'datos'   => [],
      ];
    }

    if (!configdb_validar_sesion_tienda_admin()) {
      configdb_limpiar_sesion_tienda_admin();

      return [
        'estado'  => false,
        'mensaje' => 'La sesión de tienda ya no está disponible.',
        'datos'   => [],
      ];
    }

    return [
      'estado'  => true,
      'mensaje' => 'Sesión activa.',
      'datos'   => [
        'usuario_id'              => (int) $_SESSION['tienda_admin_usuario_id'],
        'usuario_login'           => $_SESSION['tienda_admin_usuario_login'] ?? '',
        'usuario_nombre_completo' => $_SESSION['tienda_admin_usuario_nombre_completo'] ?? '',
        'roles'                   => $_SESSION['tienda_admin_roles'] ?? [],
      ],
    ];
  }

  private function usuario_puede_ingresar_tienda_admin($usuario_id, $sw_superusuario) {
    if ($sw_superusuario === '1') {
      return true;
    }

    $roles = $this->consultar_roles_usuario($usuario_id);

    foreach ($roles as $rol) {
      if (($rol['codigo'] ?? '') === 'TIENDA_ADMIN') {
        return true;
      }
    }

    return false;
  }

  private function validar_clave_usuario($clave, $clave_guardada) {
    if ($clave_guardada === '') {
      return false;
    }

    if ($this->es_hash_bcrypt($clave_guardada)) {
      if (password_verify($clave, $clave_guardada)) {
        return true;
      }

      return hash_equals($clave_guardada, crypt($clave, $clave_guardada));
    }

    return hash_equals($clave_guardada, $clave);
  }

  private function actualizar_hash_clave_usuario($usuario_id, $clave, $clave_guardada) {
    $stmt = null;

    try {
      if ($this->es_hash_bcrypt($clave_guardada) && !password_needs_rehash($clave_guardada, PASSWORD_DEFAULT)) {
        return;
      }

      $hash = password_hash($clave, PASSWORD_DEFAULT);
      $sql  = "UPDATE public.usuarios
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
            . "  usuario_id = :usuario_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':clave', $hash, PDO::PARAM_STR);
      $stmt->bindValue(':usuario_modificacion', $usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
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

  private function es_hash_bcrypt($clave_guardada) {
    return preg_match('/^\$2[aby]\$/', $clave_guardada) === 1;
  }

  private function consultar_roles_usuario($usuario_id) {
    $stmt  = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  rol.rol_id,
"
           . "  rol.codigo,
"
           . "  rol.nombre
"
           . "FROM
"
           . "  public.usuarios_roles uro
"
           . "INNER JOIN public.roles rol
"
           . "  ON rol.rol_id = uro.rol_id
"
           . "WHERE
"
           . "  uro.usuario_id = :usuario_id
"
           . "  AND uro.estado = B'1'
"
           . "  AND uro.borrado = B'0'
"
           . "  AND rol.estado = B'1'
"
           . "  AND rol.borrado = B'0'
"
           . "ORDER BY
"
           . "  rol.rol_id ASC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $usuario_id);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function actualizar_ultimo_ingreso($usuario_id) {
    $stmt = null;

    try {
      $sql = "UPDATE public.usuarios
"
           . "SET
"
           . "  ultimo_ingreso = NOW(),
"
           . "  usuario_modificacion = :usuario_id,
"
           . "  fecha_modificacion = NOW()
"
           . "WHERE
"
           . "  usuario_id = :usuario_id_actualiza;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_id_actualiza', $usuario_id, PDO::PARAM_INT);
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

  private function registrar_sesion_usuario_tienda($usuario_id) {
    $stmt = null;

    try {
      $sql = "INSERT INTO public.usuarios_sesiones_tienda
"
           . "(
"
           . "  usuario_id,
"
           . "  token,
"
           . "  fecha_inicio,
"
           . "  fecha_expiracion,
"
           . "  ip,
"
           . "  user_agent,
"
           . "  estado,
"
           . "  borrado,
"
           . "  usuario_creacion,
"
           . "  fecha_creacion
"
           . ")
"
           . "VALUES
"
           . "(
"
           . "  :usuario_id,
"
           . "  :token,
"
           . "  NOW(),
"
           . "  NOW() + interval '8 hours',
"
           . "  :ip,
"
           . "  :user_agent,
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW()
"
           . ") RETURNING usuario_sesion_tienda_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':token', $_SESSION['tienda_admin_token_sesion'], PDO::PARAM_STR);
      $stmt->bindValue(':ip', $_SERVER['REMOTE_ADDR'] ?? '', PDO::PARAM_STR);
      $stmt->bindValue(':user_agent', $_SERVER['HTTP_USER_AGENT'] ?? '', PDO::PARAM_STR);
      $stmt->bindValue(':usuario_creacion', $usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      $resultado = $stmt->fetch();
      $_SESSION['tienda_admin_usuario_sesion_id'] = $resultado ? (int) $resultado['usuario_sesion_tienda_id'] : 0;
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
