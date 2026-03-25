<?php
/**
 * Configuración de Wompi para la tienda.
 * Completar llaves para habilitar pagos reales.
 *
 * @return array Configuración activa.
 */
function pasarela_wompi_obtener_configuracion() {
  $entorno = getenv('WOMPI_ENV') !== false ? strtolower((string) getenv('WOMPI_ENV')) : 'sandbox';

  return [
    'habilitado'         => (getenv('WOMPI_ENABLED') !== false ? getenv('WOMPI_ENABLED') : '0') === '1',
    'entorno'            => in_array($entorno, ['production', 'sandbox'], true) ? $entorno : 'sandbox',
    'public_key'         => getenv('WOMPI_PUBLIC_KEY') !== false ? (string) getenv('WOMPI_PUBLIC_KEY') : '',
    'private_key'        => getenv('WOMPI_PRIVATE_KEY') !== false ? (string) getenv('WOMPI_PRIVATE_KEY') : '',
    'integrity_key'      => getenv('WOMPI_INTEGRITY_KEY') !== false ? (string) getenv('WOMPI_INTEGRITY_KEY') : '',
    'events_key'         => getenv('WOMPI_EVENTS_KEY') !== false ? (string) getenv('WOMPI_EVENTS_KEY') : '',
    'redirect_base_url'  => getenv('APP_BASE_URL') !== false ? rtrim((string) getenv('APP_BASE_URL'), '/') : 'http://localhost:8080',
    'currency'           => 'COP',
    'country_code'       => 'CO',
    'default_legal_id'   => 'CC',
  ];
}
