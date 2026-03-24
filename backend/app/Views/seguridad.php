<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/configdb.php';

if (empty($_SESSION['admin_usuario_id']) || !configdb_validar_sesion_administrativa()) {
  configdb_limpiar_sesion_administrativa();
  header('Location: login.php');
  exit;
}

if (empty($_SESSION['admin_token'])) {
  $_SESSION['admin_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seguridad administrativa</title>
  <link rel="stylesheet" href="../../public/assets/css/seguridad.css">
</head>
<body>
  <input type="hidden" id="token" value="<?php echo htmlspecialchars($_SESSION['admin_token'], ENT_QUOTES, 'UTF-8'); ?>">
  <input type="hidden" id="usuario_actual_id" value="<?php echo (int) ($_SESSION['admin_usuario_id'] ?? 0); ?>">
  <input type="hidden" id="usuario_sesion_actual" value="<?php echo (int) ($_SESSION['admin_usuario_sesion_id'] ?? 0); ?>">

  <main class="dx_layout">
    <aside class="dx_sidebar" id="dx_sidebar_seguridad">
      <div class="dx_sidebar_encabezado">
        <span class="dx_sidebar_badge">Administración</span>
        <h1>Seguridad</h1>
        <p>Control de sesiones, accesos recientes y actualización de claves administrativas.</p>
      </div>

      <div class="dx_modulos_admin">
        <a href="parametrizacion.php" class="dx_modulo_link">Parametrización</a>
        <a href="seguridad.php" class="dx_modulo_link dx_modulo_link_activo">Seguridad</a>
        <a href="/admin/tienda/" class="dx_modulo_link">Tienda</a>
      </div>

      <nav class="dx_sidebar_nav" id="dx_sidebar_nav_seguridad">
        <a href="#seccion_resumen_seguridad" data-menu-link="true">Resumen</a>
        <a href="#seccion_sesiones_activas" data-menu-link="true">Sesiones activas</a>
        <a href="#seccion_historial_seguridad" data-menu-link="true">Accesos recientes</a>
        <a href="#seccion_claves_seguridad" data-menu-link="true">Cambio de claves</a>
      </nav>
    </aside>

    <button type="button" id="btn_menu_seguridad" class="dx_menu_movil" aria-label="Abrir menú">Menú</button>
    <div id="dx_sidebar_backdrop_seguridad" class="dx_sidebar_backdrop dx_oculto"></div>

    <section class="dx_contenido">
      <header class="dx_header">
        <div class="dx_header_identidad">
          <p class="dx_header_etiqueta">Usuario activo</p>
          <h2><?php echo htmlspecialchars($_SESSION['admin_usuario_nombre_completo'] ?? 'Administrador', ENT_QUOTES, 'UTF-8'); ?></h2>
        </div>

        <div class="dx_header_acciones">
          <input type="text" id="buscar_seguridad" placeholder="Buscar sesiones o accesos" autocomplete="off">
          <button type="button" id="btn_recargar_seguridad" class="dx_btn dx_btn_secundario">Recargar</button>
          <button type="button" id="btn_cerrar_otras_sesiones" class="dx_btn dx_btn_secundario">Cerrar otras sesiones</button>
          <a href="parametrizacion.php" class="dx_btn dx_btn_secundario">Parametrización</a>
          <a href="/admin/tienda/" class="dx_btn dx_btn_secundario">Tienda</a>
          <a href="../../cerrar_sesion.php" class="dx_btn dx_btn_principal">Cerrar sesión</a>
        </div>
      </header>

      <section id="div_mensaje_seguridad" class="dx_mensajes" aria-live="polite"></section>
      <section id="div_resumen_seguridad" class="dx_resumen"></section>

      <section id="div_secciones_seguridad" class="dx_secciones">
        <article id="seccion_resumen_seguridad" class="dx_seccion dx_seccion_compacta">
          <div class="dx_seccion_encabezado">
            <div>
              <span class="dx_header_etiqueta">Resumen</span>
              <h3>Control del panel administrativo</h3>
              <p>Seguimiento centralizado de sesiones abiertas, accesos recientes y administración de claves.</p>
            </div>
          </div>
        </article>

        <article id="seccion_sesiones_activas" class="dx_seccion">
          <div class="dx_seccion_encabezado">
            <div>
              <span class="dx_header_etiqueta">Sesiones</span>
              <h3>Sesiones activas</h3>
              <p>Sesiones vigentes del panel con opción para cerrar sesiones abiertas desde otros equipos o navegadores.</p>
            </div>
          </div>

          <div id="div_sesiones_activas_seguridad"></div>
        </article>

        <article id="seccion_historial_seguridad" class="dx_seccion">
          <div class="dx_seccion_encabezado">
            <div>
              <span class="dx_header_etiqueta">Histórico</span>
              <h3>Accesos recientes</h3>
              <p>Relación de sesiones registradas con su estado, fecha de inicio y último cambio disponible.</p>
            </div>
          </div>

          <div id="div_historial_seguridad"></div>
        </article>

        <article id="seccion_claves_seguridad" class="dx_seccion">
          <div class="dx_seccion_encabezado">
            <div>
              <span class="dx_header_etiqueta">Claves</span>
              <h3>Cambio de claves</h3>
              <p>Actualización de claves encriptadas para usuarios administrativos con cierre automático de sesiones relacionadas.</p>
            </div>
          </div>

          <form id="formulario_clave_seguridad" class="dx_formulario dx_formulario_seguridad" autocomplete="off">
            <div class="dx_campos">
              <div class="dx_campo dx_campo_completo">
                <label for="seguridad_usuario_id">Usuario</label>
                <select id="seguridad_usuario_id" name="usuario_id" required></select>
                <small>Seleccione el usuario al cual se le actualizará la clave.</small>
              </div>

              <div class="dx_campo">
                <label for="seguridad_clave_nueva">Nueva clave</label>
                <input type="password" id="seguridad_clave_nueva" name="clave_nueva" placeholder="Nueva clave" minlength="8" required>
              </div>

              <div class="dx_campo">
                <label for="seguridad_clave_confirmacion">Confirmación</label>
                <input type="password" id="seguridad_clave_confirmacion" name="clave_confirmacion" placeholder="Confirme la nueva clave" minlength="8" required>
              </div>
            </div>

            <div class="dx_formulario_acciones">
              <button type="submit" id="btn_guardar_clave_seguridad" class="dx_btn dx_btn_principal">Actualizar clave</button>
              <button type="button" id="btn_limpiar_clave_seguridad" class="dx_btn dx_btn_secundario">Limpiar</button>
            </div>
          </form>
        </article>
      </section>
    </section>
  </main>

  <script src="../../public/assets/js/seguridad_template.js"></script>
  <script src="../../public/assets/js/seguridad_peticiones.js"></script>
  <script src="../../public/assets/js/seguridad.js"></script>
</body>
</html>
