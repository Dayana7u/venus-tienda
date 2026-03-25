<?php
require_once __DIR__ . '/tienda_catalogo_base_model.class.php';

class tienda_inicio_model extends tienda_catalogo_base_model {
  public function consultar_inicio_tienda() {
    $contexto = $this->consultar_contexto_tienda();

    return [
      'contexto'     => $contexto,
      'productos'    => $this->consultar_catalogo_tienda('', ''),
      'destacados'   => $this->consultar_destacados_tienda(8),
      'ofertas'      => $this->consultar_ofertas_tienda(6),
      'lineas'       => $this->consultar_lineas_tienda(),
      'carrito'      => $this->consultar_carrito_tienda(),
    ];
  }
}
?>
