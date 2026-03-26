<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

$tda_puede_editar_clientes = tienda_admin_usuario_tiene_permiso('TIENDA_CLIENTES_EDITAR');

tienda_admin_render_head('Admin tienda - Clientes', $tda_tema ?? [], $tda_tema_tokens ?? [], $tda_componentes ?? []);
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo comercial para revisar clientes registrados, contacto y actividad de compra.', $tda_branding ?? [], $tda_tema ?? []);
?>
      <section class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Clientes</span>
            <h3>Base de clientes de la tienda</h3>
            <p>Consulta clientes, correo, teléfono, ciudad, pedidos realizados y monto acumulado.</p>
          </div>
        </div>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_dos_columnas">
          <?php if ($tda_puede_editar_clientes) { ?>
          <article class="tda_admin_card_formulario">
            <div class="tda_admin_card_titulo_inline">
              <h4 id="titulo_formulario_tienda_admin_cliente">Editar cliente</h4>
              <button type="button" id="btn_cancelar_edicion_tienda_admin_cliente" class="tda_btn tda_btn_secundario tda_admin_btn_oculto">Cancelar</button>
            </div>
            <form id="formulario_tienda_admin_cliente" class="tda_admin_formulario" autocomplete="off">
              <input type="hidden" id="tienda_admin_cliente_id">
              <div class="tda_admin_grupo">
                <label for="tienda_admin_cliente_nombres" class="tda_admin_label_obligatorio">Nombres</label>
                <input type="text" id="tienda_admin_cliente_nombres" maxlength="120" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_cliente_apellidos" class="tda_admin_label_obligatorio">Apellidos</label>
                <input type="text" id="tienda_admin_cliente_apellidos" maxlength="120" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_cliente_correo" class="tda_admin_label_obligatorio">Correo</label>
                <input type="email" id="tienda_admin_cliente_correo" maxlength="180" required>
              </div>
              <div class="tda_admin_grupo tda_admin_grupo_doble">
                <div>
                  <label for="tienda_admin_cliente_celular">Celular</label>
                  <input type="text" id="tienda_admin_cliente_celular" maxlength="30">
                </div>
                <div>
                  <label for="tienda_admin_cliente_ciudad">Ciudad</label>
                  <input type="text" id="tienda_admin_cliente_ciudad" maxlength="120">
                </div>
              </div>
              <button type="submit" class="tda_btn tda_btn_principal">Guardar cliente</button>
            </form>
          </article>
          <?php } ?>

          <article class="tda_admin_card_listado">
            <div class="tda_admin_card_titulo_inline">
              <h4>Listado de clientes</h4>
              <a href="/admin/tienda/pedidos/" class="tda_btn tda_btn_secundario">Ir a pedidos</a>
            </div>
            <div id="div_listado_tienda_admin_clientes"></div>
          </article>
        </div>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
