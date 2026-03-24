<?php
require_once __DIR__ . '/../../config/configdb.php';

class parametrizacion_model {
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

  public function parametrizacion_inicializar() {
    $this->constante = self::CONSTANTE;

    return [
      'estado'    => true,
      'mensaje'   => 'Inicialización correcta.',
      'constante' => $this->constante,
      'datos'     => [
        'temas'             => $this->parametrizacion_listar_temas(),
        'branding'          => $this->parametrizacion_listar_branding(),
        'parametros'        => $this->parametrizacion_listar_parametros(),
        'modulos'           => $this->parametrizacion_listar_modulos(),
        'integraciones'     => $this->parametrizacion_listar_integraciones(),
        'menus'             => $this->parametrizacion_listar_menus(),
        'roles'             => $this->parametrizacion_listar_roles(),
        'usuarios'          => $this->parametrizacion_listar_usuarios(),
        'parametro_grupos'  => $this->parametrizacion_listar_parametro_grupos(),
        'catalogos'         => $this->parametrizacion_obtener_catalogos(),
      ],
    ];
  }

  public function parametrizacion_listar_temas() {
    $sql = "SELECT
  tema_id,
  codigo,
  nombre,
  descripcion,
  version,
  sw_predeterminado,
  orden,
  estado
FROM
  system.temas
WHERE
  borrado = B'0'
ORDER BY
  tema_id ASC;";

    return $this->ejecutar_consulta_listado($sql);
  }

  public function parametrizacion_listar_branding() {
    $sql = "SELECT
  branding_id,
  codigo,
  nombre_comercial,
  razon_social,
  nit,
  correo_contacto,
  telefono_contacto,
  direccion,
  mensaje_bienvenida,
  texto_footer,
  estado
FROM
  system.branding
WHERE
  borrado = B'0'
ORDER BY
  branding_id ASC;";

    return $this->ejecutar_consulta_listado($sql);
  }

  public function parametrizacion_listar_parametro_grupos() {
    $sql = "SELECT
  parametro_grupo_id,
  codigo,
  nombre,
  descripcion,
  orden,
  estado
FROM
  system.parametro_grupos
WHERE
  borrado = B'0'
ORDER BY
  parametro_grupo_id ASC;";

    return $this->ejecutar_consulta_listado($sql);
  }

  public function parametrizacion_listar_parametros() {
    $sql = "SELECT
  p.parametro_id,
  p.parametro_grupo_id,
  pg.nombre AS parametro_grupo,
  p.codigo,
  p.nombre,
  p.descripcion,
  p.tipo_dato,
  p.valor_defecto,
  p.sw_requerido,
  p.sw_publico,
  p.orden,
  p.estado
FROM
  system.parametros p
INNER JOIN system.parametro_grupos pg
  ON pg.parametro_grupo_id = p.parametro_grupo_id
WHERE
  p.borrado = B'0'
ORDER BY
  p.parametro_id ASC;";

    return $this->ejecutar_consulta_listado($sql);
  }

  public function parametrizacion_listar_modulos() {
    $sql = "SELECT
  modulo_id,
  codigo,
  nombre,
  descripcion,
  ruta,
  icono,
  orden,
  sw_visible_menu,
  sw_requiere_login,
  estado
FROM
  system.modulos
WHERE
  borrado = B'0'
ORDER BY
  modulo_id ASC;";

    return $this->ejecutar_consulta_listado($sql);
  }

  public function parametrizacion_listar_integraciones() {
    $sql = "SELECT
  integracion_id,
  codigo,
  nombre,
  descripcion,
  tipo_autenticacion,
  base_url,
  sw_activa,
  estado
FROM
  system.integraciones
WHERE
  borrado = B'0'
ORDER BY
  integracion_id ASC;";

    return $this->ejecutar_consulta_listado($sql);
  }

  public function parametrizacion_listar_menus() {
    $sql = "SELECT
  m.menu_id,
  m.modulo_id,
  mo.nombre AS modulo,
  m.menu_padre_id,
  COALESCE(mp.nombre, '--') AS menu_padre,
  m.codigo,
  m.nombre,
  m.ruta,
  m.icono,
  m.orden,
  m.nivel,
  m.sw_visible,
  m.sw_publico,
  m.estado
FROM
  system.menus m
INNER JOIN system.modulos mo
  ON mo.modulo_id = m.modulo_id
LEFT JOIN system.menus mp
  ON mp.menu_id = m.menu_padre_id
WHERE
  m.borrado = B'0'
ORDER BY
  m.menu_id ASC;";

    return $this->ejecutar_consulta_listado($sql);
  }

