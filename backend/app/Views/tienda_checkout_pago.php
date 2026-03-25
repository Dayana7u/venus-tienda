<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$tema = $contexto['tema'] ?? [];
$carrito = $tv_datos['carrito'] ?? ['items' => [], 'subtotal' => 0, 'envio' => 0, 'total' => 0, 'ahorro' => 0];
$pedido = $tv_datos['pedido'] ?? [];
$checkout_datos = $tv_datos['checkout_datos'] ?? [];
$bancos_pse = $tv_datos['bancos_pse'] ?? [];
$pasarela_aceptacion = $tv_datos['pasarela_aceptacion'] ?? [];
$pasarela_activa = $tv_datos['pasarela_activa'] ?? [];
$sincronizacion = $tv_datos['sincronizacion'] ?? [];
$modulo = $contexto['modulo'] ?? [];
$pedido_exitoso = ($_GET['estado'] ?? '') === 'ok' && (string) ($_GET['pedido'] ?? '') !== '' && (string) ($pedido['codigo'] ?? '') === (string) ($_GET['pedido'] ?? '');
$estado_sincronizado = (string) ($sincronizacion['estado_pago'] ?? '');

$items_resumen = $carrito['items'] ?? [];
if (count($items_resumen) === 0 && isset($_SESSION['tv_checkout_ultimo_items']) && is_array($_SESSION['tv_checkout_ultimo_items'])) {
  $items_resumen = $_SESSION['tv_checkout_ultimo_items'];
}

$pasarela_titulo = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_titulo', 'Finaliza tu compra');
$pasarela_descripcion = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_descripcion', 'Selecciona el método de pago y completa solo la información necesaria para continuar con el cobro.');
$pasarela_encabezado = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_encabezado', 'Paga tu pedido');
$pasarela_texto = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_texto', 'Selecciona el medio de pago que mejor se adapte a tu compra.');
$texto_tarjeta = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_tarjeta_texto', 'Tu pago se procesa de forma segura.');
$texto_pse = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_pse_texto', 'Al continuar serás redirigido a tu banco para completar el pago con PSE.');
$texto_contra = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_contra_texto', 'Valida quién recibe el pedido y deja lista la entrega.');
$terminos_texto = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_terminos', 'Acepto el procesamiento del pago y los términos del medio seleccionado');
$boton_pagar = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_boton', 'Pagar pedido');
$boton_descargar = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_confirmacion_boton_comprobante', 'Descargar comprobante');
$boton_soporte = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_confirmacion_boton_soporte', 'Solicitar soporte');
$boton_seguir = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_confirmacion_boton_catalogo', 'Seguir comprando');

$metodos_pago = [
  'tarjeta' => [
    'titulo' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_tarjeta_titulo', 'Tarjeta'),
    'descripcion' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_tarjeta_descripcion', 'Pago directo'),
    'icono' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_tarjeta_icono', ''),
    'mensaje' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_tarjeta_mensaje', 'La tarjeta se valida antes del cobro.'),
  ],
  'pse' => [
    'titulo' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_pse_titulo', 'PSE'),
    'descripcion' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_pse_descripcion', 'Redirección bancaria'),
    'icono' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_pse_icono', ''),
    'mensaje' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_pse_mensaje', 'PSE abrirá la validación en el banco seleccionado.'),
  ],
  'contra_entrega' => [
    'titulo' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_contra_titulo', 'Contra entrega'),
    'descripcion' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_contra_descripcion', 'Validación manual'),
    'icono' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_contra_icono', ''),
    'mensaje' => tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_pago_metodo_contra_mensaje', 'Contra entrega dejará el pedido pendiente hasta la validación manual.'),
  ],
];

