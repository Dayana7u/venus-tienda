<?php
require_once __DIR__ . '/tienda_catalogo_base_model.class.php';

class tienda_pedido_comprobante_model extends tienda_catalogo_base_model {
  public function consultar_pedido_comprobante_modulo_tienda() {
    $codigo_pedido = trim((string) ($_GET['pedido'] ?? ''));

    if ($codigo_pedido === '') {
      $codigo_pedido = trim((string) ($_SESSION['tv_checkout_ultimo_pedido']['codigo'] ?? ''));
    }

    return [
      'contexto' => $this->consultar_contexto_tienda(),
      'carrito'  => $this->consultar_carrito_tienda(),
      'pedido'   => $this->consultar_pedido_comprobante_tienda($codigo_pedido),
      'items'    => $this->consultar_items_comprobante_tienda($codigo_pedido),
      'pago'     => $this->consultar_pago_comprobante_tienda($codigo_pedido),
    ];
  }

  private function consultar_pedido_comprobante_tienda($codigo_pedido = '') {
    $stmt = null;

    try {
      if ($codigo_pedido === '') {
        return $this->consultar_pedido_comprobante_sesion_tienda();
      }

      $dbh = configdb_obtener_conexion();
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
           . "  ped.subtotal,
"
           . "  ped.descuento_total,
"
           . "  ped.envio_total,
"
           . "  ped.total,
"
           . "  ped.direccion_resumen,
"
           . "  ped.observacion,
"
           . "  ped.fecha_pedido,
"
           . "  cli.nombres,
"
           . "  cli.apellidos,
"
           . "  cli.correo,
"
           . "  cli.celular
"
           . "FROM
"
           . "  public.pedidos_tienda ped
"
           . "INNER JOIN public.clientes_tienda cli
"
           . "  ON cli.cliente_tienda_id = ped.cliente_tienda_id
"
           . "WHERE
"
           . "  ped.codigo = :codigo
"
           . "  AND ped.estado = B'1'
"
           . "  AND ped.borrado = B'0'
"
           . "LIMIT 1;";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':codigo', $codigo_pedido, PDO::PARAM_STR);
      $stmt->execute();
      $registro = $stmt->fetch();

      if (!$registro) {
        return $this->consultar_pedido_comprobante_sesion_tienda();
      }

      $registro['cliente'] = trim((string) $registro['nombres'] . ' ' . (string) $registro['apellidos']);
      return $registro;
    }
    catch (Throwable $e) {
      configdb_registrar_log(__FILE__, __FUNCTION__, $e->getMessage(), $codigo_pedido);
      return $this->consultar_pedido_comprobante_sesion_tienda();
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_items_comprobante_tienda($codigo_pedido = '') {
    $stmt = null;

    try {
      if ($codigo_pedido === '') {
        return $_SESSION['tv_checkout_ultimo_items'] ?? [];
      }

      $dbh = configdb_obtener_conexion();
      $sql = "SELECT
"
           . "  det.producto_codigo AS codigo,
"
           . "  det.producto_nombre AS nombre,
"
           . "  det.producto_slug AS slug,
"
           . "  det.imagen_url,
"
           . "  det.cantidad,
"
           . "  det.precio_unitario AS precio,
"
           . "  det.descuento_unitario AS descuento,
"
           . "  det.total_linea
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
           . "  ped.codigo = :codigo
"
           . "  AND det.estado = B'1'
"
           . "  AND det.borrado = B'0'
"
           . "ORDER BY
"
           . "  det.pedido_tienda_detalle_id ASC;";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':codigo', $codigo_pedido, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->fetchAll();
    }
    catch (Throwable $e) {
      configdb_registrar_log(__FILE__, __FUNCTION__, $e->getMessage(), $codigo_pedido);
      return $_SESSION['tv_checkout_ultimo_items'] ?? [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_pago_comprobante_tienda($codigo_pedido = '') {
    $stmt = null;

    try {
      if ($codigo_pedido === '') {
        return [];
      }

      $dbh = configdb_obtener_conexion();
      $sql = "SELECT
"
           . "  pag.codigo,
"
           . "  pag.metodo_pago,
"
           . "  pag.estado_pago,
"
           . "  pag.monto,
"
           . "  pag.titular_pagador,
"
           . "  pag.documento_pagador,
"
           . "  pag.correo_pagador,
"
           . "  pag.entidad_pse,
"
           . "  pag.franquicia_tarjeta,
"
           . "  pag.ultimos_cuatro,
"
           . "  pag.referencia_pasarela,
"
           . "  pag.fecha_procesamiento
"
           . "FROM
"
           . "  public.pagos_tienda pag
"
           . "INNER JOIN public.pedidos_tienda ped
"
           . "  ON ped.pedido_tienda_id = pag.pedido_tienda_id
"
           . "WHERE
"
           . "  ped.codigo = :codigo
"
           . "  AND pag.estado = B'1'
"
           . "  AND pag.borrado = B'0'
"
           . "ORDER BY
"
           . "  pag.pago_tienda_id DESC
"
           . "LIMIT 1;";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':codigo', $codigo_pedido, PDO::PARAM_STR);
      $stmt->execute();
      $registro = $stmt->fetch();

      return $registro ? $registro : [];
    }
    catch (Throwable $e) {
      configdb_registrar_log(__FILE__, __FUNCTION__, $e->getMessage(), $codigo_pedido);
      return [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_pedido_comprobante_sesion_tienda() {
    $pedido = $_SESSION['tv_checkout_ultimo_pedido'] ?? [];

    if (count($pedido) === 0) {
      return [
        'codigo'         => '',
        'estado_pedido'  => 'pendiente',
        'estado_pago'    => 'pendiente',
        'metodo_pago'    => '',
        'cantidad_items' => 0,
        'subtotal'       => 0,
        'descuento_total'=> 0,
        'envio_total'    => 0,
        'total'          => 0,
        'direccion_resumen' => '',
        'observacion'    => '',
        'fecha_pedido'   => date('Y-m-d H:i:s'),
        'cliente'        => '',
        'correo'         => '',
        'celular'        => '',
      ];
    }

    return [
      'codigo'            => $pedido['codigo'] ?? '',
      'estado_pedido'     => 'pendiente',
      'estado_pago'       => $pedido['estado_pago'] ?? 'pendiente',
      'metodo_pago'       => $pedido['metodo_pago'] ?? '',
      'cantidad_items'    => count($_SESSION['tv_checkout_ultimo_items'] ?? []),
      'subtotal'          => 0,
      'descuento_total'   => 0,
      'envio_total'       => 0,
      'total'             => $pedido['total'] ?? 0,
      'direccion_resumen' => '',
      'observacion'       => '',
      'fecha_pedido'      => date('Y-m-d H:i:s'),
      'cliente'           => $pedido['cliente'] ?? '',
      'correo'            => '',
      'celular'           => '',
    ];
  }
}
?>
