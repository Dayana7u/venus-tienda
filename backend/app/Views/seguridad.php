<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (empty($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seguridad</title>
</head>
<body>
  <input type="hidden" id="token" value="<?php echo htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8'); ?>">

  <main id="modulo_seguridad">
    <section>
      <h1>Seguridad</h1>
      <p>Administración base de usuarios, roles y permisos.</p>
    </section>

    <section>
      <label for="buscar_seguridad">Buscar</label>
      <input
        type="text"
        id="buscar_seguridad"
        name="buscar_seguridad"
        placeholder="Filtrar registros"
        autocomplete="off"
      >
      <button type="button" id="btn_recargar_seguridad">Recargar</button>
    </section>

    <section>
      <article>
        <h2>Usuarios</h2>
        <div id="div_contenido_usuarios_seguridad"></div>
      </article>

      <article>
        <h2>Roles</h2>
        <div id="div_contenido_roles_seguridad"></div>
      </article>

      <article>
        <h2>Permisos</h2>
        <div id="div_contenido_permisos_seguridad"></div>
      </article>
    </section>
  </main>

  <script src="../../public/assets/js/seguridad_peticiones.js"></script>
  <script src="../../public/assets/js/seguridad.js"></script>
</body>
</html>
