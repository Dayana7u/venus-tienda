<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();

if (empty($_SESSION['token']))
  $_SESSION['token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parametrización</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      background: #f1f5f9;
      color: #0f172a;
    }
    .parametrizacion_contenedor {
      max-width: 1440px;
      margin: 0 auto;
      padding: 24px;
    }
    .parametrizacion_tarjeta {
      background: #ffffff;
      border: 1px solid #d7dce2;
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(15, 23, 42, 0.06);
      overflow: hidden;
      margin-bottom: 20px;
    }
    .parametrizacion_tarjeta_header {
      padding: 18px 24px;
      border-bottom: 1px solid #e2e8f0;
      background: #ffffff;
    }
    .parametrizacion_tarjeta_body {
      padding: 24px;
    }
    .parametrizacion_h1 {
      margin: 0 0 8px 0;
      font-size: 28px;
    }
    .parametrizacion_subtitulo {
      margin: 0;
      color: #475569;
      line-height: 1.5;
    }
    .parametrizacion_acciones {
      display: grid;
      grid-template-columns: minmax(280px, 1fr) auto;
      gap: 12px;
      align-items: end;
    }
    .parametrizacion_label {
      display: block;
      margin-bottom: 6px;
      font-size: 13px;
      font-weight: 700;
      color: #334155;
    }
    .parametrizacion_input {
      width: 100%;
      min-height: 44px;
      padding: 10px 12px;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      box-sizing: border-box;
      font-size: 14px;
      background: #ffffff;
    }
    .parametrizacion_boton {
      min-height: 44px;
      padding: 10px 18px;
      border: 0;
      border-radius: 10px;
      background: #0f172a;
      color: #ffffff;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
    }
    .parametrizacion_estado {
      padding: 12px 16px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 700;
      background: #e2e8f0;
      color: #0f172a;
    }
    .parametrizacion_estado[data-tipo="success"] {
      background: #dcfce7;
      color: #166534;
    }
    .parametrizacion_estado[data-tipo="error"] {
      background: #fee2e2;
      color: #991b1b;
    }
    .parametrizacion_grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
      gap: 16px;
    }
    .parametrizacion_seccion {
      scroll-margin-top: 20px;
    }
    @media (max-width: 768px) {
      .parametrizacion_contenedor {
        padding: 16px;
      }
      .parametrizacion_acciones {
        grid-template-columns: 1fr;
      }
      .parametrizacion_tarjeta_body,
      .parametrizacion_tarjeta_header {
        padding: 18px;
      }
    }
  </style>
</head>
<body>
  <input
    value="<?php echo htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8'); ?>"
    type="hidden"
    name="token"
    id="token"
  >
  <div class="parametrizacion_contenedor">
    <section class="parametrizacion_tarjeta">
      <div class="parametrizacion_tarjeta_header">
        <h1 class="parametrizacion_h1">Parametrización</h1>
        <p class="parametrizacion_subtitulo">Vista base para consultar la parametrización del esquema <strong>system</strong> y su trazabilidad técnica.</p>
      </div>
      <div class="parametrizacion_tarjeta_body">
        <div class="parametrizacion_acciones">
          <div>
            <label class="parametrizacion_label" for="campo_buscar_parametrizacion">Buscar en la parametrización</label>
            <input
              class="parametrizacion_input"
              type="text"
              name="campo_buscar_parametrizacion"
              id="campo_buscar_parametrizacion"
              placeholder="Filtrar registros por cualquier columna"
              autocomplete="off"
            >
          </div>
          <div>
            <button class="parametrizacion_boton" type="button" id="btn_recargar_parametrizacion">Actualizar</button>
          </div>
        </div>
      </div>
    </section>

    <section class="parametrizacion_tarjeta">
      <div class="parametrizacion_tarjeta_body">
        <div class="parametrizacion_estado" id="div_estado_parametrizacion" data-tipo="info">Esperando carga inicial...</div>
      </div>
    </section>

    <section class="parametrizacion_tarjeta">
      <div class="parametrizacion_tarjeta_header">
        <h2 class="parametrizacion_h1" style="font-size:22px;margin:0;">Resumen general</h2>
      </div>
      <div class="parametrizacion_tarjeta_body">
        <div id="div_resumen_parametrizacion"></div>
      </div>
    </section>

    <section class="parametrizacion_grid">
      <article class="parametrizacion_seccion" id="article_temas_parametrizacion">
        <div id="div_contenido_temas_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_tema_tokens_parametrizacion">
        <div id="div_contenido_tema_tokens_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_tema_componentes_parametrizacion">
        <div id="div_contenido_tema_componentes_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_branding_parametrizacion">
        <div id="div_contenido_branding_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_parametro_grupos_parametrizacion">
        <div id="div_contenido_parametro_grupos_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_parametros_parametrizacion">
        <div id="div_contenido_parametros_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_parametro_valores_parametrizacion">
        <div id="div_contenido_parametro_valores_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_modulos_parametrizacion">
        <div id="div_contenido_modulos_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_modulo_configuraciones_parametrizacion">
        <div id="div_contenido_modulo_configuraciones_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_integraciones_parametrizacion">
        <div id="div_contenido_integraciones_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_integracion_configuraciones_parametrizacion">
        <div id="div_contenido_integracion_configuraciones_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_plantillas_parametrizacion">
        <div id="div_contenido_plantillas_parametrizacion"></div>
      </article>
      <article class="parametrizacion_seccion" id="article_menus_parametrizacion">
        <div id="div_contenido_menus_parametrizacion"></div>
      </article>
    </section>
  </div>
  <script type="text/javascript" src="../../public/assets/js/parametrizacion_peticiones.js"></script>
  <script type="text/javascript" src="../../public/assets/js/parametrizacion_template.js"></script>
  <script type="text/javascript" src="../../public/assets/js/parametrizacion.js"></script>
</body>
</html>
