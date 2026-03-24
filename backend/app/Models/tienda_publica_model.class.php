<?php
require_once __DIR__ . '/../../config/configdb.php';

class tienda_publica_model {
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
    $this->usuario_id    = 0;
  }

  public function tienda_publica_inicializar() {
    $this->constante = self::CONSTANTE;
    $this->datos     = [
      'token'  => $_SESSION['tienda_publica_token'] ?? '',
      'modulo' => 'TIENDA_PUBLICA',
    ];

    return [
      'estado'    => true,
      'mensaje'   => 'Inicialización correcta.',
      'constante' => $this->constante,
      'datos'     => $this->datos,
    ];
  }

  public function tienda_publica_listar_portada() {
    return [
      'estado'  => true,
      'mensaje' => 'Consulta realizada correctamente.',
      'datos'   => [
        'branding'      => $this->consultar_branding_tienda_publica(),
        'modulo'        => $this->consultar_modulo_tienda_publica(),
        'menus'         => $this->consultar_menus_tienda_publica(),
        'tema'          => $this->consultar_tema_tienda_publica(),
        'tema_tokens'   => $this->consultar_tema_tokens_tienda_publica(),
        'componentes'   => $this->consultar_tema_componentes_tienda_publica(),
        'parametros'    => $this->consultar_parametros_tienda_publica(),
      ],
    ];
  }

  public function consultar_branding_tienda_publica() {
    $stmt = null;
    $datos = [];

    try {
      $codigo_branding = $this->consultar_valor_modulo_tienda_publica('tienda_publica.branding_activo', 'PRINCIPAL');
      $sql = "SELECT
"
           . "  bra.branding_id,
"
           . "  bra.codigo,
"
           . "  bra.nombre_comercial,
"
           . "  bra.correo_contacto,
"
           . "  bra.telefono_contacto,
"
           . "  bra.direccion,
"
           . "  bra.logo_principal,
"
           . "  bra.favicon,
"
           . "  bra.banner_principal,
"
           . "  bra.mensaje_bienvenida,
"
           . "  bra.texto_footer
"
           . "FROM
"
           . "  system.branding bra
"
           . "WHERE
"
           . "  bra.codigo = :codigo
"
           . "  AND bra.estado = B'1'
"
           . "  AND bra.borrado = B'0'
"
           . "LIMIT 1;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':codigo', $codigo_branding, PDO::PARAM_STR);
      $stmt->execute();

      $datos = $stmt->fetch();

      if (!$datos) {
        return [
          'codigo'             => 'PRINCIPAL',
          'nombre_comercial'   => 'Tienda Virtual',
          'correo_contacto'    => 'soporte@localhost',
          'telefono_contacto'  => '',
          'direccion'          => '',
          'logo_principal'     => '',
          'favicon'            => '',
          'banner_principal'   => '',
          'mensaje_bienvenida' => 'Bienvenido a la tienda virtual.',
          'texto_footer'       => 'Todos los derechos reservados.',
        ];
      }

      return $datos;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'system.branding');

      return [
        'codigo'             => 'PRINCIPAL',
        'nombre_comercial'   => 'Tienda Virtual',
        'correo_contacto'    => 'soporte@localhost',
        'telefono_contacto'  => '',
        'direccion'          => '',
        'logo_principal'     => '',
        'favicon'            => '',
        'banner_principal'   => '',
        'mensaje_bienvenida' => 'Bienvenido a la tienda virtual.',
        'texto_footer'       => 'Todos los derechos reservados.',
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function consultar_modulo_tienda_publica() {
    $stmt = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  mod.modulo_id,
"
           . "  mod.codigo,
"
           . "  mod.nombre,
"
           . "  mod.descripcion,
"
           . "  mod.ruta
"
           . "FROM
"
           . "  system.modulos mod
"
           . "WHERE
"
           . "  mod.codigo = 'TIENDA_PUBLICA'
"
           . "  AND mod.estado = B'1'
"
           . "  AND mod.borrado = B'0'
"
           . "LIMIT 1;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();
      $datos = $stmt->fetch();

      if (!$datos) {
        return [
          'modulo_id'   => 0,
          'codigo'      => 'TIENDA_PUBLICA',
          'nombre'      => 'Tienda pública',
          'descripcion' => 'Vista pública independiente para la tienda comercial.',
          'ruta'        => '/',
          'configuraciones' => [],
        ];
      }

      $datos['configuraciones'] = $this->consultar_modulo_configuraciones_tienda_publica((int) $datos['modulo_id']);

      return $datos;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'system.modulos');

      return [
        'modulo_id'       => 0,
        'codigo'          => 'TIENDA_PUBLICA',
        'nombre'          => 'Tienda pública',
        'descripcion'     => 'Vista pública independiente para la tienda comercial.',
        'ruta'            => '/',
        'configuraciones' => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function consultar_menus_tienda_publica() {
    $stmt = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  men.menu_id,
"
           . "  men.codigo,
"
           . "  men.nombre,
"
           . "  men.ruta,
"
           . "  men.icono,
"
           . "  men.orden,
"
           . "  men.accion
"
           . "FROM
"
           . "  system.menus men
"
           . "INNER JOIN system.modulos mod
"
           . "  ON mod.modulo_id = men.modulo_id
"
           . "WHERE
"
           . "  mod.codigo = 'TIENDA_PUBLICA'
"
           . "  AND men.sw_visible = B'1'
"
           . "  AND men.sw_publico = B'1'
"
           . "  AND men.estado = B'1'
"
           . "  AND men.borrado = B'0'
"
           . "  AND mod.estado = B'1'
"
           . "  AND mod.borrado = B'0'
"
           . "ORDER BY
"
           . "  men.orden ASC,
"
           . "  men.menu_id ASC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[] = $registro;
      }

      return $datos;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'system.menus');
      return [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function consultar_tema_tienda_publica() {
    $stmt = null;
    $datos = [];

    try {
      $codigo_tema = $this->consultar_valor_modulo_tienda_publica('tienda_publica.tema_activo', 'BASE');
      $sql = "SELECT
"
           . "  tem.tema_id,
"
           . "  tem.codigo,
"
           . "  tem.nombre,
"
           . "  tem.descripcion,
"
           . "  tem.version
"
           . "FROM
"
           . "  system.temas tem
"
           . "WHERE
"
           . "  tem.codigo = :codigo
"
           . "  AND tem.estado = B'1'
"
           . "  AND tem.borrado = B'0'
"
           . "LIMIT 1;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':codigo', $codigo_tema, PDO::PARAM_STR);
      $stmt->execute();
      $datos = $stmt->fetch();

      if (!$datos) {
        return [
          'tema_id'      => 0,
          'codigo'       => 'BASE',
          'nombre'       => 'Base',
          'descripcion'  => 'Tema visual base.',
          'version'      => '1.0.0',
        ];
      }

      return $datos;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'system.temas');
      return [
        'tema_id'      => 0,
        'codigo'       => 'BASE',
        'nombre'       => 'Base',
        'descripcion'  => 'Tema visual base.',
        'version'      => '1.0.0',
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function consultar_tema_tokens_tienda_publica() {
    $stmt = null;
    $datos = [];

    try {
      $tema = $this->consultar_tema_tienda_publica();

      if ((int) ($tema['tema_id'] ?? 0) <= 0) {
        return [];
      }

      $sql = "SELECT
"
           . "  ttk.grupo,
"
           . "  ttk.clave,
"
           . "  ttk.valor,
"
           . "  ttk.tipo_dato
"
           . "FROM
"
           . "  system.tema_tokens ttk
"
           . "WHERE
"
           . "  ttk.tema_id = :tema_id
"
           . "  AND ttk.estado = B'1'
"
           . "  AND ttk.borrado = B'0'
"
           . "ORDER BY
"
           . "  ttk.orden ASC,
"
           . "  ttk.tema_token_id ASC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':tema_id', (int) $tema['tema_id'], PDO::PARAM_INT);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[$registro['clave']] = $registro['valor'];
      }

      return $datos;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'system.tema_tokens');
      return [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function consultar_tema_componentes_tienda_publica() {
    $stmt = null;
    $datos = [];

    try {
      $tema = $this->consultar_tema_tienda_publica();

      if ((int) ($tema['tema_id'] ?? 0) <= 0) {
        return [];
      }

      $sql = "SELECT
"
           . "  tco.componente,
"
           . "  tco.propiedad,
"
           . "  tco.valor
"
           . "FROM
"
           . "  system.tema_componentes tco
"
           . "WHERE
"
           . "  tco.tema_id = :tema_id
"
           . "  AND tco.estado = B'1'
"
           . "  AND tco.borrado = B'0'
"
           . "ORDER BY
"
           . "  tco.orden ASC,
"
           . "  tco.tema_componente_id ASC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':tema_id', (int) $tema['tema_id'], PDO::PARAM_INT);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        if (!isset($datos[$registro['componente']])) {
          $datos[$registro['componente']] = [];
        }

        $datos[$registro['componente']][$registro['propiedad']] = $registro['valor'];
      }

      return $datos;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'system.tema_componentes');
      return [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function consultar_parametros_tienda_publica() {
    $stmt = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  par.codigo,
"
           . "  COALESCE(pva.valor, par.valor_defecto) AS valor
"
           . "FROM
"
           . "  system.parametros par
"
           . "LEFT JOIN system.parametro_valores pva
"
           . "  ON pva.parametro_id = par.parametro_id
"
           . "  AND pva.estado = B'1'
"
           . "  AND pva.borrado = B'0'
"
           . "WHERE
"
           . "  par.sw_publico = B'1'
"
           . "  AND par.estado = B'1'
"
           . "  AND par.borrado = B'0'
"
           . "ORDER BY
"
           . "  par.orden ASC,
"
           . "  par.parametro_id ASC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[$registro['codigo']] = $registro['valor'];
      }

      return $datos;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'system.parametros');
      return [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_modulo_configuraciones_tienda_publica($modulo_id) {
    $stmt = null;
    $datos = [];

    try {
      if ($modulo_id <= 0) {
        return [];
      }

      $sql = "SELECT
"
           . "  mco.codigo,
"
           . "  mco.nombre,
"
           . "  mco.descripcion,
"
           . "  mco.tipo_dato,
"
           . "  COALESCE(mco.valor, mco.valor_defecto) AS valor,
"
           . "  mco.accion
"
           . "FROM
"
           . "  system.modulo_configuraciones mco
"
           . "WHERE
"
           . "  mco.modulo_id = :modulo_id
"
           . "  AND mco.estado = B'1'
"
           . "  AND mco.borrado = B'0'
"
           . "ORDER BY
"
           . "  mco.orden ASC,
"
           . "  mco.modulo_configuracion_id ASC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':modulo_id', $modulo_id, PDO::PARAM_INT);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[$registro['codigo']] = [
          'nombre'      => $registro['nombre'],
          'descripcion' => $registro['descripcion'],
          'tipo_dato'   => $registro['tipo_dato'],
          'valor'       => $registro['valor'],
          'accion'      => $registro['accion'],
        ];
      }

      return $datos;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $modulo_id);
      return [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_valor_modulo_tienda_publica($codigo, $valor_defecto) {
    $stmt = null;

    try {
      $sql = "SELECT
"
           . "  COALESCE(mco.valor, mco.valor_defecto) AS valor
"
           . "FROM
"
           . "  system.modulo_configuraciones mco
"
           . "INNER JOIN system.modulos mod
"
           . "  ON mod.modulo_id = mco.modulo_id
"
           . "WHERE
"
           . "  mod.codigo = 'TIENDA_PUBLICA'
"
           . "  AND mco.codigo = :codigo
"
           . "  AND mod.estado = B'1'
"
           . "  AND mod.borrado = B'0'
"
           . "  AND mco.estado = B'1'
"
           . "  AND mco.borrado = B'0'
"
           . "LIMIT 1;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':codigo', $codigo, PDO::PARAM_STR);
      $stmt->execute();
      $registro = $stmt->fetch();

      if (!$registro) {
        return $valor_defecto;
      }

      return $registro['valor'] !== null && $registro['valor'] !== '' ? $registro['valor'] : $valor_defecto;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $codigo);
      return $valor_defecto;
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }
}
?>
