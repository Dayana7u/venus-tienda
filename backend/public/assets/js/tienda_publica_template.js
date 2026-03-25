/**
 * Función encargada de generar la alerta visual del frente público.
 *
 * @param      string  tipo     Tipo de alerta.
 * @param      string  mensaje  Mensaje visible.
 *
 * @return     string  Estructura HTML.
 */
function template_alerta_tienda_publica(tipo, mensaje) {
  return `
    <div class="tv_alerta tv_alerta_${tipo}">
      <span>${mensaje}</span>
      <button type="button" data-alerta-cerrar="true" aria-label="Cerrar alerta">×</button>
    </div>`;
}
/**
 * Función encargada de generar la barra superior informativa.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_topbar_tienda_publica(modulo) {
  const mensaje = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.topbar_texto`,
    `Despachos a todo Colombia · Maquillaje, skincare y accesorios con imagen profesional.`
  );

  return `<div class="tv_topbar_contenido">${mensaje}</div>`;
}
/**
 * Función encargada de generar el hero del frente público.
 *
 * @param      object  branding    Datos de branding.
 * @param      object  modulo      Configuraciones del módulo.
 * @param      object  parametros  Parámetros públicos.
 *
 * @return     string  Estructura HTML.
 */
function template_hero_tienda_publica(branding, modulo, parametros) {
  const nombre_comercial = branding.nombre_comercial || parametros[`app.nombre`] || `Tienda Virtual`;
  const tema_activo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.tema_activo`,
    `PINK_NUDE`
  );
  const hero_etiqueta = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_etiqueta`,
    `Beauty commerce`
  );
  const hero_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_titulo`,
    `Belleza cotidiana con vitrina limpia, femenina y comercial`
  );
  const hero_descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_descripcion`,
    `Portada base enfocada en maquillaje, skincare, accesorios y campañas visuales que luego podrán duplicarse a otros temas sin romper la lógica.`
  );
  const hero_boton_primario = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_boton_primario`,
    `Comprar novedades`
  );
  const hero_boton_secundario = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_boton_secundario`,
    `Ver skincare`
  );
  const hero_item_1 = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_item_1`,
    `Envíos a todo Colombia`
  );
  const hero_item_2 = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_item_2`,
    `Pago seguro y acompañamiento cercano`
  );
  const hero_item_3 = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_item_3`,
    `Tema visual parametrizable`
  );
  const hero_panel_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_panel_titulo`,
    `Base lista para maquillaje, skincare y accesorios`
  );
  const hero_panel_texto = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_panel_texto`,
    `La vista pública toma branding, tema, menús y configuraciones desde base de datos sin tocar la lógica comercial.`
  );

  return `
    <div class="tv_hero_contenido">
      <div class="tv_hero_texto">
        <span class="tv_etiqueta">${hero_etiqueta}</span>
        <h1>${hero_titulo}</h1>
        <p>${hero_descripcion}</p>
        <div class="tv_hero_acciones">
          <a href="#seccion_catalogo_publico" class="tv_btn tv_btn_principal">${hero_boton_primario}</a>
          <a href="#seccion_contacto_publico" class="tv_btn tv_btn_secundario">${hero_boton_secundario}</a>
        </div>
        <ul class="tv_hero_datos">
          <li>${hero_item_1}</li>
          <li>${hero_item_2}</li>
          <li>${hero_item_3}</li>
        </ul>
      </div>
      <div class="tv_hero_visual">
        <article class="tv_hero_panel">
          <span class="tv_etiqueta">Tema activo ${tema_activo}</span>
          <h3>${nombre_comercial}</h3>
          <p>${hero_panel_texto}</p>
          <div class="tv_hero_mosaico">
            <div class="tv_hero_mosaico_tarjeta tv_hero_mosaico_tarjeta_uno">
              <span>Makeup</span>
            </div>
            <div class="tv_hero_mosaico_tarjeta tv_hero_mosaico_tarjeta_dos">
              <span>Skin</span>
            </div>
            <div class="tv_hero_mosaico_tarjeta tv_hero_mosaico_tarjeta_tres">
              <span>Glow</span>
            </div>
          </div>
          <strong class="tv_hero_respaldo">${hero_panel_titulo}</strong>
        </article>
      </div>
    </div>`;
}
/**
 * Función encargada de generar el menú principal público.
 *
 * @param      array  menus  Listado de menús visibles.
 *
 * @return     string  Estructura HTML.
 */
function template_menu_tienda_publica(menus) {
  if (!Array.isArray(menus) || menus.length === 0) {
    return `<a href="#seccion_inicio_publico" class="tv_nav_link">Inicio</a>`;
  }

  return menus.map(function(menu) {
    return `<a href="${obtener_ancla_menu_tienda_publica(menu.codigo)}" class="tv_nav_link">${menu.nombre}</a>`;
  }).join(``);
}
/**
 * Función encargada de generar el bloque de beneficios del frente público.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_beneficios_tienda_publica(modulo) {
  const configuraciones = modulo.configuraciones || {};
  const items = [
    {
      titulo      : `Tema y branding activos`,
      descripcion : `La portada toma colores, tipografías, menús y textos desde la parametrización ya registrada.`
    },
    {
      titulo      : `Base reusable por cards`,
      descripcion : `El frente queda listo para crecer por bloques reutilizables sin depender de modales.`
    },
    {
      titulo      : `Controles visibles`,
      descripcion : `Buscador: ${obtener_texto_estado_configuracion_tienda_publica(configuraciones, `tienda_publica.mostrar_buscador`)} · Carrito: ${obtener_texto_estado_configuracion_tienda_publica(configuraciones, `tienda_publica.mostrar_carrito`)}.`
    }
  ];

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Experiencia base</span>
      <h2>Vista comercial separada del panel administrativo</h2>
      <p>La tienda pública mantiene una base limpia y profesional para cosmética, skincare y accesorios.</p>
    </div>
    <div class="tv_grid_beneficios">
      ${items.map(function(item) {
        return `
          <article class="tv_tarjeta_info">
            <h3>${item.titulo}</h3>
            <p>${item.descripcion}</p>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de colecciones curadas.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_colecciones_tienda_publica(modulo) {
  const titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.colecciones_titulo`,
    `Colecciones curadas para una vitrina beauty más profesional`
  );
  const descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.colecciones_descripcion`,
    `Este bloque organiza la portada en campañas visuales fáciles de reutilizar cuando cambie la temporada o el enfoque comercial.`
  );
  const colecciones = [1, 2, 3].map(function(indice) {
    return {
      etiqueta    : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.coleccion_${indice}_etiqueta`, `Colección ${indice}`),
      titulo      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.coleccion_${indice}_titulo`, `Colección ${indice}`),
      descripcion : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.coleccion_${indice}_descripcion`, `Descripción base de la colección.`),
      boton       : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.coleccion_${indice}_boton`, `Explorar`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Colecciones</span>
      <h2>${titulo}</h2>
      <p>${descripcion}</p>
    </div>
    <div class="tv_grid_colecciones">
      ${colecciones.map(function(coleccion, indice) {
        return `
          <article class="tv_coleccion_card tv_coleccion_card_${indice + 1}">
            <span class="tv_etiqueta">${coleccion.etiqueta}</span>
            <h3>${coleccion.titulo}</h3>
            <p>${coleccion.descripcion}</p>
            <a href="#seccion_catalogo_publico" class="tv_btn tv_btn_secundario">${coleccion.boton}</a>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de líneas principales del catálogo.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_lineas_tienda_publica(modulo) {
  const mostrar_lineas = validar_configuracion_tienda_publica(
    modulo.configuraciones || {},
    `tienda_publica.mostrar_lineas_producto`
  );

  if (mostrar_lineas !== true) {
    return ``;
  }

  const titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.lineas_titulo`,
    `Compra por línea principal`
  );
  const descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.lineas_descripcion`,
    `Sección pensada para que la tienda ya empiece a verse como un catálogo real por línea de producto.`
  );
  const lineas = [1, 2, 3].map(function(indice) {
    return {
      etiqueta    : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.linea_${indice}_etiqueta`, `Línea ${indice}`),
      titulo      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.linea_${indice}_titulo`, `Título línea ${indice}`),
      descripcion : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.linea_${indice}_descripcion`, `Descripción base.`),
      item_1      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.linea_${indice}_item_1`, `Beneficio uno`),
      item_2      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.linea_${indice}_item_2`, `Beneficio dos`),
      item_3      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.linea_${indice}_item_3`, `Beneficio tres`),
      boton       : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.linea_${indice}_boton`, `Explorar`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Líneas</span>
      <h2>${titulo}</h2>
      <p>${descripcion}</p>
    </div>
    <div class="tv_grid_lineas">
      ${lineas.map(function(linea, indice) {
        return template_linea_card_tienda_publica(linea, indice + 1);
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de categorías destacadas.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_categorias_tienda_publica(modulo) {
  const categorias = [1, 2, 3, 4].map(function(indice) {
    return {
      etiqueta    : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.categoria_${indice}_etiqueta`, `Colección ${indice}`),
      titulo      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.categoria_${indice}_titulo`, `Categoría ${indice}`),
      descripcion : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.categoria_${indice}_descripcion`, `Descripción base de la categoría.`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Categorías</span>
      <h2>Bloques pensados para belleza y cuidado personal</h2>
      <p>La portada ya toma un enfoque más profesional para venta de maquillaje, skincare, accesorios y referencias de temporada.</p>
    </div>
    <div class="tv_grid_categorias">
      ${categorias.map(function(categoria, indice) {
        return `
          <article class="tv_categoria_card">
            <div class="tv_categoria_media tv_categoria_media_${indice + 1}"></div>
            <span class="tv_etiqueta">${categoria.etiqueta}</span>
            <h3>${categoria.titulo}</h3>
            <p>${categoria.descripcion}</p>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de productos destacados.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_destacados_tienda_publica(modulo) {
  const boton = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.destacado_boton`,
    `Ver detalle`
  );
  const productos = [1, 2, 3, 4].map(function(indice) {
    return {
      categoria       : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_categoria`, `Destacado`),
      nombre          : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_nombre`, `Producto ${indice}`),
      descripcion     : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_descripcion`, `Descripción breve del producto.`),
      precio          : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_precio`, `$0`),
      precio_anterior : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_precio_anterior`, ``),
      rating          : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_rating`, `4.9`),
      envio           : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_envio`, `Envío rápido`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Selección</span>
      <h2>Productos destacados con apariencia más comercial</h2>
      <p>Queda lista una grilla profesional para conectar más adelante el catálogo real desde base de datos.</p>
    </div>
    <div class="tv_grid_productos">
      ${productos.map(function(producto, indice) {
        return template_producto_card_tienda_publica(producto, indice + 1, boton);
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de más vendidos.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_mas_vendidos_tienda_publica(modulo) {
  const mostrar_mas_vendidos = validar_configuracion_tienda_publica(
    modulo.configuraciones || {},
    `tienda_publica.mostrar_mas_vendidos`
  );

  if (mostrar_mas_vendidos !== true) {
    return ``;
  }

  const titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.mas_vendidos_titulo`,
    `Más vendidos de la semana`
  );
  const descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.mas_vendidos_descripcion`,
    `Este bloque ya acerca el frente a una tienda real con tarjetas de producto, rating, precio y foco comercial.`
  );
  const boton = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.mas_vendidos_boton`,
    `Agregar pronto`
  );
  const productos = [1, 2, 3, 4, 5, 6].map(function(indice) {
    return {
      etiqueta       : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.mas_vendido_${indice}_etiqueta`, `Top venta`),
      nombre         : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.mas_vendido_${indice}_nombre`, `Producto ${indice}`),
      descripcion    : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.mas_vendido_${indice}_descripcion`, `Descripción base del producto.`),
      precio         : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.mas_vendido_${indice}_precio`, `$0`),
      precio_anterior: obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.mas_vendido_${indice}_precio_anterior`, ``),
      rating         : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.mas_vendido_${indice}_rating`, `4.9`),
      envio          : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.mas_vendido_${indice}_envio`, `Envío nacional`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Catálogo</span>
      <h2>${titulo}</h2>
      <p>${descripcion}</p>
    </div>
    <div class="tv_grid_mas_vendidos">
      ${productos.map(function(producto, indice) {
        return template_producto_comercial_tienda_publica(producto, indice + 1, boton);
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de rutina visual.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_rutina_tienda_publica(modulo) {
  const pasos = [1, 2, 3].map(function(indice) {
    return {
      titulo      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.rutina_${indice}_titulo`, `Paso ${indice}`),
      descripcion : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.rutina_${indice}_descripcion`, `Descripción base del paso.`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Rutina</span>
      <h2>Lectura rápida del recorrido de compra beauty</h2>
      <p>Se mantiene una sección por cards para presentar combinación de skincare, maquillaje y cierre de compra sin usar modales.</p>
    </div>
    <div class="tv_grid_rutina">
      ${pasos.map(function(paso, indice) {
        return `
          <article class="tv_rutina_card">
            <span class="tv_rutina_numero">0${indice + 1}</span>
            <h3>${paso.titulo}</h3>
            <p>${paso.descripcion}</p>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de ofertas del frente público.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_ofertas_tienda_publica(modulo) {
  const oferta_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.oferta_titulo`,
    `Promociones por temporada`
  );
  const oferta_descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.oferta_descripcion`,
    `Este espacio queda listo para campañas visuales como navidad, san valentín o liquidaciones.`
  );
  const oferta_secundaria_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.oferta_secundaria_titulo`,
    `Cambios visuales sin tocar la lógica`
  );
  const oferta_secundaria_descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.oferta_secundaria_descripcion`,
    `La tienda puede variar apariencia por tema y mantener la misma base administrativa y comercial.`
  );

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Campañas</span>
      <h2>${oferta_titulo}</h2>
      <p>La visibilidad del bloque responde a la configuración activa del módulo público.</p>
    </div>
    <div class="tv_grid_ofertas">
      <article class="tv_tarjeta_oferta">
        <span class="tv_etiqueta">Oferta base</span>
        <h3>${oferta_titulo}</h3>
        <p>${oferta_descripcion}</p>
      </article>
      <article class="tv_tarjeta_oferta">
        <span class="tv_etiqueta">Tema activo</span>
        <h3>${oferta_secundaria_titulo}</h3>
        <p>${oferta_secundaria_descripcion}</p>
      </article>
    </div>`;
}
/**
 * Función encargada de generar el bloque de testimonios del frente público.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_testimonios_tienda_publica(modulo) {
  const testimonios = [1, 2].map(function(indice) {
    return {
      nombre : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.testimonio_${indice}_nombre`, `Cliente ${indice}`),
      texto  : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.testimonio_${indice}_texto`, `Comentario base de experiencia.`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Confianza</span>
      <h2>Respaldo visual para una tienda más profesional</h2>
      <p>Se mantiene la línea femenina y clara del tema actual, pero con una composición más cercana a una tienda beauty real.</p>
    </div>
    <div class="tv_grid_testimonios">
      ${testimonios.map(function(testimonio) {
        return `
          <article class="tv_testimonio_card">
            <div class="tv_testimonio_estrellas">★★★★★</div>
            <p>“${testimonio.texto}”</p>
            <strong>${testimonio.nombre}</strong>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de preguntas frecuentes.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_faq_tienda_publica(modulo) {
  const mostrar_faq = validar_configuracion_tienda_publica(
    modulo.configuraciones || {},
    `tienda_publica.mostrar_faq`
  );

  if (mostrar_faq !== true) {
    return ``;
  }

  const titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.faq_titulo`,
    `Preguntas que refuerzan confianza de compra`
  );
  const descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.faq_descripcion`,
    `Queda un bloque listo para dudas frecuentes sobre envíos, pagos y tiempos de despacho.`
  );
  const preguntas = [1, 2, 3].map(function(indice) {
    return {
      pregunta  : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.faq_${indice}_pregunta`, `Pregunta ${indice}`),
      respuesta : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.faq_${indice}_respuesta`, `Respuesta base.`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">FAQ</span>
      <h2>${titulo}</h2>
      <p>${descripcion}</p>
    </div>
    <div class="tv_grid_faq">
      ${preguntas.map(function(item) {
        return `
          <article class="tv_faq_card">
            <h3>${item.pregunta}</h3>
            <p>${item.respuesta}</p>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de contacto.
 *
 * @param      object  branding  Datos de branding.
 * @param      object  modulo    Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_contacto_tienda_publica(branding, modulo) {
  const telefono = branding.telefono_contacto || `Pendiente por parametrizar`;
  const correo = branding.correo_contacto || `Pendiente por parametrizar`;
  const direccion = branding.direccion || `Pendiente por parametrizar`;
  const contacto_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.contacto_titulo`,
    `Canales base de atención`
  );
  const contacto_descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.contacto_descripcion`,
    `Los datos visibles se leen desde branding activo para conservar el enfoque parametrizable.`
  );
  const contacto_cta_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.contacto_cta_titulo`,
    `Asesoría cercana para maquillaje, skincare y regalos`
  );
  const contacto_cta_descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.contacto_cta_descripcion`,
    `Bloque principal para reforzar atención personalizada, seguimiento por WhatsApp y apoyo en la compra.`
  );
  const contacto_cta_boton = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.contacto_cta_boton`,
    `Solicitar ayuda`
  );

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Contacto</span>
      <h2>${contacto_titulo}</h2>
      <p>${contacto_descripcion}</p>
    </div>
    <div class="tv_contacto_contenido">
      <article class="tv_contacto_principal">
        <span class="tv_etiqueta">Atención</span>
        <h3>${contacto_cta_titulo}</h3>
        <p>${contacto_cta_descripcion}</p>
        <button type="button" class="tv_btn tv_btn_principal" disabled>${contacto_cta_boton}</button>
      </article>
      <div class="tv_contacto_grid_secundario">
        <article class="tv_tarjeta_info">
          <h3>Correo</h3>
          <p>${correo}</p>
        </article>
        <article class="tv_tarjeta_info">
          <h3>Teléfono</h3>
          <p>${telefono}</p>
        </article>
        <article class="tv_tarjeta_info">
          <h3>Dirección</h3>
          <p>${direccion}</p>
        </article>
      </div>
    </div>`;
}
/**
 * Función encargada de generar el bloque de cierre comercial.
 *
 * @param      object  branding  Datos de branding.
 * @param      object  modulo    Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_newsletter_tienda_publica(branding, modulo) {
  const etiqueta = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.newsletter_etiqueta`,
    `Cierre comercial`
  );
  const titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.newsletter_titulo`,
    `Un frente listo para crecer por campañas, catálogos y temporadas`
  );
  const descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.newsletter_descripcion`,
    `Este bloque final deja una salida visual más comercial para bases de datos reales, campañas mensuales y crecimiento a nuevos temas.`
  );
  const boton_primario = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.newsletter_boton_primario`,
    `Conocer novedades`
  );
  const boton_secundario = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.newsletter_boton_secundario`,
    `Ver categorías`
  );

  return `
    <article class="tv_newsletter_card">
      <div class="tv_newsletter_texto">
        <span class="tv_etiqueta">${etiqueta}</span>
        <h2>${titulo}</h2>
        <p>${descripcion}</p>
        <div class="tv_newsletter_acciones">
          <button type="button" class="tv_btn tv_btn_principal" disabled>${boton_primario}</button>
          <a href="#seccion_catalogo_publico" class="tv_btn tv_btn_secundario">${boton_secundario}</a>
        </div>
      </div>
      <div class="tv_newsletter_resumen">
        <span class="tv_etiqueta">${branding.nombre_comercial || `Tienda`}</span>
        <strong>Tema activo parametrizable</strong>
        <p>La portada sigue consumiendo tema, menús, branding y configuraciones sin tocar la base administrativa.</p>
      </div>
    </article>`;
}
/**
 * Función encargada de generar el pie de página público.
 *
 * @param      object  branding  Datos de branding.
 * @param      array   menus     Listado de menús visibles.
 *
 * @return     string  Estructura HTML.
 */
function template_footer_tienda_publica(branding, menus) {
  const nombre = branding.nombre_comercial || `Tienda Virtual`;
  const texto_footer = branding.texto_footer || `Todos los derechos reservados.`;
  const correo = branding.correo_contacto || `Pendiente por parametrizar`;
  const telefono = branding.telefono_contacto || `Pendiente por parametrizar`;
  const menu_html = template_menu_tienda_publica(menus);

  return `
    <div class="tv_footer_contenido">
      <div class="tv_footer_columna tv_footer_columna_principal">
        <span class="tv_etiqueta">Beauty commerce</span>
        <h3>${nombre}</h3>
        <p>${texto_footer}</p>
      </div>
      <div class="tv_footer_columna">
        <strong>Navegación</strong>
        <nav class="tv_footer_nav">${menu_html}</nav>
      </div>
      <div class="tv_footer_columna">
        <strong>Contacto</strong>
        <ul class="tv_footer_lista">
          <li>${correo}</li>
          <li>${telefono}</li>
        </ul>
      </div>
    </div>`;
}
/**
 * Función encargada de generar el buscador visible del header.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_buscador_tienda_publica(modulo) {
  const mostrar_buscador = validar_configuracion_tienda_publica(modulo.configuraciones || {}, `tienda_publica.mostrar_buscador`);

  if (mostrar_buscador !== true) {
    return ``;
  }

  return `
    <label class="tv_buscador_campo" for="buscar_tienda_publica">
      <input type="text" id="buscar_tienda_publica" placeholder="Buscar próximamente" autocomplete="off" disabled>
    </label>`;
}
/**
 * Función encargada de generar el acceso visual al carrito.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_carrito_tienda_publica(modulo) {
  const mostrar_carrito = validar_configuracion_tienda_publica(modulo.configuraciones || {}, `tienda_publica.mostrar_carrito`);

  if (mostrar_carrito !== true) {
    return ``;
  }

  return `<button type="button" class="tv_btn tv_btn_icono" disabled>Carrito</button>`;
}
/**
 * Función encargada de generar una card de línea comercial.
 *
 * @param      object   linea   Datos visibles de la línea.
 * @param      integer  orden   Orden visual de la card.
 *
 * @return     string  Estructura HTML.
 */
function template_linea_card_tienda_publica(linea, orden) {
  return `
    <article class="tv_linea_card tv_linea_card_${orden}">
      <div class="tv_linea_visual">
        <span>${linea.etiqueta}</span>
      </div>
      <div class="tv_linea_contenido">
        <span class="tv_etiqueta">${linea.etiqueta}</span>
        <h3>${linea.titulo}</h3>
        <p>${linea.descripcion}</p>
        <ul class="tv_linea_lista">
          <li>${linea.item_1}</li>
          <li>${linea.item_2}</li>
          <li>${linea.item_3}</li>
        </ul>
        <a href="#seccion_catalogo_publico" class="tv_btn tv_btn_secundario">${linea.boton}</a>
      </div>
    </article>`;
}
/**
 * Función encargada de generar una card de producto del bloque destacado.
 *
 * @param      object   producto  Datos del producto.
 * @param      integer  orden     Orden visual de la card.
 * @param      string   boton     Texto del botón.
 *
 * @return     string  Estructura HTML.
 */
function template_producto_card_tienda_publica(producto, orden, boton) {
  return `
    <article class="tv_producto_card">
      <div class="tv_producto_media tv_producto_media_${orden}">
        <span>${producto.categoria}</span>
      </div>
      <div class="tv_producto_badges">
        <span class="tv_etiqueta">${producto.categoria}</span>
        <span class="tv_producto_valoracion">★ ${producto.rating}</span>
      </div>
      <h3>${producto.nombre}</h3>
      <p>${producto.descripcion}</p>
      <div class="tv_producto_precios">
        <strong class="tv_producto_precio">${producto.precio}</strong>
        ${producto.precio_anterior !== `` ? `<span class="tv_producto_precio_anterior">${producto.precio_anterior}</span>` : ``}
      </div>
      <div class="tv_producto_pie">
        <span class="tv_producto_envio">${producto.envio}</span>
        <button type="button" class="tv_btn tv_btn_secundario" disabled>${boton}</button>
      </div>
    </article>`;
}
/**
 * Función encargada de generar una card comercial del bloque más vendidos.
 *
 * @param      object   producto  Datos del producto.
 * @param      integer  orden     Orden visual de la card.
 * @param      string   boton     Texto del botón.
 *
 * @return     string  Estructura HTML.
 */
function template_producto_comercial_tienda_publica(producto, orden, boton) {
  return `
    <article class="tv_producto_card tv_producto_card_comercial">
      <div class="tv_producto_media tv_producto_media_${((orden - 1) % 4) + 1}">
        <span>${producto.etiqueta}</span>
      </div>
      <div class="tv_producto_badges">
        <span class="tv_etiqueta">${producto.etiqueta}</span>
        <span class="tv_producto_valoracion">★ ${producto.rating}</span>
      </div>
      <h3>${producto.nombre}</h3>
      <p>${producto.descripcion}</p>
      <div class="tv_producto_precios">
        <strong class="tv_producto_precio">${producto.precio}</strong>
        ${producto.precio_anterior !== `` ? `<span class="tv_producto_precio_anterior">${producto.precio_anterior}</span>` : ``}
      </div>
      <div class="tv_producto_pie tv_producto_pie_columna">
        <span class="tv_producto_envio">${producto.envio}</span>
        <button type="button" class="tv_btn tv_btn_secundario" disabled>${boton}</button>
      </div>
    </article>`;
}
/**
 * Función encargada de obtener el ancla visual de cada menú público.
 *
 * @param      string  codigo  Código del menú.
 *
 * @return     string  Ruta interna del bloque.
 */
function obtener_ancla_menu_tienda_publica(codigo) {
  const anclas = {
    MENU_INICIO_PUBLICO   : `#seccion_inicio_publico`,
    MENU_CATALOGO_PUBLICO : `#seccion_catalogo_publico`,
    MENU_OFERTAS_PUBLICO  : `#seccion_ofertas_publico`,
    MENU_CONTACTO_PUBLICO : `#seccion_contacto_publico`
  };

  return anclas[codigo] || `#seccion_inicio_publico`;
}
/**
 * Función encargada de obtener el valor visible de una configuración del módulo.
 *
 * @param      object  modulo         Datos del módulo público.
 * @param      string  codigo         Código de la configuración.
 * @param      string  valor_defecto  Valor por defecto.
 *
 * @return     string  Valor visible.
 */
function obtener_valor_configuracion_tienda_publica(modulo, codigo, valor_defecto) {
  const configuraciones = modulo.configuraciones || {};

  if (!configuraciones[codigo]) {
    return valor_defecto;
  }

  const valor = configuraciones[codigo].valor;

  if (valor === null || valor === undefined || valor === ``) {
    return valor_defecto;
  }

  return valor;
}
/**
 * Función encargada de validar una configuración binaria del módulo público.
 *
 * @param      object  configuraciones  Configuraciones del módulo.
 * @param      string  codigo           Código a validar.
 *
 * @return     boolean  Resultado de la validación.
 */
function validar_configuracion_tienda_publica(configuraciones, codigo) {
  if (!configuraciones[codigo]) {
    return false;
  }

  return configuraciones[codigo].valor === `1`;
}
/**
 * Función encargada de obtener el texto visible de una configuración binaria.
 *
 * @param      object  configuraciones  Configuraciones del módulo.
 * @param      string  codigo           Código a validar.
 *
 * @return     string  Texto visible.
 */
function obtener_texto_estado_configuracion_tienda_publica(configuraciones, codigo) {
  return validar_configuracion_tienda_publica(configuraciones, codigo) ? `activo` : `inactivo`;
}
