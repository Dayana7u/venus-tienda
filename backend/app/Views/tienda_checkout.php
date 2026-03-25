<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$carrito = $tv_datos['carrito'] ?? ['items' => [], 'subtotal' => 0, 'envio' => 0, 'total' => 0, 'ahorro' => 0];
$pedido = $tv_datos['pedido'] ?? [];
$pedido_exitoso = ($_GET['estado'] ?? '') === 'ok' && (string) ($_GET['pedido'] ?? '') !== '' && (string) ($pedido['codigo'] ?? '') === (string) ($_GET['pedido'] ?? '');

tienda_render_head('Checkout', $tema_tokens, $componentes);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CHECKOUT'); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo">
    <section class="tv_bloque tv_checkout_bloque">
      <div class="tv_breadcrumb">Inicio / Carrito / Checkout</div>
      <div class="tv_bloque_encabezado tv_bloque_encabezado_inline tv_bloque_encabezado_checkout">
        <div>
          <span class="tv_etiqueta">Checkout</span>
          <h2>Finaliza tu compra</h2>
          <p>Completa datos del comprador, dirección y método de pago para registrar el pedido.</p>
        </div>
        <a href="/carrito/" class="tv_btn tv_btn_secundario">Volver al carrito</a>
      </div>

      <?php if ($pedido_exitoso === true) { ?>
        <section class="tv_checkout_exito">
          <span class="tv_etiqueta">Pago registrado</span>
          <h3>Pedido <?php echo htmlspecialchars((string) ($pedido['codigo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
          <p>La compra quedó registrada para <?php echo htmlspecialchars((string) ($pedido['cliente'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>.</p>
          <div class="tv_checkout_exito_resumen">
            <article><span>Método</span><strong><?php echo htmlspecialchars((string) ($pedido['metodo_pago'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong></article>
            <article><span>Estado pago</span><strong><?php echo htmlspecialchars((string) ($pedido['estado_pago'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong></article>
            <article><span>Total</span><strong>$<?php echo number_format((int) ($pedido['total'] ?? 0), 0, ',', '.'); ?></strong></article>
          </div>
          <div class="tv_checkout_exito_botones">
            <a href="/catalogo/" class="tv_btn tv_btn_secundario">Seguir comprando</a>
            <a href="/contacto/" class="tv_btn tv_btn_principal">Solicitar soporte</a>
          </div>
        </section>
      <?php } ?>

      <?php if (count($carrito['items'] ?? []) === 0) { ?>
        <div class="tv_vacio">El carrito está vacío. Ve al <a href="/catalogo/">catálogo</a> para continuar.</div>
      <?php } else { ?>
        <div class="tv_checkout_layout">
          <section class="tv_checkout_formulario_wrap">
            <form action="/checkout/" method="post" id="form_checkout_tienda_publica" class="tv_checkout_formulario">
              <input type="hidden" name="accion" value="guardar_checkout">
              <input type="hidden" id="token_checkout_tienda_publica" value="<?php echo htmlspecialchars((string) ($_SESSION['tienda_publica_token'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
              <input type="hidden" id="controlador_checkout_tienda_publica" value="/app/Controllers/tienda_checkout_controller.php">

              <div class="tv_checkout_grupo">
                <div class="tv_checkout_titulo">
                  <span class="tv_etiqueta">Comprador</span>
                  <h3>Datos principales</h3>
                </div>
                <div class="tv_checkout_grid tv_checkout_grid_dos">
                  <label>
                    <span>Nombres *</span>
                    <input type="text" name="nombres" required>
                  </label>
                  <label>
                    <span>Apellidos *</span>
                    <input type="text" name="apellidos" required>
                  </label>
                  <label>
                    <span>Correo *</span>
                    <input type="email" name="correo" required>
                  </label>
                  <label>
                    <span>Celular *</span>
                    <input type="text" name="celular" required>
                  </label>
                </div>
              </div>

              <div class="tv_checkout_grupo">
                <div class="tv_checkout_titulo">
                  <span class="tv_etiqueta">Entrega</span>
                  <h3>Dirección del pedido</h3>
                </div>
                <div class="tv_checkout_grid tv_checkout_grid_dos">
                  <label>
                    <span>Destinatario *</span>
                    <input type="text" name="destinatario" required>
                  </label>
                  <label>
                    <span>Teléfono dirección *</span>
                    <input type="text" name="telefono_direccion" required>
                  </label>
                  <label class="tv_checkout_campo_full">
                    <span>Dirección principal *</span>
                    <input type="text" name="direccion_linea_1" required>
                  </label>
                  <label class="tv_checkout_campo_full">
                    <span>Complemento</span>
                    <input type="text" name="direccion_linea_2">
                  </label>
                  <label>
                    <span>Ciudad *</span>
                    <input type="text" name="ciudad" required>
                  </label>
                  <label>
                    <span>Departamento *</span>
                    <input type="text" name="departamento" required>
                  </label>
                  <label>
                    <span>Código postal</span>
                    <input type="text" name="codigo_postal">
                  </label>
                  <label>
                    <span>Referencia</span>
                    <input type="text" name="referencia">
                  </label>
                  <label class="tv_checkout_campo_full">
                    <span>Observación de entrega</span>
                    <textarea name="observacion" rows="3"></textarea>
                  </label>
                </div>
              </div>

              <div class="tv_checkout_grupo">
                <div class="tv_checkout_titulo">
                  <span class="tv_etiqueta">Pasarela</span>
                  <h3>Método de pago</h3>
                </div>
                <div class="tv_checkout_metodos">
                  <div class="tv_checkout_alerta">El pago se registra sobre el pedido actual y genera una referencia interna para seguimiento administrativo.</div>
                  <label class="tv_checkout_metodo">
                    <input type="radio" name="metodo_pago" value="pse" checked>
                    <span>PSE</span>
                  </label>
                  <label class="tv_checkout_metodo">
                    <input type="radio" name="metodo_pago" value="tarjeta">
                    <span>Tarjeta</span>
                  </label>
                  <label class="tv_checkout_metodo">
                    <input type="radio" name="metodo_pago" value="contra_entrega">
                    <span>Contra entrega</span>
                  </label>
                </div>
                <input type="hidden" name="titular_pagador" id="input_titular_pagador_checkout">
                <div class="tv_checkout_grid tv_checkout_grid_dos">
                  <label>
                    <span>Documento del pagador *</span>
                    <input type="text" name="documento_pagador" required>
                  </label>
                  <label>
                    <span>Correo del pagador</span>
                    <input type="email" name="correo_pagador">
                  </label>
                </div>
                <div id="div_checkout_pse" class="tv_checkout_metodo_detalle tv_checkout_metodo_detalle_activo">
                  <div class="tv_checkout_grid tv_checkout_grid_dos">
                    <label>
                      <span>Banco PSE *</span>
                      <select name="entidad_pse">
                        <option value="">Seleccione</option>
                        <option value="bancolombia">Bancolombia</option>
                        <option value="davivienda">Davivienda</option>
                        <option value="bbva">BBVA</option>
                        <option value="bogota">Banco de Bogotá</option>
                      </select>
                    </label>
                    <label>
                      <span>Tipo persona *</span>
                      <select name="tipo_persona_pse">
                        <option value="">Seleccione</option>
                        <option value="natural">Natural</option>
                        <option value="juridica">Jurídica</option>
                      </select>
                    </label>
                    <label>
                      <span>Tipo cuenta *</span>
                      <select name="tipo_cuenta_pse">
                        <option value="">Seleccione</option>
                        <option value="ahorros">Ahorros</option>
                        <option value="corriente">Corriente</option>
                      </select>
                    </label>
                    <label>
                      <span>Titular</span>
                      <input type="text" name="titular_pagador_pse" placeholder="Nombre del titular">
                    </label>
                  </div>
                </div>
                <div id="div_checkout_tarjeta" class="tv_checkout_metodo_detalle tv_oculto">
                  <div class="tv_checkout_grid tv_checkout_grid_dos">
                    <label>
                      <span>Titular tarjeta *</span>
                      <input type="text" name="titular_pagador_tarjeta" data-checkout-mirror="titular_pagador">
                    </label>
                    <label>
                      <span>Franquicia</span>
                      <select name="franquicia_tarjeta">
                        <option value="">Seleccione</option>
                        <option value="visa">Visa</option>
                        <option value="mastercard">Mastercard</option>
                        <option value="amex">Amex</option>
                      </select>
                    </label>
                    <label class="tv_checkout_campo_full">
                      <span>Número tarjeta *</span>
                      <input type="text" name="numero_tarjeta" inputmode="numeric" placeholder="**** **** **** 1234">
                    </label>
                    <label>
                      <span>Fecha expiración *</span>
                      <input type="text" name="fecha_expiracion" placeholder="MM/AA">
                    </label>
                    <label>
                      <span>CVV *</span>
                      <input type="password" name="cvv_visual" inputmode="numeric" maxlength="4" data-checkout-cvv="true">
                    </label>
                    <label>
                      <span>Cuotas</span>
                      <select name="cuotas">
                        <option value="1">1 cuota</option>
                        <option value="3">3 cuotas</option>
                        <option value="6">6 cuotas</option>
                        <option value="12">12 cuotas</option>
                      </select>
                    </label>
                  </div>
                </div>
                <div id="div_checkout_contra_entrega" class="tv_checkout_metodo_detalle tv_oculto">
                  <div class="tv_checkout_info_pago">
                    <p>El pedido quedará registrado con pago pendiente y se completa al momento de la entrega.</p>
                  </div>
                </div>
              </div>

              <div class="tv_checkout_acciones">
                <button type="submit" class="tv_btn tv_btn_principal">Pagar pedido</button>
                <a href="/catalogo/" class="tv_btn tv_btn_secundario">Seguir comprando</a>
              </div>
              <div class="tv_checkout_legal">
                <p>Al continuar aceptas el procesamiento interno del pedido y la creación de la referencia del pago para seguimiento comercial.</p>
              </div>
            </form>
          </section>

          <aside class="tv_checkout_resumen">
            <div class="tv_checkout_resumen_bloque">
              <span class="tv_etiqueta">Resumen</span>
              <h3>Pedido actual</h3>
              <div class="tv_checkout_items">
                <?php foreach (($carrito['items'] ?? []) as $item) { ?>
                  <article class="tv_checkout_item">
                    <div class="tv_checkout_item_media">
                      <?php tienda_render_producto_media($item, 'tv_producto_media_checkout', true); ?>
                    </div>
                    <div class="tv_checkout_item_info">
                      <strong><?php echo htmlspecialchars((string) ($item['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                      <span><?php echo (int) ($item['cantidad'] ?? 1); ?> unidad(es)</span>
                      <span>$<?php echo number_format((int) ($item['total'] ?? 0), 0, ',', '.'); ?></span>
                    </div>
                  </article>
                <?php } ?>
              </div>
              <div class="tv_checkout_resumen_info">
                <article>
                  <span>Métodos habilitados</span>
                  <strong>PSE · Tarjeta · Contra entrega</strong>
                </article>
                <article>
                  <span>Despacho estimado</span>
                  <strong>1 a 8 días hábiles</strong>
                </article>
              </div>
              <div class="tv_checkout_totales">
                <div><span>Subtotal</span><strong>$<?php echo number_format((int) ($carrito['subtotal'] ?? 0), 0, ',', '.'); ?></strong></div>
                <div><span>Ahorro</span><strong>$<?php echo number_format((int) ($carrito['ahorro'] ?? 0), 0, ',', '.'); ?></strong></div>
                <div><span>Envío</span><strong>$<?php echo number_format((int) ($carrito['envio'] ?? 0), 0, ',', '.'); ?></strong></div>
                <div class="tv_resumen_total"><span>Total</span><strong>$<?php echo number_format((int) ($carrito['total'] ?? 0), 0, ',', '.'); ?></strong></div>
              </div>
            </div>
          </aside>
        </div>
      <?php } ?>
    </section>
  </main>

  <?php tienda_render_footer($branding, $menus); ?>
  <?php tienda_render_carrito_drawer($carrito); ?>
  <?php tienda_render_public_scripts(); ?>
  <script src="/public/assets/js/tienda_checkout_template.js"></script>
  <script src="/public/assets/js/tienda_checkout_peticiones.js"></script>
  <script src="/public/assets/js/tienda_checkout.js"></script>
</body>
</html>
