<?php
require_once __DIR__ . '/tienda_catalogo_base_model.class.php';
require_once __DIR__ . '/../Services/pasarela_wompi_service.class.php';

class tienda_checkout_model extends tienda_catalogo_base_model {
  private $modulo = __FILE__;
  private $usuario_sistema_id = 1;
  private $pasarela_wompi = null;

  public function __construct() {
    parent::__construct();
    $this->pasarela_wompi = new pasarela_wompi_service();
  }

  private function consultar_booleano_configuracion_checkout_tienda($codigo = '', $valor_defecto = true) {
    $valor = strtolower(trim((string) $this->consultar_valor_modulo_tienda_publica($codigo, $valor_defecto === true ? '1' : '0')));

    return in_array($valor, ['1', 'true', 'si', 'sí', 'yes', 'on'], true);
  }

  private function consultar_definicion_campo_checkout_tienda($prefijo = '', $definicion = []) {
    return [
      'visible'   => $this->consultar_booleano_configuracion_checkout_tienda($prefijo . '.visible', $definicion['visible'] ?? true),
      'requerido' => $this->consultar_booleano_configuracion_checkout_tienda($prefijo . '.required', $definicion['requerido'] ?? true),
    ];
  }

  public function consultar_checkout_modulo_tienda() {
    return [
      'contexto'       => $this->consultar_contexto_tienda(),
      'carrito'        => $this->consultar_carrito_tienda(),
      'pedido'         => $this->consultar_resumen_checkout_tienda(),
      'checkout_datos' => $_SESSION['tv_checkout_datos'] ?? [],
    ];
  }

  public function consultar_checkout_pago_modulo_tienda() {
    $sincronizacion = $this->sincronizar_retorno_pasarela_checkout_tienda();
    $bancos = $this->consultar_bancos_pse_checkout_tienda();
    $aceptacion = $this->consultar_aceptacion_pasarela_checkout_tienda();

    return [
      'contexto'           => $this->consultar_contexto_tienda(),
      'carrito'            => $this->consultar_carrito_tienda(),
      'pedido'             => $this->consultar_resumen_checkout_tienda(),
      'checkout_datos'     => $_SESSION['tv_checkout_datos'] ?? [],
      'bancos_pse'         => $bancos,
      'pasarela_aceptacion'=> $aceptacion,
      'pasarela_activa'    => $this->pasarela_wompi->obtener_configuracion_publica(),
      'sincronizacion'     => $sincronizacion,
    ];
  }

  public function guardar_checkout_datos_tienda() {
    $datos = $this->asignar_variables_guardar_checkout_datos_tienda();
    $validacion = $this->validar_datos_guardar_checkout_tienda($datos);

    if ($validacion['estado'] !== true) {
      return $validacion;
    }

    $_SESSION['tv_checkout_datos'] = $datos;

    return [
      'estado'  => true,
      'mensaje' => 'Datos del pedido guardados correctamente.',
      'datos'   => [
        'redirect' => '/checkout/pago/',
      ],
    ];
  }

  public function guardar_checkout_tienda() {
    return $this->guardar_checkout_pago_tienda();
  }