$campos_pago = [
  'titular_pagador' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_tarjeta_titular', ['label' => 'Titular de la tarjeta', 'placeholder' => 'Nombre como aparece en la tarjeta', 'visible' => true, 'requerido' => true]),
  'numero_tarjeta' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_tarjeta_numero', ['label' => 'Número de tarjeta', 'placeholder' => '0000 0000 0000 0000', 'visible' => true, 'requerido' => true]),
  'fecha_expiracion' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_tarjeta_fecha', ['label' => 'Fecha expiración', 'placeholder' => 'MM/AA', 'visible' => true, 'requerido' => true]),
  'cvv' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_tarjeta_cvv', ['label' => 'CVV', 'placeholder' => '***', 'visible' => true, 'requerido' => true]),
  'cuotas' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_tarjeta_cuotas', ['label' => 'Cuotas', 'placeholder' => '', 'visible' => true, 'requerido' => false]),
  'nombres_pagador' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_pse_nombre', ['label' => 'Nombre del pagador', 'placeholder' => 'Nombre del pagador', 'visible' => true, 'requerido' => true]),
  'correo_pagador' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_pse_correo', ['label' => 'Correo electrónico', 'placeholder' => 'nombre@correo.com', 'visible' => true, 'requerido' => true]),
  'documento_pagador' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_pse_documento', ['label' => 'Documento', 'placeholder' => 'Número de documento', 'visible' => true, 'requerido' => true]),
  'entidad_pse' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_pse_banco', ['label' => 'Banco', 'placeholder' => '', 'visible' => true, 'requerido' => true]),
  'contra_entrega_recibe' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_contra_recibe', ['label' => 'Persona que recibe', 'placeholder' => 'Nombre de quien recibe', 'visible' => true, 'requerido' => true]),
  'contra_entrega_documento' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_contra_documento', ['label' => 'Documento de quien recibe', 'placeholder' => 'Número de documento', 'visible' => true, 'requerido' => true]),
  'contra_entrega_confirma' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_pago_contra_confirma', ['label' => 'Confirmo que la persona, la dirección y el teléfono de entrega son correctos.', 'placeholder' => '', 'visible' => true, 'requerido' => true]),
];

$pedido_codigo = (string) ($pedido['codigo'] ?? '');
$comprobante_url = '/pedido/comprobante/?pedido=' . urlencode($pedido_codigo);
$soporte_url = '/contacto/?pedido=' . urlencode($pedido_codigo) . '&cliente=' . urlencode((string) ($pedido['cliente'] ?? '')) . '&total=' . urlencode((string) ($pedido['total'] ?? 0));

