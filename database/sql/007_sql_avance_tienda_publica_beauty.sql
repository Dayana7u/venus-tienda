-- 1. Se insertan configuraciones comerciales base del módulo TIENDA_PUBLICA para fortalecer la portada beauty sin alterar la estructura actual.
  INSERT INTO system.modulo_configuraciones
  (
    modulo_id,
    codigo,
    nombre,
    descripcion,
    tipo_dato,
    valor_defecto,
    valor,
    accion,
    orden,
    estado,
    borrado,
    usuario_creacion,
    fecha_creacion,
    usuario_modificacion,
    fecha_modificacion,
    usuario_borrado,
    fecha_borrado
  )
  SELECT
    sys_mod.modulo_id,
    v.codigo,
    v.nombre,
    v.descripcion,
    v.tipo_dato,
    v.valor_defecto,
    v.valor,
    v.accion,
    v.orden,
    B'1',
    B'0',
    1,
    NOW(),
    NULL,
    NULL,
    NULL,
    NULL
  FROM system.modulos sys_mod
  INNER JOIN
  (
    VALUES
      ('tienda_publica.topbar_texto',                'Texto barra superior',            'Mensaje superior del frente público.',                                            'string', 'Despachos de 1 a 8 días hábiles · Maquillaje, skincare y accesorios.', 'Despachos de 1 a 8 días hábiles · Maquillaje, skincare y accesorios.', 'obtener_texto_topbar_tienda_publica', 10),
      ('tienda_publica.hero_etiqueta',               'Etiqueta hero',                   'Etiqueta superior del bloque principal.',                                         'string', 'Glow diario',                                                           'Glow diario',                                                           'obtener_etiqueta_hero_tienda_publica', 11),
      ('tienda_publica.hero_titulo',                 'Título hero',                     'Título principal de la portada pública.',                                         'string', 'Belleza cotidiana con imagen limpia y profesional',                      'Belleza cotidiana con imagen limpia y profesional',                      'obtener_titulo_hero_tienda_publica', 12),
      ('tienda_publica.hero_descripcion',            'Descripción hero',                'Descripción principal del bloque hero.',                                          'string', 'Portada base enfocada en maquillaje, skincare, accesorios y venta digital con identidad femenina y elegante.', 'Portada base enfocada en maquillaje, skincare, accesorios y venta digital con identidad femenina y elegante.', 'obtener_descripcion_hero_tienda_publica', 13),
      ('tienda_publica.hero_boton_primario',         'Botón hero principal',            'Texto del botón principal del hero.',                                             'string', 'Comprar novedades',                                                     'Comprar novedades',                                                     'obtener_boton_principal_hero_tienda_publica', 14),
      ('tienda_publica.hero_boton_secundario',       'Botón hero secundario',           'Texto del botón secundario del hero.',                                            'string', 'Ver skincare',                                                          'Ver skincare',                                                          'obtener_boton_secundario_hero_tienda_publica', 15),
      ('tienda_publica.hero_item_1',                 'Hero ítem uno',                   'Primer mensaje corto del hero.',                                                  'string', 'Envíos a todo Colombia',                                                'Envíos a todo Colombia',                                                'obtener_item_uno_hero_tienda_publica', 16),
      ('tienda_publica.hero_item_2',                 'Hero ítem dos',                   'Segundo mensaje corto del hero.',                                                 'string', 'Pago seguro y atención cercana',                                        'Pago seguro y atención cercana',                                        'obtener_item_dos_hero_tienda_publica', 17),
      ('tienda_publica.hero_item_3',                 'Hero ítem tres',                  'Tercer mensaje corto del hero.',                                                  'string', 'Tema pink nude parametrizable',                                         'Tema pink nude parametrizable',                                         'obtener_item_tres_hero_tienda_publica', 18),
      ('tienda_publica.hero_panel_titulo',           'Título panel hero',               'Título del panel lateral del hero.',                                              'string', 'Base lista para maquillaje, skincare y accesorios',                      'Base lista para maquillaje, skincare y accesorios',                      'obtener_titulo_panel_hero_tienda_publica', 19),
      ('tienda_publica.hero_panel_texto',            'Texto panel hero',                'Texto descriptivo del panel lateral del hero.',                                   'string', 'La vista pública toma branding, tema, menús y configuraciones desde base de datos sin tocar la lógica comercial.', 'La vista pública toma branding, tema, menús y configuraciones desde base de datos sin tocar la lógica comercial.', 'obtener_texto_panel_hero_tienda_publica', 20),
      ('tienda_publica.categoria_1_etiqueta',        'Etiqueta categoría uno',          'Etiqueta visual de la primera categoría.',                                        'string', 'Maquillaje',                                                            'Maquillaje',                                                            'obtener_etiqueta_categoria_uno_tienda_publica', 21),
      ('tienda_publica.categoria_1_titulo',          'Título categoría uno',            'Título de la primera categoría.',                                                 'string', 'Maquillaje diario',                                                     'Maquillaje diario',                                                     'obtener_titulo_categoria_uno_tienda_publica', 22),
      ('tienda_publica.categoria_1_descripcion',     'Descripción categoría uno',       'Descripción de la primera categoría.',                                            'string', 'Bases, rubores, labios y pestañinas con una presentación más limpia y comercial.', 'Bases, rubores, labios y pestañinas con una presentación más limpia y comercial.', 'obtener_descripcion_categoria_uno_tienda_publica', 23),
      ('tienda_publica.categoria_2_etiqueta',        'Etiqueta categoría dos',          'Etiqueta visual de la segunda categoría.',                                        'string', 'Skincare',                                                              'Skincare',                                                              'obtener_etiqueta_categoria_dos_tienda_publica', 24),
      ('tienda_publica.categoria_2_titulo',          'Título categoría dos',            'Título de la segunda categoría.',                                                 'string', 'Cuidado facial',                                                        'Cuidado facial',                                                        'obtener_titulo_categoria_dos_tienda_publica', 25),
      ('tienda_publica.categoria_2_descripcion',     'Descripción categoría dos',       'Descripción de la segunda categoría.',                                            'string', 'Limpieza, hidratación y glow con un bloque pensado para rutinas de cuidado personal.', 'Limpieza, hidratación y glow con un bloque pensado para rutinas de cuidado personal.', 'obtener_descripcion_categoria_dos_tienda_publica', 26),
      ('tienda_publica.categoria_3_etiqueta',        'Etiqueta categoría tres',         'Etiqueta visual de la tercera categoría.',                                        'string', 'Accesorios',                                                            'Accesorios',                                                            'obtener_etiqueta_categoria_tres_tienda_publica', 27),
      ('tienda_publica.categoria_3_titulo',          'Título categoría tres',           'Título de la tercera categoría.',                                                 'string', 'Accesorios beauty',                                                     'Accesorios beauty',                                                     'obtener_titulo_categoria_tres_tienda_publica', 28),
      ('tienda_publica.categoria_3_descripcion',     'Descripción categoría tres',      'Descripción de la tercera categoría.',                                            'string', 'Cosmetiqueras, brochas, organizadores y piezas visuales para complementar la experiencia.', 'Cosmetiqueras, brochas, organizadores y piezas visuales para complementar la experiencia.', 'obtener_descripcion_categoria_tres_tienda_publica', 29),
      ('tienda_publica.categoria_4_etiqueta',        'Etiqueta categoría cuatro',       'Etiqueta visual de la cuarta categoría.',                                         'string', 'Sets',                                                                   'Sets',                                                                   'obtener_etiqueta_categoria_cuatro_tienda_publica', 30),
      ('tienda_publica.categoria_4_titulo',          'Título categoría cuatro',         'Título de la cuarta categoría.',                                                  'string', 'Detalles y regalos',                                                    'Detalles y regalos',                                                    'obtener_titulo_categoria_cuatro_tienda_publica', 31),
      ('tienda_publica.categoria_4_descripcion',     'Descripción categoría cuatro',    'Descripción de la cuarta categoría.',                                             'string', 'Bloque pensado para kits, lanzamientos y campañas especiales de temporada.', 'Bloque pensado para kits, lanzamientos y campañas especiales de temporada.', 'obtener_descripcion_categoria_cuatro_tienda_publica', 32),
      ('tienda_publica.destacado_1_categoria',       'Categoría destacado uno',         'Categoría visible del primer producto destacado.',                                'string', 'Skincare',                                                              'Skincare',                                                              'obtener_categoria_destacado_uno_tienda_publica', 33),
      ('tienda_publica.destacado_1_nombre',          'Nombre destacado uno',            'Nombre del primer producto destacado.',                                           'string', 'Bloom cleanser lumi gel',                                                'Bloom cleanser lumi gel',                                                'obtener_nombre_destacado_uno_tienda_publica', 34),
      ('tienda_publica.destacado_1_descripcion',     'Descripción destacado uno',       'Descripción del primer producto destacado.',                                      'string', 'Limpieza ligera con apariencia premium para una vitrina de skincare.',   'Limpieza ligera con apariencia premium para una vitrina de skincare.',   'obtener_descripcion_destacado_uno_tienda_publica', 35),
      ('tienda_publica.destacado_1_precio',          'Precio destacado uno',            'Precio visible del primer producto destacado.',                                   'string', '$40.900',                                                               '$40.900',                                                               'obtener_precio_destacado_uno_tienda_publica', 36),
      ('tienda_publica.destacado_2_categoria',       'Categoría destacado dos',         'Categoría visible del segundo producto destacado.',                               'string', 'Maquillaje',                                                            'Maquillaje',                                                            'obtener_categoria_destacado_dos_tienda_publica', 37),
      ('tienda_publica.destacado_2_nombre',          'Nombre destacado dos',            'Nombre del segundo producto destacado.',                                          'string', 'Soft matte lips',                                                       'Soft matte lips',                                                       'obtener_nombre_destacado_dos_tienda_publica', 38),
      ('tienda_publica.destacado_2_descripcion',     'Descripción destacado dos',       'Descripción del segundo producto destacado.',                                     'string', 'Presentación visual suave para una línea de labios y tonos rosados.',    'Presentación visual suave para una línea de labios y tonos rosados.',    'obtener_descripcion_destacado_dos_tienda_publica', 39),
      ('tienda_publica.destacado_2_precio',          'Precio destacado dos',            'Precio visible del segundo producto destacado.',                                  'string', '$32.900',                                                               '$32.900',                                                               'obtener_precio_destacado_dos_tienda_publica', 40),
      ('tienda_publica.destacado_3_categoria',       'Categoría destacado tres',        'Categoría visible del tercer producto destacado.',                                'string', 'Accesorios',                                                            'Accesorios',                                                            'obtener_categoria_destacado_tres_tienda_publica', 41),
      ('tienda_publica.destacado_3_nombre',          'Nombre destacado tres',           'Nombre del tercer producto destacado.',                                           'string', 'Cosmetiquera glow case',                                                 'Cosmetiquera glow case',                                                 'obtener_nombre_destacado_tres_tienda_publica', 42),
      ('tienda_publica.destacado_3_descripcion',     'Descripción destacado tres',      'Descripción del tercer producto destacado.',                                      'string', 'Espacio listo para mostrar accesorios con acabado limpio y femenino.',   'Espacio listo para mostrar accesorios con acabado limpio y femenino.',   'obtener_descripcion_destacado_tres_tienda_publica', 43),
      ('tienda_publica.destacado_3_precio',          'Precio destacado tres',           'Precio visible del tercer producto destacado.',                                   'string', '$45.900',                                                               '$45.900',                                                               'obtener_precio_destacado_tres_tienda_publica', 44),
      ('tienda_publica.destacado_4_categoria',       'Categoría destacado cuatro',      'Categoría visible del cuarto producto destacado.',                                'string', 'Skincare',                                                              'Skincare',                                                              'obtener_categoria_destacado_cuatro_tienda_publica', 45),
      ('tienda_publica.destacado_4_nombre',          'Nombre destacado cuatro',         'Nombre del cuarto producto destacado.',                                           'string', 'Bloom repair cream',                                                    'Bloom repair cream',                                                    'obtener_nombre_destacado_cuatro_tienda_publica', 46),
      ('tienda_publica.destacado_4_descripcion',     'Descripción destacado cuatro',    'Descripción del cuarto producto destacado.',                                      'string', 'Crema reparadora pensada para una sección hero de cuidado facial.',       'Crema reparadora pensada para una sección hero de cuidado facial.',       'obtener_descripcion_destacado_cuatro_tienda_publica', 47),
      ('tienda_publica.destacado_4_precio',          'Precio destacado cuatro',         'Precio visible del cuarto producto destacado.',                                   'string', '$60.900',                                                               '$60.900',                                                               'obtener_precio_destacado_cuatro_tienda_publica', 48),
      ('tienda_publica.rutina_1_titulo',             'Título rutina uno',               'Título del primer paso de rutina.',                                               'string', 'Limpia y prepara',                                                      'Limpia y prepara',                                                      'obtener_titulo_rutina_uno_tienda_publica', 49),
      ('tienda_publica.rutina_1_descripcion',        'Descripción rutina uno',          'Descripción del primer paso de rutina.',                                          'string', 'Primera tarjeta para destacar limpieza suave y preparación del rostro.', 'Primera tarjeta para destacar limpieza suave y preparación del rostro.', 'obtener_descripcion_rutina_uno_tienda_publica', 50),
      ('tienda_publica.rutina_2_titulo',             'Título rutina dos',               'Título del segundo paso de rutina.',                                              'string', 'Hidrata y equilibra',                                                    'Hidrata y equilibra',                                                    'obtener_titulo_rutina_dos_tienda_publica', 51),
      ('tienda_publica.rutina_2_descripcion',        'Descripción rutina dos',          'Descripción del segundo paso de rutina.',                                         'string', 'Bloque para mostrar serums, cremas y líneas que aporten glow.',         'Bloque para mostrar serums, cremas y líneas que aporten glow.',         'obtener_descripcion_rutina_dos_tienda_publica', 52),
      ('tienda_publica.rutina_3_titulo',             'Título rutina tres',              'Título del tercer paso de rutina.',                                               'string', 'Sella tu look',                                                         'Sella tu look',                                                         'obtener_titulo_rutina_tres_tienda_publica', 53),
      ('tienda_publica.rutina_3_descripcion',        'Descripción rutina tres',         'Descripción del tercer paso de rutina.',                                          'string', 'Tarjeta final para maquillaje, retoques y accesorios de uso diario.',   'Tarjeta final para maquillaje, retoques y accesorios de uso diario.',   'obtener_descripcion_rutina_tres_tienda_publica', 54),
      ('tienda_publica.oferta_titulo',               'Título oferta principal',         'Título de la oferta principal.',                                                  'string', 'Promociones por temporada',                                             'Promociones por temporada',                                             'obtener_titulo_oferta_principal_tienda_publica', 55),
      ('tienda_publica.oferta_descripcion',          'Descripción oferta principal',    'Descripción de la oferta principal.',                                             'string', 'Este espacio queda listo para campañas visuales como navidad, san valentín o liquidaciones.', 'Este espacio queda listo para campañas visuales como navidad, san valentín o liquidaciones.', 'obtener_descripcion_oferta_principal_tienda_publica', 56),
      ('tienda_publica.oferta_secundaria_titulo',    'Título oferta secundaria',        'Título de la oferta secundaria.',                                                 'string', 'Cambios visuales sin tocar la lógica',                                  'Cambios visuales sin tocar la lógica',                                  'obtener_titulo_oferta_secundaria_tienda_publica', 57),
      ('tienda_publica.oferta_secundaria_descripcion', 'Descripción oferta secundaria', 'Descripción de la oferta secundaria.',                                            'string', 'La tienda puede variar apariencia por tema y mantener la misma base administrativa y comercial.', 'La tienda puede variar apariencia por tema y mantener la misma base administrativa y comercial.', 'obtener_descripcion_oferta_secundaria_tienda_publica', 58),
      ('tienda_publica.testimonio_1_nombre',         'Nombre testimonio uno',           'Nombre visible del primer testimonio.',                                           'string', 'Cliente frecuente',                                                     'Cliente frecuente',                                                     'obtener_nombre_testimonio_uno_tienda_publica', 59),
      ('tienda_publica.testimonio_1_texto',          'Texto testimonio uno',            'Texto visible del primer testimonio.',                                            'string', 'Se siente más profesional y más cercano a una tienda de maquillaje y skincare real.', 'Se siente más profesional y más cercano a una tienda de maquillaje y skincare real.', 'obtener_texto_testimonio_uno_tienda_publica', 60),
      ('tienda_publica.testimonio_2_nombre',         'Nombre testimonio dos',           'Nombre visible del segundo testimonio.',                                          'string', 'Compradora online',                                                     'Compradora online',                                                     'obtener_nombre_testimonio_dos_tienda_publica', 61),
      ('tienda_publica.testimonio_2_texto',          'Texto testimonio dos',            'Texto visible del segundo testimonio.',                                           'string', 'La combinación entre cards, tonos nude y distribución limpia mejora mucho la percepción comercial.', 'La combinación entre cards, tonos nude y distribución limpia mejora mucho la percepción comercial.', 'obtener_texto_testimonio_dos_tienda_publica', 62),
      ('tienda_publica.contacto_titulo',             'Título contacto',                 'Título del bloque de contacto.',                                                  'string', 'Canales base de atención',                                              'Canales base de atención',                                              'obtener_titulo_contacto_tienda_publica', 63),
      ('tienda_publica.contacto_descripcion',        'Descripción contacto',            'Descripción del bloque de contacto.',                                             'string', 'Los datos visibles se leen desde branding activo para conservar el enfoque parametrizable.', 'Los datos visibles se leen desde branding activo para conservar el enfoque parametrizable.', 'obtener_descripcion_contacto_tienda_publica', 64)
  ) AS v
  (
    codigo,
    nombre,
    descripcion,
    tipo_dato,
    valor_defecto,
    valor,
    accion,
    orden
  )
    ON 1 = 1
  WHERE sys_mod.codigo = 'TIENDA_PUBLICA'
  ON CONFLICT (modulo_id, codigo) DO UPDATE
  SET
    nombre                 = EXCLUDED.nombre,
    descripcion            = EXCLUDED.descripcion,
    tipo_dato              = EXCLUDED.tipo_dato,
    valor_defecto          = EXCLUDED.valor_defecto,
    valor                  = EXCLUDED.valor,
    accion                 = EXCLUDED.accion,
    orden                  = EXCLUDED.orden,
    estado                 = B'1',
    borrado                = B'0',
    usuario_modificacion   = 1,
    fecha_modificacion     = NOW();

