<?php
require_once __DIR__ . '/tienda/tienda_helper.php';
$contexto = $tv_datos['contexto'] ?? [];
$branding = $contexto['branding'] ?? [];
$menus = $contexto['menus'] ?? [];
$tema_tokens = $contexto['tema_tokens'] ?? [];
$componentes = $contexto['componentes'] ?? [];
$tema = $contexto['tema'] ?? [];
$carrito = $tv_datos['carrito'] ?? ['items' => [], 'subtotal' => 0, 'envio' => 0, 'total' => 0, 'ahorro' => 0];
$checkout_datos = $tv_datos['checkout_datos'] ?? [];
$modulo = $contexto['modulo'] ?? [];

$checkout_titulo = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_datos_titulo', 'Datos de entrega');
$checkout_descripcion = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_datos_descripcion', 'Completa la información del comprador y la entrega. El método de pago se selecciona en el siguiente paso.');
$checkout_boton = tienda_obtener_configuracion_modulo_publico($modulo, 'tienda_publica.checkout_datos_boton', 'Continuar al pago');

$campos_checkout = [
  'nombres' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_datos_nombres', [
    'label' => 'Nombres',
    'placeholder' => 'Ingresa los nombres',
    'visible' => true,
    'requerido' => true,
  ]),
  'apellidos' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_datos_apellidos', [
    'label' => 'Apellidos',
    'placeholder' => 'Ingresa los apellidos',
    'visible' => true,
    'requerido' => true,
  ]),
  'correo' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_datos_correo', [
    'label' => 'Correo',
    'placeholder' => 'nombre@correo.com',
    'visible' => true,
    'requerido' => true,
  ]),
  'celular' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_datos_celular', [
    'label' => 'Celular',
    'placeholder' => '3001234567',
    'visible' => true,
    'requerido' => true,
  ]),
  'destinatario' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_entrega_destinatario', [
    'label' => 'Destinatario',
    'placeholder' => 'Nombre de quien recibe',
    'visible' => true,
    'requerido' => true,
  ]),
  'telefono_direccion' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_entrega_telefono', [
    'label' => 'Teléfono dirección',
    'placeholder' => '3101234567',
    'visible' => true,
    'requerido' => true,
  ]),
  'direccion_linea_1' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_entrega_direccion', [
    'label' => 'Dirección principal',
    'placeholder' => 'Calle, carrera, número, barrio',
    'visible' => true,
    'requerido' => true,
  ]),
  'ciudad' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_entrega_ciudad', [
    'label' => 'Ciudad',
    'placeholder' => 'Ciudad',
    'visible' => true,
    'requerido' => true,
  ]),
  'departamento' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_entrega_departamento', [
    'label' => 'Departamento',
    'placeholder' => 'Departamento',
    'visible' => true,
    'requerido' => true,
  ]),
  'direccion_linea_2' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_entrega_complemento', [
    'label' => 'Complemento',
    'placeholder' => 'Apartamento, torre, interior',
    'visible' => true,
    'requerido' => false,
  ]),
  'codigo_postal' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_entrega_codigo_postal', [
    'label' => 'Código postal',
    'placeholder' => 'Código postal',
    'visible' => true,
    'requerido' => false,
  ]),
  'referencia' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_entrega_referencia', [
    'label' => 'Referencia',
    'placeholder' => 'Referencia de ubicación',
    'visible' => true,
    'requerido' => false,
  ]),
  'observacion' => tienda_obtener_definicion_campo_publico($modulo, 'tienda_publica.checkout_entrega_observacion', [
    'label' => 'Observación de entrega',
    'placeholder' => 'Instrucciones adicionales para el despacho',
    'visible' => true,
    'requerido' => false,
  ]),
];

