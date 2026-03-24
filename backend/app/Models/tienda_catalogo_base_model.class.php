<?php
require_once __DIR__ . '/tienda_publica_model.class.php';

class tienda_catalogo_base_model extends tienda_publica_model {
  private $modulo = __FILE__;
  private $dbh_catalogo = null;

  public function consultar_contexto_tienda() {
    return [
      'branding'    => $this->consultar_branding_tienda_publica(),
      'modulo'      => $this->consultar_modulo_tienda_publica(),
      'menus'       => $this->consultar_menus_tienda_publica(),
      'tema'        => $this->consultar_tema_tienda_publica(),
      'tema_tokens' => $this->consultar_tema_tokens_tienda_publica(),
      'componentes' => $this->consultar_tema_componentes_tienda_publica(),
      'parametros'  => $this->consultar_parametros_tienda_publica(),
    ];
  }

  protected function consultar_productos_base_tienda() {
    $productos = $this->consultar_productos_db_tienda();

    if (count($productos) > 0) {
      return $productos;
    }

    return $this->consultar_productos_estaticos_tienda();
  }

  public function consultar_catalogo_tienda($linea = '', $buscar = '') {
    $productos = $this->consultar_productos_base_tienda();
    $linea     = strtolower(trim((string) $linea));
    $buscar    = strtolower(trim((string) $buscar));
    $datos     = [];

    foreach ($productos as $producto) {
      if ($linea !== '' && $producto['linea'] !== $linea) {
        continue;
      }

      if ($buscar !== '') {
        $texto = strtolower($producto['nombre'] . ' ' . $producto['categoria'] . ' ' . $producto['linea'] . ' ' . $producto['etiqueta']);

        if (mb_strpos($texto, $buscar) === false) {
          continue;
        }
      }

      $datos[] = $producto;
    }

    return $datos;
  }

  public function consultar_producto_tienda($slug) {
    foreach ($this->consultar_productos_base_tienda() as $producto) {
      if ($producto['slug'] === $slug) {
        return $producto;
      }
    }

    return [];
  }

  public function consultar_destacados_tienda($limite = 6) {
    return $this->consultar_productos_por_bandera_tienda('destacado', $limite);
  }

  public function consultar_ofertas_tienda($limite = 6) {
    return $this->consultar_productos_por_bandera_tienda('oferta', $limite);
  }

  public function consultar_relacionados_tienda($linea, $slug, $limite = 4) {
    $productos = $this->consultar_productos_base_tienda();
    $datos = [];

    foreach ($productos as $producto) {
      if ($producto['slug'] === $slug) {
        continue;
      }

      if ($producto['linea'] === $linea) {
        $datos[] = $producto;
      }

      if (count($datos) >= $limite) {
        break;
      }
    }

    return $datos;
  }

