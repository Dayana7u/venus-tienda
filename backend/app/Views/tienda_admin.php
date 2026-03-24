<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/configdb.php';

if (empty($_SESSION['admin_usuario_id']) || !configdb_validar_sesion_administrativa()) {
  configdb_limpiar_sesion_administrativa();
  header('Location: ../login.php');
  exit;
}

$resumen = $tv_admin_datos['resumen'] ?? [];
$categorias = $tv_admin_datos['categorias'] ?? [];
$productos = $tv_admin_datos['productos'] ?? [];
$editar_producto = $tv_admin_datos['editar_producto'] ?? [];
$mensaje = $_SESSION['tienda_admin_mensaje'] ?? '';
unset($_SESSION['tienda_admin_mensaje']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda administrativa</title>
  <link rel="stylesheet" href="../../public/assets/css/parametrizacion.css">
  <link rel="stylesheet" href="../../public/assets/css/tienda_admin.css">
</head>
<body>
  <main class="dx_layout">
    <aside class="dx_sidebar">
      <div class="dx_sidebar_encabezado">
        <span class="dx_sidebar_badge">Administración</span>
        <h1>Tienda</h1>
        <p>Catálogo comercial, descuentos, stock e imágenes del frente público.</p>
      </div>

      <div class="dx_modulos_admin">
        <a href="../parametrizacion.php" class="dx_modulo_link">Parametrización</a>
        <a href="../seguridad.php" class="dx_modulo_link">Seguridad</a>
        <a href="/admin/tienda/" class="dx_modulo_link dx_modulo_link_activo">Tienda</a>
      </div>

      <nav class="dx_sidebar_nav">
        <a href="#resumen_tienda_admin">Resumen</a>
        <a href="#categorias_tienda_admin">Categorías</a>
        <a href="#productos_tienda_admin">Productos</a>
        <a href="#formulario_tienda_admin">Formulario</a>
      </nav>
    </aside>

    <section class="dx_contenido">
      <header class="dx_header">
        <div class="dx_header_identidad">
          <p class="dx_header_etiqueta">Panel comercial</p>
          <h2><?php echo htmlspecialchars($_SESSION['admin_usuario_nombre_completo'] ?? 'Administrador', ENT_QUOTES, 'UTF-8'); ?></h2>
        </div>

        <div class="dx_header_acciones">
          <a href="../parametrizacion.php" class="dx_btn dx_btn_secundario">Parametrización</a>
          <a href="../seguridad.php" class="dx_btn dx_btn_secundario">Seguridad</a>
          <a href="../../cerrar_sesion.php" class="dx_btn dx_btn_principal">Cerrar sesión</a>
        </div>
      </header>

      <?php if ($mensaje !== '') { ?>
        <section class="dx_mensajes dx_mensajes_fijo">
          <div class="dx_alerta_exito"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        </section>
      <?php } ?>

      <section id="resumen_tienda_admin" class="dx_resumen dx_resumen_tienda">
        <article class="dx_resumen_card"><span>Categorías</span><strong><?php echo (int) ($resumen['categorias'] ?? 0); ?></strong></article>
        <article class="dx_resumen_card"><span>Productos</span><strong><?php echo (int) ($resumen['productos'] ?? 0); ?></strong></article>
        <article class="dx_resumen_card"><span>Imágenes</span><strong><?php echo (int) ($resumen['imagenes'] ?? 0); ?></strong></article>
      </section>

      <section class="dx_secciones dx_tienda_admin_grid">
        <article class="dx_seccion" id="categorias_tienda_admin">
          <div class="dx_seccion_encabezado">
            <div>
              <p class="dx_header_etiqueta">Categorías</p>
              <h3>Categorías base</h3>
            </div>
          </div>
          <div class="dx_lista_cards">
            <?php foreach ($categorias as $categoria) { ?>
              <div class="dx_card_item">
                <div>
                  <strong><?php echo htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                  <p><?php echo htmlspecialchars($categoria['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div class="dx_card_meta">
                  <span><?php echo htmlspecialchars($categoria['linea'], ENT_QUOTES, 'UTF-8'); ?></span>
                  <span><?php echo ((string) ($categoria['estado'] ?? '0')) === '1' ? 'Activo' : 'Inactivo'; ?></span>
                </div>
              </div>
            <?php } ?>
          </div>

          <form action="/admin/tienda/" method="post" class="dx_formulario dx_formulario_card">
            <input type="hidden" name="accion" value="guardar_categoria">
            <h4>Nueva categoría</h4>
            <div class="dx_campos dx_campos_2">
              <label>Código<input type="text" name="codigo" required></label>
              <label>Nombre<input type="text" name="nombre" required></label>
              <label>Slug<input type="text" name="slug" required></label>
              <label>Línea
                <select name="linea" required>
                  <option value="maquillaje">maquillaje</option>
                  <option value="skincare">skincare</option>
                  <option value="accesorios">accesorios</option>
                </select>
              </label>
              <label>Orden<input type="number" name="orden" value="1" min="1"></label>
              <label>Descripción<textarea name="descripcion" rows="3"></textarea></label>
            </div>
            <div class="dx_formulario_acciones"><button type="submit" class="dx_btn dx_btn_principal">Guardar categoría</button></div>
          </form>
        </article>

        <article class="dx_seccion" id="productos_tienda_admin">
          <div class="dx_seccion_encabezado">
            <div>
              <p class="dx_header_etiqueta">Productos</p>
              <h3>Catálogo administrable</h3>
            </div>
          </div>
          <div class="dx_lista_cards dx_lista_cards_productos">
            <?php foreach ($productos as $producto) { ?>
              <div class="dx_card_item dx_card_producto">
                <div>
                  <strong><?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                  <p><?php echo htmlspecialchars($producto['categoria_nombre'], ENT_QUOTES, 'UTF-8'); ?> · <?php echo htmlspecialchars($producto['linea'], ENT_QUOTES, 'UTF-8'); ?></p>
                  <p><?php echo htmlspecialchars($producto['resumen'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div class="dx_card_meta dx_card_meta_producto">
                  <span><?php echo tienda_formatear_precio($producto['precio_oferta']); ?></span>
                  <span>Base <?php echo tienda_formatear_precio($producto['precio_base']); ?></span>
                  <span>Stock <?php echo (int) ($producto['stock'] ?? 0); ?></span>
                </div>
                <div class="dx_card_acciones">
                  <a href="/admin/tienda/?editar_producto_id=<?php echo (int) $producto['producto_id']; ?>#formulario_tienda_admin" class="dx_btn dx_btn_secundario">Editar</a>
                  <form action="/admin/tienda/" method="post">
                    <input type="hidden" name="accion" value="cambiar_estado_producto">
                    <input type="hidden" name="producto_id" value="<?php echo (int) $producto['producto_id']; ?>">
                    <button type="submit" class="dx_btn dx_btn_secundario"><?php echo ((string) ($producto['estado'] ?? '0')) === '1' ? 'Inactivar' : 'Activar'; ?></button>
                  </form>
                </div>
              </div>
            <?php } ?>
          </div>
        </article>
      </section>

      <section class="dx_seccion" id="formulario_tienda_admin">
        <div class="dx_seccion_encabezado">
          <div>
            <p class="dx_header_etiqueta">Formulario</p>
            <h3><?php echo !empty($editar_producto) ? 'Editar producto' : 'Nuevo producto'; ?></h3>
          </div>
        </div>
        <form action="/admin/tienda/" method="post" class="dx_formulario dx_formulario_card">
          <input type="hidden" name="accion" value="guardar_producto">
          <input type="hidden" name="producto_id" value="<?php echo (int) ($editar_producto['producto_id'] ?? 0); ?>">
          <div class="dx_campos dx_campos_3">
            <label>Categoría
              <select name="categoria_id" required>
                <option value="">Seleccione</option>
                <?php foreach ($categorias as $categoria) { ?>
                  <option value="<?php echo (int) $categoria['categoria_id']; ?>"<?php echo (int) ($editar_producto['categoria_id'] ?? 0) === (int) $categoria['categoria_id'] ? ' selected' : ''; ?>><?php echo htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8'); ?></option>
                <?php } ?>
              </select>
            </label>
            <label>Código<input type="text" name="codigo" value="<?php echo htmlspecialchars($editar_producto['codigo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
            <label>Nombre<input type="text" name="nombre" value="<?php echo htmlspecialchars($editar_producto['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
            <label>Slug<input type="text" name="slug" value="<?php echo htmlspecialchars($editar_producto['slug'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
            <label>Etiqueta<input type="text" name="etiqueta" value="<?php echo htmlspecialchars($editar_producto['etiqueta'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></label>
            <label>Resumen<input type="text" name="resumen" value="<?php echo htmlspecialchars($editar_producto['resumen'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></label>
            <label>Descripción<textarea name="descripcion" rows="4"><?php echo htmlspecialchars($editar_producto['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></label>
            <label>Precio base<input type="number" name="precio_base" value="<?php echo (int) ($editar_producto['precio_base'] ?? 0); ?>" min="0" step="1"></label>
            <label>Precio oferta<input type="number" name="precio_oferta" value="<?php echo (int) ($editar_producto['precio_oferta'] ?? 0); ?>" min="0" step="1"></label>
            <label>Rating<input type="number" name="rating_promedio" value="<?php echo htmlspecialchars($editar_producto['rating_promedio'] ?? '4.8', ENT_QUOTES, 'UTF-8'); ?>" min="0" max="5" step="0.1"></label>
            <label>Stock<input type="number" name="stock" value="<?php echo (int) ($editar_producto['stock'] ?? 0); ?>" min="0" step="1"></label>
            <label>Orden<input type="number" name="orden" value="<?php echo (int) ($editar_producto['orden'] ?? 1); ?>" min="1" step="1"></label>
            <label>Recurso visual<input type="text" name="recurso_visual" value="<?php echo htmlspecialchars($editar_producto['recurso_visual'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></label>
            <label>Imagen URL<input type="text" name="imagen_url" value="<?php echo htmlspecialchars($editar_producto['imagen_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></label>
          </div>
          <div class="dx_switches">
            <label><input type="checkbox" name="sw_destacado" value="1"<?php echo !empty($editar_producto) && ((string) ($editar_producto['sw_destacado'] ?? '0')) === '1' ? ' checked' : ''; ?>> Destacado</label>
            <label><input type="checkbox" name="sw_oferta" value="1"<?php echo !empty($editar_producto) && ((string) ($editar_producto['sw_oferta'] ?? '0')) === '1' ? ' checked' : ''; ?>> Oferta</label>
          </div>
          <div class="dx_formulario_acciones">
            <button type="submit" class="dx_btn dx_btn_principal">Guardar producto</button>
            <a href="/admin/tienda/#formulario_tienda_admin" class="dx_btn dx_btn_secundario">Limpiar</a>
          </div>
        </form>
      </section>
    </section>
  </main>
</body>
</html>