  public function guardar_checkout_pago_tienda() {
    $checkout_datos = $_SESSION['tv_checkout_datos'] ?? [];

    if (count($checkout_datos) === 0) {
      return [
        'estado'  => false,
        'mensaje' => 'Primero debes completar los datos del pedido antes de pasar al pago.',
        'datos'   => [
          'redirect' => '/checkout/',
        ],
      ];
    }

    $datos = $this->asignar_variables_guardar_checkout_pago_tienda();
    $datos = array_merge($checkout_datos, $datos);
    $datos['carrito'] = $this->consultar_carrito_tienda();

    $validacion = $this->validar_datos_guardar_checkout_pago_tienda($datos);

    if ($validacion['estado'] !== true) {
      return $validacion;
    }

    $dbh = null;

    try {
      $dbh = configdb_obtener_conexion();
      $dbh->beginTransaction();

      $cliente_tienda_id = $this->guardar_cliente_checkout_tienda($dbh, $datos);
      $direccion_id = $this->guardar_direccion_checkout_tienda($dbh, $cliente_tienda_id, $datos);
      $pedido = $this->guardar_pedido_checkout_tienda($dbh, $cliente_tienda_id, $direccion_id, $datos);
      $this->guardar_detalle_pedido_checkout_tienda($dbh, $pedido['pedido_tienda_id'], $datos['carrito']);
      $pago = $this->guardar_pago_checkout_tienda($dbh, $pedido['pedido_tienda_id'], $cliente_tienda_id, $datos);

      $resultado_pasarela = $this->procesar_pasarela_checkout_tienda($dbh, $pedido, $pago, $datos);

      if (($resultado_pasarela['estado'] ?? false) !== true) {
        throw new Exception((string) ($resultado_pasarela['mensaje'] ?? 'No fue posible procesar la pasarela.'));
      }

      $estado_pago = (string) ($resultado_pasarela['datos']['estado_pago'] ?? 'pendiente');
      $estado_pedido = $estado_pago === 'pagado' ? 'alistando' : 'pendiente';

      $this->actualizar_pago_checkout_tienda($dbh, $pago['pago_tienda_id'], $resultado_pasarela['datos']);
      $this->actualizar_pedido_checkout_tienda($dbh, $pedido['pedido_tienda_id'], $estado_pago, $estado_pedido, $datos['metodo_pago']);

      $dbh->commit();

      $redirect = (string) ($resultado_pasarela['datos']['redirect'] ?? '');

      $_SESSION['tv_checkout_ultimo_items'] = $datos['carrito']['items'] ?? [];
      $_SESSION['tv_checkout_ultimo_pedido'] = [
        'codigo'       => $pedido['codigo'],
        'metodo_pago'  => $datos['metodo_pago'],
        'estado_pago'  => $estado_pago,
        'total'        => $pedido['total'],
        'cliente'      => trim($datos['nombres'] . ' ' . $datos['apellidos']),
      ];

      if ($redirect === '') {
        unset($_SESSION['tv_carrito'], $_SESSION['tv_checkout_datos']);
      }

      return [
        'estado'  => true,
        'mensaje' => (string) ($resultado_pasarela['mensaje'] ?? 'Pago registrado correctamente.'),
        'datos'   => [
          'pedido_codigo' => $pedido['codigo'],
          'redirect'      => $redirect !== '' ? $redirect : '/checkout/pago/?pedido=' . urlencode($pedido['codigo']) . '&estado=ok',
        ],
      ];
    }
    catch (Throwable $e) {
      if ($dbh && $dbh->inTransaction()) {
        $dbh->rollBack();
      }

      configdb_registrar_log($this->modulo, __FUNCTION__, $e->getMessage(), 'checkout_tienda_pago');

      return [
        'estado'  => false,
        'mensaje' => $e->getMessage() !== '' ? $e->getMessage() : 'No fue posible registrar el pago de la compra.',
        'datos'   => [],
      ];
    }
  }

  private function asignar_variables_guardar_checkout_datos_tienda() {
    return [
      'nombres'            => trim((string) ($_POST['nombres'] ?? '')),
      'apellidos'          => trim((string) ($_POST['apellidos'] ?? '')),
      'correo'             => trim((string) ($_POST['correo'] ?? '')),
      'celular'            => trim((string) ($_POST['celular'] ?? '')),
      'destinatario'       => trim((string) ($_POST['destinatario'] ?? '')),
      'telefono_direccion' => trim((string) ($_POST['telefono_direccion'] ?? '')),
      'direccion_linea_1'  => trim((string) ($_POST['direccion_linea_1'] ?? '')),
      'direccion_linea_2'  => trim((string) ($_POST['direccion_linea_2'] ?? '')),
      'ciudad'             => trim((string) ($_POST['ciudad'] ?? '')),
      'departamento'       => trim((string) ($_POST['departamento'] ?? '')),
      'codigo_postal'      => trim((string) ($_POST['codigo_postal'] ?? '')),
      'referencia'         => trim((string) ($_POST['referencia'] ?? '')),
      'observacion'        => trim((string) ($_POST['observacion'] ?? '')),
      'carrito'            => $this->consultar_carrito_tienda(),
    ];
  }

