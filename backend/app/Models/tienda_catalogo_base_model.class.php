<?php
require_once __DIR__ . '/tienda_publica_model.class.php';

class tienda_catalogo_base_model extends tienda_publica_model {
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
    return [
      [
        'producto_id'            => 1,
        'categoria_id'           => 1,
        'slug'                   => 'serum-glow-rose',
        'nombre'                 => 'Serum Glow Rose',
        'linea'                  => 'skincare',
        'categoria'              => 'sueros',
        'categoria_nombre'       => 'Sueros',
        'etiqueta'               => 'Skincare',
        'precio'                 => 89900,
        'precio_anterior'        => 109900,
        'rating'                 => 4.9,
        'media'                  => 'serum_rose',
        'resumen'                => 'Suero hidratante con acabado glow para rutina de día.',
        'descripcion'            => 'Fórmula ligera para hidratar, iluminar y preparar la piel antes del maquillaje.',
        'beneficios'             => ['Hidratación ligera', 'Brillo natural', 'Ideal para rutina diaria'],
        'destacado'              => true,
        'oferta'                 => true,
        'stock'                  => 8,
        'imagen_url'             => $this->obtener_imagen_demo_producto_tienda('serum-glow-rose', ''),
        'texto_alternativo'      => 'Serum Glow Rose',
        'imagenes'               => [],
        'descuento_porcentaje'   => 18,
      ],
      [
        'producto_id'            => 2,
        'categoria_id'           => 2,
        'slug'                   => 'base-soft-matte',
        'nombre'                 => 'Base Soft Matte',
        'linea'                  => 'maquillaje',
        'categoria'              => 'rostro',
        'categoria_nombre'       => 'Rostro',
        'etiqueta'               => 'Maquillaje',
        'precio'                 => 74900,
        'precio_anterior'        => 89900,
        'rating'                 => 4.8,
        'media'                  => 'base_matte',
        'resumen'                => 'Base de cobertura media-alta con acabado uniforme.',
        'descripcion'            => 'Cobertura construible y sensación liviana para un look profesional.',
        'beneficios'             => ['Cobertura media-alta', 'Acabado uniforme', 'Fácil de difuminar'],
        'destacado'              => true,
        'oferta'                 => true,
        'stock'                  => 10,
        'imagen_url'             => $this->obtener_imagen_demo_producto_tienda('base-soft-matte', ''),
        'texto_alternativo'      => 'Base Soft Matte',
        'imagenes'               => [],
        'descuento_porcentaje'   => 17,
      ],
      [
        'producto_id'            => 3,
        'categoria_id'           => 3,
        'slug'                   => 'lip-oil-peony',
        'nombre'                 => 'Lip Oil Peony',
        'linea'                  => 'maquillaje',
        'categoria'              => 'labios',
        'categoria_nombre'       => 'Labios',
        'etiqueta'               => 'Glow lips',
        'precio'                 => 45900,
        'precio_anterior'        => 0,
        'rating'                 => 4.7,
        'media'                  => 'lip_oil',
        'resumen'                => 'Aceite labial con brillo suave y sensación de hidratación.',
        'descripcion'            => 'Perfecto para looks limpios y combinaciones con delineado suave.',
        'beneficios'             => ['Brillo natural', 'Fórmula liviana', 'Uso diario'],
        'destacado'              => true,
        'oferta'                 => false,
        'stock'                  => 15,
        'imagen_url'             => $this->obtener_imagen_demo_producto_tienda('lip-oil-peony', ''),
        'texto_alternativo'      => 'Lip Oil Peony',
        'imagenes'               => [],
        'descuento_porcentaje'   => 0,
      ],
      [
        'producto_id'            => 4,
        'categoria_id'           => 4,
        'slug'                   => 'brochas-essential-set',
        'nombre'                 => 'Brochas Essential Set',
        'linea'                  => 'accesorios',
        'categoria'              => 'brochas',
        'categoria_nombre'       => 'Brochas',
        'etiqueta'               => 'Accesorios',
        'precio'                 => 69900,
        'precio_anterior'        => 84900,
        'rating'                 => 4.9,
        'media'                  => 'brochas_set',
        'resumen'                => 'Set base para rostro, ojos y detalles.',
        'descripcion'            => 'Kit de brochas pensado para una rutina completa de maquillaje.',
        'beneficios'             => ['Set completo', 'Cerdas suaves', 'Uso diario'],
        'destacado'              => true,
        'oferta'                 => true,
        'stock'                  => 6,
        'imagen_url'             => $this->obtener_imagen_demo_producto_tienda('brochas-essential-set', ''),
        'texto_alternativo'      => 'Brochas Essential Set',
        'imagenes'               => [],
        'descuento_porcentaje'   => 18,
      ],
      [
        'producto_id'            => 5,
        'categoria_id'           => 5,
        'slug'                   => 'crema-barrier-night',
        'nombre'                 => 'Crema Barrier Night',
        'linea'                  => 'skincare',
        'categoria'              => 'hidratantes',
        'categoria_nombre'       => 'Hidratantes',
        'etiqueta'               => 'Noche',
        'precio'                 => 83900,
        'precio_anterior'        => 0,
        'rating'                 => 4.8,
        'media'                  => 'crema_noche',
        'resumen'                => 'Crema de noche para reparar y suavizar la barrera cutánea.',
        'descripcion'            => 'Ideal para cerrar la rutina y dejar sensación de confort.',
        'beneficios'             => ['Nutrición nocturna', 'Textura cremosa', 'Apoyo a la barrera'],
        'destacado'              => false,
        'oferta'                 => false,
        'stock'                  => 9,
        'imagen_url'             => $this->obtener_imagen_demo_producto_tienda('crema-barrier-night', ''),
        'texto_alternativo'      => 'Crema Barrier Night',
        'imagenes'               => [],
        'descuento_porcentaje'   => 0,
      ],
      [
        'producto_id'            => 6,
        'categoria_id'           => 6,
        'slug'                   => 'organizador-vanity-mini',
        'nombre'                 => 'Organizador Vanity Mini',
        'linea'                  => 'accesorios',
        'categoria'              => 'organizadores',
        'categoria_nombre'       => 'Organizadores',
        'etiqueta'               => 'Vanity',
        'precio'                 => 52900,
        'precio_anterior'        => 63900,
        'rating'                 => 4.6,
        'media'                  => 'organizador',
        'resumen'                => 'Organizador compacto para skincare y maquillaje.',
        'descripcion'            => 'Perfecto para tocador, viaje o kits de regalo.',
        'beneficios'             => ['Compacto', 'Orden visual', 'Práctico para viaje'],
        'destacado'              => false,
        'oferta'                 => true,
        'stock'                  => 7,
        'imagen_url'             => $this->obtener_imagen_demo_producto_tienda('organizador-vanity-mini', ''),
        'texto_alternativo'      => 'Organizador Vanity Mini',
        'imagenes'               => [],
        'descuento_porcentaje'   => 16,
      ],
      [
        'producto_id'            => 7,
        'categoria_id'           => 7,
        'slug'                   => 'mask-hydra-cloud',
        'nombre'                 => 'Mask Hydra Cloud',
        'linea'                  => 'skincare',
        'categoria'              => 'mascaras',
        'categoria_nombre'       => 'Máscaras',
        'etiqueta'               => 'Tratamiento',
        'precio'                 => 58900,
        'precio_anterior'        => 69900,
        'rating'                 => 4.7,
        'media'                  => 'mask_cloud',
        'resumen'                => 'Mascarilla cremosa para hidratación profunda.',
        'descripcion'            => 'Se usa dos veces por semana para una piel más luminosa.',
        'beneficios'             => ['Hidratación intensa', 'Textura cremosa', 'Brillo saludable'],
        'destacado'              => false,
        'oferta'                 => true,
        'stock'                  => 5,
        'imagen_url'             => $this->obtener_imagen_demo_producto_tienda('mask-hydra-cloud', ''),
        'texto_alternativo'      => 'Mask Hydra Cloud',
        'imagenes'               => [],
        'descuento_porcentaje'   => 16,
      ],
      [
        'producto_id'            => 8,
        'categoria_id'           => 8,
        'slug'                   => 'blush-soft-peach',
        'nombre'                 => 'Blush Soft Peach',
        'linea'                  => 'maquillaje',
        'categoria'              => 'rostro',
        'categoria_nombre'       => 'Rostro',
        'etiqueta'               => 'Color',
        'precio'                 => 42900,
        'precio_anterior'        => 0,
        'rating'                 => 4.8,
        'media'                  => 'blush_peach',
        'resumen'                => 'Rubor de acabado natural para un look fresco.',
        'descripcion'            => 'Textura sedosa que se integra fácilmente a la piel.',
        'beneficios'             => ['Acabado natural', 'Fácil de trabajar', 'Tono versátil'],
        'destacado'              => true,
        'oferta'                 => false,
        'stock'                  => 12,
        'imagen_url'             => $this->obtener_imagen_demo_producto_tienda('blush-soft-peach', ''),
        'texto_alternativo'      => 'Blush Soft Peach',
        'imagenes'               => [],
        'descuento_porcentaje'   => 0,
      ],
      [
        'producto_id'            => 9,
        'categoria_id'           => 9,
        'slug'                   => 'kit-gift-bloom',
        'nombre'                 => 'Kit Gift Bloom',
        'linea'                  => 'accesorios',
        'categoria'              => 'sets',
        'categoria_nombre'       => 'Sets',
        'etiqueta'               => 'Regalo',
        'precio'                 => 119900,
        'precio_anterior'        => 149900,
        'rating'                 => 4.9,
        'media'                  => 'gift_bloom',
        'resumen'                => 'Set listo para regalo con cosmetiquera y selección glow.',
        'descripcion'            => 'Campaña comercial pensada para fechas especiales.',
        'beneficios'             => ['Listo para regalar', 'Presentación premium', 'Edición especial'],
        'destacado'              => true,
        'oferta'                 => true,
        'stock'                  => 4,
        'imagen_url'             => $this->obtener_imagen_demo_producto_tienda('kit-gift-bloom', ''),
        'texto_alternativo'      => 'Kit Gift Bloom',
        'imagenes'               => [],
        'descuento_porcentaje'   => 20,
      ],
    ];
  }


  private function resolver_imagen_producto_tienda($imagen_url, $slug, $linea = 'general') {
    $imagen_url = trim((string) $imagen_url);

    if ($imagen_url !== '') {
      return $imagen_url;
    }

    return $this->obtener_imagen_demo_producto_tienda($slug, $linea);
  }

  private function resolver_imagen_categoria_tienda($imagen_url, $slug, $linea = 'general') {
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

  private function obtener_imagen_demo_producto_tienda($slug, $linea = 'general') {
    $slug = trim((string) $slug);

    if ($slug === '') {
      $slug = strtolower(str_replace(' ', '-', (string) $linea));
    }

    $ruta = '/public/uploads/tienda/demo/productos/' . $slug . '.jpg';
    $ruta_absoluta = dirname(__DIR__, 2) . '/public/uploads/tienda/demo/productos/' . $slug . '.jpg';

    if (is_file($ruta_absoluta)) {
      return $ruta;
    }

    return '/public/uploads/tienda/demo/categorias/general.jpg';
  }

  private function texto_minuscula_tienda($texto) {
    $texto = (string) $texto;

    if (extension_loaded('mbstring')) {
      return mb_strtolower($texto, 'UTF-8');
    }

    return strtolower($texto);
  }

  private function texto_contiene_tienda($texto, $buscar) {
    $texto = $this->texto_minuscula_tienda($texto);
    $buscar = $this->texto_minuscula_tienda($buscar);

    if ($buscar === '') {
      return true;
    }

    return strpos($texto, $buscar) !== false;
  }

  private function consultar_conexion_tienda_bd() {
    return configdb_obtener_conexion();
  }

  private function calcular_descuento_producto_tienda($precio_anterior, $precio_actual) {
    $precio_anterior = (int) $precio_anterior;
    $precio_actual = (int) $precio_actual;

    if ($precio_anterior <= 0 || $precio_actual <= 0 || $precio_anterior <= $precio_actual) {
      return 0;
    }

    return (int) round((($precio_anterior - $precio_actual) / $precio_anterior) * 100);
  }

  private function construir_beneficios_producto_tienda($registro = []) {
    $beneficios = [];
    $stock = (int) ($registro['stock'] ?? 0);
    $rating = (float) ($registro['rating_promedio'] ?? $registro['rating'] ?? 0);

    if ($stock > 0) {
      $beneficios[] = 'Disponible para entrega inmediata';
    }

    if ($rating > 0) {
      $beneficios[] = 'Calificación promedio ' . number_format($rating, 1, ',', '.');
    }

    if (($registro['linea'] ?? '') !== '') {
      $beneficios[] = 'Línea ' . ucfirst((string) $registro['linea']);
    }

    if (count($beneficios) === 0) {
      $beneficios[] = 'Producto activo de la tienda';
    }

    return $beneficios;
  }

  private function consultar_imagenes_producto_tienda_bd($producto_id, $slug = '', $linea = 'general') {
    $stmt = null;
    $datos = [];

    try {
      $dbh = $this->consultar_conexion_tienda_bd();
      $sql = "SELECT
"
           . "  pim.producto_imagen_id,
"
           . "  pim.imagen_url,
"
           . "  pim.texto_alternativo,
"
           . "  pim.sw_principal,
"
           . "  pim.orden
"
           . "FROM
"
           . "  public.producto_imagenes pim
"
           . "WHERE
"
           . "  pim.producto_id = :producto_id
"
           . "  AND pim.estado = B'1'
"
           . "  AND pim.borrado = B'0'
"
           . "ORDER BY
"
           . "  pim.sw_principal DESC,
"
           . "  pim.orden ASC,
"
           . "  pim.producto_imagen_id ASC;";

      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':producto_id', (int) $producto_id, PDO::PARAM_INT);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $datos[] = $registro;
      }
    }
    catch (PDOException $e) {
      $datos = [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function consultar_productos_tienda_bd() {
    $stmt = null;
    $datos = [];

    try {
      $dbh = $this->consultar_conexion_tienda_bd();
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
           . "  COALESCE(pro.etiqueta, cat.nombre, '') AS etiqueta,
"
           . "  COALESCE(pro.resumen, '') AS resumen,
"
           . "  COALESCE(pro.descripcion, '') AS descripcion,
"
           . "  COALESCE(pro.precio_oferta, pro.precio_base, 0) AS precio,
"
           . "  COALESCE(pro.precio_base, pro.precio_oferta, 0) AS precio_anterior,
"
           . "  COALESCE(pro.rating_promedio, 0) AS rating,
"
           . "  COALESCE(pro.stock, 0) AS stock,
"
           . "  COALESCE(cat.linea, 'general') AS linea,
"
           . "  COALESCE(cat.codigo, '') AS categoria,
"
           . "  COALESCE(cat.nombre, '') AS categoria_nombre,
"
           . "  pro.sw_destacado,
"
           . "  pro.sw_oferta,
"
           . "  COALESCE(pim.imagen_url, '') AS imagen_url,
"
           . "  COALESCE(pim.texto_alternativo, pro.nombre) AS texto_alternativo
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
           . "  AND cat.estado = B'1'
"
           . "  AND cat.borrado = B'0'
"
           . "ORDER BY
"
           . "  pro.orden ASC,
"
           . "  pro.producto_id ASC;";

      $stmt = $dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $precio = (int) ($registro['precio'] ?? 0);
        $precio_anterior = (int) ($registro['precio_anterior'] ?? 0);
        $rating = (float) ($registro['rating'] ?? 0);
        $producto_id = (int) ($registro['producto_id'] ?? 0);
        $datos[] = [
          'producto_id'            => $producto_id,
          'categoria_id'           => (int) ($registro['categoria_id'] ?? 0),
          'slug'                   => (string) ($registro['slug'] ?? ''),
          'nombre'                 => (string) ($registro['nombre'] ?? ''),
          'linea'                  => (string) ($registro['linea'] ?? 'general'),
          'categoria'              => strtolower((string) ($registro['categoria'] ?? 'general')),
          'categoria_nombre'       => (string) ($registro['categoria_nombre'] ?? ''),
          'etiqueta'               => (string) ($registro['etiqueta'] ?? ''),
          'precio'                 => $precio,
          'precio_anterior'        => $precio_anterior,
          'rating'                 => $rating,
          'media'                  => strtolower(str_replace([' ', '-'], '_', (string) ($registro['slug'] ?? 'producto'))),
          'resumen'                => (string) ($registro['resumen'] ?? ''),
          'descripcion'            => (string) ($registro['descripcion'] ?? ''),
          'beneficios'             => $this->construir_beneficios_producto_tienda($registro),
          'destacado'              => ((string) ($registro['sw_destacado'] ?? '0') === '1'),
          'oferta'                 => ((string) ($registro['sw_oferta'] ?? '0') === '1') || ($precio_anterior > $precio && $precio > 0),
          'stock'                  => (int) ($registro['stock'] ?? 0),
          'imagen_url'             => $this->resolver_imagen_producto_tienda((string) ($registro['imagen_url'] ?? ''), (string) ($registro['slug'] ?? ''), (string) ($registro['linea'] ?? 'general')),
          'texto_alternativo'      => (string) ($registro['texto_alternativo'] ?? $registro['nombre'] ?? ''),
          'imagenes'               => $this->consultar_imagenes_producto_tienda_bd($producto_id, (string) ($registro['slug'] ?? ''), (string) ($registro['linea'] ?? 'general')),
          'descuento_porcentaje'   => $this->calcular_descuento_producto_tienda($precio_anterior, $precio),
        ];
      }
    }
    catch (PDOException $e) {
      $datos = [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    return $datos;
  }

  private function consultar_productos_tienda() {
    $productos = $this->consultar_productos_tienda_bd();

    if (count($productos) > 0) {
      return $productos;
    }

    return $this->consultar_productos_base_tienda();
  }

  public function consultar_catalogo_tienda($linea = '', $buscar = '') {
    $productos = $this->consultar_productos_tienda();
    $linea = strtolower(trim((string) $linea));
    $buscar = $this->texto_minuscula_tienda(trim((string) $buscar));
    $datos = [];

    foreach ($productos as $producto) {
      if ($linea !== '' && strtolower((string) ($producto['linea'] ?? '')) !== $linea) {
        continue;
      }

      if ($buscar !== '') {
        $texto = $this->texto_minuscula_tienda(
          (string) ($producto['nombre'] ?? '')
          . ' ' . (string) ($producto['categoria'] ?? '')
          . ' ' . (string) ($producto['categoria_nombre'] ?? '')
          . ' ' . (string) ($producto['linea'] ?? '')
        );

        if (!$this->texto_contiene_tienda($texto, $buscar)) {
          continue;
        }
      }

      $datos[] = $producto;
    }

    return $datos;
  }

  public function consultar_producto_tienda($slug) {
    foreach ($this->consultar_productos_tienda() as $producto) {
      if ((string) ($producto['slug'] ?? '') === (string) $slug) {
        return $producto;
      }
    }

    return [];
  }

  public function consultar_destacados_tienda($limite = 6) {
    $datos = [];

    foreach ($this->consultar_productos_tienda() as $producto) {
      if (($producto['destacado'] ?? false) === true) {
        $datos[] = $producto;
      }

      if (count($datos) >= (int) $limite) {
        break;
      }
    }

    return $datos;
  }

  public function consultar_ofertas_tienda($limite = 6) {
    $datos = [];

    foreach ($this->consultar_productos_tienda() as $producto) {
      if (($producto['oferta'] ?? false) === true) {
        $datos[] = $producto;
      }

      if (count($datos) >= (int) $limite) {
        break;
      }
    }

    return $datos;
  }

  public function consultar_relacionados_tienda($linea, $slug, $limite = 4) {
    $datos = [];

    foreach ($this->consultar_productos_tienda() as $producto) {
      if ((string) ($producto['slug'] ?? '') === (string) $slug) {
        continue;
      }

      if ((string) ($producto['linea'] ?? '') === (string) $linea) {
        $datos[] = $producto;
      }

      if (count($datos) >= (int) $limite) {
        break;
      }
    }

    return $datos;
  }

  public function consultar_lineas_tienda() {
    $stmt = null;
    $lineas = [];

    try {
      $dbh = $this->consultar_conexion_tienda_bd();
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

      $stmt = $dbh->prepare($sql);
      $stmt->execute();

      while ($registro = $stmt->fetch()) {
        $codigo_linea = $this->texto_minuscula_tienda((string) ($registro['linea'] ?? ''));

        if ($codigo_linea === '' || isset($lineas[$codigo_linea])) {
          continue;
        }

        $titulo = trim((string) ($registro['nombre'] ?? ''));

        if ($titulo === '') {
          $titulo = ucfirst($codigo_linea);
        }

        $lineas[$codigo_linea] = [
          'titulo'             => $titulo,
          'descripcion'        => trim((string) ($registro['descripcion'] ?? '')),
          'ruta'               => '/catalogo/?linea=' . rawurlencode($codigo_linea),
          'imagen_url'         => $this->resolver_imagen_categoria_tienda((string) ($registro['imagen_url'] ?? ''), (string) ($registro['slug'] ?? ''), $codigo_linea),
          'texto_alternativo'  => trim((string) ($registro['texto_alternativo'] ?? $titulo)),
          'categoria_id'       => (int) ($registro['categoria_id'] ?? 0),
          'codigo_categoria'   => (string) ($registro['codigo'] ?? ''),
          'slug'               => (string) ($registro['slug'] ?? ''),
          'linea'              => $codigo_linea,
        ];
      }
    }
    catch (PDOException $e) {
      $lineas = [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }

    if (count($lineas) > 0) {
      return $lineas;
    }

    return [
      'maquillaje' => [
        'titulo'             => 'Maquillaje',
        'descripcion'        => 'Bases, rubores, labios y pestañas con foco comercial.',
        'ruta'               => '/catalogo/?linea=maquillaje',
        'imagen_url'         => $this->resolver_imagen_categoria_tienda('', 'maquillaje', 'maquillaje'),
        'texto_alternativo'  => 'Maquillaje',
        'categoria_id'       => 0,
        'codigo_categoria'   => 'MAQ',
        'slug'               => 'maquillaje',
        'linea'              => 'maquillaje',
      ],
      'skincare' => [
        'titulo'             => 'Skincare',
        'descripcion'        => 'Rutinas de limpieza, hidratación y glow.',
        'ruta'               => '/catalogo/?linea=skincare',
        'imagen_url'         => $this->resolver_imagen_categoria_tienda('', 'skincare', 'skincare'),
        'texto_alternativo'  => 'Skincare',
        'categoria_id'       => 0,
        'codigo_categoria'   => 'SKIN',
        'slug'               => 'skincare',
        'linea'              => 'skincare',
      ],
      'accesorios' => [
        'titulo'             => 'Accesorios',
        'descripcion'        => 'Brochas, kits, organizadores y detalles para regalo.',
        'ruta'               => '/catalogo/?linea=accesorios',
        'imagen_url'         => $this->resolver_imagen_categoria_tienda('', 'accesorios', 'accesorios'),
        'texto_alternativo'  => 'Accesorios',
        'categoria_id'       => 0,
        'codigo_categoria'   => 'ACC',
        'slug'               => 'accesorios',
        'linea'              => 'accesorios',
      ],
    ];
  }

  public function consultar_carrito_tienda() {
    $items = $_SESSION['tv_carrito'] ?? [];
    $datos = [];
    $subtotal = 0;
    $ahorro = 0;

    foreach ($items as $slug => $cantidad) {
      $producto = $this->consultar_producto_tienda($slug);

      if (empty($producto)) {
        continue;
      }

      $cantidad = (int) $cantidad;
      $precio = (int) ($producto['precio'] ?? 0);
      $precio_anterior = (int) ($producto['precio_anterior'] ?? 0);
      $producto['cantidad'] = $cantidad;
      $producto['total'] = $precio * $cantidad;
      $producto['total_anterior'] = $precio_anterior > $precio ? $precio_anterior * $cantidad : 0;
      $subtotal += (int) $producto['total'];
      $ahorro += $producto['total_anterior'] > (int) $producto['total'] ? $producto['total_anterior'] - (int) $producto['total'] : 0;
      $datos[] = $producto;
    }

    $envio = $subtotal > 0 ? 15000 : 0;

    return [
      'items'     => $datos,
      'subtotal'  => $subtotal,
      'ahorro'    => $ahorro,
      'envio'     => $envio,
      'total'     => $subtotal + $envio,
      'cantidad'  => array_sum(array_map('intval', $items)),
    ];
  }

  public function agregar_producto_carrito_tienda($slug, $cantidad = 1) {
    if ($slug === '' || (int) $cantidad <= 0) {
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
}
?>
