<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

$tda_permiso_formulario = tienda_admin_usuario_tiene_permiso('TIENDA_PRODUCTOS_GUARDAR') || tienda_admin_usuario_tiene_permiso('TIENDA_PRODUCTOS_EDITAR');

tienda_admin_render_head('Admin tienda - Productos');
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo para crear, editar e inactivar productos del catálogo sin romper el frente comercial.');
?>
      <section id="seccion_productos_tienda_admin" class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Comercial</span>
            <h3>Productos</h3>
            <p>Administra referencia, categoría, precios, stock, resumen, descripción y material principal de producto.</p>
          </div>
        </div>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_dos_columnas">
          <?php if ($tda_permiso_formulario) { ?>
          <article class="tda_admin_card_formulario">
            <div class="tda_admin_card_titulo_inline">
              <h4 id="titulo_formulario_tienda_admin_producto">Crear producto</h4>
              <button type="button" id="btn_cancelar_edicion_tienda_admin_producto" class="tda_btn tda_btn_secundario tda_admin_btn_oculto">Cancelar</button>
            </div>
            <form id="formulario_tienda_admin_producto" class="tda_admin_formulario" enctype="multipart/form-data">
              <input type="hidden" id="tienda_admin_producto_id">
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_categoria_id" class="tda_admin_label_obligatorio">Categoría</label>
                <select id="tienda_admin_producto_categoria_id" required></select>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_codigo" class="tda_admin_label_obligatorio">Código</label>
                <input type="text" id="tienda_admin_producto_codigo" maxlength="60" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_nombre" class="tda_admin_label_obligatorio">Nombre</label>
                <input type="text" id="tienda_admin_producto_nombre" maxlength="180" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_etiqueta">Etiqueta</label>
                <input type="text" id="tienda_admin_producto_etiqueta" maxlength="80">
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_resumen" class="tda_admin_label_obligatorio">Resumen</label>
                <input type="text" id="tienda_admin_producto_resumen" maxlength="255" required>
              </div>
              <div class="tda_admin_grupo tda_admin_grupo_doble">
                <div>
                  <label for="tienda_admin_producto_precio_base" class="tda_admin_label_obligatorio">Precio base</label>
                  <input type="number" id="tienda_admin_producto_precio_base" min="0" step="1" required>
                </div>
                <div>
                  <label for="tienda_admin_producto_precio_oferta" class="tda_admin_label_obligatorio">Precio oferta</label>
                  <input type="number" id="tienda_admin_producto_precio_oferta" min="0" step="1" required>
                </div>
              </div>
              <div class="tda_admin_grupo tda_admin_grupo_doble">
                <div>
                  <label for="tienda_admin_producto_stock" class="tda_admin_label_obligatorio">Stock</label>
                  <input type="number" id="tienda_admin_producto_stock" min="0" step="1" required>
                </div>
                <div>
                  <label for="tienda_admin_producto_rating" class="tda_admin_label_obligatorio">Rating</label>
                  <input type="number" id="tienda_admin_producto_rating" min="0" max="5" step="0.1" required>
                </div>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_descripcion" class="tda_admin_label_obligatorio">Descripción</label>
                <textarea id="tienda_admin_producto_descripcion" maxlength="1000" required></textarea>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_imagen_principal">Imagen principal producto</label>
                <input type="file" id="tienda_admin_producto_imagen_principal" accept="image/*">
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_texto_alternativo" class="tda_admin_label_obligatorio">Texto alternativo imagen</label>
                <input type="text" id="tienda_admin_producto_texto_alternativo" maxlength="180" required>
              </div>
              <button type="submit" id="btn_guardar_tienda_admin_producto" class="tda_btn tda_btn_principal">Guardar producto</button>
            </form>
          </article>
          <?php } ?>

          <article class="tda_admin_card_listado">
            <div class="tda_admin_card_titulo_inline">
              <h4>Listado de productos</h4>
              <a href="/admin/tienda/imagenes/" class="tda_btn tda_btn_secundario">Ir a imágenes</a>
            </div>
            <div id="div_listado_tienda_admin_productos"></div>
          </article>
        </div>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