-- 2. Se insertan componentes visuales adicionales del tema PINK_NUDE para reforzar el estilo profesional del frente beauty.
  INSERT INTO system.tema_componentes
  (
    tema_id,
    componente,
    propiedad,
    valor,
    tipo_dato,
    descripcion,
    orden,
    estado,
    borrado,
    usuario_creacion,
    fecha_creacion,
    usuario_modificacion,
    fecha_modificacion,
    usuario_borrado,
    fecha_borrado
  )
  SELECT
    sys_tem.tema_id,
    v.componente,
    v.propiedad,
    v.valor,
    v.tipo_dato,
    v.descripcion,
    v.orden,
    B'1',
    B'0',
    1,
    NOW(),
    NULL,
    NULL,
    NULL,
    NULL
  FROM system.temas sys_tem
  INNER JOIN
  (
    VALUES
      ('topbar',        'background_color', '#D7A1AE',                 'string', 'Color de fondo de la barra superior del frente público.', 20),
      ('topbar',        'text_color',       '#FFF9F8',                 'string', 'Color de texto de la barra superior del frente público.', 21),
      ('hero.panel',    'background_color', '#FFF5F7',                 'string', 'Color de fondo del panel lateral del hero.', 22),
      ('hero.panel',    'border_color',     '#ECD3DA',                 'string', 'Color de borde del panel lateral del hero.', 23),
      ('section.soft',  'background_color', '#FFF8FA',                 'string', 'Color de fondo de las secciones suaves del frente público.', 24),
      ('product.price', 'text_color',       '#B96A7F',                 'string', 'Color del precio visible en tarjetas de producto.', 25),
      ('product.media', 'background_color', '#F8EDF0',                 'string', 'Color base de apoyo para el bloque visual del producto.', 26),
      ('testimonial',   'background_color', '#FFF7F9',                 'string', 'Color base de apoyo para las tarjetas de testimonios.', 27)
  ) AS v
  (
    componente,
    propiedad,
    valor,
    tipo_dato,
    descripcion,
    orden
  )
    ON 1 = 1
  WHERE sys_tem.codigo = 'PINK_NUDE'
  ON CONFLICT (tema_id, componente, propiedad) DO UPDATE
  SET
    valor                 = EXCLUDED.valor,
    tipo_dato             = EXCLUDED.tipo_dato,
    descripcion           = EXCLUDED.descripcion,
    orden                 = EXCLUDED.orden,
    estado                = B'1',
    borrado               = B'0',
    usuario_modificacion  = 1,
    fecha_modificacion    = NOW();