  public function parametrizacion_listar_roles() {
    $sql = "SELECT
  rol_id,
  codigo,
  nombre,
  descripcion,
  sw_predeterminado,
  estado
FROM
  public.roles
WHERE
  borrado = B'0'
ORDER BY
  rol_id ASC;";

    return $this->ejecutar_consulta_listado($sql);
  }

  public function parametrizacion_listar_usuarios() {
    $sql = "SELECT
  u.usuario_id,
  u.nombres,
  u.apellidos,
  u.login,
  u.correo,
  u.sw_superusuario,
  COALESCE(r.nombre, '--') AS rol,
  u.ultimo_ingreso,
  u.fecha_ultimo_cierre_sesion,
  u.estado
FROM
  public.usuarios u
LEFT JOIN public.usuarios_roles ur
  ON ur.usuario_id = u.usuario_id
  AND ur.estado = B'1'
  AND ur.borrado = B'0'
LEFT JOIN public.roles r
  ON r.rol_id = ur.rol_id
  AND r.estado = B'1'
  AND r.borrado = B'0'
WHERE
  u.borrado = B'0'
ORDER BY
  u.usuario_id ASC;";

    return $this->ejecutar_consulta_listado($sql);
  }

  public function parametrizacion_obtener_catalogos() {
    return [
      'parametro_grupos' => $this->parametrizacion_listar_parametro_grupos(),
      'modulos'          => $this->parametrizacion_listar_modulos(),
      'menus'            => $this->parametrizacion_listar_menus(),
      'roles'            => $this->parametrizacion_listar_roles(),
    ];
  }

  public function parametrizacion_consultar_registro($seccion, $registro_id) {
    $metadata = $this->obtener_metadata_tabla($seccion);

    if (!$metadata) {
      return [
        'estado'  => false,
        'mensaje' => 'Sección no válida.',
        'datos'   => [],
      ];
    }

    $stmt = null;

    try {
      $sql = $metadata['consulta_registro'];

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':registro_id', $registro_id, PDO::PARAM_INT);
      $stmt->execute();

      $this->datos = $stmt->fetch();

      if (!$this->datos) {
        return [
          'estado'  => false,
          'mensaje' => 'No se encontró el registro solicitado.',
          'datos'   => [],
        ];
      }

      return [
        'estado'  => true,
        'mensaje' => 'Registro consultado correctamente.',
        'datos'   => $this->datos,
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $seccion);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible consultar el registro.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function parametrizacion_guardar_registro($seccion, $datos) {
    $metadata = $this->obtener_metadata_tabla($seccion);

    if (!$metadata) {
      return [
        'estado'  => false,
        'mensaje' => 'Sección no válida.',
        'datos'   => [],
      ];
    }

    $datos_asignados = $this->asignar_variables_guardado($seccion, $datos);

    if ($datos_asignados['estado'] !== true) {
      return $datos_asignados;
    }

    $validacion = $this->validar_datos_registro($metadata, $datos_asignados['datos']);

    if ($validacion['estado'] !== true) {
      return $validacion;
    }

    if (!empty($datos_asignados['datos'][$metadata['pk']])) {
      return $this->actualizar_registro($metadata, $datos_asignados['datos']);
    }

    return $this->crear_registro($metadata, $datos_asignados['datos']);
  }

  public function parametrizacion_cambiar_estado_registro($seccion, $registro_id, $estado) {
    $metadata = $this->obtener_metadata_tabla($seccion);

    if (!$metadata) {
      return [
        'estado'  => false,
        'mensaje' => 'Sección no válida.',
        'datos'   => [],
      ];
    }

    $stmt = null;

    try {
      $sql = "UPDATE {$metadata['tabla']}
SET
  estado = :estado,
  usuario_modificacion = :usuario_modificacion,
  fecha_modificacion = NOW()
WHERE
  {$metadata['pk']} = :registro_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':estado', $estado === '1' ? '1' : '0', PDO::PARAM_STR);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':registro_id', $registro_id, PDO::PARAM_INT);
      $stmt->execute();

      if ($seccion === 'usuarios') {
        $this->actualizar_estado_relacion_usuario_rol($registro_id, $estado === '1' ? '1' : '0');
      }

      return [
        'estado'  => true,
        'mensaje' => 'Estado actualizado correctamente.',
        'datos'   => [],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $seccion);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible actualizar el estado.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }


