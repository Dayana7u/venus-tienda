-- 1. Se insertan configuraciones adicionales del módulo TIENDA_PUBLICA para ampliar el frente beauty por cards reutilizables.
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
      ('tienda_publica.colecciones_titulo',                 'Título colecciones',                    'Título principal del bloque de colecciones curadas.',                          'string', 'Colecciones curadas para una vitrina beauty más profesional', 'Colecciones curadas para una vitrina beauty más profesional', 'obtener_titulo_colecciones_tienda_publica', 65),
      ('tienda_publica.colecciones_descripcion',            'Descripción colecciones',               'Texto descriptivo del bloque de colecciones curadas.',                         'string', 'Este bloque organiza la portada en campañas visuales fáciles de reutilizar cuando cambie la temporada o el enfoque comercial.', 'Este bloque organiza la portada en campañas visuales fáciles de reutilizar cuando cambie la temporada o el enfoque comercial.', 'obtener_descripcion_colecciones_tienda_publica', 66),
      ('tienda_publica.coleccion_1_etiqueta',               'Etiqueta colección uno',                'Etiqueta visible de la primera colección curada.',                             'string', 'Rutina glow', 'Rutina glow', 'obtener_etiqueta_coleccion_uno_tienda_publica', 67),
      ('tienda_publica.coleccion_1_titulo',                 'Título colección uno',                  'Título visible de la primera colección curada.',                               'string', 'Preparación y acabado natural', 'Preparación y acabado natural', 'obtener_titulo_coleccion_uno_tienda_publica', 68),
      ('tienda_publica.coleccion_1_descripcion',            'Descripción colección uno',             'Descripción visible de la primera colección curada.',                          'string', 'Bloque pensado para primers, bases ligeras, rubor suave y un look diario con apariencia limpia.', 'Bloque pensado para primers, bases ligeras, rubor suave y un look diario con apariencia limpia.', 'obtener_descripcion_coleccion_uno_tienda_publica', 69),
      ('tienda_publica.coleccion_1_boton',                  'Botón colección uno',                   'Texto del botón visible de la primera colección curada.',                      'string', 'Ver rutina', 'Ver rutina', 'obtener_boton_coleccion_uno_tienda_publica', 70),
      ('tienda_publica.coleccion_2_etiqueta',               'Etiqueta colección dos',                'Etiqueta visible de la segunda colección curada.',                             'string', 'Skincare base', 'Skincare base', 'obtener_etiqueta_coleccion_dos_tienda_publica', 71),
      ('tienda_publica.coleccion_2_titulo',                 'Título colección dos',                  'Título visible de la segunda colección curada.',                               'string', 'Hidratación, balance y glow', 'Hidratación, balance y glow', 'obtener_titulo_coleccion_dos_tienda_publica', 72),
      ('tienda_publica.coleccion_2_descripcion',            'Descripción colección dos',             'Descripción visible de la segunda colección curada.',                          'string', 'Espacio orientado a limpiadores, tónicos, serums y cremas de cuidado facial con imagen profesional.', 'Espacio orientado a limpiadores, tónicos, serums y cremas de cuidado facial con imagen profesional.', 'obtener_descripcion_coleccion_dos_tienda_publica', 73),
      ('tienda_publica.coleccion_2_boton',                  'Botón colección dos',                   'Texto del botón visible de la segunda colección curada.',                      'string', 'Ver skincare', 'Ver skincare', 'obtener_boton_coleccion_dos_tienda_publica', 74),
      ('tienda_publica.coleccion_3_etiqueta',               'Etiqueta colección tres',               'Etiqueta visible de la tercera colección curada.',                             'string', 'Regalos y sets', 'Regalos y sets', 'obtener_etiqueta_coleccion_tres_tienda_publica', 75),
      ('tienda_publica.coleccion_3_titulo',                 'Título colección tres',                 'Título visible de la tercera colección curada.',                               'string', 'Detalles para fechas especiales', 'Detalles para fechas especiales', 'obtener_titulo_coleccion_tres_tienda_publica', 76),
      ('tienda_publica.coleccion_3_descripcion',            'Descripción colección tres',            'Descripción visible de la tercera colección curada.',                          'string', 'Tarjeta lista para campañas de kits, accesorios, lanzamientos y regalos con una presentación más comercial.', 'Tarjeta lista para campañas de kits, accesorios, lanzamientos y regalos con una presentación más comercial.', 'obtener_descripcion_coleccion_tres_tienda_publica', 77),
      ('tienda_publica.coleccion_3_boton',                  'Botón colección tres',                  'Texto del botón visible de la tercera colección curada.',                      'string', 'Ver sets', 'Ver sets', 'obtener_boton_coleccion_tres_tienda_publica', 78),
      ('tienda_publica.destacado_1_precio_anterior',        'Precio anterior destacado uno',         'Precio comparativo visible del primer producto destacado.',                    'string', '$86.900', '$86.900', 'obtener_precio_anterior_destacado_uno_tienda_publica', 79),
      ('tienda_publica.destacado_2_precio_anterior',        'Precio anterior destacado dos',         'Precio comparativo visible del segundo producto destacado.',                   'string', '$98.900', '$98.900', 'obtener_precio_anterior_destacado_dos_tienda_publica', 80),
      ('tienda_publica.destacado_3_precio_anterior',        'Precio anterior destacado tres',        'Precio comparativo visible del tercer producto destacado.',                    'string', '$79.900', '$79.900', 'obtener_precio_anterior_destacado_tres_tienda_publica', 81),
      ('tienda_publica.destacado_4_precio_anterior',        'Precio anterior destacado cuatro',      'Precio comparativo visible del cuarto producto destacado.',                    'string', '$73.900', '$73.900', 'obtener_precio_anterior_destacado_cuatro_tienda_publica', 82),
      ('tienda_publica.destacado_boton',                    'Botón destacados',                      'Texto visible del botón de tarjetas destacadas.',                              'string', 'Ver detalle', 'Ver detalle', 'obtener_boton_destacados_tienda_publica', 83),
      ('tienda_publica.contacto_cta_titulo',                'Título CTA contacto',                   'Título del bloque principal de contacto y asesoría.',                          'string', 'Asesoría cercana para maquillaje, skincare y regalos', 'Asesoría cercana para maquillaje, skincare y regalos', 'obtener_titulo_cta_contacto_tienda_publica', 84),
      ('tienda_publica.contacto_cta_descripcion',           'Descripción CTA contacto',              'Descripción del bloque principal de contacto y asesoría.',                     'string', 'Bloque principal para reforzar atención personalizada, seguimiento por WhatsApp y apoyo en la compra.', 'Bloque principal para reforzar atención personalizada, seguimiento por WhatsApp y apoyo en la compra.', 'obtener_descripcion_cta_contacto_tienda_publica', 85),
      ('tienda_publica.contacto_cta_boton',                 'Botón CTA contacto',                    'Texto visible del botón principal de contacto.',                               'string', 'Solicitar ayuda', 'Solicitar ayuda', 'obtener_boton_cta_contacto_tienda_publica', 86),
      ('tienda_publica.newsletter_etiqueta',                'Etiqueta cierre comercial',             'Etiqueta visible del bloque final comercial.',                                 'string', 'Cierre comercial', 'Cierre comercial', 'obtener_etiqueta_newsletter_tienda_publica', 87),
      ('tienda_publica.newsletter_titulo',                  'Título cierre comercial',               'Título visible del bloque final comercial.',                                   'string', 'Un frente listo para crecer por campañas, catálogos y temporadas', 'Un frente listo para crecer por campañas, catálogos y temporadas', 'obtener_titulo_newsletter_tienda_publica', 88),
      ('tienda_publica.newsletter_descripcion',             'Descripción cierre comercial',          'Descripción visible del bloque final comercial.',                              'string', 'Este bloque final deja una salida visual más comercial para bases de datos reales, campañas mensuales y crecimiento a nuevos temas.', 'Este bloque final deja una salida visual más comercial para bases de datos reales, campañas mensuales y crecimiento a nuevos temas.', 'obtener_descripcion_newsletter_tienda_publica', 89),
      ('tienda_publica.newsletter_boton_primario',          'Botón cierre comercial principal',      'Texto del botón principal del bloque final comercial.',                        'string', 'Conocer novedades', 'Conocer novedades', 'obtener_boton_principal_newsletter_tienda_publica', 90),
      ('tienda_publica.newsletter_boton_secundario',        'Botón cierre comercial secundario',     'Texto del botón secundario del bloque final comercial.',                       'string', 'Ver categorías', 'Ver categorías', 'obtener_boton_secundario_newsletter_tienda_publica', 91)
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

-- 2. Se insertan componentes visuales adicionales del tema PINK_NUDE para nuevos bloques reutilizables del frente público.
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
      ('collection.card',  'background_color', '#FFF5F7', 'string', 'Color base del bloque de colecciones curadas.', 28),
      ('collection.card',  'border_color',     '#ECD3DA', 'string', 'Color de borde del bloque de colecciones curadas.', 29),
      ('newsletter',       'background_color', '#FFF3F6', 'string', 'Color base del bloque final comercial.', 30),
      ('newsletter',       'border_color',     '#E9D3D9', 'string', 'Color de borde del bloque final comercial.', 31),
      ('contact.highlight','background_color', '#FFF2F5', 'string', 'Color base del bloque principal de contacto.', 32),
      ('contact.highlight','border_color',     '#EAD2D8', 'string', 'Color de borde del bloque principal de contacto.', 33)
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