tienda_render_head('Checkout · Pago', $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CHECKOUT', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo tv_checkout_pagina tv_checkout_pago_pagina_v24">
    <section class="tv_bloque tv_bloque_checkout_amplio tv_checkout_intro tv_checkout_intro_pago_v24">
      <div class="tv_checkout_intro_texto">
        <span class="tv_etiqueta">Pasarela</span>
        <h1><?php echo htmlspecialchars((string) $pasarela_titulo, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars((string) $pasarela_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
      </div>
      <div class="tv_checkout_pasos">
        <a href="/checkout/" class="tv_checkout_paso">1. Datos</a>
        <span class="tv_checkout_paso tv_checkout_paso_activo">2. Pago</span>
        <span class="tv_checkout_paso">3. Confirmación</span>
      </div>
    </section>

    <?php if ($pedido_exitoso === true) { ?>
      <section class="tv_bloque tv_bloque_checkout_amplio tv_checkout_exito tv_checkout_exito_pago">
        <span class="tv_etiqueta">Pago registrado</span>
        <h3>Pedido <?php echo htmlspecialchars((string) ($pedido['codigo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
        <p>La compra quedó registrada para <?php echo htmlspecialchars((string) ($pedido['cliente'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>.</p>
        <div class="tv_checkout_exito_resumen">
          <article><span>Método</span><strong><?php echo htmlspecialchars((string) tienda_formatear_metodo_pago_publico((string) ($pedido['metodo_pago'] ?? '')), ENT_QUOTES, 'UTF-8'); ?></strong></article>
          <article><span>Estado pago</span><strong><?php echo htmlspecialchars((string) ($pedido['estado_pago'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong></article>
          <article><span>Total</span><strong>$<?php echo number_format((int) ($pedido['total'] ?? 0), 0, ',', '.'); ?></strong></article>
        </div>
        <div class="tv_checkout_exito_botones tv_checkout_exito_botones_v26">
          <a href="/catalogo/" class="tv_btn tv_btn_secundario"><?php echo htmlspecialchars((string) $boton_seguir, ENT_QUOTES, 'UTF-8'); ?></a>
          <a href="<?php echo htmlspecialchars($comprobante_url, ENT_QUOTES, 'UTF-8'); ?>" class="tv_btn tv_btn_secundario" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars((string) $boton_descargar, ENT_QUOTES, 'UTF-8'); ?></a>
          <a href="<?php echo htmlspecialchars($soporte_url, ENT_QUOTES, 'UTF-8'); ?>" class="tv_btn tv_btn_principal"><?php echo htmlspecialchars((string) $boton_soporte, ENT_QUOTES, 'UTF-8'); ?></a>
        </div>
      </section>
    <?php } else if ($estado_sincronizado === 'pendiente') { ?>
      <section class="tv_bloque tv_bloque_checkout_amplio tv_checkout_exito tv_checkout_exito_pago tv_checkout_estado_pendiente">
        <span class="tv_etiqueta">Pago en validación</span>
        <h3>Estamos validando la transacción</h3>
        <p>La pasarela todavía reporta el pago como pendiente. Puedes actualizar esta vista en unos segundos o revisar luego en tu correo.</p>
        <div class="tv_checkout_exito_botones">
          <a href="/checkout/pago/?pago=<?php echo htmlspecialchars((string) ($_GET['pago'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>&pasarela=wompi" class="tv_btn tv_btn_principal">Actualizar estado</a>
          <a href="/catalogo/" class="tv_btn tv_btn_secundario">Volver al catálogo</a>
        </div>
      </section>
    <?php } else if (count($checkout_datos) === 0) { ?>
      <div class="tv_bloque tv_bloque_checkout_amplio">
        <div class="tv_vacio">Primero completa los <a href="/checkout/">datos del pedido</a> antes de pasar al pago.</div>
      </div>
    <?php } else { ?>
      <section class="tv_bloque tv_bloque_checkout_amplio tv_checkout_pago_layout tv_checkout_pago_layout_v24">
        <article class="tv_checkout_panel tv_checkout_panel_pago_formulario tv_checkout_panel_pago_v24">
          <div class="tv_checkout_pago_encabezado tv_checkout_pago_encabezado_v24">
            <div>
              <span class="tv_etiqueta">Método de pago</span>
              <h3><?php echo htmlspecialchars((string) $pasarela_encabezado, ENT_QUOTES, 'UTF-8'); ?></h3>
              <p class="tv_checkout_pago_texto"><?php echo htmlspecialchars((string) $pasarela_texto, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="tv_checkout_pago_badges">
              <span class="tv_checkout_pago_badge"><?php echo htmlspecialchars((string) $metodos_pago['pse']['titulo'], ENT_QUOTES, 'UTF-8'); ?></span>
              <span class="tv_checkout_pago_badge"><?php echo htmlspecialchars((string) $metodos_pago['tarjeta']['titulo'], ENT_QUOTES, 'UTF-8'); ?></span>
              <span class="tv_checkout_pago_badge"><?php echo htmlspecialchars((string) $metodos_pago['contra_entrega']['titulo'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
          </div>

          <form action="/checkout/pago/" method="post" id="form_checkout_pago_tienda_publica" class="tv_checkout_pago_formulario tv_checkout_pago_formulario_v24" novalidate>
            <input type="hidden" name="accion" value="guardar_checkout_pago">
            <input type="hidden" id="token_checkout_pago_tienda_publica" value="<?php echo htmlspecialchars((string) ($_SESSION['tienda_publica_token'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" id="controlador_checkout_pago_tienda_publica" value="/app/Controllers/tienda_checkout_pago_controller.php">
            <input type="hidden" name="franquicia_tarjeta" id="input_franquicia_tarjeta_checkout_pago">
            <input type="hidden" name="token_tarjeta_wompi" id="input_token_tarjeta_checkout_pago">
            <input type="hidden" name="ultimos_cuatro_tarjeta" id="input_ultimos_cuatro_checkout_pago">
            <input type="hidden" id="input_public_key_checkout_pago" value="<?php echo htmlspecialchars((string) ($pasarela_activa['public_key'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" id="input_pasarela_habilitada_checkout_pago" value="<?php echo (($pasarela_activa['habilitado'] ?? false) === true ? '1' : '0'); ?>">

            <div class="tv_checkout_metodos tv_checkout_metodos_pago tv_checkout_metodos_pago_v24">
              <?php foreach ($metodos_pago as $codigo_metodo => $metodo_pago) { ?>
                <label class="tv_checkout_metodo tv_checkout_metodo_pago_item tv_checkout_metodo_pago_card <?php echo $codigo_metodo === 'tarjeta' ? 'tv_checkout_metodo_pago_card_activo' : ''; ?>">
                  <input type="radio" name="metodo_pago" value="<?php echo htmlspecialchars((string) $codigo_metodo, ENT_QUOTES, 'UTF-8'); ?>" data-mensaje-metodo="<?php echo htmlspecialchars((string) ($metodo_pago['mensaje'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"<?php echo $codigo_metodo === 'tarjeta' ? ' checked' : ''; ?>>
                  <span class="tv_checkout_metodo_visual">
                    <?php echo tienda_render_icono_metodo_pago_publico($codigo_metodo, (string) ($metodo_pago['icono'] ?? '')); ?>
                    <span class="tv_checkout_metodo_textos">
                      <span><?php echo htmlspecialchars((string) ($metodo_pago['titulo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                      <small><?php echo htmlspecialchars((string) ($metodo_pago['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></small>
                    </span>
                  </span>
                </label>
              <?php } ?>
            </div>

            <div id="div_checkout_pago_tarjeta" class="tv_checkout_pago_metodo tv_checkout_pago_metodo_activo tv_checkout_pago_metodo_v24">
              <div class="tv_checkout_tarjeta_preview tv_checkout_tarjeta_preview_v24">
                <div>
                  <span>Franquicia detectada</span>
                  <strong id="strong_franquicia_checkout_pago">Por detectar</strong>
                </div>
                <small><?php echo htmlspecialchars((string) $texto_tarjeta, ENT_QUOTES, 'UTF-8'); ?></small>
              </div>
              <div class="tv_checkout_grid tv_checkout_grid_dos tv_checkout_tarjeta_grid_v24">
                <?php if (($campos_pago['titular_pagador']['visible'] ?? false) === true) { ?>
                  <label class="tv_checkout_campo_full">
                    <span><?php echo htmlspecialchars((string) $campos_pago['titular_pagador']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['titular_pagador']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <input type="text" name="titular_pagador" data-checkout-tarjeta="titular"<?php echo ($campos_pago['titular_pagador']['requerido'] ?? false) === true ? ' required' : ''; ?> autocomplete="cc-name" placeholder="<?php echo htmlspecialchars((string) $campos_pago['titular_pagador']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars(trim((string) (($checkout_datos['nombres'] ?? '') . ' ' . ($checkout_datos['apellidos'] ?? ''))), ENT_QUOTES, 'UTF-8'); ?>">
                  </label>
                <?php } ?>
                <?php if (($campos_pago['numero_tarjeta']['visible'] ?? false) === true) { ?>
                  <label class="tv_checkout_campo_full">
                    <span><?php echo htmlspecialchars((string) $campos_pago['numero_tarjeta']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['numero_tarjeta']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <input type="text" data-checkout-tarjeta="numero"<?php echo ($campos_pago['numero_tarjeta']['requerido'] ?? false) === true ? ' required' : ''; ?> inputmode="numeric" autocomplete="cc-number" maxlength="23" placeholder="<?php echo htmlspecialchars((string) $campos_pago['numero_tarjeta']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>">
                  </label>
                <?php } ?>
                <?php if (($campos_pago['fecha_expiracion']['visible'] ?? false) === true) { ?>
                  <label>
                    <span><?php echo htmlspecialchars((string) $campos_pago['fecha_expiracion']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['fecha_expiracion']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <input type="text" name="fecha_expiracion" data-checkout-tarjeta="fecha"<?php echo ($campos_pago['fecha_expiracion']['requerido'] ?? false) === true ? ' required' : ''; ?> inputmode="numeric" autocomplete="cc-exp" maxlength="5" placeholder="<?php echo htmlspecialchars((string) $campos_pago['fecha_expiracion']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>">
                  </label>
                <?php } ?>
                <?php if (($campos_pago['cvv']['visible'] ?? false) === true) { ?>
                  <label>
                    <span><?php echo htmlspecialchars((string) $campos_pago['cvv']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['cvv']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <input type="password" data-checkout-tarjeta="cvv"<?php echo ($campos_pago['cvv']['requerido'] ?? false) === true ? ' required' : ''; ?> inputmode="numeric" autocomplete="cc-csc" maxlength="4" placeholder="<?php echo htmlspecialchars((string) $campos_pago['cvv']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>">
                  </label>
                <?php } ?>
                <?php if (($campos_pago['cuotas']['visible'] ?? false) === true) { ?>
                  <label>
                    <span><?php echo htmlspecialchars((string) $campos_pago['cuotas']['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <select name="cuotas">
                      <option value="1">1 cuota</option>
                      <option value="3">3 cuotas</option>
                      <option value="6">6 cuotas</option>
                      <option value="12">12 cuotas</option>
                    </select>
                  </label>
                <?php } ?>
              </div>
            </div>

            <div id="div_checkout_pago_pse" class="tv_checkout_pago_metodo tv_checkout_pago_metodo_v24 tv_oculto">
              <div class="tv_checkout_alerta tv_checkout_alerta_compacta"><?php echo htmlspecialchars((string) $texto_pse, ENT_QUOTES, 'UTF-8'); ?></div>
              <div class="tv_checkout_grid tv_checkout_grid_dos tv_checkout_pse_grid_v24">
                <?php if (($campos_pago['nombres_pagador']['visible'] ?? false) === true) { ?>
                  <label>
                    <span><?php echo htmlspecialchars((string) $campos_pago['nombres_pagador']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['nombres_pagador']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <input type="text" name="nombres_pagador"<?php echo ($campos_pago['nombres_pagador']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_pago['nombres_pagador']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars(trim((string) (($checkout_datos['nombres'] ?? '') . ' ' . ($checkout_datos['apellidos'] ?? ''))), ENT_QUOTES, 'UTF-8'); ?>">
                  </label>
                <?php } ?>
                <?php if (($campos_pago['correo_pagador']['visible'] ?? false) === true) { ?>
                  <label>
                    <span><?php echo htmlspecialchars((string) $campos_pago['correo_pagador']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['correo_pagador']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <input type="email" name="correo_pagador"<?php echo ($campos_pago['correo_pagador']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_pago['correo_pagador']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['correo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                  </label>
                <?php } ?>
                <?php if (($campos_pago['documento_pagador']['visible'] ?? false) === true) { ?>
                  <label>
                    <span><?php echo htmlspecialchars((string) $campos_pago['documento_pagador']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['documento_pagador']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <input type="text" name="documento_pagador"<?php echo ($campos_pago['documento_pagador']['requerido'] ?? false) === true ? ' required' : ''; ?> inputmode="numeric" maxlength="20" placeholder="<?php echo htmlspecialchars((string) $campos_pago['documento_pagador']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>">
                  </label>
                <?php } ?>
                <?php if (($campos_pago['entidad_pse']['visible'] ?? false) === true) { ?>
                  <label>
                    <span><?php echo htmlspecialchars((string) $campos_pago['entidad_pse']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['entidad_pse']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <select name="entidad_pse"<?php echo ($campos_pago['entidad_pse']['requerido'] ?? false) === true ? ' required' : ''; ?>>
                      <option value="">Seleccione</option>
                      <?php foreach ($bancos_pse as $banco) { ?>
                        <option value="<?php echo htmlspecialchars((string) ($banco['financial_institution_code'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) ($banco['financial_institution_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></option>
                      <?php } ?>
                    </select>
                  </label>
                <?php } ?>
              </div>
              <input type="hidden" name="user_type_pse" value="0">
            </div>

            <div id="div_checkout_pago_contra_entrega" class="tv_checkout_pago_metodo tv_checkout_pago_metodo_v24 tv_oculto">
              <div class="tv_checkout_alerta tv_checkout_alerta_compacta tv_checkout_alerta_compacta_contra"><?php echo htmlspecialchars((string) $texto_contra, ENT_QUOTES, 'UTF-8'); ?></div>
              <div class="tv_checkout_entrega_resumen tv_checkout_entrega_resumen_v24">
                <article>
                  <span>Recibe</span>
                  <strong><?php echo htmlspecialchars((string) ($checkout_datos['destinatario'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                </article>
                <article>
                  <span>Dirección</span>
                  <strong><?php echo htmlspecialchars((string) ($checkout_datos['direccion_linea_1'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                </article>
                <article>
                  <span>Ciudad</span>
                  <strong><?php echo htmlspecialchars((string) ($checkout_datos['ciudad'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                </article>
              </div>
              <div class="tv_checkout_grid tv_checkout_grid_dos">
                <?php if (($campos_pago['contra_entrega_recibe']['visible'] ?? false) === true) { ?>
                  <label>
                    <span><?php echo htmlspecialchars((string) $campos_pago['contra_entrega_recibe']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['contra_entrega_recibe']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <input type="text" name="contra_entrega_recibe"<?php echo ($campos_pago['contra_entrega_recibe']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_pago['contra_entrega_recibe']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['destinatario'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                  </label>
                <?php } ?>
                <?php if (($campos_pago['contra_entrega_documento']['visible'] ?? false) === true) { ?>
                  <label>
                    <span><?php echo htmlspecialchars((string) $campos_pago['contra_entrega_documento']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_pago['contra_entrega_documento']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                    <input type="text" name="contra_entrega_documento"<?php echo ($campos_pago['contra_entrega_documento']['requerido'] ?? false) === true ? ' required' : ''; ?> inputmode="numeric" maxlength="20" placeholder="<?php echo htmlspecialchars((string) $campos_pago['contra_entrega_documento']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>">
                  </label>
                <?php } ?>
                <?php if (($campos_pago['contra_entrega_confirma']['visible'] ?? false) === true) { ?>
                  <label class="tv_checkout_campo_full tv_checkout_check_label">
                    <input type="checkbox" name="contra_entrega_confirma" value="1"<?php echo ($campos_pago['contra_entrega_confirma']['requerido'] ?? false) === true ? ' required' : ''; ?>>
                    <span><?php echo htmlspecialchars((string) $campos_pago['contra_entrega_confirma']['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                  </label>
                <?php } ?>
              </div>
            </div>

            <div class="tv_checkout_legal tv_checkout_legal_pasarela">
              <label class="tv_checkout_check_label tv_checkout_check_label_pasarela">
                <input type="checkbox" name="acepta_pasarela" value="1">
                <span><?php echo htmlspecialchars((string) $terminos_texto, ENT_QUOTES, 'UTF-8'); ?><?php if (($pasarela_aceptacion['permalink'] ?? '') !== '') { ?>, <a href="<?php echo htmlspecialchars((string) $pasarela_aceptacion['permalink'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">ver términos</a><?php } ?>.</span>
              </label>
            </div>

            <div class="tv_checkout_acciones_compactas tv_checkout_acciones_pago">
              <button type="submit" class="tv_btn tv_btn_principal" id="btn_guardar_checkout_pago" data-texto-base="<?php echo htmlspecialchars((string) $boton_pagar, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $boton_pagar, ENT_QUOTES, 'UTF-8'); ?></button>
              <a href="/checkout/" class="tv_btn tv_btn_secundario">Volver a datos</a>
            </div>
          </form>
        </article>

        <aside class="tv_checkout_panel tv_checkout_panel_pago_resumen tv_checkout_panel_pago_resumen_v24">
          <header class="tv_checkout_panel_header">
            <span class="tv_etiqueta">Resumen</span>
            <h3>Pedido actual</h3>
          </header>
          <div class="tv_checkout_resumen_lista tv_checkout_resumen_lista_pago">
            <?php foreach ($items_resumen as $item) { ?>
              <article class="tv_checkout_resumen_item tv_checkout_resumen_item_pago tv_checkout_resumen_item_pago_v24">
                <div class="tv_checkout_resumen_media tv_checkout_resumen_media_pago">
                  <?php tienda_render_producto_media($item, 'tv_producto_media_checkout', false); ?>
                </div>
                <div class="tv_checkout_resumen_info">
                  <strong><?php echo htmlspecialchars((string) ($item['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                  <span><?php echo (int) ($item['cantidad'] ?? 0); ?> unidad(es)</span>
                  <b>$<?php echo number_format((int) ($item['total_linea'] ?? $item['total'] ?? 0), 0, ',', '.'); ?></b>
                </div>
              </article>
            <?php } ?>
          </div>
          <div class="tv_checkout_totales tv_checkout_totales_laterales">
            <div><span>Subtotal</span><strong>$<?php echo number_format((int) ($carrito['subtotal'] ?? 0), 0, ',', '.'); ?></strong></div>
            <div><span>Ahorro</span><strong>$<?php echo number_format((int) ($carrito['ahorro'] ?? 0), 0, ',', '.'); ?></strong></div>
            <div><span>Envío</span><strong>$<?php echo number_format((int) ($carrito['envio'] ?? 0), 0, ',', '.'); ?></strong></div>
            <div class="tv_checkout_total_principal"><span>Total</span><strong>$<?php echo number_format((int) ($carrito['total'] ?? 0), 0, ',', '.'); ?></strong></div>
          </div>
          <div class="tv_checkout_resumen_beneficios tv_checkout_resumen_cliente">
            <article><span>Comprador</span><strong><?php echo htmlspecialchars(trim((string) (($checkout_datos['nombres'] ?? '') . ' ' . ($checkout_datos['apellidos'] ?? ''))), ENT_QUOTES, 'UTF-8'); ?></strong></article>
            <article><span>Entrega</span><strong><?php echo htmlspecialchars((string) ($checkout_datos['ciudad'] ?? ''), ENT_QUOTES, 'UTF-8'); ?> · <?php echo htmlspecialchars((string) ($checkout_datos['departamento'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong></article>
          </div>
        </aside>
      </section>
    <?php } ?>
  </main>

  <?php tienda_render_footer($branding, $menus, $tema); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
  <script src="/public/assets/js/tienda_checkout_template.js"></script>
  <script src="/public/assets/js/tienda_checkout_peticiones.js"></script>
  <script src="/public/assets/js/tienda_checkout_modal.js"></script>
  <script src="/public/assets/js/tienda_checkout_pago.js"></script>
</body>
</html>
