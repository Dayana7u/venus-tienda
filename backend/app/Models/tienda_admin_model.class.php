<?php
require_once __DIR__ . '/../../config/configdb.php';

class tienda_admin_model {
  private $dbh;
  private $modulo = __FILE__;

  public function __construct() {
    $this->dbh = configdb_obtener_conexion();
  }

  public function consultar_resumen_tienda_admin() {
    return [
      'categorias' => $this->consultar_total_tienda_admin('public.categorias', 'categoria_id'),
      'productos'  => $this->consultar_total_tienda_admin('public.productos', 'producto_id'),
      'imagenes'   => $this->consultar_total_tienda_admin('public.producto_imagenes', 'producto_imagen_id'),
    ];
  }

  public function consultar_categorias_tienda_admin() {
    $sql = "SELECT\n"
         . "  categoria_id,\n"
         . "  codigo,\n"
         . "  nombre,\n"
         . "  slug,\n"
         . "  linea,\n"
         . "  descripcion,\n"
         . "  orden,\n"
         . "  estado\n"
         . "FROM\n"
         . "  public.categorias\n"
         . "WHERE\n"
         . "  borrado = B'0'\n"
         . "ORDER BY\n"
         . "  orden ASC,\n"
         . "  nombre ASC;";

    return $this->consultar_registros_tienda_admin($sql);
  }

  public function consultar_productos_tienda_admin() {
    $sql = "SELECT\n"
         . "  pro.producto_id,\n"
         . "  pro.categoria_id,\n"
         . "  pro.codigo,\n"
         . "  pro.nombre,\n"
         . "  pro.slug,\n"
         . "  pro.etiqueta,\n"
         . "  pro.resumen,\n"
         . "  pro.precio_base,\n"
         . "  pro.precio_oferta,\n"
         . "  pro.stock,\n"
         . "  pro.rating_promedio,\n"
         . "  pro.sw_destacado,\n"
         . "  pro.sw_oferta,\n"
         . "  pro.estado,\n"
         . "  cat.nombre AS categoria_nombre,\n"
         . "  cat.linea AS linea,\n"
         . "  COALESCE(pim.imagen_url, '') AS imagen_url,\n"
         . "  COALESCE(pim.recurso_visual, '') AS recurso_visual\n"
         . "FROM\n"
         . "  public.productos pro\n"
         . "INNER JOIN public.categorias cat\n"
         . "  ON cat.categoria_id = pro.categoria_id\n"
         . "  AND cat.borrado = B'0'\n"
         . "LEFT JOIN public.producto_imagenes pim\n"
         . "  ON pim.producto_id = pro.producto_id\n"
         . "  AND pim.sw_principal = B'1'\n"
         . "  AND pim.borrado = B'0'\n"
         . "WHERE\n"
         . "  pro.borrado = B'0'\n"
         . "ORDER BY\n"
         . "  pro.orden ASC,\n"
         . "  pro.nombre ASC;";

    return $this->consultar_registros_tienda_admin($sql);
  }

  public function consultar_producto_tienda_admin($producto_id) {
    $sql = "SELECT\n"
         . "  pro.producto_id,\n"
         . "  pro.categoria_id,\n"
         . "  pro.codigo,\n"
         . "  pro.nombre,\n"
         . "  pro.slug,\n"
         . "  pro.etiqueta,\n"
         . "  pro.resumen,\n"
         . "  pro.descripcion,\n"
         . "  pro.precio_base,\n"
         . "  pro.precio_oferta,\n"
         . "  pro.stock,\n"
         . "  pro.rating_promedio,\n"
         . "  pro.sw_destacado,\n"
         . "  pro.sw_oferta,\n"
         . "  pro.orden,\n"
         . "  pro.estado,\n"
         . "  COALESCE(pim.imagen_url, '') AS imagen_url,\n"
         . "  COALESCE(pim.recurso_visual, '') AS recurso_visual\n"
         . "FROM\n"
         . "  public.productos pro\n"
         . "LEFT JOIN public.producto_imagenes pim\n"
         . "  ON pim.producto_id = pro.producto_id\n"
         . "  AND pim.sw_principal = B'1'\n"
         . "  AND pim.borrado = B'0'\n"
         . "WHERE\n"
         . "  pro.producto_id = :producto_id\n"
         . "  AND pro.borrado = B'0'\n"
         . "LIMIT 1;";

    return $this->consultar_registro_tienda_admin($sql, [':producto_id' => (int) $producto_id]);
  }

