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
        'pagos'      => $this->consultar_pagos_tienda_admin(),
        'auditoria'  => $this->consultar_auditoria_tienda_admin(),
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
        $this->registrar_auditoria_tienda_admin('CATALOGO', 'categorias', (int) $datos['categoria_id'], 'editar', 'Se actualiza la categoría ' . $datos['nombre'] . '.', $datos);

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
      $categoria_id = (int) ($stmt->fetch()['categoria_id'] ?? 0);
      $this->registrar_auditoria_tienda_admin('CATALOGO', 'categorias', $categoria_id, 'crear', 'Se registra la categoría ' . $datos['nombre'] . '.', $datos);

      return [
        'estado'  => true,
        'mensaje' => 'Categoría registrada correctamente.',
        'datos'   => ['categoria_id' => $categoria_id],
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
        $this->registrar_auditoria_tienda_admin('CATALOGO', 'productos', (int) $datos['producto_id'], 'editar', 'Se actualiza el producto ' . $datos['nombre'] . '.', $datos);

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

      $this->registrar_auditoria_tienda_admin('CATALOGO', 'productos', $producto_id, 'crear', 'Se registra el producto ' . $datos['nombre'] . '.', $datos);

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
        $this->registrar_auditoria_tienda_admin('VISUAL', 'producto_imagenes', (int) $datos['producto_imagen_id'], 'editar', 'Se actualiza imagen del producto ' . $datos['producto_id'] . '.', $datos);

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
      $producto_imagen_id = (int) ($stmt->fetch()['producto_imagen_id'] ?? 0);
      $this->registrar_auditoria_tienda_admin('VISUAL', 'producto_imagenes', $producto_imagen_id, 'crear', 'Se registra una imagen para el producto ' . $datos['producto_id'] . '.', $datos);

      return [
        'estado'  => true,
        'mensaje' => 'Imagen registrada correctamente.',
        'datos'   => ['producto_imagen_id' => $producto_imagen_id],
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

  public function tienda_admin_guardar_cliente() {
    $stmt = null;
    $datos = $this->asignar_variables_tienda_admin_guardar_cliente();
    $validacion = $this->validar_datos_tienda_admin_guardar_cliente($datos);

    if ($validacion['estado'] !== true) {
      return $validacion;
    }

    try {
      $sql = "UPDATE public.clientes_tienda
"
           . "SET
"
           . "  nombres              = :nombres,
"
           . "  apellidos            = :apellidos,
"
           . "  correo               = :correo,
"
           . "  celular              = :celular,
"
           . "  usuario_modificacion = :usuario_modificacion,
"
           . "  fecha_modificacion   = NOW()
"
           . "WHERE
"
           . "  cliente_tienda_id = :cliente_tienda_id
"
           . "  AND borrado = B'0';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':cliente_tienda_id', $datos['cliente_tienda_id'], PDO::PARAM_INT);
      $stmt->bindValue(':nombres', $datos['nombres'], PDO::PARAM_STR);
      $stmt->bindValue(':apellidos', $datos['apellidos'], PDO::PARAM_STR);
      $stmt->bindValue(':correo', $datos['correo'], PDO::PARAM_STR);
      $stmt->bindValue(':celular', $datos['celular'], PDO::PARAM_STR);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();
      $stmt = null;

      if ($this->tabla_existe_tienda_admin('public.clientes_tienda_direcciones')) {
        $sql = "UPDATE public.clientes_tienda_direcciones
"
             . "SET
"
             . "  ciudad                = :ciudad,
"
             . "  telefono              = :telefono,
"
             . "  usuario_modificacion  = :usuario_modificacion,
"
             . "  fecha_modificacion    = NOW()
"
             . "WHERE
"
             . "  cliente_tienda_id = :cliente_tienda_id
"
             . "  AND sw_principal = B'1'
"
             . "  AND borrado = B'0';";

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':cliente_tienda_id', $datos['cliente_tienda_id'], PDO::PARAM_INT);
        $stmt->bindValue(':ciudad', $datos['ciudad'], PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $datos['celular'], PDO::PARAM_STR);
        $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
        $stmt->execute();
      }

      $this->registrar_auditoria_tienda_admin('CLIENTES', 'clientes_tienda', (int) $datos['cliente_tienda_id'], 'editar', 'Se actualiza información del cliente ' . trim($datos['nombres'] . ' ' . $datos['apellidos']) . '.', $datos);

      return [
        'estado'  => true,
        'mensaje' => 'Cliente actualizado correctamente.',
        'datos'   => ['cliente_tienda_id' => $datos['cliente_tienda_id']],
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $datos['cliente_tienda_id']);
      return [
        'estado'  => false,
        'mensaje' => 'No fue posible actualizar el cliente.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function tienda_admin_borrar_producto() {
    $producto_id = (int) ($_POST['producto_id'] ?? 0);
    $stmt = null;

    if ($producto_id <= 0) {
      return ['estado' => false, 'mensaje' => 'Producto inválido.', 'datos' => []];
    }

    if ($this->producto_tiene_pedidos_abiertos_tienda_admin($producto_id)) {
      return ['estado' => false, 'mensaje' => 'No es posible eliminar el producto porque tiene pedidos activos o pendientes de entrega.', 'datos' => []];
    }

    try {
      $sql = "UPDATE public.productos
"
           . "SET
"
           . "  estado                = B'0',
"
           . "  borrado               = B'1',
"
           . "  usuario_modificacion  = :usuario_modificacion,
"
           . "  fecha_modificacion    = NOW(),
"
           . "  usuario_borrado       = :usuario_borrado,
"
           . "  fecha_borrado         = NOW()
"
           . "WHERE
"
           . "  producto_id = :producto_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':producto_id', $producto_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_borrado', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();
      $this->registrar_auditoria_tienda_admin('CATALOGO', 'productos', $producto_id, 'eliminar', 'Se ejecuta borrado lógico del producto ' . $this->obtener_nombre_producto_tienda_admin($producto_id) . '.', ['producto_id' => $producto_id]);

      return ['estado' => true, 'mensaje' => 'Producto eliminado correctamente.', 'datos' => []];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $producto_id);
      return ['estado' => false, 'mensaje' => 'No fue posible eliminar el producto.', 'datos' => []];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function tienda_admin_borrar_categoria() {
    $categoria_id = (int) ($_POST['categoria_id'] ?? 0);
    $stmt = null;

    if ($categoria_id <= 0) {
      return ['estado' => false, 'mensaje' => 'Categoría inválida.', 'datos' => []];
    }

    if ($this->categoria_tiene_productos_registrados_tienda_admin($categoria_id)) {
      return ['estado' => false, 'mensaje' => 'No es posible eliminar la categoría porque aún tiene productos asociados.', 'datos' => []];
    }

    try {
      $sql = "UPDATE public.categorias
"
           . "SET
"
           . "  estado                = B'0',
"
           . "  borrado               = B'1',
"
           . "  usuario_modificacion  = :usuario_modificacion,
"
           . "  fecha_modificacion    = NOW(),
"
           . "  usuario_borrado       = :usuario_borrado,
"
           . "  fecha_borrado         = NOW()
"
           . "WHERE
"
           . "  categoria_id = :categoria_id;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':categoria_id', $categoria_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_borrado', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();
      $this->registrar_auditoria_tienda_admin('CATALOGO', 'categorias', $categoria_id, 'eliminar', 'Se ejecuta borrado lógico de la categoría ' . $this->obtener_nombre_categoria_tienda_admin($categoria_id) . '.', ['categoria_id' => $categoria_id]);

      return ['estado' => true, 'mensaje' => 'Categoría eliminada correctamente.', 'datos' => []];
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $categoria_id);
      return ['estado' => false, 'mensaje' => 'No fue posible eliminar la categoría.', 'datos' => []];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  public function tienda_admin_inactivar_categoria() {
    $categoria_id = (int) ($_POST['categoria_id'] ?? 0);

    if ($this->categoria_tiene_productos_activos_tienda_admin($categoria_id)) {
      return ['estado' => false, 'mensaje' => 'No es posible inactivar la categoría porque tiene productos activos asociados.', 'datos' => []];
    }

    $respuesta = $this->cambiar_estado_registro_tienda_admin('public.categorias', 'categoria_id', $categoria_id, false, 'Categoría inactivada correctamente.');

    if (($respuesta['estado'] ?? false) === true) {
      $this->registrar_auditoria_tienda_admin('CATALOGO', 'categorias', $categoria_id, 'inactivar', 'Se inactiva la categoría ' . $this->obtener_nombre_categoria_tienda_admin($categoria_id) . '.', ['categoria_id' => $categoria_id]);
    }

    return $respuesta;
  }

  public function tienda_admin_activar_categoria() {
    $categoria_id = (int) ($_POST['categoria_id'] ?? 0);
    $respuesta = $this->cambiar_estado_registro_tienda_admin('public.categorias', 'categoria_id', $categoria_id, true, 'Categoría activada correctamente.');

    if (($respuesta['estado'] ?? false) === true) {
      $this->registrar_auditoria_tienda_admin('CATALOGO', 'categorias', $categoria_id, 'activar', 'Se activa la categoría ' . $this->obtener_nombre_categoria_tienda_admin($categoria_id) . '.', ['categoria_id' => $categoria_id]);
    }

    return $respuesta;
  }

  public function tienda_admin_inactivar_producto() {
    $producto_id = (int) ($_POST['producto_id'] ?? 0);

    if ($this->producto_tiene_pedidos_abiertos_tienda_admin($producto_id)) {
      return ['estado' => false, 'mensaje' => 'No es posible inactivar el producto porque participa en pedidos activos o pendientes de entrega.', 'datos' => []];
    }

    $respuesta = $this->cambiar_estado_registro_tienda_admin('public.productos', 'producto_id', $producto_id, false, 'Producto inactivado correctamente.');

    if (($respuesta['estado'] ?? false) === true) {
      $this->registrar_auditoria_tienda_admin('CATALOGO', 'productos', $producto_id, 'inactivar', 'Se inactiva el producto ' . $this->obtener_nombre_producto_tienda_admin($producto_id) . '.', ['producto_id' => $producto_id]);
    }

    return $respuesta;
  }

  public function tienda_admin_activar_producto() {
    $producto_id = (int) ($_POST['producto_id'] ?? 0);
    $respuesta = $this->cambiar_estado_registro_tienda_admin('public.productos', 'producto_id', $producto_id, true, 'Producto activado correctamente.');

    if (($respuesta['estado'] ?? false) === true) {
      $this->registrar_auditoria_tienda_admin('CATALOGO', 'productos', $producto_id, 'activar', 'Se activa el producto ' . $this->obtener_nombre_producto_tienda_admin($producto_id) . '.', ['producto_id' => $producto_id]);
    }

    return $respuesta;
  }

  public function tienda_admin_inactivar_imagen() {
    $producto_imagen_id = (int) ($_POST['producto_imagen_id'] ?? 0);
    $respuesta = $this->cambiar_estado_registro_tienda_admin('public.producto_imagenes', 'producto_imagen_id', $producto_imagen_id, false, 'Imagen inactivada correctamente.');

    if (($respuesta['estado'] ?? false) === true) {
      $this->registrar_auditoria_tienda_admin('VISUAL', 'producto_imagenes', $producto_imagen_id, 'inactivar', 'Se inactiva una imagen de producto.', ['producto_imagen_id' => $producto_imagen_id]);
    }

    return $respuesta;
  }

  public function tienda_admin_activar_imagen() {
    $producto_imagen_id = (int) ($_POST['producto_imagen_id'] ?? 0);
    $respuesta = $this->cambiar_estado_registro_tienda_admin('public.producto_imagenes', 'producto_imagen_id', $producto_imagen_id, true, 'Imagen activada correctamente.');

    if (($respuesta['estado'] ?? false) === true) {
      $this->registrar_auditoria_tienda_admin('VISUAL', 'producto_imagenes', $producto_imagen_id, 'activar', 'Se activa una imagen de producto.', ['producto_imagen_id' => $producto_imagen_id]);
    }

    return $respuesta;
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
      $descripcion = 'Se actualiza el pedido ' . $this->obtener_codigo_pedido_tienda_admin($pedido_id) . '.';
      $this->registrar_auditoria_tienda_admin('OPERACION', 'pedidos_tienda', $pedido_id, 'editar', $descripcion, ['estado_pedido' => $estado_pedido, 'estado_pago' => $estado_pago]);

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
           . "  borrado = B'0'
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
           . "  orden,
"
           . "  CASE WHEN estado = B'1' THEN 'Activo' ELSE 'Inactivo' END AS estado_texto,
"
           . "  CASE WHEN estado = B'1' THEN 1 ELSE 0 END AS estado_numero
"
           . "FROM
"
           . "  public.categorias
"
           . "WHERE
"
           . "  borrado = B'0'
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
           . "  CASE WHEN pro.estado = B'1' THEN 'Activo' ELSE 'Inactivo' END AS estado_texto,
"
           . "  CASE WHEN pro.estado = B'1' THEN 1 ELSE 0 END AS estado_numero,
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
           . "  pro.borrado = B'0'
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
           . "  CASE WHEN pim.estado = B'1' THEN 'Activo' ELSE 'Inactivo' END AS estado_texto,
"
           . "  CASE WHEN pim.estado = B'1' THEN 1 ELSE 0 END AS estado_numero,
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
           . "  pim.borrado = B'0'
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
           . "  cli.nombres,
"
           . "  cli.apellidos,
"
           . "  TRIM(cli.nombres || ' ' || cli.apellidos) AS nombre_completo,
"
           . "  cli.correo,
"
           . "  cli.celular,
"
           . "  COALESCE(dir.ciudad, '') AS ciudad,
"
           . "  COALESCE(dir.direccion_linea_1, '') AS direccion_linea_1,
"
           . "  COALESCE(dir.telefono, '') AS telefono_direccion,
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
           . "  AND dir.borrado = B'0'
"
           . "LEFT JOIN public.pedidos_tienda ped
"
           . "  ON ped.cliente_tienda_id = cli.cliente_tienda_id
"
           . "  AND ped.borrado = B'0'
"
           . "WHERE
"
           . "  cli.borrado = B'0'
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
           . "  dir.ciudad,
"
           . "  dir.direccion_linea_1,
"
           . "  dir.telefono
"
           . "ORDER BY
"
           . "  cli.fecha_creacion DESC,
"
           . "  cli.cliente_tienda_id DESC
"
           . "LIMIT 30;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
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

  private function consultar_pagos_tienda_admin() {
    $stmt  = null;
    $datos = [];

    try {
      if (!$this->tabla_existe_tienda_admin('public.pagos_tienda')) {
        return $datos;
      }

      $sql = "SELECT
"
           . "  pag.pago_tienda_id,
"
           . "  pag.codigo,
"
           . "  pag.metodo_pago,
"
           . "  pag.estado_pago,
"
           . "  pag.monto,
"
           . "  COALESCE(pag.referencia_pasarela, '') AS referencia_pasarela,
"
           . "  COALESCE(TO_CHAR(pag.fecha_procesamiento, 'YYYY-MM-DD HH24:MI'), TO_CHAR(pag.fecha_creacion, 'YYYY-MM-DD HH24:MI')) AS fecha_pago_texto,
"
           . "  COALESCE(ped.codigo, '') AS pedido_codigo,
"
           . "  TRIM(COALESCE(cli.nombres, '') || ' ' || COALESCE(cli.apellidos, '')) AS cliente_nombre_completo
"
           . "FROM
"
           . "  public.pagos_tienda pag
"
           . "LEFT JOIN public.pedidos_tienda ped
"
           . "  ON ped.pedido_tienda_id = pag.pedido_tienda_id
"
           . "LEFT JOIN public.clientes_tienda cli
"
           . "  ON cli.cliente_tienda_id = pag.cliente_tienda_id
"
           . "WHERE
"
           . "  pag.estado = B'1'
"
           . "  AND pag.borrado = B'0'
"
           . "ORDER BY
"
           . "  pag.fecha_creacion DESC,
"
           . "  pag.pago_tienda_id DESC
"
           . "LIMIT 20;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'public.pagos_tienda');
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
             . "  MAX(COALESCE(det.imagen_url, '')) AS imagen_url,
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

  private function asignar_variables_tienda_admin_guardar_cliente() {
    return [
      'cliente_tienda_id' => (int) ($_POST['tienda_admin_cliente_id'] ?? 0),
      'nombres'           => trim((string) ($_POST['tienda_admin_cliente_nombres'] ?? '')),
      'apellidos'         => trim((string) ($_POST['tienda_admin_cliente_apellidos'] ?? '')),
      'correo'            => trim((string) ($_POST['tienda_admin_cliente_correo'] ?? '')),
      'celular'           => trim((string) ($_POST['tienda_admin_cliente_celular'] ?? '')),
      'ciudad'            => trim((string) ($_POST['tienda_admin_cliente_ciudad'] ?? '')),
    ];
  }

  private function validar_datos_tienda_admin_guardar_cliente($datos) {
    if ((int) ($datos['cliente_tienda_id'] ?? 0) <= 0) {
      return ['estado' => false, 'mensaje' => 'Debe seleccionar el cliente a editar.', 'datos' => []];
    }

    if (($datos['nombres'] ?? '') === '' || ($datos['apellidos'] ?? '') === '' || ($datos['correo'] ?? '') === '') {
      return ['estado' => false, 'mensaje' => 'Debe diligenciar nombres, apellidos y correo del cliente.', 'datos' => []];
    }

    if (!filter_var((string) ($datos['correo'] ?? ''), FILTER_VALIDATE_EMAIL)) {
      return ['estado' => false, 'mensaje' => 'Debe ingresar un correo válido para el cliente.', 'datos' => []];
    }

    return ['estado' => true, 'mensaje' => 'Validación correcta.', 'datos' => []];
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

  private function cambiar_estado_registro_tienda_admin($tabla, $campo_id, $registro_id, $estado_activo, $mensaje_ok) {
    $stmt = null;

    if ($registro_id <= 0) {
      return ['estado' => false, 'mensaje' => 'Registro inválido.', 'datos' => []];
    }

    try {
      $sql = "UPDATE " . $tabla . "\n"
           . "SET\n"
           . "  estado               = :estado,\n"
           . "  usuario_modificacion = :usuario_modificacion,\n"
           . "  fecha_modificacion   = NOW()\n"
           . "WHERE\n"
           . "  " . $campo_id . " = :registro_id\n"
           . "  AND borrado = B'0';";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':registro_id', $registro_id, PDO::PARAM_INT);
      $stmt->bindValue(':estado', $estado_activo ? '1' : '0', PDO::PARAM_STR);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_id, PDO::PARAM_INT);
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

  private function consultar_auditoria_tienda_admin() {
    $stmt  = null;
    $datos = [];

    try {
      if (!$this->tabla_existe_tienda_admin('public.tienda_admin_auditoria')) {
        return $datos;
      }

      $sql = "SELECT
"
           . "  tienda_admin_auditoria_id,
"
           . "  modulo,
"
           . "  entidad,
"
           . "  registro_id,
"
           . "  accion,
"
           . "  descripcion,
"
           . "  usuario_nombre,
"
           . "  TO_CHAR(fecha_evento, 'YYYY-MM-DD HH24:MI') AS fecha_evento_texto
"
           . "FROM
"
           . "  public.tienda_admin_auditoria
"
           . "WHERE
"
           . "  borrado = B'0'
"
           . "ORDER BY
"
           . "  fecha_evento DESC,
"
           . "  tienda_admin_auditoria_id DESC
"
           . "LIMIT 30;";

      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'public.tienda_admin_auditoria');
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function registrar_auditoria_tienda_admin($modulo, $entidad, $registro_id, $accion, $descripcion, $detalle = []) {
    $stmt = null;

    try {
      if (!$this->tabla_existe_tienda_admin('public.tienda_admin_auditoria')) {
        return;
      }

      $sql = "INSERT INTO public.tienda_admin_auditoria
"
           . "(
"
           . "  modulo,
"
           . "  entidad,
"
           . "  registro_id,
"
           . "  accion,
"
           . "  descripcion,
"
           . "  detalle_json,
"
           . "  usuario_id,
"
           . "  usuario_nombre,
"
           . "  estado,
"
           . "  borrado,
"
           . "  usuario_creacion,
"
           . "  fecha_creacion,
"
           . "  fecha_evento
"
           . ")
"
           . "VALUES
"
           . "(
"
           . "  :modulo,
"
           . "  :entidad,
"
           . "  :registro_id,
"
           . "  :accion,
"
           . "  :descripcion,
"
           . "  :detalle_json,
"
           . "  :usuario_id,
"
           . "  :usuario_nombre,
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW(),
"
           . "  NOW()
"
           . ");";

      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':modulo', (string) $modulo, PDO::PARAM_STR);
      $stmt->bindValue(':entidad', (string) $entidad, PDO::PARAM_STR);
      $stmt->bindValue(':registro_id', (int) $registro_id, PDO::PARAM_INT);
      $stmt->bindValue(':accion', (string) $accion, PDO::PARAM_STR);
      $stmt->bindValue(':descripcion', (string) $descripcion, PDO::PARAM_STR);
      $stmt->bindValue(':detalle_json', json_encode($detalle, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PDO::PARAM_STR);
      $stmt->bindValue(':usuario_id', (int) $this->usuario_id, PDO::PARAM_INT);
      $stmt->bindValue(':usuario_nombre', (string) ($_SESSION['tienda_admin_usuario_nombre_completo'] ?? 'Administrador tienda'), PDO::PARAM_STR);
      $stmt->bindValue(':usuario_creacion', (int) $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $registro_id);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function categoria_tiene_productos_activos_tienda_admin($categoria_id) {
    return $this->contar_registros_tienda_admin(
      "SELECT COUNT(producto_id) AS total FROM public.productos WHERE categoria_id = :registro_id AND estado = B'1' AND borrado = B'0';",
      $categoria_id
    ) > 0;
  }

  private function categoria_tiene_productos_registrados_tienda_admin($categoria_id) {
    return $this->contar_registros_tienda_admin(
      "SELECT COUNT(producto_id) AS total FROM public.productos WHERE categoria_id = :registro_id AND borrado = B'0';",
      $categoria_id
    ) > 0;
  }

  private function producto_tiene_pedidos_abiertos_tienda_admin($producto_id) {
    return $this->contar_registros_tienda_admin(
      "SELECT COUNT(det.pedido_tienda_detalle_id) AS total
"
      . "FROM public.pedido_tienda_detalles det
"
      . "INNER JOIN public.pedidos_tienda ped ON ped.pedido_tienda_id = det.pedido_tienda_id
"
      . "WHERE det.producto_id = :registro_id
"
      . "  AND det.borrado = B'0'
"
      . "  AND ped.borrado = B'0'
"
      . "  AND LOWER(COALESCE(ped.estado_pedido, 'pendiente')) NOT IN ('enviado', 'entregado', 'cerrado', 'cancelado');",
      $producto_id
    ) > 0;
  }

  private function contar_registros_tienda_admin($sql, $registro_id) {
    $stmt = null;

    try {
      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':registro_id', (int) $registro_id, PDO::PARAM_INT);
      $stmt->execute();
      return (int) ($stmt->fetch()['total'] ?? 0);
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), (string) $registro_id);
      return 0;
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function obtener_nombre_categoria_tienda_admin($categoria_id) {
    return $this->obtener_valor_simple_tienda_admin('public.categorias', 'categoria_id', $categoria_id, 'nombre');
  }

  private function obtener_nombre_producto_tienda_admin($producto_id) {
    return $this->obtener_valor_simple_tienda_admin('public.productos', 'producto_id', $producto_id, 'nombre');
  }

  private function obtener_codigo_pedido_tienda_admin($pedido_id) {
    return $this->obtener_valor_simple_tienda_admin('public.pedidos_tienda', 'pedido_tienda_id', $pedido_id, 'codigo');
  }

  private function obtener_valor_simple_tienda_admin($tabla, $campo_id, $registro_id, $campo_valor) {
    $stmt = null;

    try {
      $sql = "SELECT " . $campo_valor . " AS valor FROM " . $tabla . " WHERE " . $campo_id . " = :registro_id LIMIT 1;";
      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':registro_id', (int) $registro_id, PDO::PARAM_INT);
      $stmt->execute();
      return (string) ($stmt->fetch()['valor'] ?? '');
    }
    catch (PDOException $e) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), $tabla . '.' . $registro_id);
      return '';
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
