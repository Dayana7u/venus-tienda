<?php
require_once __DIR__ . '/tienda_catalogo_base_model.class.php';

class tienda_checkout_model extends tienda_catalogo_base_model {
  private $modulo = __FILE__;
  private $usuario_sistema_id = 1;

  public function consultar_checkout_modulo_tienda() {
    return [
      'contexto' => $this->consultar_contexto_tienda(),
      'carrito'  => $this->consultar_carrito_tienda(),
      'pedido'   => $this->consultar_resumen_checkout_tienda(),
    ];
  }

  public function guardar_checkout_tienda() {
    $datos = $this->asignar_variables_guardar_checkout_tienda();
    $validacion = $this->validar_datos_guardar_checkout_tienda($datos);

    if ($validacion['estado'] !== true) {
      return $validacion;
    }

    $dbh = null;
    $stmt = null;

    try {
      $dbh = configdb_obtener_conexion();
      $dbh->beginTransaction();

      $cliente_tienda_id = $this->guardar_cliente_checkout_tienda($dbh, $datos);
      $direccion_id = $this->guardar_direccion_checkout_tienda($dbh, $cliente_tienda_id, $datos);
      $pedido = $this->guardar_pedido_checkout_tienda($dbh, $cliente_tienda_id, $direccion_id, $datos);
      $this->guardar_detalle_pedido_checkout_tienda($dbh, $pedido['pedido_tienda_id'], $datos['carrito']);
      $pago = $this->guardar_pago_checkout_tienda($dbh, $pedido['pedido_tienda_id'], $cliente_tienda_id, $datos);

      $sql = "UPDATE public.pedidos_tienda
"
           . "SET
"
           . "  estado_pago          = :estado_pago,
"
           . "  estado_pedido        = :estado_pedido,
"
           . "  metodo_pago          = :metodo_pago,
"
           . "  usuario_modificacion = :usuario_modificacion,
"
           . "  fecha_modificacion   = NOW()
"
           . "WHERE
"
           . "  pedido_tienda_id = :pedido_tienda_id;";

      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':estado_pago', $pago['estado_pago'], PDO::PARAM_STR);
      $stmt->bindValue(':estado_pedido', $pago['estado_pago'] === 'pagado' ? 'alistando' : 'pendiente', PDO::PARAM_STR);
      $stmt->bindValue(':metodo_pago', $datos['metodo_pago'], PDO::PARAM_STR);
      $stmt->bindValue(':usuario_modificacion', $this->usuario_sistema_id, PDO::PARAM_INT);
      $stmt->bindValue(':pedido_tienda_id', $pedido['pedido_tienda_id'], PDO::PARAM_INT);
      $stmt->execute();
      $stmt = null;

      $dbh->commit();

      $_SESSION['tv_checkout_ultimo_pedido'] = [
        'codigo'       => $pedido['codigo'],
        'metodo_pago'  => $datos['metodo_pago'],
        'estado_pago'  => $pago['estado_pago'],
        'total'        => $pedido['total'],
        'cliente'      => trim($datos['nombres'] . ' ' . $datos['apellidos']),
      ];
      $_SESSION['tv_mensaje'] = 'Pago registrado correctamente. Pedido ' . $pedido['codigo'] . '.';
      unset($_SESSION['tv_carrito']);

      return [
        'estado'  => true,
        'mensaje' => 'Pago registrado correctamente.',
        'datos'   => [
          'pedido_codigo' => $pedido['codigo'],
          'redirect'      => '/checkout/?pedido=' . urlencode($pedido['codigo']) . '&estado=ok',
        ],
      ];
    }
    catch (Throwable $e) {
      if ($dbh && $dbh->inTransaction()) {
        $dbh->rollBack();
      }

      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'checkout_tienda');

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible registrar el pago de la compra.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function asignar_variables_guardar_checkout_tienda() {
    return [
      'nombres'                 => trim((string) ($_POST['nombres'] ?? '')),
      'apellidos'               => trim((string) ($_POST['apellidos'] ?? '')),
      'correo'                  => trim((string) ($_POST['correo'] ?? '')),
      'celular'                 => trim((string) ($_POST['celular'] ?? '')),
      'destinatario'            => trim((string) ($_POST['destinatario'] ?? '')),
      'telefono_direccion'      => trim((string) ($_POST['telefono_direccion'] ?? '')),
      'direccion_linea_1'       => trim((string) ($_POST['direccion_linea_1'] ?? '')),
      'direccion_linea_2'       => trim((string) ($_POST['direccion_linea_2'] ?? '')),
      'ciudad'                  => trim((string) ($_POST['ciudad'] ?? '')),
      'departamento'            => trim((string) ($_POST['departamento'] ?? '')),
      'codigo_postal'           => trim((string) ($_POST['codigo_postal'] ?? '')),
      'referencia'              => trim((string) ($_POST['referencia'] ?? '')),
      'observacion'             => trim((string) ($_POST['observacion'] ?? '')),
      'metodo_pago'             => trim((string) ($_POST['metodo_pago'] ?? '')),
      'documento_pagador'       => trim((string) ($_POST['documento_pagador'] ?? '')),
      'titular_pagador'         => trim((string) ($_POST['titular_pagador'] ?? '')),
      'correo_pagador'          => trim((string) ($_POST['correo_pagador'] ?? '')),
      'entidad_pse'             => trim((string) ($_POST['entidad_pse'] ?? '')),
      'tipo_persona_pse'        => trim((string) ($_POST['tipo_persona_pse'] ?? '')),
      'tipo_cuenta_pse'         => trim((string) ($_POST['tipo_cuenta_pse'] ?? '')),
      'franquicia_tarjeta'      => trim((string) ($_POST['franquicia_tarjeta'] ?? '')),
      'numero_tarjeta'          => preg_replace('/\D+/', '', (string) ($_POST['numero_tarjeta'] ?? '')),
      'fecha_expiracion'        => trim((string) ($_POST['fecha_expiracion'] ?? '')),
      'cuotas'                  => (int) ($_POST['cuotas'] ?? 1),
      'carrito'                 => $this->consultar_carrito_tienda(),
    ];
  }

