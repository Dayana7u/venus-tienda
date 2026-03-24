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
        'producto_id'     => 1,
        'slug'            => 'serum-glow-rose',
        'nombre'          => 'Serum Glow Rose',
        'linea'           => 'skincare',
        'categoria'       => 'sueros',
        'etiqueta'        => 'Skincare',
        'precio'          => 89900,
        'precio_anterior' => 109900,
        'rating'          => 4.9,
        'media'           => 'serum_rose',
        'resumen'         => 'Suero hidratante con acabado glow para rutina de día.',
        'descripcion'     => 'Fórmula ligera para hidratar, iluminar y preparar la piel antes del maquillaje.',
        'beneficios'      => ['Hidratación ligera', 'Brillo natural', 'Ideal para rutina diaria'],
        'destacado'       => true,
        'oferta'          => true,
      ],
      [
        'producto_id'     => 2,
        'slug'            => 'base-soft-matte',
        'nombre'          => 'Base Soft Matte',
        'linea'           => 'maquillaje',
        'categoria'       => 'rostro',
        'etiqueta'        => 'Maquillaje',
        'precio'          => 74900,
        'precio_anterior' => 89900,
        'rating'          => 4.8,
        'media'           => 'base_matte',
        'resumen'         => 'Base de cobertura media-alta con acabado uniforme.',
        'descripcion'     => 'Cobertura construible y sensación liviana para un look profesional.',
        'beneficios'      => ['Cobertura media-alta', 'Acabado uniforme', 'Fácil de difuminar'],
        'destacado'       => true,
        'oferta'          => true,
      ],
      [
        'producto_id'     => 3,
        'slug'            => 'lip-oil-peony',
        'nombre'          => 'Lip Oil Peony',
        'linea'           => 'maquillaje',
        'categoria'       => 'labios',
        'etiqueta'        => 'Glow lips',
        'precio'          => 45900,
        'precio_anterior' => 0,
        'rating'          => 4.7,
        'media'           => 'lip_oil',
        'resumen'         => 'Aceite labial con brillo suave y sensación de hidratación.',
        'descripcion'     => 'Perfecto para looks limpios y combinaciones con delineado suave.',
        'beneficios'      => ['Brillo natural', 'Fórmula liviana', 'Uso diario'],
        'destacado'       => true,
        'oferta'          => false,
      ],
      [
        'producto_id'     => 4,
        'slug'            => 'brochas-essential-set',
        'nombre'          => 'Brochas Essential Set',
        'linea'           => 'accesorios',
        'categoria'       => 'brochas',
        'etiqueta'        => 'Accesorios',
        'precio'          => 69900,
        'precio_anterior' => 84900,
        'rating'          => 4.9,
        'media'           => 'brochas_set',
        'resumen'         => 'Set base para rostro, ojos y detalles.',
        'descripcion'     => 'Kit de brochas pensado para una rutina completa de maquillaje.',
        'beneficios'      => ['Set completo', 'Cerdas suaves', 'Uso diario'],
        'destacado'       => true,
        'oferta'          => true,
      ],
      [
        'producto_id'     => 5,
        'slug'            => 'crema-barrier-night',
        'nombre'          => 'Crema Barrier Night',
        'linea'           => 'skincare',
        'categoria'       => 'hidratantes',
        'etiqueta'        => 'Noche',
        'precio'          => 83900,
        'precio_anterior' => 0,
        'rating'          => 4.8,
        'media'           => 'crema_noche',
        'resumen'         => 'Crema de noche para reparar y suavizar la barrera cutánea.',
        'descripcion'     => 'Ideal para cerrar la rutina y dejar sensación de confort.',
        'beneficios'      => ['Nutrición nocturna', 'Textura cremosa', 'Apoyo a la barrera'],
        'destacado'       => false,
        'oferta'          => false,
      ],
      [
        'producto_id'     => 6,
        'slug'            => 'organizador-vanity-mini',
        'nombre'          => 'Organizador Vanity Mini',
        'linea'           => 'accesorios',
        'categoria'       => 'organizadores',
        'etiqueta'        => 'Vanity',
        'precio'          => 52900,
        'precio_anterior' => 63900,
        'rating'          => 4.6,
        'media'           => 'organizador',
        'resumen'         => 'Organizador compacto para skincare y maquillaje.',
        'descripcion'     => 'Perfecto para tocador, viaje o kits de regalo.',
        'beneficios'      => ['Compacto', 'Orden visual', 'Práctico para viaje'],
        'destacado'       => false,
        'oferta'          => true,
      ],
      [
        'producto_id'     => 7,
        'slug'            => 'mask-hydra-cloud',
        'nombre'          => 'Mask Hydra Cloud',
        'linea'           => 'skincare',
        'categoria'       => 'mascaras',
        'etiqueta'        => 'Tratamiento',
        'precio'          => 58900,
        'precio_anterior' => 69900,
        'rating'          => 4.7,
        'media'           => 'mask_cloud',
        'resumen'         => 'Mascarilla cremosa para hidratación profunda.',
        'descripcion'     => 'Se usa dos veces por semana para una piel más luminosa.',
        'beneficios'      => ['Hidratación intensa', 'Textura cremosa', 'Brillo saludable'],
        'destacado'       => false,
        'oferta'          => true,
      ],
      [
        'producto_id'     => 8,
        'slug'            => 'blush-soft-peach',
        'nombre'          => 'Blush Soft Peach',
        'linea'           => 'maquillaje',
        'categoria'       => 'rostro',
        'etiqueta'        => 'Color',
        'precio'          => 42900,
        'precio_anterior' => 0,
        'rating'          => 4.8,
        'media'           => 'blush_peach',
        'resumen'         => 'Rubor de acabado natural para un look fresco.',
        'descripcion'     => 'Textura sedosa que se integra fácilmente a la piel.',
        'beneficios'      => ['Acabado natural', 'Fácil de trabajar', 'Tono versátil'],
        'destacado'       => true,
        'oferta'          => false,
      ],
      [
        'producto_id'     => 9,
        'slug'            => 'kit-gift-bloom',
        'nombre'          => 'Kit Gift Bloom',
        'linea'           => 'accesorios',
        'categoria'       => 'sets',
        'etiqueta'        => 'Regalo',
        'precio'          => 119900,
        'precio_anterior' => 149900,
        'rating'          => 4.9,
        'media'           => 'gift_bloom',
        'resumen'         => 'Set listo para regalo con cosmetiquera y selección glow.',
        'descripcion'     => 'Campaña comercial pensada para fechas especiales.',
        'beneficios'      => ['Listo para regalar', 'Presentación premium', 'Edición especial'],
        'destacado'       => true,
        'oferta'          => true,
      ],
    ];
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
        $texto = strtolower($producto['nombre'] . ' ' . $producto['categoria'] . ' ' . $producto['linea']);

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
    $datos = [];

    foreach ($this->consultar_productos_base_tienda() as $producto) {
      if ($producto['destacado'] === true) {
        $datos[] = $producto;
      }

      if (count($datos) >= $limite) {
        break;
      }
    }

    return $datos;
  }

  public function consultar_ofertas_tienda($limite = 6) {
    $datos = [];

    foreach ($this->consultar_productos_base_tienda() as $producto) {
      if ($producto['oferta'] === true) {
        $datos[] = $producto;
      }

      if (count($datos) >= $limite) {
        break;
      }
    }

    return $datos;
  }

  public function consultar_relacionados_tienda($linea, $slug, $limite = 4) {
    $datos = [];

    foreach ($this->consultar_productos_base_tienda() as $producto) {
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
    return [
      'maquillaje' => [
        'titulo'       => 'Maquillaje diario',
        'descripcion'  => 'Bases, rubores, labios y pestañas con foco comercial.',
      ],
      'skincare' => [
        'titulo'       => 'Skincare',
        'descripcion'  => 'Rutinas de limpieza, hidratación y glow.',
      ],
      'accesorios' => [
        'titulo'       => 'Accesorios',
        'descripcion'  => 'Brochas, kits, organizadores y detalles para regalo.',
      ],
    ];
  }

  public function consultar_carrito_tienda() {
    $items = $_SESSION['tv_carrito'] ?? [];
    $datos = [];
    $subtotal = 0;

    foreach ($items as $slug => $cantidad) {
      $producto = $this->consultar_producto_tienda($slug);

      if (empty($producto)) {
        continue;
      }

      $producto['cantidad'] = (int) $cantidad;
      $producto['total']    = (int) $producto['precio'] * (int) $cantidad;
      $subtotal            += (int) $producto['total'];
      $datos[]              = $producto;
    }

    return [
      'items'     => $datos,
      'subtotal'  => $subtotal,
      'envio'     => $subtotal > 0 ? 15000 : 0,
      'total'     => $subtotal > 0 ? $subtotal + 15000 : 0,
      'cantidad'  => array_sum(array_map('intval', $items)),
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
}
?>