  public function consultar_lineas_tienda() {
    $dbh = null;
    $stmt = null;
    $datos = [];

    try {
      $dbh = $this->obtener_conexion_catalogo_tienda();
      $sql = "SELECT\n"
           . "  cat.linea,\n"
           . "  cat.nombre,\n"
           . "  cat.descripcion\n"
           . "FROM\n"
           . "  public.categorias cat\n"
           . "WHERE\n"
           . "  cat.estado = B'1'\n"
           . "  AND cat.borrado = B'0'\n"
           . "ORDER BY\n"
           . "  cat.orden ASC,\n"
           . "  cat.nombre ASC;";

      $stmt = $dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $linea = strtolower((string) ($registro['linea'] ?? ''));

        if ($linea === '' || isset($datos[$linea])) {
          continue;
        }

        $datos[$linea] = [
          'titulo'      => (string) ($registro['nombre'] ?? ''),
          'descripcion' => (string) ($registro['descripcion'] ?? ''),
        ];
      }
    }
    catch (Throwable $throwable) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $throwable->getMessage(), 'public.categorias');
      $datos = [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }

      if ($dbh) {
        $dbh = null;
      }
    }

    if (count($datos) > 0) {
      return $datos;
    }

    return [
      'maquillaje' => [
        'titulo'      => 'Maquillaje diario',
        'descripcion' => 'Bases, rubores, labios y pestañas con foco comercial.',
      ],
      'skincare' => [
        'titulo'      => 'Skincare',
        'descripcion' => 'Rutinas de limpieza, hidratación y glow.',
      ],
      'accesorios' => [
        'titulo'      => 'Accesorios',
        'descripcion' => 'Brochas, kits, organizadores y detalles para regalo.',
      ],
    ];
  }

  public function consultar_carrito_tienda() {
    $items = $_SESSION['tv_carrito'] ?? [];
    $datos = [];
    $subtotal = 0;
    $subtotal_anterior = 0;

    foreach ($items as $slug => $cantidad) {
      $producto = $this->consultar_producto_tienda($slug);

      if (empty($producto)) {
        continue;
      }

      $cantidad = (int) $cantidad;
      $precio = (int) $producto['precio'];
      $precio_anterior = (int) $producto['precio_anterior'];
      $producto['cantidad'] = $cantidad;
      $producto['total'] = $precio * $cantidad;
      $producto['total_anterior'] = $precio_anterior > 0 ? $precio_anterior * $cantidad : 0;
      $producto['ahorro_total'] = $producto['total_anterior'] > 0 ? $producto['total_anterior'] - $producto['total'] : 0;
      $subtotal += $producto['total'];
      $subtotal_anterior += $producto['total_anterior'] > 0 ? $producto['total_anterior'] : $producto['total'];
      $datos[] = $producto;
    }

    $envio = $subtotal > 0 ? 15000 : 0;
    $total = $subtotal + $envio;
    $ahorro = $subtotal_anterior > $subtotal ? $subtotal_anterior - $subtotal : 0;

    return [
      'items'              => $datos,
      'subtotal'           => $subtotal,
      'subtotal_anterior'  => $subtotal_anterior,
      'ahorro'             => $ahorro,
      'envio'              => $envio,
      'total'              => $total,
      'cantidad'           => array_sum(array_map('intval', $items)),
    ];
  }

  public function agregar_producto_carrito_tienda($slug, $cantidad = 1) {
    if ($slug === '' || $cantidad <= 0) {
      return false;
    }

    $producto = $this->consultar_producto_tienda($slug);

    if (empty($producto)) {
      return false;
    }

    if (!isset($_SESSION['tv_carrito'])) {
      $_SESSION['tv_carrito'] = [];
    }

    if (!isset($_SESSION['tv_carrito'][$slug])) {
      $_SESSION['tv_carrito'][$slug] = 0;
    }

    $_SESSION['tv_carrito'][$slug] = (int) $_SESSION['tv_carrito'][$slug] + (int) $cantidad;
    return true;
  }

  public function actualizar_producto_carrito_tienda($slug, $cantidad) {
    if (!isset($_SESSION['tv_carrito'][$slug])) {
      return false;
    }

    if ((int) $cantidad <= 0) {
      unset($_SESSION['tv_carrito'][$slug]);
      return true;
    }

    $_SESSION['tv_carrito'][$slug] = (int) $cantidad;
    return true;
  }

  public function eliminar_producto_carrito_tienda($slug) {
    if (isset($_SESSION['tv_carrito'][$slug])) {
      unset($_SESSION['tv_carrito'][$slug]);
      return true;
    }

    return false;
  }

  private function consultar_productos_por_bandera_tienda($bandera, $limite) {
    $productos = $this->consultar_productos_base_tienda();
    $datos = [];

    foreach ($productos as $producto) {
      if ($bandera === 'destacado' && $producto['destacado'] === true) {
        $datos[] = $producto;
      }

      if ($bandera === 'oferta' && $producto['oferta'] === true) {
        $datos[] = $producto;
      }

      if (count($datos) >= $limite) {
        break;
      }
    }

    return $datos;
  }

  private function consultar_productos_db_tienda() {
    $dbh = null;
    $stmt = null;
    $datos = [];

    try {
      $dbh = $this->obtener_conexion_catalogo_tienda();
      $sql = "SELECT\n"
           . "  pro.producto_id,\n"
           . "  pro.slug,\n"
           . "  pro.nombre,\n"
           . "  LOWER(cat.linea) AS linea,\n"
           . "  LOWER(cat.slug) AS categoria,\n"
           . "  COALESCE(pro.etiqueta, cat.nombre) AS etiqueta,\n"
           . "  COALESCE(NULLIF(pro.precio_oferta, 0), pro.precio_base, 0) AS precio,\n"
           . "  CASE\n"
           . "    WHEN COALESCE(NULLIF(pro.precio_oferta, 0), pro.precio_base, 0) < COALESCE(pro.precio_base, 0) THEN pro.precio_base\n"
           . "    ELSE 0\n"
           . "  END AS precio_anterior,\n"
           . "  COALESCE(pro.rating_promedio, 0) AS rating,\n"
           . "  COALESCE(pim.recurso_visual, LOWER(cat.linea)) AS media,\n"
           . "  COALESCE(pim.imagen_url, '') AS imagen_url,\n"
           . "  COALESCE(pim.texto_alternativo, pro.nombre) AS texto_alternativo,\n"
           . "  COALESCE(pro.resumen, '') AS resumen,\n"
           . "  COALESCE(pro.descripcion, '') AS descripcion,\n"
           . "  COALESCE(pro.stock, 0) AS stock,\n"
           . "  pro.sw_destacado,\n"
           . "  pro.sw_oferta\n"
           . "FROM\n"
           . "  public.productos pro\n"
           . "INNER JOIN public.categorias cat\n"
           . "  ON cat.categoria_id = pro.categoria_id\n"
           . "  AND cat.estado = B'1'\n"
           . "  AND cat.borrado = B'0'\n"
           . "LEFT JOIN public.producto_imagenes pim\n"
           . "  ON pim.producto_id = pro.producto_id\n"
           . "  AND pim.sw_principal = B'1'\n"
           . "  AND pim.estado = B'1'\n"
           . "  AND pim.borrado = B'0'\n"
           . "WHERE\n"
           . "  pro.estado = B'1'\n"
           . "  AND pro.borrado = B'0'\n"
           . "ORDER BY\n"
           . "  pro.orden ASC,\n"
           . "  pro.nombre ASC;";

      $stmt = $dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $datos[] = $this->asignar_variables_producto_tienda($registro);
      }
    }
    catch (Throwable $throwable) {
      configdb_registrar_log($this->modulo, __FUNCTION__, $throwable->getMessage(), 'public.productos');
      $datos = [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }

      if ($dbh) {
        $dbh = null;
      }
    }

    return $datos;
  }

  private function asignar_variables_producto_tienda($registro) {
    $precio = (int) round((float) ($registro['precio'] ?? 0));
    $precio_anterior = (int) round((float) ($registro['precio_anterior'] ?? 0));
    $descuento = 0;

    if ($precio_anterior > $precio && $precio_anterior > 0) {
      $descuento = (int) round((($precio_anterior - $precio) / $precio_anterior) * 100);
    }

    return [
      'producto_id'          => (int) ($registro['producto_id'] ?? 0),
      'slug'                 => (string) ($registro['slug'] ?? ''),
      'nombre'               => (string) ($registro['nombre'] ?? ''),
      'linea'                => strtolower((string) ($registro['linea'] ?? '')), 
      'categoria'            => strtolower((string) ($registro['categoria'] ?? '')),
      'etiqueta'             => (string) ($registro['etiqueta'] ?? ''),
      'precio'               => $precio,
      'precio_anterior'      => $precio_anterior,
      'rating'               => (float) ($registro['rating'] ?? 0),
      'media'                => (string) ($registro['media'] ?? 'default'),
      'imagen_url'           => (string) ($registro['imagen_url'] ?? ''),
      'texto_alternativo'    => (string) ($registro['texto_alternativo'] ?? ''),
      'resumen'              => (string) ($registro['resumen'] ?? ''),
      'descripcion'          => (string) ($registro['descripcion'] ?? ''),
      'beneficios'           => $this->asignar_beneficios_producto_tienda((string) ($registro['linea'] ?? ''), (string) ($registro['categoria'] ?? '')),
      'destacado'            => ((string) ($registro['sw_destacado'] ?? '0')) === '1',
      'oferta'               => ((string) ($registro['sw_oferta'] ?? '0')) === '1',
      'stock'                => (int) ($registro['stock'] ?? 0),
      'porcentaje_descuento' => $descuento,
    ];
  }

  private function asignar_beneficios_producto_tienda($linea, $categoria) {
    $linea = strtolower($linea);
    $categoria = strtolower($categoria);

    if ($linea === 'maquillaje') {
      return ['Fácil de aplicar', 'Look profesional', 'Uso diario'];
    }

    if ($linea === 'skincare') {
      return ['Rutina diaria', 'Textura ligera', 'Sensación de confort'];
    }

    if ($linea === 'accesorios') {
      return ['Fácil de combinar', 'Apoyo a la rutina', 'Formato práctico'];
    }

    return ['Producto visible', 'Configurado desde catálogo', 'Listo para la tienda'];
  }

  private function obtener_conexion_catalogo_tienda() {
    if ($this->dbh_catalogo === null) {
      $this->dbh_catalogo = configdb_obtener_conexion();
    }

    return $this->dbh_catalogo;
  }

  private function consultar_productos_estaticos_tienda() {
    return [
      [
        'producto_id'          => 1,
        'slug'                 => 'serum-glow-rose',
        'nombre'               => 'Serum Glow Rose',
        'linea'                => 'skincare',
        'categoria'            => 'sueros',
        'etiqueta'             => 'Skincare',
        'precio'               => 89900,
        'precio_anterior'      => 109900,
        'rating'               => 4.9,
        'media'                => 'serum_rose',
        'imagen_url'           => '',
        'texto_alternativo'    => 'Serum Glow Rose',
        'resumen'              => 'Suero hidratante con acabado glow para rutina de día.',
        'descripcion'          => 'Fórmula ligera para hidratar, iluminar y preparar la piel antes del maquillaje.',
        'beneficios'           => ['Hidratación ligera', 'Brillo natural', 'Ideal para rutina diaria'],
        'destacado'            => true,
        'oferta'               => true,
        'stock'                => 25,
        'porcentaje_descuento' => 18,
      ],
      [
        'producto_id'          => 2,
        'slug'                 => 'base-soft-matte',
        'nombre'               => 'Base Soft Matte',
        'linea'                => 'maquillaje',
        'categoria'            => 'rostro',
        'etiqueta'             => 'Maquillaje',
        'precio'               => 74900,
        'precio_anterior'      => 89900,
        'rating'               => 4.8,
        'media'                => 'base_matte',
        'imagen_url'           => '',
        'texto_alternativo'    => 'Base Soft Matte',
        'resumen'              => 'Base de cobertura media-alta con acabado uniforme.',
        'descripcion'          => 'Cobertura construible y sensación liviana para un look profesional.',
        'beneficios'           => ['Cobertura media-alta', 'Acabado uniforme', 'Fácil de difuminar'],
        'destacado'            => true,
        'oferta'               => true,
        'stock'                => 30,
        'porcentaje_descuento' => 17,
      ],
      [
        'producto_id'          => 3,
        'slug'                 => 'lip-oil-peony',
        'nombre'               => 'Lip Oil Peony',
        'linea'                => 'maquillaje',
        'categoria'            => 'labios',
        'etiqueta'             => 'Glow lips',
        'precio'               => 45900,
        'precio_anterior'      => 0,
        'rating'               => 4.7,
        'media'                => 'lip_oil',
        'imagen_url'           => '',
        'texto_alternativo'    => 'Lip Oil Peony',
        'resumen'              => 'Aceite labial con brillo suave y sensación de hidratación.',
        'descripcion'          => 'Perfecto para looks limpios y combinaciones con delineado suave.',
        'beneficios'           => ['Brillo natural', 'Fórmula liviana', 'Uso diario'],
        'destacado'            => true,
        'oferta'               => false,
        'stock'                => 40,
        'porcentaje_descuento' => 0,
      ],
      [
        'producto_id'          => 4,
        'slug'                 => 'brochas-essential-set',
        'nombre'               => 'Brochas Essential Set',
        'linea'                => 'accesorios',
        'categoria'            => 'brochas',
        'etiqueta'             => 'Accesorios',
        'precio'               => 69900,
        'precio_anterior'      => 84900,
        'rating'               => 4.9,
        'media'                => 'brochas_set',
        'imagen_url'           => '',
        'texto_alternativo'    => 'Brochas Essential Set',
        'resumen'              => 'Set base para rostro, ojos y detalles.',
        'descripcion'          => 'Kit de brochas pensado para una rutina completa de maquillaje.',
        'beneficios'           => ['Set completo', 'Cerdas suaves', 'Uso diario'],
        'destacado'            => true,
        'oferta'               => true,
        'stock'                => 20,
        'porcentaje_descuento' => 18,
      ],
      [
        'producto_id'          => 5,
        'slug'                 => 'crema-barrier-night',
        'nombre'               => 'Crema Barrier Night',
        'linea'                => 'skincare',
        'categoria'            => 'hidratantes',
        'etiqueta'             => 'Noche',
        'precio'               => 83900,
        'precio_anterior'      => 0,
        'rating'               => 4.8,
        'media'                => 'crema_noche',
        'imagen_url'           => '',
        'texto_alternativo'    => 'Crema Barrier Night',
        'resumen'              => 'Crema de noche para reparar y suavizar la barrera cutánea.',
        'descripcion'          => 'Ideal para cerrar la rutina y dejar sensación de confort.',
        'beneficios'           => ['Nutrición nocturna', 'Textura cremosa', 'Apoyo a la barrera'],
        'destacado'            => false,
        'oferta'               => false,
        'stock'                => 18,
        'porcentaje_descuento' => 0,
      ],
      [
        'producto_id'          => 6,
        'slug'                 => 'organizador-vanity-mini',
        'nombre'               => 'Organizador Vanity Mini',
        'linea'                => 'accesorios',
        'categoria'            => 'organizadores',
        'etiqueta'             => 'Vanity',
        'precio'               => 52900,
        'precio_anterior'      => 63900,
        'rating'               => 4.6,
        'media'                => 'organizador',
        'imagen_url'           => '',
        'texto_alternativo'    => 'Organizador Vanity Mini',
        'resumen'              => 'Organizador compacto para skincare y maquillaje.',
        'descripcion'          => 'Perfecto para tocador, viaje o kits de regalo.',
        'beneficios'           => ['Compacto', 'Orden visual', 'Práctico para viaje'],
        'destacado'            => false,
        'oferta'               => true,
        'stock'                => 16,
        'porcentaje_descuento' => 17,
      ],
      [
        'producto_id'          => 7,
        'slug'                 => 'mask-hydra-cloud',
        'nombre'               => 'Mask Hydra Cloud',
        'linea'                => 'skincare',
        'categoria'            => 'mascaras',
        'etiqueta'             => 'Tratamiento',
        'precio'               => 58900,
        'precio_anterior'      => 69900,
        'rating'               => 4.7,
        'media'                => 'mask_cloud',
        'imagen_url'           => '',
        'texto_alternativo'    => 'Mask Hydra Cloud',
        'resumen'              => 'Mascarilla cremosa para hidratación profunda.',
        'descripcion'          => 'Se usa dos veces por semana para una piel más luminosa.',
        'beneficios'           => ['Hidratación intensa', 'Textura cremosa', 'Brillo saludable'],
        'destacado'            => false,
        'oferta'               => true,
        'stock'                => 15,
        'porcentaje_descuento' => 16,
      ],
      [
        'producto_id'          => 8,
        'slug'                 => 'blush-soft-peach',
        'nombre'               => 'Blush Soft Peach',
        'linea'                => 'maquillaje',
        'categoria'            => 'rostro',
        'etiqueta'             => 'Color',
        'precio'               => 42900,
        'precio_anterior'      => 0,
        'rating'               => 4.8,
        'media'                => 'blush_peach',
        'imagen_url'           => '',
        'texto_alternativo'    => 'Blush Soft Peach',
        'resumen'              => 'Rubor de acabado natural para un look fresco.',
        'descripcion'          => 'Textura sedosa que se integra fácilmente a la piel.',
        'beneficios'           => ['Acabado natural', 'Fácil de trabajar', 'Tono versátil'],
        'destacado'            => true,
        'oferta'               => false,
        'stock'                => 32,
        'porcentaje_descuento' => 0,
      ],
      [
        'producto_id'          => 9,
        'slug'                 => 'kit-gift-bloom',
        'nombre'               => 'Kit Gift Bloom',
        'linea'                => 'accesorios',
        'categoria'            => 'sets',
        'etiqueta'             => 'Regalo',
        'precio'               => 119900,
        'precio_anterior'      => 149900,
        'rating'               => 4.9,
        'media'                => 'gift_bloom',
        'imagen_url'           => '',
        'texto_alternativo'    => 'Kit Gift Bloom',
        'resumen'              => 'Set listo para regalo con cosmetiquera y selección glow.',
        'descripcion'          => 'Campaña comercial pensada para fechas especiales.',
        'beneficios'           => ['Listo para regalar', 'Presentación premium', 'Edición especial'],
        'destacado'            => true,
        'oferta'               => true,
        'stock'                => 12,
        'porcentaje_descuento' => 20,
      ],
    ];
  }
}
?>
