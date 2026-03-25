<?php
require_once __DIR__ . '/../../config/pasarela_wompi.php';

class pasarela_wompi_service {
  private $config = [];

  public function __construct() {
    $this->config = pasarela_wompi_obtener_configuracion();
  }

  public function obtener_configuracion_publica() {
    return [
      'habilitado'        => $this->tiene_configuracion_valida(),
      'entorno'           => $this->config['entorno'],
      'public_key'        => $this->config['public_key'],
      'redirect_base_url' => $this->config['redirect_base_url'],
    ];
  }

  public function tiene_configuracion_valida() {
    return (
      ($this->config['habilitado'] ?? false) === true
      && trim((string) ($this->config['public_key'] ?? '')) !== ''
      && trim((string) ($this->config['private_key'] ?? '')) !== ''
      && trim((string) ($this->config['integrity_key'] ?? '')) !== ''
    );
  }

  public function obtener_mensaje_configuracion() {
    return 'Debes configurar WOMPI_ENABLED, WOMPI_PUBLIC_KEY, WOMPI_PRIVATE_KEY e WOMPI_INTEGRITY_KEY para habilitar pagos reales.';
  }

  public function consultar_token_aceptacion() {
    if ($this->tiene_configuracion_valida() !== true) {
      return [
        'estado'  => false,
        'mensaje' => $this->obtener_mensaje_configuracion(),
        'datos'   => [],
      ];
    }

    $respuesta = $this->request('GET', '/merchants/' . rawurlencode((string) $this->config['public_key']), [], [], false);

    if (($respuesta['estado'] ?? false) !== true) {
      return $respuesta;
    }

    $data = $respuesta['datos']['data'] ?? [];
    $presigned = $data['presigned_acceptance'] ?? [];

    return [
      'estado'  => true,
      'mensaje' => 'Token de aceptación consultado correctamente.',
      'datos'   => [
        'acceptance_token' => (string) ($presigned['acceptance_token'] ?? ''),
        'permalink'        => (string) ($presigned['permalink'] ?? ''),
      ],
    ];
  }

  public function consultar_bancos_pse() {
    $respuesta = $this->request('GET', '/pse/financial_institutions', [], [], false);

    if (($respuesta['estado'] ?? false) !== true) {
      return [
        'estado'  => false,
        'mensaje' => 'No fue posible consultar bancos PSE.',
        'datos'   => [],
      ];
    }

    return [
      'estado'  => true,
      'mensaje' => 'Bancos PSE consultados correctamente.',
      'datos'   => $respuesta['datos']['data'] ?? [],
    ];
  }

  public function crear_transaccion_pse($payload = []) {
    return $this->request('POST', '/transactions', $payload, [
      'Authorization: Bearer ' . $this->config['private_key'],
      'Content-Type: application/json',
    ], true);
  }

  public function crear_transaccion_tarjeta($payload = []) {
    return $this->request('POST', '/transactions', $payload, [
      'Authorization: Bearer ' . $this->config['private_key'],
      'Content-Type: application/json',
    ], true);
  }

  public function consultar_transaccion($transactionId) {
    return $this->request('GET', '/transactions/' . rawurlencode((string) $transactionId), [], [
      'Authorization: Bearer ' . $this->config['private_key'],
    ], true);
  }

  public function consultar_async_payment_url_pse($transactionId, $intentos = 12, $esperaMicrosegundos = 600000) {
    for ($i = 0; $i < $intentos; $i++) {
      $consulta = $this->consultar_transaccion($transactionId);

      if (($consulta['estado'] ?? false) !== true) {
        return $consulta;
      }

      $extra = $consulta['datos']['data']['payment_method']['extra'] ?? [];
      $asyncUrl = (string) ($extra['async_payment_url'] ?? '');

      if ($asyncUrl !== '') {
        return [
          'estado'  => true,
          'mensaje' => 'URL asíncrona obtenida correctamente.',
          'datos'   => [
            'async_payment_url' => $asyncUrl,
            'transaccion'       => $consulta['datos']['data'] ?? [],
          ],
        ];
      }

      usleep($esperaMicrosegundos);
    }

    return [
      'estado'  => false,
      'mensaje' => 'La pasarela no devolvió la URL bancaria para continuar con PSE.',
      'datos'   => [],
    ];
  }