  public function guardar_categoria_tienda_admin($datos, $usuario_id) {
    $sql = "INSERT INTO public.categorias\n"
         . "(\n"
         . "  codigo,\n"
         . "  nombre,\n"
         . "  slug,\n"
         . "  linea,\n"
         . "  descripcion,\n"
         . "  orden,\n"
         . "  estado,\n"
         . "  borrado,\n"
         . "  usuario_creacion,\n"
         . "  fecha_creacion\n"
         . ")\n"
         . "VALUES\n"
         . "(\n"
         . "  :codigo,\n"
         . "  :nombre,\n"
         . "  :slug,\n"
         . "  :linea,\n"
         . "  :descripcion,\n"
         . "  :orden,\n"
         . "  B'1',\n"
         . "  B'0',\n"
         . "  :usuario_creacion,\n"
         . "  NOW()\n"
         . ");";

    return $this->ejecutar_sentencia_tienda_admin($sql, [
      ':codigo'           => strtoupper(trim((string) ($datos['codigo'] ?? ''))),
      ':nombre'           => trim((string) ($datos['nombre'] ?? '')),
      ':slug'             => trim((string) ($datos['slug'] ?? '')),
      ':linea'            => strtolower(trim((string) ($datos['linea'] ?? ''))),
      ':descripcion'      => trim((string) ($datos['descripcion'] ?? '')),
      ':orden'            => (int) ($datos['orden'] ?? 1),
      ':usuario_creacion' => (int) $usuario_id,
    ]);
  }

  public function guardar_producto_tienda_admin($datos, $usuario_id) {
    $producto_id = (int) ($datos['producto_id'] ?? 0);

    if ($producto_id > 0) {
      return $this->editar_producto_tienda_admin($datos, $usuario_id);
    }

    $sql = "INSERT INTO public.productos\n"
         . "(\n"
         . "  categoria_id,\n"
         . "  codigo,\n"
         . "  nombre,\n"
         . "  slug,\n"
         . "  resumen,\n"
         . "  descripcion,\n"
         . "  etiqueta,\n"
         . "  precio_base,\n"
         . "  precio_oferta,\n"
         . "  rating_promedio,\n"
         . "  stock,\n"
         . "  sw_destacado,\n"
         . "  sw_oferta,\n"
         . "  orden,\n"
         . "  estado,\n"
         . "  borrado,\n"
         . "  usuario_creacion,\n"
         . "  fecha_creacion\n"
         . ")\n"
         . "VALUES\n"
         . "(\n"
         . "  :categoria_id,\n"
         . "  :codigo,\n"
         . "  :nombre,\n"
         . "  :slug,\n"
         . "  :resumen,\n"
         . "  :descripcion,\n"
         . "  :etiqueta,\n"
         . "  :precio_base,\n"
         . "  :precio_oferta,\n"
         . "  :rating_promedio,\n"
         . "  :stock,\n"
         . "  CAST(:sw_destacado AS bit(1)),\n"
         . "  CAST(:sw_oferta AS bit(1)),\n"
         . "  :orden,\n"
         . "  B'1',\n"
         . "  B'0',\n"
         . "  :usuario_creacion,\n"
         . "  NOW()\n"
         . ");";

    $resultado = $this->ejecutar_sentencia_tienda_admin($sql, $this->asignar_variables_producto_tienda_admin($datos, $usuario_id));

    if ($resultado === true) {
      $producto_id = $this->consultar_ultimo_producto_tienda_admin();
      $this->guardar_imagen_producto_tienda_admin($producto_id, $datos, $usuario_id);
    }

    return $resultado;
  }

  public function cambiar_estado_producto_tienda_admin($producto_id, $usuario_id) {
    $sql = "UPDATE public.productos\n"
         . "SET\n"
         . "  estado = CASE WHEN estado = B'1' THEN B'0' ELSE B'1' END,\n"
         . "  usuario_modificacion = :usuario_modificacion,\n"
         . "  fecha_modificacion = NOW()\n"
         . "WHERE\n"
         . "  producto_id = :producto_id\n"
         . "  AND borrado = B'0';";

    return $this->ejecutar_sentencia_tienda_admin($sql, [
      ':usuario_modificacion' => (int) $usuario_id,
      ':producto_id'          => (int) $producto_id,
    ]);
  }

  private function editar_producto_tienda_admin($datos, $usuario_id) {
    $sql = "UPDATE public.productos\n"
         . "SET\n"
         . "  categoria_id = :categoria_id,\n"
         . "  codigo = :codigo,\n"
         . "  nombre = :nombre,\n"
         . "  slug = :slug,\n"
         . "  resumen = :resumen,\n"
         . "  descripcion = :descripcion,\n"
         . "  etiqueta = :etiqueta,\n"
         . "  precio_base = :precio_base,\n"
         . "  precio_oferta = :precio_oferta,\n"
         . "  rating_promedio = :rating_promedio,\n"
         . "  stock = :stock,\n"
         . "  sw_destacado = CAST(:sw_destacado AS bit(1)),\n"
         . "  sw_oferta = CAST(:sw_oferta AS bit(1)),\n"
         . "  orden = :orden,\n"
         . "  usuario_modificacion = :usuario_modificacion,\n"
         . "  fecha_modificacion = NOW()\n"
         . "WHERE\n"
         . "  producto_id = :producto_id\n"
         . "  AND borrado = B'0';";

    $parametros = $this->asignar_variables_producto_tienda_admin($datos, $usuario_id);
    $parametros[':producto_id'] = (int) ($datos['producto_id'] ?? 0);
    $resultado = $this->ejecutar_sentencia_tienda_admin($sql, $parametros);

    if ($resultado === true) {
      $this->guardar_imagen_producto_tienda_admin((int) ($datos['producto_id'] ?? 0), $datos, $usuario_id);
    }

    return $resultado;
  }

