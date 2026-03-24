<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/configdb.php';

if (!empty($_SESSION['tienda_admin_usuario_id']) && configdb_validar_sesion_tienda_admin()) {
  header('Location: /admin/tienda/dashboard/');
  exit;
}

if (!empty($_SESSION['tienda_admin_usuario_id'])) {
  configdb_limpiar_sesion_tienda_admin();
}

if (empty($_SESSION['tienda_admin_token'])) {
  $_SESSION['tienda_admin_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ingreso administración tienda</title>
  <link rel="stylesheet" href="../../public/assets/css/tienda_admin_login.css">
</head>
<body class="tda_login_body">
  <input type="hidden" id="token" value="<?php echo htmlspecialchars($_SESSION['tienda_admin_token'], ENT_QUOTES, 'UTF-8'); ?>">
  <input type="hidden" id="controlador_tienda_admin_login" value="/app/Controllers/tienda_admin_login_controller.php">

  <main class="tda_login_principal">
    <section class="tda_login_panel">
      <div class="tda_login_info">
        <span class="tda_login_badge">Administración de tienda</span>
        <h1>Ingreso comercial</h1>
        <p>Acceso separado para categorías, productos, imágenes y operación visual de la tienda.</p>
        <ul class="tda_login_lista">
          <li>Panel distinto a parametrización y seguridad.</li>
          <li>Sesión propia para la operación comercial.</li>
          <li>Preparado para catálogo, imágenes y descuentos.</li>
        </ul>
      </div>

      <div class="tda_login_formulario_columna">
        <div class="tda_login_encabezado">
          <span class="tda_login_etiqueta">Inicio de sesión</span>
          <h2>Continuar</h2>
        </div>

        <div id="div_mensaje_tienda_admin_login" class="tda_login_mensaje" aria-live="polite"></div>

        <form id="formulario_tienda_admin_login" class="tda_login_formulario" autocomplete="off">
          <div class="tda_login_grupo">
            <label for="tienda_admin_login_usuario">Usuario</label>
            <input type="text" id="tienda_admin_login_usuario" name="tienda_admin_login_usuario" maxlength="60" placeholder="Ingrese el usuario" required>
          </div>

          <div class="tda_login_grupo">
            <label for="tienda_admin_login_clave">Clave</label>
            <div class="tda_login_campo_clave">
              <input type="password" id="tienda_admin_login_clave" name="tienda_admin_login_clave" maxlength="120" placeholder="Ingrese la clave" required>
              <button type="button" id="btn_ver_clave_tienda_admin_login" class="tda_btn_icono">Ver</button>
            </div>
          </div>

          <div class="tda_login_acciones">
            <button type="submit" id="btn_ingresar_tienda_admin_login" class="tda_btn tda_btn_principal">Ingresar</button>
            <button type="button" id="btn_limpiar_tienda_admin_login" class="tda_btn tda_btn_secundario">Limpiar</button>
          </div>
        </form>

        <div id="div_resumen_tienda_admin_login" class="tda_login_resumen"></div>
      </div>
    </section>
  </main>

  <script src="../../public/assets/js/tienda_admin_login_template.js"></script>
  <script src="../../public/assets/js/tienda_admin_login_peticiones.js"></script>
  <script src="../../public/assets/js/tienda_admin_login.js"></script>
</body>
</html>
