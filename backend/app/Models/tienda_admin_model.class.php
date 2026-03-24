<?php
require_once __DIR__ . '/../../config/configdb.php';

class tienda_admin_model {
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

  public function tienda_admin_inicializar() {
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

  public function tienda_admin_listar_dashboard() {
    if (!$this->validar_sesion_tienda_admin()) {
      return [
        'estado'  => false,
        'mensaje' => 'La sesión de tienda no es válida.',
        'datos'   => [],
      ];
    }

    return [
      'estado'  => true,
      'mensaje' => 'Consulta realizada correctamente.',
      'datos'   => [
        'resumen'    => $this->consultar_resumen_tienda_admin(),
        'categorias' => $this->consultar_categorias_tienda_admin(),
        'productos'  => $this->consultar_productos_tienda_admin(),
        'imagenes'   => $this->consultar_imagenes_tienda_admin(),
        'clientes'   => $this->consultar_clientes_tienda_admin(),
        'pedidos'    => $this->consultar_pedidos_tienda_admin(),
        'ventas'     => $this->consultar_ventas_tienda_admin(),
      ],
    ];
  }

  public function tienda_admin_guardar_categoria() {
    $stmt       = null;
    $datos      = $this->asignar_variables_tienda_admin_guardar_categoria();
    $validacion = $this->validar_datos_tienda_admin_guardar_categoria($datos);

    if ($validacion['estado'] !== true) {
      return $validacion;
    }

    try {
      if ($datos['categoria_id'] > 0) {
        $sql = "UPDATE public.categorias
"
             . "SET
"
             . "  codigo               = :codigo,
"
             . "  nombre               = :nombre,
"
             . "  slug                 = :slug,
"
             . "  linea                = :linea,
"
             . "  descripcion          = :descripcion,
"
             . "  imagen_url           = CASE WHEN :imagen_url = '' THEN imagen_url ELSE :imagen_url END,
"
             . "  texto_alternativo    = :texto_alternativo,
"
             . "  usuario_modificacion = :usuario_modificacion,
"
             . "  fecha_modificacion   = NOW()
"
             . "WHERE
"
             . "  categoria_id = :categoria_id
"
             . "  AND borrado = B'0';";

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':categoria_id', $datos['categoria_id'], PDO::PARAM_INT);
        $stmt->bindValue(':codigo', $datos['codigo'], PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindValue(':slug', $datos['slug'], PDO::PARAM_STR);
        $stmt->bindValue(':linea', $datos['linea'], PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
        $stmt->bindValue(':imagen_url', $datos['imagen_url'], PDO::PARAM_STR);
        $stmt->bindValue(':texto_alternativo', $datos['texto_alternativo'], PDO::PARAM_STR);
        $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        return [
          'estado'  => true,
          'mensaje' => 'Categoría actualizada correctamente.',
          'datos'   => ['categoria_id' => $datos['categoria_id']],
        ];
      }

      $sql = "INSERT INTO public.categorias
"
           . "(
"
           . "  codigo,
"
           . "  nombre,
"
           . "  slug,
"
           . "  linea,
"
           . "  descripcion,
"
           . "  imagen_url,
"
           . "  texto_alternativo,
"
           . "  orden,
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
           . "  :codigo,
"
           . "  :nombre,
"
           . "  :slug,
"
           . "  :linea,
"
           . "  :descripcion,
"
           . "  :imagen_url,
"
           . "  :texto_alternativo,
"
           . "  :orden,
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW()
"
           . ") RETURNING categoria_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':codigo', $datos['codigo'], PDO::PARAM_STR);
      $stmt->bindValue(':nombre', $datos['nombre'], PDO::PARAM_STR);
      $stmt->bindValue(':slug', $datos['slug'], PDO::PARAM_STR);
      $stmt->bindValue(':linea', $datos['linea'], PDO::PARAM_STR);
      $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
      $stmt->bindValue(':imagen_url', $datos['imagen_url'], PDO::PARAM_STR);
      $stmt->bindValue(':texto_alternativo', $datos['texto_alternativo'], PDO::PARAM_STR);
      $stmt->bindValue(':orden', $this->consultar_siguiente_orden_categoria_tienda_admin(), PDO::PARAM_INT);
      $stmt->bindValue(':usuario_creacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      return [
        'estado'  => true,
        'mensaje' => 'Categoría registrada correctamente.',
        'datos'   => ['categoria_id' => (int) ($stmt->fetch()['categoria_id'] ?? 0)],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $datos['codigo']);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible guardar la categoría.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function tienda_admin_guardar_producto() {
    $stmt       = null;
    $datos      = $this->asignar_variables_tienda_admin_guardar_producto();
    $validacion = $this->validar_datos_tienda_admin_guardar_producto($datos);

    if ($validacion['estado'] !== true) {
      return $validacion;
    }

    try {
      if ($datos['producto_id'] > 0) {
        $sql = "UPDATE public.productos
"
             . "SET
"
             . "  categoria_id         = :categoria_id,
"
             . "  codigo               = :codigo,
"
             . "  nombre               = :nombre,
"
             . "  slug                 = :slug,
"
             . "  resumen              = :resumen,
"
             . "  descripcion          = :descripcion,
"
             . "  etiqueta             = :etiqueta,
"
             . "  precio_base          = :precio_base,
"
             . "  precio_oferta        = :precio_oferta,
"
             . "  rating_promedio      = :rating_promedio,
"
             . "  stock                = :stock,
"
             . "  sw_destacado         = :sw_destacado,
"
             . "  sw_oferta            = :sw_oferta,
"
             . "  usuario_modificacion = :usuario_modificacion,
"
             . "  fecha_modificacion   = NOW()
"
             . "WHERE
"
             . "  producto_id = :producto_id
"
             . "  AND borrado = B'0';";

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':producto_id', $datos['producto_id'], PDO::PARAM_INT);
        $stmt->bindValue(':categoria_id', $datos['categoria_id'], PDO::PARAM_INT);
        $stmt->bindValue(':codigo', $datos['codigo'], PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindValue(':slug', $datos['slug'], PDO::PARAM_STR);
        $stmt->bindValue(':resumen', $datos['resumen'], PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
        $stmt->bindValue(':etiqueta', $datos['etiqueta'], PDO::PARAM_STR);
        $stmt->bindValue(':precio_base', $datos['precio_base']);
        $stmt->bindValue(':precio_oferta', $datos['precio_oferta']);
        $stmt->bindValue(':rating_promedio', $datos['rating_promedio']);
        $stmt->bindValue(':stock', $datos['stock'], PDO::PARAM_INT);
        $stmt->bindValue(':sw_destacado', $datos['sw_destacado'], PDO::PARAM_STR);
        $stmt->bindValue(':sw_oferta', $datos['sw_oferta'], PDO::PARAM_STR);
        $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($datos['imagen_principal_url'] !== '') {
          $this->registrar_imagen_producto_principal_tienda_admin($datos['producto_id'], $datos['imagen_principal_url'], $datos['texto_alternativo']);
        }
        else {
          $this->actualizar_texto_alternativo_principal_tienda_admin($datos['producto_id'], $datos['texto_alternativo']);
        }

        return [
          'estado'  => true,
          'mensaje' => 'Producto actualizado correctamente.',
          'datos'   => ['producto_id' => $datos['producto_id']],
        ];
      }

      $sql = "INSERT INTO public.productos
"
           . "(
"
           . "  categoria_id,
"
           . "  codigo,
"
           . "  nombre,
"
           . "  slug,
"
           . "  resumen,
"
           . "  descripcion,
"
           . "  etiqueta,
"
           . "  precio_base,
"
           . "  precio_oferta,
"
           . "  rating_promedio,
"
           . "  stock,
"
           . "  sw_destacado,
"
           . "  sw_oferta,
"
           . "  orden,
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
           . "  :categoria_id,
"
           . "  :codigo,
"
           . "  :nombre,
"
           . "  :slug,
"
           . "  :resumen,
"
           . "  :descripcion,
"
           . "  :etiqueta,
"
           . "  :precio_base,
"
           . "  :precio_oferta,
"
           . "  :rating_promedio,
"
           . "  :stock,
"
           . "  :sw_destacado,
"
           . "  :sw_oferta,
"
           . "  :orden,
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW()
"
           . ") RETURNING producto_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':categoria_id', $datos['categoria_id'], PDO::PARAM_INT);
      $stmt->bindValue(':codigo', $datos['codigo'], PDO::PARAM_STR);
      $stmt->bindValue(':nombre', $datos['nombre'], PDO::PARAM_STR);
      $stmt->bindValue(':slug', $datos['slug'], PDO::PARAM_STR);
      $stmt->bindValue(':resumen', $datos['resumen'], PDO::PARAM_STR);
      $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
      $stmt->bindValue(':etiqueta', $datos['etiqueta'], PDO::PARAM_STR);
      $stmt->bindValue(':precio_base', $datos['precio_base']);
      $stmt->bindValue(':precio_oferta', $datos['precio_oferta']);
      $stmt->bindValue(':rating_promedio', $datos['rating_promedio']);
      $stmt->bindValue(':stock', $datos['stock'], PDO::PARAM_INT);
      $stmt->bindValue(':sw_destacado', $datos['sw_destacado'], PDO::PARAM_STR);
      $stmt->bindValue(':sw_oferta', $datos['sw_oferta'], PDO::PARAM_STR);
      $stmt->bindValue(':orden', $this->consultar_siguiente_orden_producto_tienda_admin(), PDO::PARAM_INT);
      $stmt->bindValue(':usuario_creacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      $producto_id = (int) ($stmt->fetch()['producto_id'] ?? 0);

      if ($producto_id > 0 && $datos['imagen_principal_url'] !== '') {
        $this->registrar_imagen_producto_principal_tienda_admin($producto_id, $datos['imagen_principal_url'], $datos['texto_alternativo']);
      }

      return [
        'estado'  => true,
        'mensaje' => 'Producto registrado correctamente.',
        'datos'   => ['producto_id' => $producto_id],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $datos['codigo']);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible guardar el producto.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function tienda_admin_guardar_imagen() {
    $stmt       = null;
    $datos      = $this->asignar_variables_tienda_admin_guardar_imagen();
    $validacion = $this->validar_datos_tienda_admin_guardar_imagen($datos);

    if ($validacion['estado'] !== true) {
      return $validacion;
    }

    try {
      if ($datos['producto_imagen_id'] > 0) {
        $sql = "UPDATE public.producto_imagenes
"
             . "SET
"
             . "  producto_id           = :producto_id,
"
             . "  imagen_url            = CASE WHEN :imagen_url = '' THEN imagen_url ELSE :imagen_url END,
"
             . "  texto_alternativo     = :texto_alternativo,
"
             . "  usuario_modificacion  = :usuario_modificacion,
"
             . "  fecha_modificacion    = NOW()
"
             . "WHERE
"
             . "  producto_imagen_id = :producto_imagen_id
"
             . "  AND borrado = B'0';";

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':producto_imagen_id', $datos['producto_imagen_id'], PDO::PARAM_INT);
        $stmt->bindValue(':producto_id', $datos['producto_id'], PDO::PARAM_INT);
        $stmt->bindValue(':imagen_url', $datos['imagen_url'], PDO::PARAM_STR);
        $stmt->bindValue(':texto_alternativo', $datos['texto_alternativo'], PDO::PARAM_STR);
        $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        return [
          'estado'  => true,
          'mensaje' => 'Imagen actualizada correctamente.',
          'datos'   => ['producto_imagen_id' => $datos['producto_imagen_id']],
        ];
      }

      $sql = "INSERT INTO public.producto_imagenes
"
           . "(
"
           . "  producto_id,
"
           . "  imagen_url,
"
           . "  recurso_visual,
"
           . "  texto_alternativo,
"
           . "  sw_principal,
"
           . "  orden,
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
           . "  :producto_id,
"
           . "  :imagen_url,
"
           . "  :recurso_visual,
"
           . "  :texto_alternativo,
"
           . "  :sw_principal,
"
           . "  :orden,
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW()
"
           . ") RETURNING producto_imagen_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':producto_id', $datos['producto_id'], PDO::PARAM_INT);
      $stmt->bindValue(':imagen_url', $datos['imagen_url'], PDO::PARAM_STR);
      $stmt->bindValue(':recurso_visual', $datos['recurso_visual'], PDO::PARAM_STR);
      $stmt->bindValue(':texto_alternativo', $datos['texto_alternativo'], PDO::PARAM_STR);
      $stmt->bindValue(':sw_principal', $datos['sw_principal'], PDO::PARAM_STR);
      $stmt->bindValue(':orden', $this->consultar_siguiente_orden_imagen_tienda_admin($datos['producto_id']), PDO::PARAM_INT);
      $stmt->bindValue(':usuario_creacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      return [
        'estado'  => true,
        'mensaje' => 'Imagen registrada correctamente.',
        'datos'   => ['producto_imagen_id' => (int) ($stmt->fetch()['producto_imagen_id'] ?? 0)],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $datos['producto_id']);

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible guardar la imagen.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function tienda_admin_inactivar_categoria() {
    return $this->inactivar_registro_tienda_admin('public.categorias', 'categoria_id', (int) ($_POST['categoria_id'] ?? 0), 'Categoría inactivada correctamente.');
  }

  public function tienda_admin_inactivar_producto() {
    return $this->inactivar_registro_tienda_admin('public.productos', 'producto_id', (int) ($_POST['producto_id'] ?? 0), 'Producto inactivado correctamente.');
  }

  public function tienda_admin_inactivar_imagen() {
    return $this->inactivar_registro_tienda_admin('public.producto_imagenes', 'producto_imagen_id', (int) ($_POST['producto_imagen_id'] ?? 0), 'Imagen inactivada correctamente.');
  }

  public function tienda_admin_actualizar_pedido() {
    $pedido_id      = (int) ($_POST['pedido_tienda_id'] ?? 0);
    $estado_pedido  = trim((string) ($_POST['estado_pedido'] ?? ''));
    $estado_pago    = trim((string) ($_POST['estado_pago'] ?? ''));
    $stmt = null;

    if ($pedido_id <= 0) {
      return ['estado' => false, 'mensaje' => 'Pedido inválido.', 'datos' => []];
    }

    try {
      $sql = "UPDATE public.pedidos_tienda
"
           . "SET
"
           . "  estado_pedido         = CASE WHEN :estado_pedido = '' THEN estado_pedido ELSE :estado_pedido END,
"
           . "  estado_pago           = CASE WHEN :estado_pago = '' THEN estado_pago ELSE :estado_pago END,
"
           . "  usuario_modificacion  = :usuario_modificacion,
"
           . "  fecha_modificacion    = NOW()
"
           . "WHERE
"
           . "  pedido_tienda_id = :pedido_tienda_id
"
           . "  AND borrado = B'0';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':pedido_tienda_id', $pedido_id, PDO::PARAM_INT);
      $stmt->bindValue(':estado_pedido', $estado_pedido, PDO::PARAM_STR);
      $stmt->bindValue(':estado_pago', $estado_pago, PDO::PARAM_STR);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      return ['estado' => true, 'mensaje' => 'Pedido actualizado correctamente.', 'datos' => []];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $pedido_id);
      return ['estado' => false, 'mensaje' => 'No fue posible actualizar el pedido.', 'datos' => []];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function validar_sesion_tienda_admin() {
    return configdb_validar_sesion_tienda_admin();
  }

  private function consultar_resumen_tienda_admin() {
    return [
      'categorias'        => $this->consultar_total_tienda_admin('public.categorias', 'categoria_id'),
      'productos'         => $this->consultar_total_tienda_admin('public.productos', 'producto_id'),
      'imagenes'          => $this->consultar_total_tienda_admin('public.producto_imagenes', 'producto_imagen_id'),
      'clientes'          => $this->consultar_total_tienda_admin('public.clientes_tienda', 'cliente_tienda_id'),
      'pedidos'           => $this->consultar_total_tienda_admin('public.pedidos_tienda', 'pedido_tienda_id'),
      'pedidos_pendientes'=> $this->consultar_total_pedidos_estado_tienda_admin('pendiente'),
    ];
  }

  private function consultar_total_tienda_admin($tabla, $campo_id) {
    $stmt = null;

    try {
      if (!$this->tabla_existe_tienda_admin($tabla)) {
        return 0;
      }

      $sql = "SELECT
"
           . "  COUNT(" . $campo_id . ") AS total
"
           . "FROM
"
           . "  " . $tabla . "
"
           . "WHERE
"
           . "  estado = B'1'
"
           . "  AND borrado = B'0';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      return (int) ($stmt->fetch()['total'] ?? 0);
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $tabla);
      return 0;
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_total_pedidos_estado_tienda_admin($estado_pedido) {
    $stmt = null;

    try {
      if (!$this->tabla_existe_tienda_admin('public.pedidos_tienda')) {
        return 0;
      }

      $sql = "SELECT
"
           . "  COUNT(pedido_tienda_id) AS total
"
           . "FROM
"
           . "  public.pedidos_tienda
"
           . "WHERE
"
           . "  estado = B'1'
"
           . "  AND borrado = B'0'
"
           . "  AND estado_pedido = :estado_pedido;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':estado_pedido', $estado_pedido, PDO::PARAM_STR);
      $stmt->execute();

      return (int) ($stmt->fetch()['total'] ?? 0);
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $estado_pedido);
      return 0;
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function tabla_existe_tienda_admin($tabla) {
    $stmt = null;

    try {
      $sql = "SELECT to_regclass(:tabla) AS tabla;";
      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':tabla', $tabla, PDO::PARAM_STR);
      $stmt->execute();

      return ($stmt->fetch()['tabla'] ?? '') !== '';
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $tabla);
      return false;
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_categorias_tienda_admin() {
    $stmt  = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  categoria_id,
"
           . "  codigo,
"
           . "  nombre,
"
           . "  slug,
"
           . "  linea,
"
           . "  descripcion,
"
           . "  imagen_url,
"
           . "  texto_alternativo,
"
           . "  orden
"
           . "FROM
"
           . "  public.categorias
"
           . "WHERE
"
           . "  estado = B'1'
"
           . "  AND borrado = B'0'
"
           . "ORDER BY
"
           . "  orden ASC,
"
           . "  categoria_id ASC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $registro['imagen_url'] = $this->resolver_imagen_categoria_tienda_admin((string) ($registro['imagen_url'] ?? ''), (string) ($registro['slug'] ?? ''), (string) ($registro['linea'] ?? 'general'));
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'public.categorias');
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function consultar_productos_tienda_admin() {
    $stmt  = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  pro.producto_id,
"
           . "  pro.categoria_id,
"
           . "  pro.codigo,
"
           . "  pro.nombre,
"
           . "  pro.slug,
"
           . "  pro.etiqueta,
"
           . "  pro.resumen,
"
           . "  pro.descripcion,
"
           . "  pro.precio_base,
"
           . "  pro.precio_oferta,
"
           . "  pro.stock,
"
           . "  pro.rating_promedio,
"
           . "  cat.nombre AS categoria_nombre,
"
           . "  pim.imagen_url,
"
           . "  pim.texto_alternativo
"
           . "FROM
"
           . "  public.productos pro
"
           . "INNER JOIN public.categorias cat
"
           . "  ON cat.categoria_id = pro.categoria_id
"
           . "LEFT JOIN LATERAL (
"
           . "  SELECT
"
           . "    pim.imagen_url,
"
           . "    pim.texto_alternativo
"
           . "  FROM
"
           . "    public.producto_imagenes pim
"
           . "  WHERE
"
           . "    pim.producto_id = pro.producto_id
"
           . "    AND pim.estado = B'1'
"
           . "    AND pim.borrado = B'0'
"
           . "  ORDER BY
"
           . "    pim.sw_principal DESC,
"
           . "    pim.orden ASC,
"
           . "    pim.producto_imagen_id ASC
"
           . "  LIMIT 1
"
           . ") pim ON TRUE
"
           . "WHERE
"
           . "  pro.estado = B'1'
"
           . "  AND pro.borrado = B'0'
"
           . "ORDER BY
"
           . "  pro.orden ASC,
"
           . "  pro.producto_id ASC;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $registro['imagen_url'] = $this->resolver_imagen_producto_tienda_admin((string) ($registro['imagen_url'] ?? ''), (string) ($registro['slug'] ?? ''), (string) ($registro['categoria_nombre'] ?? 'general'));
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'public.productos');
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function consultar_imagenes_tienda_admin() {
    $stmt  = null;
    $datos = [];

    try {
      $sql = "SELECT
"
           . "  pim.producto_imagen_id,
"
           . "  pim.producto_id,
"
           . "  pim.imagen_url,
"
           . "  pim.texto_alternativo,
"
           . "  pro.nombre AS producto_nombre
"
           . "FROM
"
           . "  public.producto_imagenes pim
"
           . "INNER JOIN public.productos pro
"
           . "  ON pro.producto_id = pim.producto_id
"
           . "WHERE
"
           . "  pim.estado = B'1'
"
           . "  AND pim.borrado = B'0'
"
           . "ORDER BY
"
           . "  pim.orden ASC,
"
           . "  pim.producto_imagen_id DESC
"
           . "LIMIT 12;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $registro['imagen_url'] = $this->resolver_imagen_producto_tienda_admin((string) ($registro['imagen_url'] ?? ''), $this->generar_slug_tienda_admin((string) ($registro['producto_nombre'] ?? 'producto')), 'general');
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'public.producto_imagenes');
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }


  private function consultar_clientes_tienda_admin() {
    $stmt  = null;
    $datos = [];

    try {
      if (!$this->tabla_existe_tienda_admin('public.clientes_tienda')) {
        return $datos;
      }

      $sql = "SELECT
"
           . "  cli.cliente_tienda_id,
"
           . "  cli.codigo,
"
           . "  TRIM(cli.nombres || ' ' || cli.apellidos) AS nombre_completo,
"
           . "  cli.correo,
"
           . "  cli.celular,
"
           . "  dir.ciudad,
"
           . "  COUNT(DISTINCT ped.pedido_tienda_id) AS total_pedidos,
"
           . "  COALESCE(SUM(CASE WHEN ped.estado_pago = 'pagado' THEN ped.total ELSE 0 END), 0) AS total_compras
"
           . "FROM
"
           . "  public.clientes_tienda cli
"
           . "LEFT JOIN public.clientes_tienda_direcciones dir
"
           . "  ON dir.cliente_tienda_id = cli.cliente_tienda_id
"
           . "  AND dir.sw_principal = B'1'
"
           . "  AND dir.estado = B'1'
"
           . "  AND dir.borrado = B'0'
"
           . "LEFT JOIN public.pedidos_tienda ped
"
           . "  ON ped.cliente_tienda_id = cli.cliente_tienda_id
"
           . "  AND ped.estado = B'1'
"
           . "  AND ped.borrado = B'0'
"
           . "WHERE
"
           . "  cli.estado = B'1'
"
           . "  AND cli.borrado = B'0'
"
           . "GROUP BY
"
           . "  cli.cliente_tienda_id,
"
           . "  cli.codigo,
"
           . "  cli.nombres,
"
           . "  cli.apellidos,
"
           . "  cli.correo,
"
           . "  cli.celular,
"
           . "  dir.ciudad
"
           . "ORDER BY
"
           . "  cli.fecha_creacion DESC,
"
           . "  cli.cliente_tienda_id DESC
"
           . "LIMIT 12;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $registro['imagen_url'] = $this->resolver_imagen_producto_tienda_admin((string) ($registro['imagen_url'] ?? ''), $this->generar_slug_tienda_admin((string) ($registro['producto_nombre'] ?? 'producto')), 'general');
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'public.clientes_tienda');
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function consultar_pedidos_tienda_admin() {
    $stmt  = null;
    $datos = [];

    try {
      if (!$this->tabla_existe_tienda_admin('public.pedidos_tienda')) {
        return $datos;
      }

      $sql = "SELECT
"
           . "  ped.pedido_tienda_id,
"
           . "  ped.codigo,
"
           . "  ped.estado_pedido,
"
           . "  ped.estado_pago,
"
           . "  ped.metodo_pago,
"
           . "  ped.cantidad_items,
"
           . "  ped.total,
"
           . "  TO_CHAR(ped.fecha_pedido, 'YYYY-MM-DD HH24:MI') AS fecha_pedido_texto,
"
           . "  TRIM(cli.nombres || ' ' || cli.apellidos) AS cliente_nombre_completo
"
           . "FROM
"
           . "  public.pedidos_tienda ped
"
           . "LEFT JOIN public.clientes_tienda cli
"
           . "  ON cli.cliente_tienda_id = ped.cliente_tienda_id
"
           . "WHERE
"
           . "  ped.estado = B'1'
"
           . "  AND ped.borrado = B'0'
"
           . "ORDER BY
"
           . "  ped.fecha_pedido DESC,
"
           . "  ped.pedido_tienda_id DESC
"
           . "LIMIT 12;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $registro['imagen_url'] = $this->resolver_imagen_producto_tienda_admin((string) ($registro['imagen_url'] ?? ''), $this->generar_slug_tienda_admin((string) ($registro['producto_nombre'] ?? 'producto')), 'general');
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'public.pedidos_tienda');
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function consultar_ventas_tienda_admin() {
    $stmt  = null;
    $datos = [
      'total_ingresos'   => 0,
      'ticket_promedio'  => 0,
      'total_descuentos' => 0,
      'pedidos_pagados'  => 0,
      'productos_top'    => [],
    ];

    try {
      if ($this->tabla_existe_tienda_admin('public.pedidos_tienda')) {
        $sql = "SELECT
"
             . "  COALESCE(SUM(CASE WHEN estado_pago = 'pagado' THEN total ELSE 0 END), 0) AS total_ingresos,
"
             . "  COALESCE(AVG(CASE WHEN estado_pago = 'pagado' THEN total ELSE NULL END), 0) AS ticket_promedio,
"
             . "  COALESCE(SUM(descuento_total), 0) AS total_descuentos,
"
             . "  COUNT(CASE WHEN estado_pago = 'pagado' THEN 1 ELSE NULL END) AS pedidos_pagados
"
             . "FROM
"
             . "  public.pedidos_tienda
"
             . "WHERE
"
             . "  estado = B'1'
"
             . "  AND borrado = B'0';";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $registro = $stmt->fetch();
        $datos['total_ingresos']   = (float) ($registro['total_ingresos'] ?? 0);
        $datos['ticket_promedio']  = (float) ($registro['ticket_promedio'] ?? 0);
        $datos['total_descuentos'] = (float) ($registro['total_descuentos'] ?? 0);
        $datos['pedidos_pagados']  = (int) ($registro['pedidos_pagados'] ?? 0);
        $stmt = null;
      }

      if ($this->tabla_existe_tienda_admin('public.pedido_tienda_detalles')) {
        $sql = "SELECT
"
             . "  det.producto_id,
"
             . "  det.producto_nombre,
"
             . "  det.producto_codigo,
"
             . "  det.producto_slug,
"
             . "  COALESCE(SUM(det.cantidad), 0) AS unidades_vendidas,
"
             . "  COALESCE(SUM(det.total_linea), 0) AS total_vendido
"
             . "FROM
"
             . "  public.pedido_tienda_detalles det
"
             . "INNER JOIN public.pedidos_tienda ped
"
             . "  ON ped.pedido_tienda_id = det.pedido_tienda_id
"
             . "WHERE
"
             . "  det.estado = B'1'
"
             . "  AND det.borrado = B'0'
"
             . "  AND ped.estado = B'1'
"
             . "  AND ped.borrado = B'0'
"
             . "GROUP BY
"
             . "  det.producto_id,
"
             . "  det.producto_nombre,
"
             . "  det.producto_codigo,
"
             . "  det.producto_slug
"
             . "ORDER BY
"
             . "  total_vendido DESC,
"
             . "  unidades_vendidas DESC
"
             . "LIMIT 8;";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();

        while ($registro = $stmt->fetch()) {
          $datos['productos_top'][] = $registro;
        }
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'ventas_tienda_admin');
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function asignar_variables_tienda_admin_guardar_categoria() {
    $categoria_id      = (int) ($_POST['tienda_admin_categoria_id'] ?? 0);
    $codigo            = trim($_POST['tienda_admin_categoria_codigo'] ?? '');
    $nombre            = trim($_POST['tienda_admin_categoria_nombre'] ?? '');
    $linea             = trim($_POST['tienda_admin_categoria_linea'] ?? '');
    $descripcion       = trim($_POST['tienda_admin_categoria_descripcion'] ?? '');
    $texto_alternativo = trim($_POST['tienda_admin_categoria_texto_alternativo'] ?? '');
    $imagen_url        = $this->subir_archivo_tienda_admin('tienda_admin_categoria_imagen', 'categorias');

    return [
      'categoria_id'      => $categoria_id,
      'codigo'            => strtoupper($codigo),
      'nombre'            => $nombre,
      'slug'              => $this->generar_slug_tienda_admin($nombre),
      'linea'             => strtolower($linea),
      'descripcion'       => $descripcion,
      'imagen_url'        => $imagen_url,
      'texto_alternativo' => $texto_alternativo,
    ];
  }

  private function validar_datos_tienda_admin_guardar_categoria($datos) {
    if ($datos['codigo'] === '' || $datos['nombre'] === '' || $datos['linea'] === '' || $datos['descripcion'] === '' || $datos['texto_alternativo'] === '') {
      return [
        'estado'  => false,
        'mensaje' => 'Debe completar código, nombre, línea, descripción y texto alternativo de la categoría.',
        'datos'   => [],
      ];
    }

    return [
      'estado'  => true,
      'mensaje' => '',
      'datos'   => [],
    ];
  }

  private function asignar_variables_tienda_admin_guardar_producto() {
    $producto_id       = (int) ($_POST['tienda_admin_producto_id'] ?? 0);
    $categoria_id      = (int) ($_POST['tienda_admin_producto_categoria_id'] ?? 0);
    $codigo            = trim($_POST['tienda_admin_producto_codigo'] ?? '');
    $nombre            = trim($_POST['tienda_admin_producto_nombre'] ?? '');
    $etiqueta          = trim($_POST['tienda_admin_producto_etiqueta'] ?? '');
    $resumen           = trim($_POST['tienda_admin_producto_resumen'] ?? '');
    $descripcion       = trim($_POST['tienda_admin_producto_descripcion'] ?? '');
    $precio_base       = (float) ($_POST['tienda_admin_producto_precio_base'] ?? 0);
    $precio_oferta     = (float) ($_POST['tienda_admin_producto_precio_oferta'] ?? 0);
    $stock             = (int) ($_POST['tienda_admin_producto_stock'] ?? 0);
    $rating            = (float) ($_POST['tienda_admin_producto_rating'] ?? 0);
    $texto_alternativo = trim($_POST['tienda_admin_producto_texto_alternativo'] ?? '');
    $imagen_principal  = $this->subir_archivo_tienda_admin('tienda_admin_producto_imagen_principal', 'productos');
    $sw_oferta         = $precio_oferta > 0 && $precio_oferta < $precio_base ? "1" : "0";
    $sw_destacado      = $stock > 0 ? "1" : "0";

    return [
      'producto_id'        => $producto_id,
      'categoria_id'       => $categoria_id,
      'codigo'             => strtoupper($codigo),
      'nombre'             => $nombre,
      'slug'               => $this->generar_slug_tienda_admin($nombre),
      'etiqueta'           => $etiqueta,
      'resumen'            => $resumen,
      'descripcion'        => $descripcion,
      'precio_base'        => $precio_base,
      'precio_oferta'      => $precio_oferta,
      'stock'              => $stock,
      'rating_promedio'    => $rating,
      'sw_destacado'       => $sw_destacado,
      'sw_oferta'          => $sw_oferta,
      'imagen_principal_url' => $imagen_principal,
      'texto_alternativo'  => $texto_alternativo,
    ];
  }

  private function validar_datos_tienda_admin_guardar_producto($datos) {
    if ($datos['categoria_id'] <= 0 || $datos['codigo'] === '' || $datos['nombre'] === '' || $datos['resumen'] === '' || $datos['descripcion'] === '' || $datos['texto_alternativo'] === '') {
      return [
        'estado'  => false,
        'mensaje' => 'Debe completar categoría, código, nombre, resumen, descripción y texto alternativo del producto.',
        'datos'   => [],
      ];
    }

    if ($datos['precio_base'] <= 0 || $datos['precio_oferta'] <= 0) {
      return [
        'estado'  => false,
        'mensaje' => 'Debe ingresar precios válidos para el producto.',
        'datos'   => [],
      ];
    }

    return [
      'estado'  => true,
      'mensaje' => '',
      'datos'   => [],
    ];
  }

  private function asignar_variables_tienda_admin_guardar_imagen() {
    $producto_imagen_id = (int) ($_POST['tienda_admin_imagen_id'] ?? 0);
    $producto_id        = (int) ($_POST['tienda_admin_imagen_producto_id'] ?? 0);
    $texto_alternativo  = trim($_POST['tienda_admin_imagen_texto_alternativo'] ?? '');
    $imagen_url         = $this->subir_archivo_tienda_admin('tienda_admin_imagen_archivo', 'productos');

    return [
      'producto_imagen_id'=> $producto_imagen_id,
      'producto_id'       => $producto_id,
      'imagen_url'        => $imagen_url,
      'texto_alternativo' => $texto_alternativo,
      'recurso_visual'    => '',
      'sw_principal'      => '1',
    ];
  }

  private function validar_datos_tienda_admin_guardar_imagen($datos) {
    if ($datos['producto_id'] <= 0 || ($datos['producto_imagen_id'] <= 0 && $datos['imagen_url'] === '') || $datos['texto_alternativo'] === '') {
      return [
        'estado'  => false,
        'mensaje' => 'Debe seleccionar producto, cargar archivo de imagen y completar el texto alternativo.',
        'datos'   => [],
      ];
    }

    return [
      'estado'  => true,
      'mensaje' => '',
      'datos'   => [],
    ];
  }

  private function resolver_imagen_categoria_tienda_admin($imagen_url, $slug, $linea = 'general') {
    $imagen_url = trim((string) $imagen_url);

    if ($imagen_url !== '') {
      return $imagen_url;
    }

    $slug = trim((string) $slug);
    $linea = trim((string) $linea);
    $clave = $slug !== '' ? $slug : $linea;
    $ruta = '/public/uploads/tienda/demo/categorias/' . $clave . '.jpg';
    $ruta_absoluta = dirname(__DIR__, 2) . '/public/uploads/tienda/demo/categorias/' . $clave . '.jpg';

    if (is_file($ruta_absoluta)) {
      return $ruta;
    }

    return '/public/uploads/tienda/demo/categorias/general.jpg';
  }

  private function resolver_imagen_producto_tienda_admin($imagen_url, $slug, $linea = 'general') {
    $imagen_url = trim((string) $imagen_url);

    if ($imagen_url !== '') {
      return $imagen_url;
    }

    $slug = trim((string) $slug);

    if ($slug === '') {
      $slug = $this->generar_slug_tienda_admin((string) $linea);
    }

    $ruta = '/public/uploads/tienda/demo/productos/' . $slug . '.jpg';
    $ruta_absoluta = dirname(__DIR__, 2) . '/public/uploads/tienda/demo/productos/' . $slug . '.jpg';

    if (is_file($ruta_absoluta)) {
      return $ruta;
    }

    return '/public/uploads/tienda/demo/categorias/general.jpg';
  }

  private function actualizar_texto_alternativo_principal_tienda_admin($producto_id, $texto_alternativo) {
    $stmt = null;

    try {
      if ($producto_id <= 0 || trim((string) $texto_alternativo) === '') {
        return;
      }

      $sql = "UPDATE public.producto_imagenes\n"
           . "SET\n"
           . "  texto_alternativo     = :texto_alternativo,\n"
           . "  usuario_modificacion  = :usuario_modificacion,\n"
           . "  fecha_modificacion    = NOW()\n"
           . "WHERE\n"
           . "  producto_id = :producto_id\n"
           . "  AND sw_principal = B'1'\n"
           . "  AND borrado = B'0';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':producto_id', $producto_id, PDO::PARAM_INT);
      $stmt->bindValue(':texto_alternativo', $texto_alternativo, PDO::PARAM_STR);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $producto_id);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function inactivar_registro_tienda_admin($tabla, $campo_id, $registro_id, $mensaje_ok) {
    $stmt = null;

    if ($registro_id <= 0) {
      return ['estado' => false, 'mensaje' => 'Registro inválido.', 'datos' => []];
    }

    try {
      $sql = "UPDATE " . $tabla . "\n"
           . "SET\n"
           . "  estado               = B'0',\n"
           . "  borrado              = B'1',\n"
           . "  usuario_borrado      = :usuario_borrado,\n"
           . "  fecha_borrado        = NOW()\n"
           . "WHERE\n"
           . "  " . $campo_id . " = :registro_id\n"
           . "  AND borrado = B'0';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':registro_id', $registro_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_borrado', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();

      return ['estado' => true, 'mensaje' => $mensaje_ok, 'datos' => []];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $tabla . '.' . $registro_id);
      return ['estado' => false, 'mensaje' => 'No fue posible actualizar el registro.', 'datos' => []];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_siguiente_orden_categoria_tienda_admin() {
    return $this->consultar_siguiente_orden_tienda_admin('public.categorias', 'categoria_id');
  }

  private function consultar_siguiente_orden_producto_tienda_admin() {
    return $this->consultar_siguiente_orden_tienda_admin('public.productos', 'producto_id');
  }

  private function consultar_siguiente_orden_tienda_admin($tabla, $campo_id) {
    $stmt = null;

    try {
      $sql = "SELECT
"
           . "  COALESCE(MAX(orden), 0) + 1 AS orden
"
           . "FROM
"
           . "  " . $tabla . "
"
           . "WHERE
"
           . "  borrado = B'0';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      return (int) ($stmt->fetch()['orden'] ?? 1);
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $tabla . '.' . $campo_id);
      return 1;
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_siguiente_orden_imagen_tienda_admin($producto_id) {
    $stmt = null;

    try {
      $sql = "SELECT
"
           . "  COALESCE(MAX(orden), 0) + 1 AS orden
"
           . "FROM
"
           . "  public.producto_imagenes
"
           . "WHERE
"
           . "  producto_id = :producto_id
"
           . "  AND borrado = B'0';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':producto_id', $producto_id, PDO::PARAM_INT);
      $stmt->execute();

      return (int) ($stmt->fetch()['orden'] ?? 1);
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $producto_id);
      return 1;
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function registrar_imagen_producto_principal_tienda_admin($producto_id, $imagen_url, $texto_alternativo) {
    $stmt = null;

    try {
      if ($producto_id <= 0 || $imagen_url === '') {
        return;
      }

      $sql = "INSERT INTO public.producto_imagenes
"
           . "(
"
           . "  producto_id,
"
           . "  imagen_url,
"
           . "  recurso_visual,
"
           . "  texto_alternativo,
"
           . "  sw_principal,
"
           . "  orden,
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
           . "  :producto_id,
"
           . "  :imagen_url,
"
           . "  '',
"
           . "  :texto_alternativo,
"
           . "  B'1',
"
           . "  :orden,
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW()
"
           . ");";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':producto_id', $producto_id, PDO::PARAM_INT);
      $stmt->bindValue(':imagen_url', $imagen_url, PDO::PARAM_STR);
      $stmt->bindValue(':texto_alternativo', $texto_alternativo, PDO::PARAM_STR);
      $stmt->bindValue(':orden', $this->consultar_siguiente_orden_imagen_tienda_admin($producto_id), PDO::PARAM_INT);
      $stmt->bindValue(':usuario_creacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $producto_id);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function subir_archivo_tienda_admin($campo, $carpeta) {
    if (!isset($_FILES[$campo]) || !is_array($_FILES[$campo])) {
      return '';
    }

    if ((int) ($_FILES[$campo]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
      return '';
    }

    $nombre_archivo = $_FILES[$campo]['name'] ?? '';
    $ruta_temporal  = $_FILES[$campo]['tmp_name'] ?? '';
    $extension      = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    $extensiones    = ['jpg', 'jpeg', 'png', 'webp'];

    if ($ruta_temporal === '' || !in_array($extension, $extensiones, true)) {
      return '';
    }

    $nombre_final = uniqid($carpeta . '_', true) . '.' . $extension;
    $ruta_directorio = dirname(__DIR__, 2) . '/public/uploads/tienda/' . $carpeta;

    if (!is_dir($ruta_directorio)) {
      mkdir($ruta_directorio, 0775, true);
    }

    $ruta_destino = $ruta_directorio . '/' . $nombre_final;

    if (!move_uploaded_file($ruta_temporal, $ruta_destino)) {
      return '';
    }

    return '/public/uploads/tienda/' . $carpeta . '/' . $nombre_final;
  }


  private function texto_minuscula_tienda_admin($texto) {
    $texto = (string) $texto;

    if (extension_loaded('mbstring')) {
      return mb_strtolower($texto, 'UTF-8');
    }

    return strtolower($texto);
  }

  private function generar_slug_tienda_admin($texto) {
    $texto = $this->texto_minuscula_tienda_admin($texto);
    $texto = preg_replace('/[áàäâ]/u', 'a', $texto);
    $texto = preg_replace('/[éèëê]/u', 'e', $texto);
    $texto = preg_replace('/[íìïî]/u', 'i', $texto);
    $texto = preg_replace('/[óòöô]/u', 'o', $texto);
    $texto = preg_replace('/[úùüû]/u', 'u', $texto);
    $texto = preg_replace('/ñ/u', 'n', $texto);
    $texto = preg_replace('/[^a-z0-9]+/u', '-', $texto);
    $texto = trim($texto, '-');

    return $texto;
  }
}
?>