  private function validar_datos_guardar_checkout_tienda($datos = []) {
    if (count($datos['carrito']['items'] ?? []) === 0) {
      return [
        'estado'  => false,
        'mensaje' => 'No hay productos en el carrito para continuar con el checkout.',
        'datos'   => [],
      ];
    }

    $campos = [
      'nombres' => [
        'configuracion' => 'tienda_publica.checkout_nombres',
        'mensaje'       => 'Debes ingresar los nombres.',
        'visible'       => true,
        'requerido'     => true,
      ],
      'apellidos' => [
        'configuracion' => 'tienda_publica.checkout_apellidos',
        'mensaje'       => 'Debes ingresar los apellidos.',
        'visible'       => true,
        'requerido'     => true,
      ],
      'correo' => [
        'configuracion' => 'tienda_publica.checkout_correo',
        'mensaje'       => 'Debes ingresar el correo.',
        'visible'       => true,
        'requerido'     => true,
      ],
      'celular' => [
        'configuracion' => 'tienda_publica.checkout_celular',
        'mensaje'       => 'Debes ingresar el celular.',
        'visible'       => true,
        'requerido'     => true,
      ],
      'destinatario' => [
        'configuracion' => 'tienda_publica.checkout_destinatario',
        'mensaje'       => 'Debes ingresar el destinatario.',
        'visible'       => true,
        'requerido'     => true,
      ],
      'telefono_direccion' => [
        'configuracion' => 'tienda_publica.checkout_telefono_direccion',
        'mensaje'       => 'Debes ingresar el teléfono de la dirección.',
        'visible'       => true,
        'requerido'     => true,
      ],
      'direccion_linea_1' => [
        'configuracion' => 'tienda_publica.checkout_direccion_linea_1',
        'mensaje'       => 'Debes ingresar la dirección principal.',
        'visible'       => true,
        'requerido'     => true,
      ],
      'direccion_linea_2' => [
        'configuracion' => 'tienda_publica.checkout_direccion_linea_2',
        'mensaje'       => 'Debes ingresar el complemento de la dirección.',
        'visible'       => true,
        'requerido'     => false,
      ],
      'ciudad' => [
        'configuracion' => 'tienda_publica.checkout_ciudad',
        'mensaje'       => 'Debes ingresar la ciudad.',
        'visible'       => true,
        'requerido'     => true,
      ],
      'departamento' => [
        'configuracion' => 'tienda_publica.checkout_departamento',
        'mensaje'       => 'Debes ingresar el departamento.',
        'visible'       => true,
        'requerido'     => true,
      ],
      'codigo_postal' => [
        'configuracion' => 'tienda_publica.checkout_codigo_postal',
        'mensaje'       => 'Debes ingresar el código postal.',
        'visible'       => true,
        'requerido'     => false,
      ],
      'referencia' => [
        'configuracion' => 'tienda_publica.checkout_referencia',
        'mensaje'       => 'Debes ingresar una referencia de entrega.',
        'visible'       => true,
        'requerido'     => false,
      ],
      'observacion' => [
        'configuracion' => 'tienda_publica.checkout_observacion',
        'mensaje'       => 'Debes ingresar la observación del pedido.',
        'visible'       => true,
        'requerido'     => false,
      ],
    ];

    foreach ($campos as $campo => $info) {
      $definicion_campo = $this->consultar_definicion_campo_checkout_tienda($info['configuracion'], [
        'visible'   => $info['visible'],
        'requerido' => $info['requerido'],
      ]);

      if ($definicion_campo['visible'] === true && $definicion_campo['requerido'] === true && trim((string) ($datos[$campo] ?? '')) === '') {
        return [
          'estado'  => false,
          'mensaje' => $info['mensaje'],
          'datos'   => [],
        ];
      }
    }

    $correo = trim((string) ($datos['correo'] ?? ''));
    if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
      return [
        'estado'  => false,
        'mensaje' => 'El correo del comprador no es válido.',
        'datos'   => [],
      ];
    }

    $celular = preg_replace('/\D+/', '', (string) ($datos['celular'] ?? ''));
    if ($celular !== '' && !preg_match('/^[0-9]{10}$/', $celular)) {
      return [
        'estado'  => false,
        'mensaje' => 'El celular del comprador debe tener 10 dígitos.',
        'datos'   => [],
      ];
    }

    $telefono_direccion = preg_replace('/\D+/', '', (string) ($datos['telefono_direccion'] ?? ''));
    if ($telefono_direccion !== '' && !preg_match('/^[0-9]{7,10}$/', $telefono_direccion)) {
      return [
        'estado'  => false,
        'mensaje' => 'El teléfono de la dirección no es válido.',
        'datos'   => [],
      ];
    }

    $codigo_postal = preg_replace('/\D+/', '', (string) ($datos['codigo_postal'] ?? ''));
    if ($codigo_postal !== '' && !preg_match('/^[0-9]{4,10}$/', $codigo_postal)) {
      return [
        'estado'  => false,
        'mensaje' => 'El código postal no es válido.',
        'datos'   => [],
      ];
    }