  private function validar_datos_guardar_checkout_tienda($datos = []) {
    if (count($datos['carrito']['items'] ?? []) === 0) {
      return [
        'estado'  => false,
        'mensaje' => 'No hay productos en el carrito para continuar con el pago.',
        'datos'   => [],
      ];
    }

    $campos = [
      'nombres'            => 'Debes ingresar los nombres.',
      'apellidos'          => 'Debes ingresar los apellidos.',
      'correo'             => 'Debes ingresar el correo.',
      'celular'            => 'Debes ingresar el celular.',
      'destinatario'       => 'Debes ingresar el destinatario.',
      'telefono_direccion' => 'Debes ingresar el teléfono de la dirección.',
      'direccion_linea_1'  => 'Debes ingresar la dirección principal.',
      'ciudad'             => 'Debes ingresar la ciudad.',
      'departamento'       => 'Debes ingresar el departamento.',
      'metodo_pago'        => 'Debes seleccionar el método de pago.',
      'documento_pagador'  => 'Debes ingresar el documento del pagador.',
    ];

    foreach ($campos as $campo => $mensaje) {
      if (trim((string) ($datos[$campo] ?? '')) === '') {
        return [
          'estado'  => false,
          'mensaje' => $mensaje,
          'datos'   => [],
        ];
      }
    }

    if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
      return [
        'estado'  => false,
        'mensaje' => 'El correo del comprador no es válido.',
        'datos'   => [],
      ];
    }

    if ($datos['correo_pagador'] !== '' && !filter_var($datos['correo_pagador'], FILTER_VALIDATE_EMAIL)) {
      return [
        'estado'  => false,
        'mensaje' => 'El correo del pagador no es válido.',
        'datos'   => [],
      ];
    }

