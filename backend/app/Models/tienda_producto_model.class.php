<?php
require_once __DIR__ . '/tienda_catalogo_base_model.class.php';

class tienda_producto_model extends tienda_catalogo_base_model {
  public function consultar_producto_modulo_tienda($slug = '') {
    $contexto = $this->consultar_contexto_tienda();
    $producto = $this->consultar_producto_tienda($slug);

    return [
      'contexto'     => $contexto,
      'producto'     => $producto,
      'relacionados' => empty($producto) ? [] : $this->consultar_relacionados_tienda($producto['linea'], $producto['slug'], 4),
      'carrito'      => $this->consultar_carrito_tienda(),
    ];
  }
}
?>