tienda_render_head('Checkout · Datos del pedido', $tema_tokens, $componentes, $tema);
?>
<body>
  <?php tienda_render_topbar($contexto['modulo'] ?? []); ?>
  <?php tienda_render_header($branding, $menus, 'CHECKOUT', $tema); ?>
  <?php tienda_render_flash(); ?>

  <main class="tv_pagina_modulo tv_checkout_pagina">
    <section class="tv_bloque tv_bloque_checkout_amplio tv_checkout_intro">
      <div class="tv_checkout_intro_texto">
        <span class="tv_etiqueta">Checkout</span>
        <h1><?php echo htmlspecialchars((string) $checkout_titulo, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars((string) $checkout_descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
      </div>
      <div class="tv_checkout_pasos">
        <span class="tv_checkout_paso tv_checkout_paso_activo">1. Datos</span>
        <span class="tv_checkout_paso">2. Pago</span>
        <span class="tv_checkout_paso">3. Confirmación</span>
      </div>
    </section>

    <?php if (count($carrito['items'] ?? []) === 0) { ?>
      <div class="tv_bloque tv_bloque_checkout_amplio">
        <div class="tv_vacio">El carrito está vacío. Ve al <a href="/catalogo/">catálogo</a> para continuar.</div>
      </div>
    <?php } else { ?>
      <section class="tv_bloque tv_bloque_checkout_amplio tv_checkout_compacto">
        <form action="/checkout/" method="post" id="form_checkout_tienda_publica" class="tv_checkout_form_compacto">
          <input type="hidden" name="accion" value="guardar_checkout_datos">
          <input type="hidden" id="token_checkout_tienda_publica" value="<?php echo htmlspecialchars((string) ($_SESSION['tienda_publica_token'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" id="controlador_checkout_tienda_publica" value="/app/Controllers/tienda_checkout_controller.php">

          <article class="tv_checkout_panel tv_checkout_panel_formulario">
            <header class="tv_checkout_panel_header">
              <span class="tv_etiqueta">Comprador</span>
              <h3>Información principal</h3>
            </header>
            <div class="tv_checkout_grid tv_checkout_grid_dos">
              <?php if (($campos_checkout['nombres']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['nombres']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['nombres']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="nombres"<?php echo ($campos_checkout['nombres']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['nombres']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['nombres'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['apellidos']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['apellidos']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['apellidos']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="apellidos"<?php echo ($campos_checkout['apellidos']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['apellidos']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['apellidos'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['correo']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['correo']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['correo']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="email" name="correo"<?php echo ($campos_checkout['correo']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['correo']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['correo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['celular']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['celular']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['celular']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="celular"<?php echo ($campos_checkout['celular']['requerido'] ?? false) === true ? ' required' : ''; ?> inputmode="numeric" maxlength="10" placeholder="<?php echo htmlspecialchars((string) $campos_checkout['celular']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['celular'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
            </div>
          </article>

          <article class="tv_checkout_panel tv_checkout_panel_formulario">
            <header class="tv_checkout_panel_header">
              <span class="tv_etiqueta">Entrega</span>
              <h3>Dirección del pedido</h3>
            </header>
            <div class="tv_checkout_grid tv_checkout_grid_dos">
              <?php if (($campos_checkout['destinatario']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['destinatario']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['destinatario']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="destinatario"<?php echo ($campos_checkout['destinatario']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['destinatario']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['destinatario'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['telefono_direccion']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['telefono_direccion']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['telefono_direccion']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="telefono_direccion"<?php echo ($campos_checkout['telefono_direccion']['requerido'] ?? false) === true ? ' required' : ''; ?> inputmode="numeric" maxlength="10" placeholder="<?php echo htmlspecialchars((string) $campos_checkout['telefono_direccion']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['telefono_direccion'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['direccion_linea_1']['visible'] ?? false) === true) { ?>
                <label class="tv_checkout_campo_full">
                  <span><?php echo htmlspecialchars((string) $campos_checkout['direccion_linea_1']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['direccion_linea_1']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="direccion_linea_1"<?php echo ($campos_checkout['direccion_linea_1']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['direccion_linea_1']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['direccion_linea_1'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['ciudad']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['ciudad']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['ciudad']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="ciudad"<?php echo ($campos_checkout['ciudad']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['ciudad']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['ciudad'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['departamento']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['departamento']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['departamento']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="departamento"<?php echo ($campos_checkout['departamento']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['departamento']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['departamento'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['direccion_linea_2']['visible'] ?? false) === true) { ?>
                <label class="tv_checkout_campo_full">
                  <span><?php echo htmlspecialchars((string) $campos_checkout['direccion_linea_2']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['direccion_linea_2']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="direccion_linea_2"<?php echo ($campos_checkout['direccion_linea_2']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['direccion_linea_2']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['direccion_linea_2'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['codigo_postal']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['codigo_postal']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['codigo_postal']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="codigo_postal"<?php echo ($campos_checkout['codigo_postal']['requerido'] ?? false) === true ? ' required' : ''; ?> inputmode="numeric" maxlength="10" placeholder="<?php echo htmlspecialchars((string) $campos_checkout['codigo_postal']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['codigo_postal'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['referencia']['visible'] ?? false) === true) { ?>
                <label>
                  <span><?php echo htmlspecialchars((string) $campos_checkout['referencia']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['referencia']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <input type="text" name="referencia"<?php echo ($campos_checkout['referencia']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['referencia']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) ($checkout_datos['referencia'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                </label>
              <?php } ?>
              <?php if (($campos_checkout['observacion']['visible'] ?? false) === true) { ?>
                <label class="tv_checkout_campo_full">
                  <span><?php echo htmlspecialchars((string) $campos_checkout['observacion']['label'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($campos_checkout['observacion']['requerido'] ?? false) === true ? ' *' : ''; ?></span>
                  <textarea name="observacion" rows="3"<?php echo ($campos_checkout['observacion']['requerido'] ?? false) === true ? ' required' : ''; ?> placeholder="<?php echo htmlspecialchars((string) $campos_checkout['observacion']['placeholder'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) ($checkout_datos['observacion'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                </label>
              <?php } ?>
            </div>
            <div class="tv_checkout_acciones_compactas">
              <button type="submit" class="tv_btn tv_btn_principal"><?php echo htmlspecialchars((string) $checkout_boton, ENT_QUOTES, 'UTF-8'); ?></button>
              <a href="/carrito/" class="tv_btn tv_btn_secundario">Volver al carrito</a>
            </div>
          </article>
        </form>

        <aside class="tv_checkout_panel tv_checkout_panel_resumen">
          <header class="tv_checkout_panel_header">
            <span class="tv_etiqueta">Resumen</span>
            <h3>Pedido actual</h3>
          </header>
          <div class="tv_checkout_resumen_lista">
            <?php foreach (($carrito['items'] ?? []) as $item) { ?>
              <article class="tv_checkout_resumen_item">
                <div class="tv_checkout_resumen_media">
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
          <div class="tv_checkout_resumen_beneficios">
            <article><span>Despacho</span><strong>1 a 8 días hábiles</strong></article>
            <article><span>Métodos</span><strong>PSE · Tarjeta · Contra entrega</strong></article>
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
  <script src="/public/assets/js/tienda_checkout.js"></script>
</body>
</html>