    return [
      'estado'  => true,
      'mensaje' => 'Validación correcta.',
      'datos'   => [],
    ];
  }

  private function asignar_variables_guardar_checkout_pago_tienda() {
    return [
      'metodo_pago'              => trim((string) ($_POST['metodo_pago'] ?? '')),
      'documento_pagador'        => trim((string) ($_POST['documento_pagador'] ?? '')),
      'nombres_pagador'          => trim((string) ($_POST['nombres_pagador'] ?? '')),
      'titular_pagador'          => trim((string) ($_POST['titular_pagador'] ?? '')),
      'correo_pagador'           => trim((string) ($_POST['correo_pagador'] ?? '')),
      'entidad_pse'              => trim((string) ($_POST['entidad_pse'] ?? '')),
      'user_type_pse'            => (int) ($_POST['user_type_pse'] ?? 0),
      'franquicia_tarjeta'       => trim((string) ($_POST['franquicia_tarjeta'] ?? '')),
      'token_tarjeta_wompi'      => trim((string) ($_POST['token_tarjeta_wompi'] ?? '')),
      'ultimos_cuatro_tarjeta'   => trim((string) ($_POST['ultimos_cuatro_tarjeta'] ?? '')),
      'fecha_expiracion'         => trim((string) ($_POST['fecha_expiracion'] ?? '')),
      'cuotas'                   => (int) ($_POST['cuotas'] ?? 1),
      'contra_entrega_recibe'    => trim((string) ($_POST['contra_entrega_recibe'] ?? '')),
      'contra_entrega_documento' => trim((string) ($_POST['contra_entrega_documento'] ?? '')),
      'contra_entrega_confirma'  => trim((string) ($_POST['contra_entrega_confirma'] ?? '')),
      'acepta_pasarela'          => trim((string) ($_POST['acepta_pasarela'] ?? '')),
    ];
  }

  private function validar_datos_guardar_checkout_pago_tienda($datos = []) {
    if (count($datos['carrito']['items'] ?? []) === 0) {
      return [
        'estado'  => false,
        'mensaje' => 'No hay productos en el carrito para continuar con el pago.',
        'datos'   => [],
      ];
    }

    if (!in_array($datos['metodo_pago'], ['pse', 'tarjeta', 'contra_entrega'], true)) {
      return [
        'estado'  => false,
        'mensaje' => 'Debes seleccionar un método de pago válido.',
        'datos'   => [],
      ];
    }

    if ($datos['metodo_pago'] === 'pse') {
      $campos_pse = [
        'nombres_pagador' => [
          'configuracion' => 'tienda_publica.checkout_pago_nombres_pagador',
          'mensaje'       => 'Debes ingresar el nombre del pagador para PSE.',
          'visible'       => true,
          'requerido'     => true,
        ],
        'correo_pagador' => [
          'configuracion' => 'tienda_publica.checkout_pago_correo_pagador',
          'mensaje'       => 'Debes ingresar un correo válido para PSE.',
          'visible'       => true,
          'requerido'     => true,
        ],
        'documento_pagador' => [
          'configuracion' => 'tienda_publica.checkout_pago_documento_pagador',
          'mensaje'       => 'Debes ingresar el documento del pagador para PSE.',
          'visible'       => true,
          'requerido'     => true,
        ],
        'entidad_pse' => [
          'configuracion' => 'tienda_publica.checkout_pago_entidad_pse',
          'mensaje'       => 'Debes seleccionar el banco para continuar con PSE.',
          'visible'       => true,
          'requerido'     => true,
        ],
      ];

      foreach ($campos_pse as $campo => $info) {
        $definicion_campo = $this->consultar_definicion_campo_checkout_tienda($info['configuracion'], [
          'visible'   => $info['visible'],
          'requerido' => $info['requerido'],
        ]);

        if ($definicion_campo['visible'] === true && $definicion_campo['requerido'] === true && trim((string) ($datos[$campo] ?? '')) === '') {
          return [
            'estado'  => false,
            'mensaje' => $info['mensaje'],
            'datos'   => [],
          ];
        }
      }

      if ($datos['documento_pagador'] !== '' && !preg_match('/^[0-9]{5,20}$/', preg_replace('/\D+/', '', (string) $datos['documento_pagador']))) {
        return [
          'estado'  => false,
          'mensaje' => 'El documento del pagador para PSE no es válido.',
          'datos'   => [],
        ];
      }

      if ($datos['correo_pagador'] !== '' && !filter_var($datos['correo_pagador'], FILTER_VALIDATE_EMAIL)) {
        return [
          'estado'  => false,
          'mensaje' => 'Debes ingresar un correo válido para PSE.',
          'datos'   => [],
        ];
      }

      $datos['titular_pagador'] = $datos['nombres_pagador'];
    }

    if ($datos['acepta_pasarela'] !== '1') {
      return [
        'estado'  => false,
        'mensaje' => 'Debes aceptar los términos del medio de pago para continuar.',
        'datos'   => [],
      ];
    }

    if ($datos['metodo_pago'] === 'tarjeta') {
      $campos_tarjeta = [
        'titular_pagador' => [
          'configuracion' => 'tienda_publica.checkout_pago_titular_pagador',
          'mensaje'       => 'Debes ingresar el titular de la tarjeta.',
          'visible'       => true,
          'requerido'     => true,
        ],
        'fecha_expiracion' => [
          'configuracion' => 'tienda_publica.checkout_pago_fecha_expiracion',
          'mensaje'       => 'Debes ingresar la fecha de expiración de la tarjeta.',
          'visible'       => true,
          'requerido'     => true,
        ],
      ];

      foreach ($campos_tarjeta as $campo => $info) {
        $definicion_campo = $this->consultar_definicion_campo_checkout_tienda($info['configuracion'], [
          'visible'   => $info['visible'],
          'requerido' => $info['requerido'],
        ]);

        if ($definicion_campo['visible'] === true && $definicion_campo['requerido'] === true && trim((string) ($datos[$campo] ?? '')) === '') {
          return [
            'estado'  => false,
            'mensaje' => $info['mensaje'],
            'datos'   => [],
          ];
        }
      }

      if ($datos['token_tarjeta_wompi'] === '') {
        return [
          'estado'  => false,
          'mensaje' => 'Completa la validación de la tarjeta para continuar.',
          'datos'   => [],
        ];
      }

      if (strlen(trim((string) $datos['titular_pagador'])) < 5) {
        return [
          'estado'  => false,
          'mensaje' => 'El titular de la tarjeta debe tener al menos 5 caracteres.',
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

      if ($datos['franquicia_tarjeta'] === '') {
        return [
          'estado'  => false,
          'mensaje' => 'No fue posible detectar la franquicia de la tarjeta.',
          'datos'   => [],
        ];
      }
    }

    if ($datos['metodo_pago'] === 'contra_entrega') {
      $campos_contra_entrega = [
        'contra_entrega_recibe' => [
          'configuracion' => 'tienda_publica.checkout_pago_contra_entrega_recibe',
          'mensaje'       => 'Confirma quién recibe el pedido para contra entrega.',
          'visible'       => true,
          'requerido'     => true,
        ],
        'contra_entrega_documento' => [
          'configuracion' => 'tienda_publica.checkout_pago_contra_entrega_documento',
          'mensaje'       => 'Debes ingresar el documento de quien recibe el pedido.',
          'visible'       => true,
          'requerido'     => true,
        ],
      ];

      foreach ($campos_contra_entrega as $campo => $info) {
        $definicion_campo = $this->consultar_definicion_campo_checkout_tienda($info['configuracion'], [
          'visible'   => $info['visible'],
          'requerido' => $info['requerido'],
        ]);

        if ($definicion_campo['visible'] === true && $definicion_campo['requerido'] === true && trim((string) ($datos[$campo] ?? '')) === '') {
          return [
            'estado'  => false,
            'mensaje' => $info['mensaje'],
            'datos'   => [],
          ];
        }
      }

      if (!preg_match('/^[0-9]{5,20}$/', preg_replace('/\D+/', '', (string) $datos['contra_entrega_documento']))) {
        return [
          'estado'  => false,
          'mensaje' => 'El documento de quien recibe no es válido.',
          'datos'   => [],
        ];
      }

      if ($datos['contra_entrega_confirma'] !== '1') {
        return [
          'estado'  => false,
          'mensaje' => 'Debes confirmar la información de entrega para continuar.',
          'datos'   => [],
        ];
      }

      $datos['documento_pagador'] = $datos['contra_entrega_documento'];
      $datos['titular_pagador']   = $datos['contra_entrega_recibe'];
      $datos['correo_pagador']    = $datos['correo'];
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
    $estado_pago = 'pendiente';
    $estado_pedido = 'pendiente';
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
    $estado_pago = 'pendiente';
    $franquicia_tarjeta = $datos['metodo_pago'] === 'tarjeta' ? ($datos['franquicia_tarjeta'] !== '' ? $datos['franquicia_tarjeta'] : null) : null;
    $ultimos_cuatro = $datos['metodo_pago'] === 'tarjeta' ? ($datos['ultimos_cuatro_tarjeta'] !== '' ? $datos['ultimos_cuatro_tarjeta'] : null) : null;
    $referencia_pasarela = '';
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
      'tipo_persona_pse'     => $datos['user_type_pse'] ?? '',
      'franquicia_tarjeta'   => $datos['franquicia_tarjeta'] ?? '',
      'cuotas'               => (int) ($datos['cuotas'] ?? 1),
      'ultimos_cuatro'       => $datos['ultimos_cuatro_tarjeta'] ?? '',
      'fecha'                => date('Y-m-d H:i:s'),
      'origen'               => 'checkout_tienda',
    ];

    return json_encode($respuesta, JSON_UNESCAPED_UNICODE);
  }


  private function consultar_bancos_pse_checkout_tienda() {
    $respuesta = $this->pasarela_wompi->consultar_bancos_pse();

    if (($respuesta['estado'] ?? false) !== true) {
      return [
        ['financial_institution_code' => '1', 'financial_institution_name' => 'Bancolombia'],
        ['financial_institution_code' => '7', 'financial_institution_name' => 'Banco de Bogotá'],
        ['financial_institution_code' => '13', 'financial_institution_name' => 'BBVA Colombia'],
        ['financial_institution_code' => '51', 'financial_institution_name' => 'Davivienda'],
      ];
    }

    return $respuesta['datos'] ?? [];
  }

  private function consultar_aceptacion_pasarela_checkout_tienda() {
    $respuesta = $this->pasarela_wompi->consultar_token_aceptacion();

    return [
      'habilitado'        => ($respuesta['estado'] ?? false) === true,
      'acceptance_token'  => (string) ($respuesta['datos']['acceptance_token'] ?? ''),
      'permalink'         => (string) ($respuesta['datos']['permalink'] ?? ''),
      'mensaje'           => (string) ($respuesta['mensaje'] ?? ''),
    ];
  }

  private function procesar_pasarela_checkout_tienda($dbh, $pedido = [], $pago = [], $datos = []) {
    if ($datos['metodo_pago'] === 'contra_entrega') {
      return [
        'estado'  => true,
        'mensaje' => 'Pedido registrado para pago contra entrega.',
        'datos'   => [
          'estado_pago'         => 'pendiente',
          'referencia_pasarela' => $pago['codigo'],
          'respuesta_pasarela'  => json_encode(['origen' => 'contra_entrega'], JSON_UNESCAPED_UNICODE),
          'ultimos_cuatro'      => null,
          'redirect'            => '/checkout/pago/?pedido=' . urlencode((string) $pedido['codigo']) . '&estado=ok',
        ],
      ];
    }

    if ($this->pasarela_wompi->tiene_configuracion_valida() !== true) {
      return [
        'estado'  => false,
        'mensaje' => $this->pasarela_wompi->obtener_mensaje_configuracion(),
        'datos'   => [],
      ];
    }

    if ($datos['metodo_pago'] === 'pse') {
      return $this->procesar_pago_pse_checkout_tienda($pedido, $pago, $datos);
    }

    return $this->procesar_pago_tarjeta_checkout_tienda($pedido, $pago, $datos);
  }

  private function procesar_pago_pse_checkout_tienda($pedido = [], $pago = [], $datos = []) {
    $config = $this->pasarela_wompi->obtener_configuracion_publica();
    $aceptacion = $this->consultar_aceptacion_pasarela_checkout_tienda();
    $redirectBase = rtrim((string) ($config['redirect_base_url'] ?? ''), '/');
    $amountInCents = (int) round(((float) ($datos['carrito']['total'] ?? 0)) * 100);
    $reference = (string) ($pago['codigo'] ?? $pedido['codigo']);
    $payload = [
      'acceptance_token' => (string) ($aceptacion['acceptance_token'] ?? ''),
      'amount_in_cents'  => $amountInCents,
      'currency'         => 'COP',
      'customer_email'   => (string) $datos['correo_pagador'],
      'reference'        => $reference,
      'signature'        => $this->pasarela_wompi->generar_firma_integridad($reference, $amountInCents, 'COP'),
      'redirect_url'     => $redirectBase . '/checkout/pago/?pago=' . urlencode((string) $pago['codigo']) . '&pasarela=wompi',
      'customer_data'    => [
        'phone_number' => '57' . preg_replace('/\D+/', '', (string) ($datos['celular'] ?? '')),
        'full_name'    => (string) $datos['nombres_pagador'],
      ],
      'payment_method'   => [
        'type'                       => 'PSE',
        'user_type'                  => (int) ($datos['user_type_pse'] ?? 0),
        'user_legal_id_type'         => 'CC',
        'user_legal_id'              => (string) $datos['documento_pagador'],
        'financial_institution_code' => (string) $datos['entidad_pse'],
        'payment_description'        => substr('Pago pedido ' . (string) ($pedido['codigo'] ?? ''), 0, 64),
        'reference_one'              => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
        'reference_two'              => date('Ymd'),
        'reference_three'            => (string) $datos['documento_pagador'],
      ],
    ];

    $creacion = $this->pasarela_wompi->crear_transaccion_pse($payload);

    if (($creacion['estado'] ?? false) !== true) {
      return $creacion;
    }

    $transaccion = $creacion['datos']['data'] ?? [];
    $estadoPago = $this->pasarela_wompi->mapear_estado_transaccion_local((string) ($transaccion['status'] ?? 'PENDING'));
    $respuesta = [
      'origen'      => 'wompi_pse',
      'transaccion' => $transaccion,
    ];
    $redirect = '';
    $async = $this->pasarela_wompi->consultar_async_payment_url_pse((string) ($transaccion['id'] ?? ''));

    if (($async['estado'] ?? false) === true) {
      $redirect = (string) ($async['datos']['async_payment_url'] ?? '');
      $transaccion = $async['datos']['transaccion'] ?? $transaccion;
      $respuesta['transaccion'] = $transaccion;
    }

    return [
      'estado'  => true,
      'mensaje' => 'Redirigiendo a PSE para continuar el pago.',
      'datos'   => [
        'estado_pago'         => $estadoPago,
        'referencia_pasarela' => (string) ($transaccion['id'] ?? ''),
        'respuesta_pasarela'  => json_encode($respuesta, JSON_UNESCAPED_UNICODE),
        'ultimos_cuatro'      => null,
        'redirect'            => $redirect,
      ],
    ];
  }

  private function procesar_pago_tarjeta_checkout_tienda($pedido = [], $pago = [], $datos = []) {
    $config = $this->pasarela_wompi->obtener_configuracion_publica();
    $aceptacion = $this->consultar_aceptacion_pasarela_checkout_tienda();
    $redirectBase = rtrim((string) ($config['redirect_base_url'] ?? ''), '/');
    $amountInCents = (int) round(((float) ($datos['carrito']['total'] ?? 0)) * 100);
    $reference = (string) ($pago['codigo'] ?? $pedido['codigo']);
    $payload = [
      'acceptance_token' => (string) ($aceptacion['acceptance_token'] ?? ''),
      'amount_in_cents'  => $amountInCents,
      'currency'         => 'COP',
      'customer_email'   => (string) $datos['correo'],
      'reference'        => $reference,
      'signature'        => $this->pasarela_wompi->generar_firma_integridad($reference, $amountInCents, 'COP'),
      'redirect_url'     => $redirectBase . '/checkout/pago/?pago=' . urlencode((string) $pago['codigo']) . '&pasarela=wompi',
      'payment_method'   => [
        'type'         => 'CARD',
        'token'        => (string) $datos['token_tarjeta_wompi'],
        'installments' => max(1, (int) ($datos['cuotas'] ?? 1)),
      ],
    ];

    $creacion = $this->pasarela_wompi->crear_transaccion_tarjeta($payload);

    if (($creacion['estado'] ?? false) !== true) {
      return $creacion;
    }

    $transaccion = $creacion['datos']['data'] ?? [];
    $estadoPago = $this->pasarela_wompi->mapear_estado_transaccion_local((string) ($transaccion['status'] ?? 'PENDING'));
    $redirect = (string) (($transaccion['payment_method']['extra']['async_payment_url'] ?? '') ?: '');

    return [
      'estado'  => true,
      'mensaje' => $estadoPago === 'pagado' ? 'Pago aprobado correctamente.' : 'Pago enviado a validación.',
      'datos'   => [
        'estado_pago'         => $estadoPago,
        'referencia_pasarela' => (string) ($transaccion['id'] ?? ''),
        'respuesta_pasarela'  => json_encode(['origen' => 'wompi_card', 'transaccion' => $transaccion], JSON_UNESCAPED_UNICODE),
        'ultimos_cuatro'      => (string) ($datos['ultimos_cuatro_tarjeta'] ?? ''),
        'redirect'            => $redirect,
      ],
    ];
  }

  private function sincronizar_retorno_pasarela_checkout_tienda() {
    $pagoCodigo = trim((string) ($_GET['pago'] ?? ''));
    $pasarela = trim((string) ($_GET['pasarela'] ?? ''));

    if ($pagoCodigo === '' || $pasarela !== 'wompi' || $this->pasarela_wompi->tiene_configuracion_valida() !== true) {
      return [];
    }

    $dbh = null;
    $stmt = null;

    try {
      $dbh = configdb_obtener_conexion();
      $sql = "SELECT
"
           . "  pag.pago_tienda_id,
"
           . "  pag.codigo,
"
           . "  pag.referencia_pasarela,
"
           . "  ped.pedido_tienda_id,
"
           . "  ped.codigo AS pedido_codigo
"
           . "FROM
"
           . "  public.pagos_tienda pag
"
           . "INNER JOIN public.pedidos_tienda ped ON ped.pedido_tienda_id = pag.pedido_tienda_id
"
           . "WHERE
"
           . "  pag.codigo = :codigo
"
           . "LIMIT 1;";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':codigo', $pagoCodigo, PDO::PARAM_STR);
      $stmt->execute();
      $fila = $stmt->fetch() ?: [];
      $stmt = null;

      if (count($fila) === 0 || trim((string) ($fila['referencia_pasarela'] ?? '')) === '') {
        return [];
      }

      $consulta = $this->pasarela_wompi->consultar_transaccion((string) $fila['referencia_pasarela']);

      if (($consulta['estado'] ?? false) !== true) {
        return [];
      }

      $transaccion = $consulta['datos']['data'] ?? [];
      $estadoPago = $this->pasarela_wompi->mapear_estado_transaccion_local((string) ($transaccion['status'] ?? 'PENDING'));
      $estadoPedido = $estadoPago === 'pagado' ? 'alistando' : 'pendiente';

      $dbh->beginTransaction();
      $this->actualizar_pago_checkout_tienda($dbh, (int) $fila['pago_tienda_id'], [
        'estado_pago'         => $estadoPago,
        'referencia_pasarela' => (string) ($transaccion['id'] ?? ''),
        'respuesta_pasarela'  => json_encode(['origen' => 'wompi_return', 'transaccion' => $transaccion], JSON_UNESCAPED_UNICODE),
        'ultimos_cuatro'      => null,
      ]);
      $this->actualizar_pedido_checkout_tienda($dbh, (int) $fila['pedido_tienda_id'], $estadoPago, $estadoPedido, null);
      $dbh->commit();

      $_SESSION['tv_checkout_ultimo_pedido'] = [
        'codigo'       => (string) ($fila['pedido_codigo'] ?? ''),
        'metodo_pago'  => 'wompi',
        'estado_pago'  => $estadoPago,
        'total'        => (float) ($fila['total'] ?? 0),
        'cliente'      => trim((string) ($_SESSION['tv_checkout_datos']['nombres'] ?? '') . ' ' . (string) ($_SESSION['tv_checkout_datos']['apellidos'] ?? '')),
      ];

      if ($estadoPago === 'pagado') {
        unset($_SESSION['tv_carrito'], $_SESSION['tv_checkout_datos']);
      }

      return [
        'estado_pago'  => $estadoPago,
        'pedido_codigo'=> (string) ($fila['pedido_codigo'] ?? ''),
      ];
    }
    catch (Throwable $e) {
      if ($dbh && $dbh->inTransaction()) {
        $dbh->rollBack();
      }
      return [];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
      if ($dbh) {
        $dbh = null;
      }
    }
  }

  private function actualizar_pedido_checkout_tienda($dbh, $pedidoTiendaId, $estadoPago, $estadoPedido, $metodoPago = null) {
    $stmt = $dbh->prepare("UPDATE public.pedidos_tienda
SET
  estado_pago = :estado_pago,
  estado_pedido = :estado_pedido,
  metodo_pago = COALESCE(:metodo_pago, metodo_pago),
  usuario_modificacion = :usuario_modificacion,
  fecha_modificacion = NOW()
WHERE
  pedido_tienda_id = :pedido_tienda_id;");
    $stmt->bindValue(':estado_pago', (string) $estadoPago, PDO::PARAM_STR);
    $stmt->bindValue(':estado_pedido', (string) $estadoPedido, PDO::PARAM_STR);
    if ($metodoPago === null) {
      $stmt->bindValue(':metodo_pago', null, PDO::PARAM_NULL);
    }
    else {
      $stmt->bindValue(':metodo_pago', (string) $metodoPago, PDO::PARAM_STR);
    }
    $stmt->bindValue(':usuario_modificacion', $this->usuario_sistema_id, PDO::PARAM_INT);
    $stmt->bindValue(':pedido_tienda_id', (int) $pedidoTiendaId, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = null;
  }

  private function actualizar_pago_checkout_tienda($dbh, $pagoTiendaId, $datos = []) {
    $stmt = $dbh->prepare("UPDATE public.pagos_tienda
SET
  estado_pago = :estado_pago,
  referencia_pasarela = :referencia_pasarela,
  respuesta_pasarela = :respuesta_pasarela,
  ultimos_cuatro = :ultimos_cuatro,
  fecha_procesamiento = NOW(),
  usuario_modificacion = :usuario_modificacion,
  fecha_modificacion = NOW()
WHERE
  pago_tienda_id = :pago_tienda_id;");
    $stmt->bindValue(':estado_pago', (string) ($datos['estado_pago'] ?? 'pendiente'), PDO::PARAM_STR);
    $stmt->bindValue(':referencia_pasarela', (string) ($datos['referencia_pasarela'] ?? ''), PDO::PARAM_STR);
    $stmt->bindValue(':respuesta_pasarela', (string) ($datos['respuesta_pasarela'] ?? ''), PDO::PARAM_STR);
    if (($datos['ultimos_cuatro'] ?? null) === null || (string) ($datos['ultimos_cuatro'] ?? '') === '') {
      $stmt->bindValue(':ultimos_cuatro', null, PDO::PARAM_NULL);
    }
    else {
      $stmt->bindValue(':ultimos_cuatro', (string) $datos['ultimos_cuatro'], PDO::PARAM_STR);
    }
    $stmt->bindValue(':usuario_modificacion', $this->usuario_sistema_id, PDO::PARAM_INT);
    $stmt->bindValue(':pago_tienda_id', (int) $pagoTiendaId, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = null;
  }

  private function consultar_resumen_checkout_tienda() {
    return $_SESSION['tv_checkout_ultimo_pedido'] ?? [];
  }
}
?>