    if ($datos['correo_pagador'] === '') {
      $datos['correo_pagador'] = $datos['correo'];
    }

    if (!in_array($datos['metodo_pago'], ['pse', 'tarjeta', 'contra_entrega'], true)) {
      return [
        'estado'  => false,
        'mensaje' => 'El método de pago seleccionado no es válido.',
        'datos'   => [],
      ];
    }

    if ($datos['metodo_pago'] === 'pse') {
      if ($datos['entidad_pse'] === '') {
        return [
          'estado'  => false,
          'mensaje' => 'Debes seleccionar la entidad bancaria para PSE.',
          'datos'   => [],
        ];
      }

      if ($datos['tipo_persona_pse'] === '' || $datos['tipo_cuenta_pse'] === '') {
        return [
          'estado'  => false,
          'mensaje' => 'Debes completar el tipo de persona y cuenta para PSE.',
          'datos'   => [],
        ];
      }
    }

    if ($datos['metodo_pago'] === 'tarjeta') {
      if ($datos['titular_pagador'] === '' || $datos['numero_tarjeta'] === '' || $datos['fecha_expiracion'] === '') {
        return [
          'estado'  => false,
          'mensaje' => 'Completa los datos de la tarjeta para continuar.',
          'datos'   => [],
        ];
      }

      if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $datos['fecha_expiracion'])) {
        return [
          'estado'  => false,
          'mensaje' => 'La fecha de expiración de la tarjeta no es válida.',
          'datos'   => [],
        ];
      }

      if ($datos['cuotas'] < 1) {
        return [
          'estado'  => false,
          'mensaje' => 'Las cuotas seleccionadas no son válidas.',
          'datos'   => [],
        ];
      }

      if (strlen($datos['numero_tarjeta']) < 12) {
        return [
          'estado'  => false,
          'mensaje' => 'El número de tarjeta no es válido.',
          'datos'   => [],
        ];
      }
    }

    return [
      'estado'  => true,
      'mensaje' => 'Validación correcta.',
      'datos'   => [],
    ];
  }

  private function consultar_codigo_cliente_checkout_tienda($dbh) {
    $stmt = null;

    try {
      $sql = "SELECT
"
           . "  COALESCE(MAX(cli.cliente_tienda_id), 0) + 1 AS siguiente
"
           . "FROM
"
           . "  public.clientes_tienda cli;";
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $siguiente = (int) ($stmt->fetch()['siguiente'] ?? 1);
      return 'CLI-TIENDA-' . str_pad((string) $siguiente, 4, '0', STR_PAD_LEFT);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_codigo_pedido_checkout_tienda($dbh) {
    $stmt = null;

    try {
      $sql = "SELECT
"
           . "  COALESCE(MAX(ped.pedido_tienda_id), 0) + 1 AS siguiente
"
           . "FROM
"
           . "  public.pedidos_tienda ped;";
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $siguiente = (int) ($stmt->fetch()['siguiente'] ?? 1);
      return 'PED-' . date('Y') . '-' . str_pad((string) $siguiente, 4, '0', STR_PAD_LEFT);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_codigo_pago_checkout_tienda($dbh) {
    $stmt = null;

    try {
      $sql = "SELECT
"
           . "  COALESCE(MAX(pag.pago_tienda_id), 0) + 1 AS siguiente
"
           . "FROM
"
           . "  public.pagos_tienda pag;";
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $siguiente = (int) ($stmt->fetch()['siguiente'] ?? 1);
      return 'PAG-' . date('Y') . '-' . str_pad((string) $siguiente, 4, '0', STR_PAD_LEFT);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function guardar_cliente_checkout_tienda($dbh, $datos = []) {
    $stmt = null;

    try {
      $sql = "SELECT
"
           . "  cli.cliente_tienda_id
"
           . "FROM
"
           . "  public.clientes_tienda cli
"
           . "WHERE
"
           . "  cli.correo = :correo
"
           . "  AND cli.borrado = B'0'
"
           . "LIMIT 1;";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':correo', $datos['correo'], PDO::PARAM_STR);
      $stmt->execute();
      $cliente_tienda_id = (int) ($stmt->fetch()['cliente_tienda_id'] ?? 0);
      $stmt = null;

      if ($cliente_tienda_id > 0) {
        $sql = "UPDATE public.clientes_tienda
"
             . "SET
"
             . "  nombres              = :nombres,
"
             . "  apellidos            = :apellidos,
"
             . "  celular              = :celular,
"
             . "  usuario_modificacion = :usuario_modificacion,
"
             . "  fecha_modificacion   = NOW()
"
             . "WHERE
"
             . "  cliente_tienda_id = :cliente_tienda_id;";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':nombres', $datos['nombres'], PDO::PARAM_STR);
        $stmt->bindValue(':apellidos', $datos['apellidos'], PDO::PARAM_STR);
        $stmt->bindValue(':celular', $datos['celular'], PDO::PARAM_STR);
        $stmt->bindValue(':usuario_modificacion', $this->usuario_sistema_id, PDO::PARAM_INT);
        $stmt->bindValue(':cliente_tienda_id', $cliente_tienda_id, PDO::PARAM_INT);
        $stmt->execute();
        return $cliente_tienda_id;
      }

      $sql = "INSERT INTO public.clientes_tienda
"
           . "(
"
           . "  codigo,
"
           . "  nombres,
"
           . "  apellidos,
"
           . "  correo,
"
           . "  celular,
"
           . "  clave,
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
           . "  :nombres,
"
           . "  :apellidos,
"
           . "  :correo,
"
           . "  :celular,
"
           . "  :clave,
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW()
"
           . ") RETURNING cliente_tienda_id;";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':codigo', $this->consultar_codigo_cliente_checkout_tienda($dbh), PDO::PARAM_STR);
        $stmt->bindValue(':nombres', $datos['nombres'], PDO::PARAM_STR);
        $stmt->bindValue(':apellidos', $datos['apellidos'], PDO::PARAM_STR);
        $stmt->bindValue(':correo', $datos['correo'], PDO::PARAM_STR);
        $stmt->bindValue(':celular', $datos['celular'], PDO::PARAM_STR);
        $stmt->bindValue(':clave', password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':usuario_creacion', $this->usuario_sistema_id, PDO::PARAM_INT);
        $stmt->execute();
        return (int) ($stmt->fetch()['cliente_tienda_id'] ?? 0);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function guardar_direccion_checkout_tienda($dbh, $cliente_tienda_id, $datos = []) {
    $stmt = null;

    try {
      $sql = "SELECT
"
           . "  dir.cliente_tienda_direccion_id
"
           . "FROM
"
           . "  public.clientes_tienda_direcciones dir
"
           . "WHERE
"
           . "  dir.cliente_tienda_id = :cliente_tienda_id
"
           . "  AND dir.sw_principal = B'1'
"
           . "  AND dir.borrado = B'0'
"
           . "LIMIT 1;";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':cliente_tienda_id', $cliente_tienda_id, PDO::PARAM_INT);
      $stmt->execute();
      $direccion_id = (int) ($stmt->fetch()['cliente_tienda_direccion_id'] ?? 0);
      $stmt = null;

      if ($direccion_id > 0) {
        $sql = "UPDATE public.clientes_tienda_direcciones
"
             . "SET
"
             . "  destinatario         = :destinatario,
"
             . "  telefono             = :telefono,
"
             . "  direccion_linea_1    = :direccion_linea_1,
"
             . "  direccion_linea_2    = :direccion_linea_2,
"
             . "  ciudad               = :ciudad,
"
             . "  departamento         = :departamento,
"
             . "  codigo_postal        = :codigo_postal,
"
             . "  referencia           = :referencia,
"
             . "  usuario_modificacion = :usuario_modificacion,
"
             . "  fecha_modificacion   = NOW()
"
             . "WHERE
"
             . "  cliente_tienda_direccion_id = :cliente_tienda_direccion_id;";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':destinatario', $datos['destinatario'], PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $datos['telefono_direccion'], PDO::PARAM_STR);
        $stmt->bindValue(':direccion_linea_1', $datos['direccion_linea_1'], PDO::PARAM_STR);
        $stmt->bindValue(':direccion_linea_2', $datos['direccion_linea_2'], PDO::PARAM_STR);
        $stmt->bindValue(':ciudad', $datos['ciudad'], PDO::PARAM_STR);
        $stmt->bindValue(':departamento', $datos['departamento'], PDO::PARAM_STR);
        $stmt->bindValue(':codigo_postal', $datos['codigo_postal'], PDO::PARAM_STR);
        $stmt->bindValue(':referencia', $datos['referencia'], PDO::PARAM_STR);
        $stmt->bindValue(':usuario_modificacion', $this->usuario_sistema_id, PDO::PARAM_INT);
        $stmt->bindValue(':cliente_tienda_direccion_id', $direccion_id, PDO::PARAM_INT);
        $stmt->execute();
        return $direccion_id;
      }

      $sql = "INSERT INTO public.clientes_tienda_direcciones
"
           . "(
"
           . "  cliente_tienda_id,
"
           . "  alias,
"
           . "  destinatario,
"
           . "  telefono,
"
           . "  direccion_linea_1,
"
           . "  direccion_linea_2,
"
           . "  ciudad,
"
           . "  departamento,
"
           . "  codigo_postal,
"
           . "  referencia,
"
           . "  sw_principal,
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
           . "  :cliente_tienda_id,
"
           . "  :alias,
"
           . "  :destinatario,
"
           . "  :telefono,
"
           . "  :direccion_linea_1,
"
           . "  :direccion_linea_2,
"
           . "  :ciudad,
"
           . "  :departamento,
"
           . "  :codigo_postal,
"
           . "  :referencia,
"
           . "  B'1',
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW()
"
           . ") RETURNING cliente_tienda_direccion_id;";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':cliente_tienda_id', $cliente_tienda_id, PDO::PARAM_INT);
      $stmt->bindValue(':alias', 'Principal', PDO::PARAM_STR);
      $stmt->bindValue(':destinatario', $datos['destinatario'], PDO::PARAM_STR);
      $stmt->bindValue(':telefono', $datos['telefono_direccion'], PDO::PARAM_STR);
      $stmt->bindValue(':direccion_linea_1', $datos['direccion_linea_1'], PDO::PARAM_STR);
      $stmt->bindValue(':direccion_linea_2', $datos['direccion_linea_2'], PDO::PARAM_STR);
      $stmt->bindValue(':ciudad', $datos['ciudad'], PDO::PARAM_STR);
      $stmt->bindValue(':departamento', $datos['departamento'], PDO::PARAM_STR);
      $stmt->bindValue(':codigo_postal', $datos['codigo_postal'], PDO::PARAM_STR);
      $stmt->bindValue(':referencia', $datos['referencia'], PDO::PARAM_STR);
      $stmt->bindValue(':usuario_creacion', $this->usuario_sistema_id, PDO::PARAM_INT);
      $stmt->execute();
      return (int) ($stmt->fetch()['cliente_tienda_direccion_id'] ?? 0);
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function guardar_pedido_checkout_tienda($dbh, $cliente_tienda_id, $direccion_id, $datos = []) {
    $stmt = null;
    $carrito = $datos['carrito'];
    $subtotal = (float) ($carrito['subtotal'] ?? 0);
    $ahorro = (float) ($carrito['ahorro'] ?? 0);
    $envio = (float) ($carrito['envio'] ?? 0);
    $total = (float) ($carrito['total'] ?? 0);
    $estado_pago = $datos['metodo_pago'] === 'contra_entrega' ? 'pendiente' : 'pagado';
    $estado_pedido = $estado_pago === 'pagado' ? 'alistando' : 'pendiente';
    $direccion_resumen = $datos['direccion_linea_1']
      . ($datos['direccion_linea_2'] !== '' ? ' ' . $datos['direccion_linea_2'] : '')
      . ', ' . $datos['ciudad']
      . ', ' . $datos['departamento'];

    try {
      $codigo = $this->consultar_codigo_pedido_checkout_tienda($dbh);
      $sql = "INSERT INTO public.pedidos_tienda
"
           . "(
"
           . "  cliente_tienda_id,
"
           . "  codigo,
"
           . "  estado_pedido,
"
           . "  estado_pago,
"
           . "  metodo_pago,
"
           . "  cantidad_items,
"
           . "  subtotal,
"
           . "  descuento_total,
"
           . "  envio_total,
"
           . "  total,
"
           . "  direccion_resumen,
"
           . "  observacion,
"
           . "  fecha_pedido,
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
           . "  :cliente_tienda_id,
"
           . "  :codigo,
"
           . "  :estado_pedido,
"
           . "  :estado_pago,
"
           . "  :metodo_pago,
"
           . "  :cantidad_items,
"
           . "  :subtotal,
"
           . "  :descuento_total,
"
           . "  :envio_total,
"
           . "  :total,
"
           . "  :direccion_resumen,
"
           . "  :observacion,
"
           . "  NOW(),
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW()
"
           . ") RETURNING pedido_tienda_id;";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':cliente_tienda_id', $cliente_tienda_id, PDO::PARAM_INT);
      $stmt->bindValue(':codigo', $codigo, PDO::PARAM_STR);
      $stmt->bindValue(':estado_pedido', $estado_pedido, PDO::PARAM_STR);
      $stmt->bindValue(':estado_pago', $estado_pago, PDO::PARAM_STR);
      $stmt->bindValue(':metodo_pago', $datos['metodo_pago'], PDO::PARAM_STR);
      $stmt->bindValue(':cantidad_items', (int) ($carrito['cantidad'] ?? 0), PDO::PARAM_INT);
      $stmt->bindValue(':subtotal', $subtotal);
      $stmt->bindValue(':descuento_total', $ahorro);
      $stmt->bindValue(':envio_total', $envio);
      $stmt->bindValue(':total', $total);
      $stmt->bindValue(':direccion_resumen', $direccion_resumen, PDO::PARAM_STR);
      $stmt->bindValue(':observacion', $datos['observacion'], PDO::PARAM_STR);
      $stmt->bindValue(':usuario_creacion', $this->usuario_sistema_id, PDO::PARAM_INT);
      $stmt->execute();
      $pedido_tienda_id = (int) ($stmt->fetch()['pedido_tienda_id'] ?? 0);

      return [
        'pedido_tienda_id' => $pedido_tienda_id,
        'codigo'           => $codigo,
        'total'            => $total,
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function guardar_detalle_pedido_checkout_tienda($dbh, $pedido_tienda_id, $carrito = []) {
    $items = $carrito['items'] ?? [];
    $stmt = null;

    try {
      foreach ($items as $item) {
        $precio = (float) ($item['precio'] ?? 0);
        $precio_anterior = (float) ($item['precio_anterior'] ?? 0);
        $descuento_unitario = $precio_anterior > $precio ? $precio_anterior - $precio : 0;
        $cantidad = (int) ($item['cantidad'] ?? 1);
        $total_linea = (float) ($item['total'] ?? ($precio * $cantidad));

        $sql = "INSERT INTO public.pedido_tienda_detalles
"
             . "(
"
             . "  pedido_tienda_id,
"
             . "  producto_id,
"
             . "  producto_codigo,
"
             . "  producto_nombre,
"
             . "  producto_slug,
"
             . "  imagen_url,
"
             . "  cantidad,
"
             . "  precio_unitario,
"
             . "  descuento_unitario,
"
             . "  total_linea,
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
             . "  :pedido_tienda_id,
"
             . "  :producto_id,
"
             . "  :producto_codigo,
"
             . "  :producto_nombre,
"
             . "  :producto_slug,
"
             . "  :imagen_url,
"
             . "  :cantidad,
"
             . "  :precio_unitario,
"
             . "  :descuento_unitario,
"
             . "  :total_linea,
"
             . "  B'1',
"
             . "  B'0',
"
             . "  :usuario_creacion,
"
             . "  NOW()
"
             . ")";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':pedido_tienda_id', $pedido_tienda_id, PDO::PARAM_INT);
        $stmt->bindValue(':producto_id', (int) ($item['producto_id'] ?? 0), PDO::PARAM_INT);
        $stmt->bindValue(':producto_codigo', (string) ($item['codigo'] ?? strtoupper(str_replace('-', '_', (string) ($item['slug'] ?? 'PRD')))), PDO::PARAM_STR);
        $stmt->bindValue(':producto_nombre', (string) ($item['nombre'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':producto_slug', (string) ($item['slug'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':imagen_url', (string) ($item['imagen_url'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindValue(':precio_unitario', $precio);
        $stmt->bindValue(':descuento_unitario', $descuento_unitario);
        $stmt->bindValue(':total_linea', $total_linea);
        $stmt->bindValue(':usuario_creacion', $this->usuario_sistema_id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = null;
      }
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function guardar_pago_checkout_tienda($dbh, $pedido_tienda_id, $cliente_tienda_id, $datos = []) {
    $stmt = null;
    $estado_pago = $datos['metodo_pago'] === 'contra_entrega' ? 'pendiente' : 'pagado';
    $franquicia_tarjeta = $datos['metodo_pago'] === 'tarjeta' ? ($datos['franquicia_tarjeta'] !== '' ? $datos['franquicia_tarjeta'] : 'visa_mastercard') : null;
    $ultimos_cuatro = $datos['numero_tarjeta'] !== '' ? substr($datos['numero_tarjeta'], -4) : null;
    $referencia_pasarela = $this->consultar_referencia_pasarela_checkout_tienda($datos['metodo_pago']);
    $respuesta_pasarela = $this->consultar_respuesta_pasarela_checkout_tienda($datos, $estado_pago, $referencia_pasarela);

    try {
      $codigo = $this->consultar_codigo_pago_checkout_tienda($dbh);
      $sql = "INSERT INTO public.pagos_tienda
"
           . "(
"
           . "  pedido_tienda_id,
"
           . "  cliente_tienda_id,
"
           . "  codigo,
"
           . "  metodo_pago,
"
           . "  estado_pago,
"
           . "  monto,
"
           . "  titular_pagador,
"
           . "  documento_pagador,
"
           . "  correo_pagador,
"
           . "  entidad_pse,
"
           . "  franquicia_tarjeta,
"
           . "  ultimos_cuatro,
"
           . "  referencia_pasarela,
"
           . "  respuesta_pasarela,
"
           . "  fecha_procesamiento,
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
           . "  :pedido_tienda_id,
"
           . "  :cliente_tienda_id,
"
           . "  :codigo,
"
           . "  :metodo_pago,
"
           . "  :estado_pago,
"
           . "  :monto,
"
           . "  :titular_pagador,
"
           . "  :documento_pagador,
"
           . "  :correo_pagador,
"
           . "  :entidad_pse,
"
           . "  :franquicia_tarjeta,
"
           . "  :ultimos_cuatro,
"
           . "  :referencia_pasarela,
"
           . "  :respuesta_pasarela,
"
           . "  NOW(),
"
           . "  B'1',
"
           . "  B'0',
"
           . "  :usuario_creacion,
"
           . "  NOW()
"
           . ") RETURNING pago_tienda_id;";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':pedido_tienda_id', $pedido_tienda_id, PDO::PARAM_INT);
      $stmt->bindValue(':cliente_tienda_id', $cliente_tienda_id, PDO::PARAM_INT);
      $stmt->bindValue(':codigo', $codigo, PDO::PARAM_STR);
      $stmt->bindValue(':metodo_pago', $datos['metodo_pago'], PDO::PARAM_STR);
      $stmt->bindValue(':estado_pago', $estado_pago, PDO::PARAM_STR);
      $stmt->bindValue(':monto', (float) ($datos['carrito']['total'] ?? 0));
      $stmt->bindValue(':titular_pagador', $datos['titular_pagador'] !== '' ? $datos['titular_pagador'] : trim($datos['nombres'] . ' ' . $datos['apellidos']), PDO::PARAM_STR);
      $stmt->bindValue(':documento_pagador', $datos['documento_pagador'], PDO::PARAM_STR);
      $stmt->bindValue(':correo_pagador', $datos['correo_pagador'] !== '' ? $datos['correo_pagador'] : $datos['correo'], PDO::PARAM_STR);
      $stmt->bindValue(':entidad_pse', $datos['entidad_pse'], PDO::PARAM_STR);
      $stmt->bindValue(':franquicia_tarjeta', $franquicia_tarjeta, PDO::PARAM_STR);
      $stmt->bindValue(':ultimos_cuatro', $ultimos_cuatro, PDO::PARAM_STR);
      $stmt->bindValue(':referencia_pasarela', $referencia_pasarela, PDO::PARAM_STR);
      $stmt->bindValue(':respuesta_pasarela', $respuesta_pasarela, PDO::PARAM_STR);
      $stmt->bindValue(':usuario_creacion', $this->usuario_sistema_id, PDO::PARAM_INT);
      $stmt->execute();

      return [
        'pago_tienda_id' => (int) ($stmt->fetch()['pago_tienda_id'] ?? 0),
        'codigo'         => $codigo,
        'estado_pago'          => $estado_pago,
        'referencia_pasarela'   => $referencia_pasarela,
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function consultar_referencia_pasarela_checkout_tienda($metodo_pago) {
    $prefijo = 'CHK';

    if ($metodo_pago === 'pse') {
      $prefijo = 'PSE';
    }
    else if ($metodo_pago === 'tarjeta') {
      $prefijo = 'TAR';
    }
    else if ($metodo_pago === 'contra_entrega') {
      $prefijo = 'CON';
    }

    return $prefijo . '-' . date('YmdHis') . '-' . strtoupper(substr(md5((string) microtime(true)), 0, 6));
  }

  private function consultar_respuesta_pasarela_checkout_tienda($datos = [], $estado_pago = 'pendiente', $referencia_pasarela = '') {
    $respuesta = [
      'metodo_pago'          => $datos['metodo_pago'] ?? '',
      'estado_pago'          => $estado_pago,
      'referencia_pasarela'  => $referencia_pasarela,
      'entidad_pse'          => $datos['entidad_pse'] ?? '',
      'tipo_persona_pse'     => $datos['tipo_persona_pse'] ?? '',
      'tipo_cuenta_pse'      => $datos['tipo_cuenta_pse'] ?? '',
      'franquicia_tarjeta'   => $datos['franquicia_tarjeta'] ?? '',
      'cuotas'               => (int) ($datos['cuotas'] ?? 1),
      'ultimos_cuatro'       => $datos['numero_tarjeta'] !== '' ? substr((string) $datos['numero_tarjeta'], -4) : '',
      'fecha'                => date('Y-m-d H:i:s'),
      'origen'               => 'checkout_tienda',
    ];

    return json_encode($respuesta, JSON_UNESCAPED_UNICODE);
  }

  private function consultar_resumen_checkout_tienda() {
    return $_SESSION['tv_checkout_ultimo_pedido'] ?? [];
  }
}
?>