  private function guardar_imagen_producto_tienda_admin($producto_id, $datos, $usuario_id) {
    if ((int) $producto_id <= 0) {
      return false;
    }

    $this->ejecutar_sentencia_tienda_admin(
      "DELETE FROM public.producto_imagenes WHERE producto_id = :producto_id;",
      [':producto_id' => (int) $producto_id]
    );

    $sql = "INSERT INTO public.producto_imagenes\n"
         . "(\n"
         . "  producto_id,\n"
         . "  imagen_url,\n"
         . "  recurso_visual,\n"
         . "  texto_alternativo,\n"
         . "  sw_principal,\n"
         . "  orden,\n"
         . "  estado,\n"
         . "  borrado,\n"
         . "  usuario_creacion,\n"
         . "  fecha_creacion\n"
         . ")\n"
         . "VALUES\n"
         . "(\n"
         . "  :producto_id,\n"
         . "  :imagen_url,\n"
         . "  :recurso_visual,\n"
         . "  :texto_alternativo,\n"
         . "  B'1',\n"
         . "  1,\n"
         . "  B'1',\n"
         . "  B'0',\n"
         . "  :usuario_creacion,\n"
         . "  NOW()\n"
         . ");";

    return $this->ejecutar_sentencia_tienda_admin($sql, [
      ':producto_id'      => (int) $producto_id,
      ':imagen_url'       => trim((string) ($datos['imagen_url'] ?? '')),
      ':recurso_visual'   => trim((string) ($datos['recurso_visual'] ?? 'default')),
      ':texto_alternativo'=> trim((string) ($datos['nombre'] ?? 'Producto tienda')),
      ':usuario_creacion' => (int) $usuario_id,
    ]);
  }

  private function consultar_ultimo_producto_tienda_admin() {
    $sql = "SELECT MAX(producto_id) AS producto_id FROM public.productos;";
    $dato = $this->consultar_registro_tienda_admin($sql);
    return (int) ($dato['producto_id'] ?? 0);
  }

  private function asignar_variables_producto_tienda_admin($datos, $usuario_id) {
    return [
      ':categoria_id'          => (int) ($datos['categoria_id'] ?? 0),
      ':codigo'                => strtoupper(trim((string) ($datos['codigo'] ?? ''))),
      ':nombre'                => trim((string) ($datos['nombre'] ?? '')),
      ':slug'                  => trim((string) ($datos['slug'] ?? '')),
      ':resumen'               => trim((string) ($datos['resumen'] ?? '')),
      ':descripcion'           => trim((string) ($datos['descripcion'] ?? '')),
      ':etiqueta'              => trim((string) ($datos['etiqueta'] ?? '')),
      ':precio_base'           => (float) ($datos['precio_base'] ?? 0),
      ':precio_oferta'         => (float) ($datos['precio_oferta'] ?? 0),
      ':rating_promedio'       => (float) ($datos['rating_promedio'] ?? 0),
      ':stock'                 => (int) ($datos['stock'] ?? 0),
      ':sw_destacado'          => !empty($datos['sw_destacado']) ? '1' : '0',
      ':sw_oferta'             => !empty($datos['sw_oferta']) ? '1' : '0',
      ':orden'                 => (int) ($datos['orden'] ?? 1),
      ':usuario_creacion'      => (int) $usuario_id,
      ':usuario_modificacion'  => (int) $usuario_id,
    ];
  }

  private function consultar_total_tienda_admin($tabla, $campo) {
    $sql = "SELECT COUNT({$campo}) AS total FROM {$tabla} WHERE borrado = B'0';";
    $dato = $this->consultar_registro_tienda_admin($sql);
    return (int) ($dato['total'] ?? 0);
  }

  private function consultar_registros_tienda_admin($sql, $parametros = []) {
    $stmt = null;
    $datos = [];

    try {
      $stmt = $this->dbh->prepare($sql);
      foreach ($parametros as $parametro => $valor) {
        $stmt->bindValue($parametro, $valor);
      }
      $stmt->execute();
      while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $datos[] = $registro;
      }
    }
    catch (Throwable $throwable) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $throwable->getMessage(), $sql);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function consultar_registro_tienda_admin($sql, $parametros = []) {
    $stmt = null;
    $dato = [];

    try {
      $stmt = $this->dbh->prepare($sql);
      foreach ($parametros as $parametro => $valor) {
        $stmt->bindValue($parametro, $valor);
      }
      $stmt->execute();
      $dato = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
    catch (Throwable $throwable) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $throwable->getMessage(), $sql);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $dato;
  }

  private function ejecutar_sentencia_tienda_admin($sql, $parametros = []) {
    $stmt = null;
    $resultado = false;

    try {
      $stmt = $this->dbh->prepare($sql);
      foreach ($parametros as $parametro => $valor) {
        $stmt->bindValue($parametro, $valor);
      }
      $resultado = $stmt->execute() ? true : false;
    }
    catch (Throwable $throwable) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $throwable->getMessage(), $sql);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $resultado;
  }
}
?>