  public function generar_firma_integridad($referencia, $amountInCents, $currency, $expirationTime = '') {
    $texto = (string) $referencia . (string) $amountInCents . (string) $currency;

    if ($expirationTime !== '') {
      $texto .= (string) $expirationTime;
    }

    $texto .= (string) $this->config['integrity_key'];

    return hash('sha256', $texto);
  }

  public function mapear_estado_transaccion_local($estadoWompi) {
    $estadoWompi = strtoupper((string) $estadoWompi);

    if (in_array($estadoWompi, ['APPROVED', 'APPROVED_PARTIAL'], true)) {
      return 'pagado';
    }

    if (in_array($estadoWompi, ['PENDING', 'PENDING_VALIDATION'], true)) {
      return 'pendiente';
    }

    return 'rechazado';
  }

  private function request($method, $path, $body = [], $headers = [], $usarPrivada = false) {
    $baseUrl = $this->config['entorno'] === 'production'
      ? 'https://production.wompi.co/v1'
      : 'https://sandbox.wompi.co/v1';

    $url = $baseUrl . $path;
    $payload = '';

    if ($method === 'GET' && count($body) > 0) {
      $url .= '?' . http_build_query($body);
    }
    else if ($method !== 'GET') {
      $payload = json_encode($body, JSON_UNESCAPED_UNICODE);
    }

    if (function_exists('curl_init')) {
      return $this->request_curl($method, $url, $payload, $headers);
    }

    return $this->request_stream($method, $url, $payload, $headers);
  }

  private function request_curl($method, $url, $payload, $headers = []) {
    $ch = curl_init();

    curl_setopt_array($ch, [
      CURLOPT_URL            => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST  => $method,
      CURLOPT_TIMEOUT        => 30,
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_HTTPHEADER     => $headers,
    ]);

    if ($method !== 'GET') {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }

    $respuesta = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($respuesta === false || $error !== '') {
      return [
        'estado'  => false,
        'mensaje' => 'No fue posible comunicarse con la pasarela Wompi.',
        'datos'   => [
          'http_code' => $httpCode,
          'error'     => $error,
        ],
      ];
    }

    return $this->normalizar_respuesta_request((string) $respuesta, $httpCode);
  }

  private function request_stream($method, $url, $payload, $headers = []) {
    $contexto = stream_context_create([
      'http' => [
        'method'        => $method,
        'header'        => implode("\r\n", $headers),
        'content'       => $method !== 'GET' ? $payload : '',
        'timeout'       => 30,
        'ignore_errors' => true,
      ],
      'ssl' => [
        'verify_peer'      => true,
        'verify_peer_name' => true,
      ],
    ]);

    $respuesta = @file_get_contents($url, false, $contexto);
    $httpCode = 0;

    if (!empty($http_response_header[0]) && preg_match('/\s(\d{3})\s/', (string) $http_response_header[0], $coincidencia)) {
      $httpCode = (int) $coincidencia[1];
    }

    if ($respuesta === false) {
      $error = error_get_last();

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible comunicarse con la pasarela Wompi.',
        'datos'   => [
          'http_code' => $httpCode,
          'error'     => (string) ($error['message'] ?? 'Error de comunicación.'),
        ],
      ];
    }

    return $this->normalizar_respuesta_request((string) $respuesta, $httpCode);
  }

  private function normalizar_respuesta_request($respuesta, $httpCode) {
    $json = json_decode((string) $respuesta, true);

    if ($httpCode < 200 || $httpCode >= 300) {
      return [
        'estado'  => false,
        'mensaje' => (string) (($json['error']['reason'] ?? '') ?: ($json['error']['messages'][0]['message'] ?? '') ?: 'La pasarela devolvió un error.'),
        'datos'   => [
          'http_code' => $httpCode,
          'respuesta' => $json,
        ],
      ];
    }

    return [
      'estado'  => true,
      'mensaje' => 'Operación realizada correctamente.',
      'datos'   => $json,
    ];
  }
}
