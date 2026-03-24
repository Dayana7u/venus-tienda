<?php
require_once __DIR__ . '/tienda_catalogo_base_model.class.php';

class tienda_ofertas_model extends tienda_catalogo_base_model {
  public function consultar_ofertas_modulo_tienda() {
    return [
      'contexto'   => $this->consultar_contexto_tienda(),
      'productos'  => $this->consultar_ofertas_tienda(8),
      'carrito'    => $this->consultar_carrito_tienda(),
    ];
  }
}
?>
