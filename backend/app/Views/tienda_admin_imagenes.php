<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

$tda_permiso_formulario = tienda_admin_usuario_tiene_permiso('TIENDA_IMAGENES_GUARDAR') || tienda_admin_usuario_tiene_permiso('TIENDA_IMAGENES_EDITAR');

tienda_admin_render_head('Admin tienda - Imágenes', $tda_tema ?? [], $tda_tema_tokens ?? [], $tda_componentes ?? []);
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo exclusivo para registrar, actualizar e inactivar galerías de producto.', $tda_branding ?? [], $tda_tema ?? []);
?>
      <section id="seccion_imagenes_tienda_admin" class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Visual</span>
            <h3>Imágenes</h3>
            <p>Gestiona únicamente el material visual del producto: archivo principal, galería y texto alternativo.</p>
          </div>
        </div>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_dos_columnas">
          <?php if ($tda_permiso_formulario) { ?>
          <article class="tda_admin_card_formulario">
            <div class="tda_admin_card_titulo_inline">
              <h4 id="titulo_formulario_tienda_admin_imagen">Registrar imagen</h4>
              <button type="button" id="btn_cancelar_edicion_tienda_admin_imagen" class="tda_btn tda_btn_secundario tda_admin_btn_oculto">Cancelar</button>
            </div>
            <form id="formulario_tienda_admin_imagen" class="tda_admin_formulario" enctype="multipart/form-data">
              <input type="hidden" id="tienda_admin_imagen_id">
              <div class="tda_admin_grupo">
                <label for="tienda_admin_imagen_producto_id" class="tda_admin_label_obligatorio">Producto</label>
                <select id="tienda_admin_imagen_producto_id" required></select>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_imagen_archivo" class="tda_admin_label_obligatorio">Archivo imagen</label>
                <input type="file" id="tienda_admin_imagen_archivo" accept="image/*">
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_imagen_texto_alternativo" class="tda_admin_label_obligatorio">Texto alternativo</label>
                <input type="text" id="tienda_admin_imagen_texto_alternativo" maxlength="180" required>
              </div>
              <button type="submit" id="btn_guardar_tienda_admin_imagen" class="tda_btn tda_btn_principal">Guardar imagen</button>
            </form>
          </article>
          <?php } ?>

          <article class="tda_admin_card_listado">
            <div class="tda_admin_card_titulo_inline">
              <h4>Listado de imágenes</h4>
              <a href="/admin/tienda/productos/" class="tda_btn tda_btn_secundario">Volver a productos</a>
            </div>
            <div id="div_listado_tienda_admin_imagenes"></div>
          </article>
        </div>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