  public function parametrizacion_borrar_registro($seccion, $registro_id) {
    $metadata = $this->obtener_metadata_tabla($seccion);

    if (!$metadata) {
      return [
        'estado'  => false,
        'mensaje' => 'Sección no válida.',
        'datos'   => [],
      ];
    }

    $stmt = null;

    try {
      $sql = "UPDATE {$metadata['tabla']}
SET
  estado = B'0',
  borrado = B'1',
  usuario_modificacion = :usuario_modificacion,
  fecha_modificacion = NOW(),
  usuario_borrado = :usuario_borrado,
  fecha_borrado = NOW()
WHERE
  {$metadata['pk']} = :registro_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_borrado', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':registro_id', $registro_id, PDO::PARAM_INT);
      $stmt->execute();

      if ($seccion === 'usuarios') {
        $this->borrar_relacion_usuario_rol($registro_id);
      }

      return [
        'estado'  => true,
        'mensaje' => 'Registro eliminado correctamente.',
        'datos'   => [],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $seccion);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible eliminar el registro.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function crear_registro($metadata, $datos) {
    $stmt = null;

    try {
      $columnas = [];
      $marcadores = [];

      foreach ($metadata['campos'] as $campo) {
        if (array_key_exists($campo, $datos)) {
          $columnas[]   = $campo;
          $marcadores[] = ':' . $campo;
        }
      }

      $columnas[]   = 'usuario_creacion';
      $columnas[]   = 'fecha_creacion';
      $marcadores[] = ':usuario_creacion';
      $marcadores[] = 'NOW()';

      $sql = "INSERT INTO {$metadata['tabla']}
(
  " . implode(",
  ", $columnas) . "
)
VALUES
(
  " . implode(",
  ", $marcadores) . "
) RETURNING {$metadata['pk']};";

      $stmt = $this->dbh->prepare($sql);

      foreach ($metadata['campos'] as $campo) {
        if (array_key_exists($campo, $datos)) {
          $this->bind_param_valor(
            $stmt,
            ':' . $campo,
            $datos[$campo],
            in_array($campo, $metadata['campos_bits'], true)
          );
        }
      }

      $stmt->bindValue(':usuario_creacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      $resultado = $stmt->fetch();
      $registro_id = $resultado ? (int) $resultado[$metadata['pk']] : 0;

      if ($metadata['seccion'] === 'usuarios') {
        $this->gestionar_rol_usuario($registro_id, $datos['rol_id'] ?? 0);
      }

      return [
        'estado'  => true,
        'mensaje' => 'Registro guardado correctamente.',
        'datos'   => [
          'registro_id' => $registro_id,
        ],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $metadata['seccion']);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible guardar el registro.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function actualizar_registro($metadata, $datos) {
    $stmt = null;

    try {
      $set = [];

      foreach ($metadata['campos'] as $campo) {
        if (array_key_exists($campo, $datos)) {
          $set[] = $campo . ' = :' . $campo;
        }
      }

      $set[] = 'usuario_modificacion = :usuario_modificacion';
      $set[] = 'fecha_modificacion = NOW()';

      $sql = "UPDATE {$metadata['tabla']}
SET
  " . implode(",
  ", $set) . "
WHERE
  {$metadata['pk']} = :registro_id;";

      $stmt = $this->dbh->prepare($sql);

      foreach ($metadata['campos'] as $campo) {
        if (array_key_exists($campo, $datos)) {
          $this->bind_param_valor(
            $stmt,
            ':' . $campo,
            $datos[$campo],
            in_array($campo, $metadata['campos_bits'], true)
          );
        }
      }

      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':registro_id', $datos[$metadata['pk']], PDO::PARAM_INT);
      $stmt->execute();

      if ($metadata['seccion'] === 'usuarios') {
        $this->gestionar_rol_usuario((int) $datos[$metadata['pk']], $datos['rol_id'] ?? 0);
      }

      return [
        'estado'  => true,
        'mensaje' => 'Registro actualizado correctamente.',
        'datos'   => [
          'registro_id' => (int) $datos[$metadata['pk']],
        ],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $metadata['seccion']);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible actualizar el registro.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function asignar_variables_guardado($seccion, $datos) {
    $metadata = $this->obtener_metadata_tabla($seccion);
    $datos_limpios = [];

    foreach ($metadata['campos'] as $campo) {
      if ($campo === 'clave') {
        if (isset($datos[$campo]) && trim((string) $datos[$campo]) !== '') {
          $datos_limpios[$campo] = password_hash(trim((string) $datos[$campo]), PASSWORD_DEFAULT);
        }
      }
      else {
        $datos_limpios[$campo] = $this->limpiar_valor($datos[$campo] ?? null);
      }
    }

    foreach ($metadata['campos_extra'] as $campo_extra) {
      $datos_limpios[$campo_extra] = $this->limpiar_valor($datos[$campo_extra] ?? null);
    }

    if ($metadata['pk_post'] !== '') {
      $datos_limpios[$metadata['pk']] = (int) ($datos[$metadata['pk_post']] ?? 0);
    }

    if ($seccion === 'usuarios' && empty($datos_limpios['clave']) && empty($datos_limpios[$metadata['pk']])) {
      return [
        'estado'  => false,
        'mensaje' => 'Debe ingresar la clave del usuario.',
        'datos'   => [],
      ];
    }

    return [
      'estado'  => true,
      'mensaje' => 'Variables asignadas correctamente.',
      'datos'   => $datos_limpios,
    ];
  }

  private function validar_datos_registro($metadata, $datos) {
    $validacion_unicos = $this->validar_registro_duplicado($metadata, $datos);

    if ($validacion_unicos['estado'] !== true) {
      return $validacion_unicos;
    }

    if ($metadata['seccion'] === 'usuarios') {
      if (($datos['sw_superusuario'] ?? '0') !== '1' && (int) ($datos['rol_id'] ?? 0) <= 0) {
        return [
          'estado'  => false,
          'mensaje' => 'Debe seleccionar el rol del usuario.',
          'datos'   => [],
        ];
      }

      if (!filter_var((string) ($datos['correo'] ?? ''), FILTER_VALIDATE_EMAIL)) {
        return [
          'estado'  => false,
          'mensaje' => 'Debe ingresar un correo válido.',
          'datos'   => [],
        ];
      }
    }

    return [
      'estado'  => true,
      'mensaje' => 'Validación correcta.',
      'datos'   => [],
    ];
  }

  private function validar_registro_duplicado($metadata, $datos) {
    $stmt = null;

    try {
      if (empty($metadata['campos_unicos'])) {
        return [
          'estado'  => true,
          'mensaje' => 'Sin validaciones únicas.',
          'datos'   => [],
        ];
      }

      foreach ($metadata['campos_unicos'] as $campo_unico) {
        if (!array_key_exists($campo_unico, $datos) || $datos[$campo_unico] === null || $datos[$campo_unico] === '') {
          continue;
        }

        $sql = "SELECT
  {$metadata['pk']}
FROM
  {$metadata['tabla']}
WHERE
  {$campo_unico} = :valor
  AND borrado = B'0'";

        if (!empty($datos[$metadata['pk']])) {
          $sql .= "
  AND {$metadata['pk']} <> :registro_id";
        }

        $sql .= "
LIMIT 1;";

        $stmt = $this->dbh->prepare($sql);
        $this->bind_param_valor($stmt, ':valor', $datos[$campo_unico], in_array($campo_unico, $metadata['campos_bits'], true));

        if (!empty($datos[$metadata['pk']])) {
          $stmt->bindValue(':registro_id', (int) $datos[$metadata['pk']], PDO::PARAM_INT);
        }

        $stmt->execute();
        $registro = $stmt->fetch();
        $stmt = null;

        if ($registro) {
          return [
            'estado'  => false,
            'mensaje' => 'Ya existe un registro con el mismo valor en ' . $campo_unico . '.',
            'datos'   => [],
          ];
        }
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $metadata['seccion']);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible validar el registro.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return [
      'estado'  => true,
      'mensaje' => 'Validación correcta.',
      'datos'   => [],
    ];
  }

  private function gestionar_rol_usuario($usuario_id, $rol_id) {
    $stmt = null;

    try {
      $sql = "UPDATE public.usuarios_roles
SET
  estado = B'0',
  usuario_modificacion = :usuario_modificacion,
  fecha_modificacion = NOW()
WHERE
  usuario_id = :usuario_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      $stmt = null;

      if ((int) $rol_id <= 0) {
        return;
      }

      $sql = "SELECT
  usuario_rol_id
FROM
  public.usuarios_roles
WHERE
  usuario_id = :usuario_id
  AND rol_id = :rol_id
LIMIT 1;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':rol_id', $rol_id, PDO::PARAM_INT);
      $stmt->execute();

      $registro = $stmt->fetch();
      $stmt = null;

      if ($registro) {
        $sql = "UPDATE public.usuarios_roles
SET
  estado = B'1',
  borrado = B'0',
  usuario_modificacion = :usuario_modificacion,
  fecha_modificacion = NOW()
WHERE
  usuario_rol_id = :usuario_rol_id;";

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_rol_id', $registro['usuario_rol_id'], PDO::PARAM_INT);
        $stmt->execute();
      }
      else {
        $sql = "INSERT INTO public.usuarios_roles
(
  usuario_id,
  rol_id,
  estado,
  borrado,
  usuario_creacion,
  fecha_creacion
)
VALUES
(
  :usuario_id,
  :rol_id,
  B'1',
  B'0',
  :usuario_creacion,
  NOW()
);";

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindValue(':rol_id', $rol_id, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_creacion', $this->usuario_id, PDO::PARAM_INT);
        $stmt->execute();
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
  }

  private function borrar_relacion_usuario_rol($usuario_id) {
    $stmt = null;

    try {
      $sql = "UPDATE public.usuarios_roles
SET
  estado = B'0',
  borrado = B'1',
  usuario_modificacion = :usuario_modificacion,
  fecha_modificacion = NOW(),
  usuario_borrado = :usuario_borrado,
  fecha_borrado = NOW()
WHERE
  usuario_id = :usuario_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_borrado', $this->usuario_id, PDO::PARAM_INT);
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

  private function actualizar_estado_relacion_usuario_rol($usuario_id, $estado) {
    $stmt = null;

    try {
      $sql = "UPDATE public.usuarios_roles
SET
  estado = :estado,
  usuario_modificacion = :usuario_modificacion,
  fecha_modificacion = NOW()
WHERE
  usuario_id = :usuario_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
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

  private function ejecutar_consulta_listado($sql) {
    $stmt = null;

    try {
      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();
      $this->datos = $stmt->fetchAll();

      return $this->datos;
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $sql);
      return [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function limpiar_valor($valor) {
    if ($valor === null) {
      return null;
    }

    if (is_string($valor)) {
      $valor = trim($valor);

      if ($valor === '') {
        return null;
      }
    }

    return $valor;
  }

  private function bind_param_valor($stmt, $parametro, $valor, $campo_bit = false) {
    if ($valor === null) {
      $stmt->bindValue($parametro, null, PDO::PARAM_NULL);
      return;
    }

    if ($campo_bit === true) {
      $stmt->bindValue($parametro, (string) $valor, PDO::PARAM_STR);
      return;
    }

    if (is_numeric($valor) && (string) (int) $valor === (string) $valor) {
      $stmt->bindValue($parametro, (int) $valor, PDO::PARAM_INT);
      return;
    }

    $stmt->bindValue($parametro, $valor, PDO::PARAM_STR);
  }

  private function obtener_metadata_tabla($seccion) {
    $metadata = [
      'temas' => [
        'seccion'       => 'temas',
        'tabla'         => 'system.temas',
        'pk'            => 'tema_id',
        'pk_post'       => 'registro_id',
        'campos_unicos' => ['codigo'],
        'campos'        => [
          'codigo',
          'nombre',
          'descripcion',
          'version',
          'sw_predeterminado',
          'orden',
          'estado',
          'borrado',
        ],
        'campos_bits'   => ['sw_predeterminado', 'estado', 'borrado'],
      ],
      'branding' => [
        'seccion'       => 'branding',
        'tabla'         => 'system.branding',
        'pk'            => 'branding_id',
        'pk_post'       => 'registro_id',
        'campos_unicos' => ['codigo'],
        'campos'        => [
          'codigo',
          'nombre_comercial',
          'razon_social',
          'nit',
          'correo_contacto',
          'telefono_contacto',
          'direccion',
          'mensaje_bienvenida',
          'texto_footer',
          'estado',
          'borrado',
        ],
        'campos_bits'   => ['estado', 'borrado'],
      ],
      'parametros' => [
        'seccion'       => 'parametros',
        'tabla'         => 'system.parametros',
        'pk'            => 'parametro_id',
        'pk_post'       => 'registro_id',
        'campos_unicos' => ['codigo'],
        'campos'        => [
          'parametro_grupo_id',
          'codigo',
          'nombre',
          'descripcion',
          'tipo_dato',
          'valor_defecto',
          'sw_requerido',
          'sw_publico',
          'orden',
          'estado',
          'borrado',
        ],
        'campos_bits'   => ['sw_requerido', 'sw_publico', 'estado', 'borrado'],
      ],
      'modulos' => [
        'seccion'       => 'modulos',
        'tabla'         => 'system.modulos',
        'pk'            => 'modulo_id',
        'pk_post'       => 'registro_id',
        'campos_unicos' => ['codigo'],
        'campos'        => [
          'codigo',
          'nombre',
          'descripcion',
          'ruta',
          'icono',
          'orden',
          'sw_visible_menu',
          'sw_requiere_login',
          'estado',
          'borrado',
        ],
        'campos_bits'   => ['sw_visible_menu', 'sw_requiere_login', 'estado', 'borrado'],
      ],
      'integraciones' => [
        'seccion'       => 'integraciones',
        'tabla'         => 'system.integraciones',
        'pk'            => 'integracion_id',
        'pk_post'       => 'registro_id',
        'campos_unicos' => ['codigo'],
        'campos'        => [
          'codigo',
          'nombre',
          'descripcion',
          'tipo_autenticacion',
          'base_url',
          'sw_activa',
          'estado',
          'borrado',
        ],
        'campos_bits'   => ['sw_activa', 'estado', 'borrado'],
      ],
      'menus' => [
        'seccion'       => 'menus',
        'tabla'         => 'system.menus',
        'pk'            => 'menu_id',
        'pk_post'       => 'registro_id',
        'campos_unicos' => ['codigo'],
        'campos'        => [
          'modulo_id',
          'menu_padre_id',
          'codigo',
          'nombre',
          'ruta',
          'icono',
          'orden',
          'nivel',
          'sw_visible',
          'sw_publico',
          'estado',
          'borrado',
        ],
        'campos_bits'   => ['sw_visible', 'sw_publico', 'estado', 'borrado'],
      ],
      'roles' => [
        'seccion'       => 'roles',
        'tabla'         => 'public.roles',
        'pk'            => 'rol_id',
        'pk_post'       => 'registro_id',
        'campos_unicos' => ['codigo'],
        'campos'        => [
          'codigo',
          'nombre',
          'descripcion',
          'sw_predeterminado',
          'estado',
          'borrado',
        ],
        'campos_bits'   => ['sw_predeterminado', 'estado', 'borrado'],
      ],
      'usuarios' => [
        'seccion'       => 'usuarios',
        'tabla'         => 'public.usuarios',
        'pk'            => 'usuario_id',
        'pk_post'       => 'registro_id',
        'campos_unicos' => ['login', 'correo'],
        'campos'        => [
          'nombres',
          'apellidos',
          'login',
          'correo',
          'clave',
          'sw_superusuario',
          'estado',
          'borrado',
        ],
        'campos_bits'   => ['sw_superusuario', 'estado', 'borrado'],
        'campos_extra' => [
          'rol_id',
        ],
      ],
    ];

    foreach ($metadata as $llave => $informacion) {
      $metadata[$llave]['campos_extra'] = $informacion['campos_extra'] ?? [];
      $metadata[$llave]['campos_bits'] = $informacion['campos_bits'] ?? [];
      $metadata[$llave]['consulta_registro'] = $informacion['consulta_registro'] ?? "SELECT
  *
FROM
  " . $informacion['tabla'] . "
WHERE
  " . $informacion['pk'] . " = :registro_id
  AND borrado = B'0'
LIMIT 1;";
    }

    $metadata['usuarios']['consulta_registro'] = "SELECT
  u.*,
  COALESCE(ur.rol_id, 0) AS rol_id
FROM
  public.usuarios u
LEFT JOIN public.usuarios_roles ur
  ON ur.usuario_id = u.usuario_id
  AND ur.estado = B'1'
  AND ur.borrado = B'0'
WHERE
  u.usuario_id = :registro_id
  AND u.borrado = B'0'
LIMIT 1;";

    return $metadata[$seccion] ?? [];
  }
}
?>
