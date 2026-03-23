<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();

if (empty($_SESSION['token']))
  $_SESSION['token'] = bin2hex(random_bytes(32));

$token = $_SESSION['token'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parametrización</title>
</head>
<body class="bg-light">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="card">
          <div class="card-body px-3 px-md-4">
            <input
              value = "<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>"
              type  = "hidden"
              name  = "token"
              id    = "token"
            >
            <div class="card mb-3">
              <div class="card-header bg-orange text-white px-3 p-2 text-start d-flex justify-content-start">
                <span class="material-icons pe-1">tune</span>
                <text>Parametrización</text>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 mb-3">
                    <p class="mb-0">Consulta base del esquema system para temas, branding, parámetros, módulos, integraciones y menús.</p>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-xxl-4 mb-3">
                    <div class="d-flex gap-2 justify-content-lg-end">
                      <button
                        class = "btn btn-sm btn-success d-flex align-items-center"
                        type  = "button"
                        id    = "btn_recargar_parametrizacion"
                      >
                        <span class="material-icons fs-6 pe-1">refresh</span>
                        <span>Recargar</span>
                      </button>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-3">
                    <div class="form-outline">
                      <input
                        autocomplete = "off"
                        class        = "form-control form-control-sm"
                        type         = "text"
                        name         = "buscar_parametrizacion"
                        id           = "buscar_parametrizacion"
                      >
                      <label class="form-label small" for="buscar_parametrizacion">Buscar</label>
                    </div>
                  </div>
                  <div class="col-12 mb-3" id="div_resumen_parametrizacion"></div>
                  <div class="col-12 mb-3" id="div_contenido_temas_parametrizacion"></div>
                  <div class="col-12 mb-3" id="div_contenido_branding_parametrizacion"></div>
                  <div class="col-12 mb-3" id="div_contenido_parametros_parametrizacion"></div>
                  <div class="col-12 mb-3" id="div_contenido_modulos_parametrizacion"></div>
                  <div class="col-12 mb-3" id="div_contenido_integraciones_parametrizacion"></div>
                  <div class="col-12 mb-3" id="div_contenido_menus_parametrizacion"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../../public/assets/js/parametrizacion_peticiones.js"></script>
  <script src="../../public/assets/js/parametrizacion_template.js"></script>
  <script src="../../public/assets/js/parametrizacion.js"></script>
</body>
</html>
