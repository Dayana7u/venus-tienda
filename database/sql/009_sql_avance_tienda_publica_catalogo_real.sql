-- 1. Se insertan configuraciones adicionales del módulo TIENDA_PUBLICA para acercar el frente a una tienda beauty más real.
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
      ('tienda_publica.mostrar_lineas_producto',           'Mostrar líneas de producto',                 'Controla la visibilidad del bloque de líneas principales del catálogo.',                        'boolean', '1', '1', 'mostrar_lineas_producto_tienda_publica', 92),
      ('tienda_publica.mostrar_mas_vendidos',              'Mostrar más vendidos',                       'Controla la visibilidad del bloque de más vendidos del catálogo.',                              'boolean', '1', '1', 'mostrar_mas_vendidos_tienda_publica', 93),
      ('tienda_publica.mostrar_faq',                       'Mostrar preguntas frecuentes',               'Controla la visibilidad del bloque FAQ del frente público.',                                    'boolean', '1', '1', 'mostrar_faq_tienda_publica', 94),
      ('tienda_publica.lineas_titulo',                     'Título líneas de producto',                  'Título principal del bloque de líneas principales del catálogo.',                               'string',  'Compra por línea principal', 'Compra por línea principal', 'obtener_titulo_lineas_tienda_publica', 95),
      ('tienda_publica.lineas_descripcion',                'Descripción líneas de producto',             'Descripción principal del bloque de líneas principales del catálogo.',                           'string',  'Sección pensada para que la tienda ya empiece a verse como un catálogo real por línea de producto.', 'Sección pensada para que la tienda ya empiece a verse como un catálogo real por línea de producto.', 'obtener_descripcion_lineas_tienda_publica', 96),
      ('tienda_publica.linea_1_etiqueta',                  'Etiqueta línea uno',                         'Etiqueta visible de la primera línea de producto.',                                             'string',  'Makeup diario', 'Makeup diario', 'obtener_etiqueta_linea_uno_tienda_publica', 97),
      ('tienda_publica.linea_1_titulo',                    'Título línea uno',                           'Título visible de la primera línea de producto.',                                               'string',  'Maquillaje para uso diario', 'Maquillaje para uso diario', 'obtener_titulo_linea_uno_tienda_publica', 98),
      ('tienda_publica.linea_1_descripcion',               'Descripción línea uno',                      'Descripción visible de la primera línea de producto.',                                          'string',  'Bloque pensado para bases ligeras, correctores, rubores y labiales con imagen comercial más limpia.', 'Bloque pensado para bases ligeras, correctores, rubores y labiales con imagen comercial más limpia.', 'obtener_descripcion_linea_uno_tienda_publica', 99),
      ('tienda_publica.linea_1_item_1',                    'Item línea uno uno',                         'Primer item visible de apoyo de la primera línea.',                                             'string',  'Productos de rotación rápida', 'Productos de rotación rápida', 'obtener_item_uno_linea_uno_tienda_publica', 100),
      ('tienda_publica.linea_1_item_2',                    'Item línea uno dos',                         'Segundo item visible de apoyo de la primera línea.',                                            'string',  'Tonos suaves y neutros', 'Tonos suaves y neutros', 'obtener_item_dos_linea_uno_tienda_publica', 101),
      ('tienda_publica.linea_1_item_3',                    'Item línea uno tres',                        'Tercer item visible de apoyo de la primera línea.',                                             'string',  'Fácil de convertir en campañas', 'Fácil de convertir en campañas', 'obtener_item_tres_linea_uno_tienda_publica', 102),
      ('tienda_publica.linea_1_boton',                     'Botón línea uno',                            'Texto del botón visible de la primera línea.',                                                  'string',  'Explorar makeup', 'Explorar makeup', 'obtener_boton_linea_uno_tienda_publica', 103),
      ('tienda_publica.linea_2_etiqueta',                  'Etiqueta línea dos',                         'Etiqueta visible de la segunda línea de producto.',                                             'string',  'Skincare', 'Skincare', 'obtener_etiqueta_linea_dos_tienda_publica', 104),
      ('tienda_publica.linea_2_titulo',                    'Título línea dos',                           'Título visible de la segunda línea de producto.',                                               'string',  'Rutinas de cuidado facial', 'Rutinas de cuidado facial', 'obtener_titulo_linea_dos_tienda_publica', 105),
      ('tienda_publica.linea_2_descripcion',               'Descripción línea dos',                      'Descripción visible de la segunda línea de producto.',                                          'string',  'Espacio orientado a limpiadores, tónicos, serums y cremas con foco en glow, hidratación y textura.', 'Espacio orientado a limpiadores, tónicos, serums y cremas con foco en glow, hidratación y textura.', 'obtener_descripcion_linea_dos_tienda_publica', 106),
      ('tienda_publica.linea_2_item_1',                    'Item línea dos uno',                         'Primer item visible de apoyo de la segunda línea.',                                             'string',  'Lectura clara por rutina', 'Lectura clara por rutina', 'obtener_item_uno_linea_dos_tienda_publica', 107),
      ('tienda_publica.linea_2_item_2',                    'Item línea dos dos',                         'Segundo item visible de apoyo de la segunda línea.',                                            'string',  'Ideal para campañas glow', 'Ideal para campañas glow', 'obtener_item_dos_linea_dos_tienda_publica', 108),
      ('tienda_publica.linea_2_item_3',                    'Item línea dos tres',                        'Tercer item visible de apoyo de la segunda línea.',                                             'string',  'Se adapta a lanzamientos', 'Se adapta a lanzamientos', 'obtener_item_tres_linea_dos_tienda_publica', 109),
      ('tienda_publica.linea_2_boton',                     'Botón línea dos',                            'Texto del botón visible de la segunda línea.',                                                  'string',  'Ver skincare', 'Ver skincare', 'obtener_boton_linea_dos_tienda_publica', 110),
      ('tienda_publica.linea_3_etiqueta',                  'Etiqueta línea tres',                        'Etiqueta visible de la tercera línea de producto.',                                             'string',  'Accesorios', 'Accesorios', 'obtener_etiqueta_linea_tres_tienda_publica', 111),
      ('tienda_publica.linea_3_titulo',                    'Título línea tres',                          'Título visible de la tercera línea de producto.',                                               'string',  'Complementos y detalles beauty', 'Complementos y detalles beauty', 'obtener_titulo_linea_tres_tienda_publica', 112),
      ('tienda_publica.linea_3_descripcion',               'Descripción línea tres',                     'Descripción visible de la tercera línea de producto.',                                          'string',  'Bloque pensado para cosmetiqueras, brochas, kits y piezas visuales que ayudan a cerrar la compra.', 'Bloque pensado para cosmetiqueras, brochas, kits y piezas visuales que ayudan a cerrar la compra.', 'obtener_descripcion_linea_tres_tienda_publica', 113),
      ('tienda_publica.linea_3_item_1',                    'Item línea tres uno',                        'Primer item visible de apoyo de la tercera línea.',                                             'string',  'Ideal para regalos', 'Ideal para regalos', 'obtener_item_uno_linea_tres_tienda_publica', 114),
      ('tienda_publica.linea_3_item_2',                    'Item línea tres dos',                        'Segundo item visible de apoyo de la tercera línea.',                                            'string',  'Aumenta ticket promedio', 'Aumenta ticket promedio', 'obtener_item_dos_linea_tres_tienda_publica', 115),
      ('tienda_publica.linea_3_item_3',                    'Item línea tres tres',                       'Tercer item visible de apoyo de la tercera línea.',                                             'string',  'Funciona para temporadas', 'Funciona para temporadas', 'obtener_item_tres_linea_tres_tienda_publica', 116),
      ('tienda_publica.linea_3_boton',                     'Botón línea tres',                           'Texto del botón visible de la tercera línea.',                                                  'string',  'Ver accesorios', 'Ver accesorios', 'obtener_boton_linea_tres_tienda_publica', 117),
      ('tienda_publica.mas_vendidos_titulo',               'Título más vendidos',                        'Título principal del bloque de más vendidos.',                                                  'string',  'Más vendidos de la semana', 'Más vendidos de la semana', 'obtener_titulo_mas_vendidos_tienda_publica', 118),
      ('tienda_publica.mas_vendidos_descripcion',          'Descripción más vendidos',                   'Descripción principal del bloque de más vendidos.',                                             'string',  'Este bloque ya acerca el frente a una tienda real con tarjetas de producto, rating, precio y foco comercial.', 'Este bloque ya acerca el frente a una tienda real con tarjetas de producto, rating, precio y foco comercial.', 'obtener_descripcion_mas_vendidos_tienda_publica', 119),
      ('tienda_publica.mas_vendidos_boton',                'Botón más vendidos',                         'Texto del botón visible del bloque de más vendidos.',                                           'string',  'Agregar pronto', 'Agregar pronto', 'obtener_boton_mas_vendidos_tienda_publica', 120),
      ('tienda_publica.mas_vendido_1_etiqueta',            'Etiqueta más vendido uno',                   'Etiqueta visible del primer producto del bloque de más vendidos.',                              'string',  'Top makeup', 'Top makeup', 'obtener_etiqueta_mas_vendido_uno_tienda_publica', 121),
      ('tienda_publica.mas_vendido_1_nombre',              'Nombre más vendido uno',                     'Nombre visible del primer producto del bloque de más vendidos.',                                'string',  'Base skin tint glow', 'Base skin tint glow', 'obtener_nombre_mas_vendido_uno_tienda_publica', 122),
      ('tienda_publica.mas_vendido_1_descripcion',         'Descripción más vendido uno',                'Descripción visible del primer producto del bloque de más vendidos.',                           'string',  'Cobertura ligera, acabado natural y presentación pensada para vitrina principal.', 'Cobertura ligera, acabado natural y presentación pensada para vitrina principal.', 'obtener_descripcion_mas_vendido_uno_tienda_publica', 123),
      ('tienda_publica.mas_vendido_1_precio',              'Precio más vendido uno',                     'Precio visible del primer producto del bloque de más vendidos.',                                'string',  '$74.900', '$74.900', 'obtener_precio_mas_vendido_uno_tienda_publica', 124),
      ('tienda_publica.mas_vendido_1_precio_anterior',     'Precio anterior más vendido uno',            'Precio comparativo visible del primer producto del bloque de más vendidos.',                    'string',  '$89.900', '$89.900', 'obtener_precio_anterior_mas_vendido_uno_tienda_publica', 125),
      ('tienda_publica.mas_vendido_1_rating',              'Rating más vendido uno',                     'Valoración visible del primer producto del bloque de más vendidos.',                            'string',  '4.9', '4.9', 'obtener_rating_mas_vendido_uno_tienda_publica', 126),
      ('tienda_publica.mas_vendido_1_envio',               'Envío más vendido uno',                      'Texto visible de apoyo logístico del primer producto del bloque de más vendidos.',              'string',  'Entrega nacional', 'Entrega nacional', 'obtener_envio_mas_vendido_uno_tienda_publica', 127),
      ('tienda_publica.mas_vendido_2_etiqueta',            'Etiqueta más vendido dos',                   'Etiqueta visible del segundo producto del bloque de más vendidos.',                             'string',  'Skincare glow', 'Skincare glow', 'obtener_etiqueta_mas_vendido_dos_tienda_publica', 128),
      ('tienda_publica.mas_vendido_2_nombre',              'Nombre más vendido dos',                     'Nombre visible del segundo producto del bloque de más vendidos.',                               'string',  'Serum hidratante rosa', 'Serum hidratante rosa', 'obtener_nombre_mas_vendido_dos_tienda_publica', 129),
      ('tienda_publica.mas_vendido_2_descripcion',         'Descripción más vendido dos',                'Descripción visible del segundo producto del bloque de más vendidos.',                          'string',  'Bloque pensado para glow facial y campañas de cuidado personal con imagen premium.', 'Bloque pensado para glow facial y campañas de cuidado personal con imagen premium.', 'obtener_descripcion_mas_vendido_dos_tienda_publica', 130),
      ('tienda_publica.mas_vendido_2_precio',              'Precio más vendido dos',                     'Precio visible del segundo producto del bloque de más vendidos.',                               'string',  '$68.900', '$68.900', 'obtener_precio_mas_vendido_dos_tienda_publica', 131),
      ('tienda_publica.mas_vendido_2_precio_anterior',     'Precio anterior más vendido dos',            'Precio comparativo visible del segundo producto del bloque de más vendidos.',                   'string',  '$79.900', '$79.900', 'obtener_precio_anterior_mas_vendido_dos_tienda_publica', 132),
      ('tienda_publica.mas_vendido_2_rating',              'Rating más vendido dos',                     'Valoración visible del segundo producto del bloque de más vendidos.',                           'string',  '4.8', '4.8', 'obtener_rating_mas_vendido_dos_tienda_publica', 133),
      ('tienda_publica.mas_vendido_2_envio',               'Envío más vendido dos',                      'Texto visible de apoyo logístico del segundo producto del bloque de más vendidos.',             'string',  'Despacho rápido', 'Despacho rápido', 'obtener_envio_mas_vendido_dos_tienda_publica', 134),
      ('tienda_publica.mas_vendido_3_etiqueta',            'Etiqueta más vendido tres',                  'Etiqueta visible del tercer producto del bloque de más vendidos.',                              'string',  'Labios', 'Labios', 'obtener_etiqueta_mas_vendido_tres_tienda_publica', 135),
      ('tienda_publica.mas_vendido_3_nombre',              'Nombre más vendido tres',                    'Nombre visible del tercer producto del bloque de más vendidos.',                                'string',  'Lip oil nude shine', 'Lip oil nude shine', 'obtener_nombre_mas_vendido_tres_tienda_publica', 136),
      ('tienda_publica.mas_vendido_3_descripcion',         'Descripción más vendido tres',               'Descripción visible del tercer producto del bloque de más vendidos.',                           'string',  'Producto pensado para campañas de brillo, rutina rápida y compra por impulso.', 'Producto pensado para campañas de brillo, rutina rápida y compra por impulso.', 'obtener_descripcion_mas_vendido_tres_tienda_publica', 137),
      ('tienda_publica.mas_vendido_3_precio',              'Precio más vendido tres',                    'Precio visible del tercer producto del bloque de más vendidos.',                                'string',  '$39.900', '$39.900', 'obtener_precio_mas_vendido_tres_tienda_publica', 138),
      ('tienda_publica.mas_vendido_3_precio_anterior',     'Precio anterior más vendido tres',           'Precio comparativo visible del tercer producto del bloque de más vendidos.',                    'string',  '$47.900', '$47.900', 'obtener_precio_anterior_mas_vendido_tres_tienda_publica', 139),
      ('tienda_publica.mas_vendido_3_rating',              'Rating más vendido tres',                    'Valoración visible del tercer producto del bloque de más vendidos.',                            'string',  '4.9', '4.9', 'obtener_rating_mas_vendido_tres_tienda_publica', 140),
      ('tienda_publica.mas_vendido_3_envio',               'Envío más vendido tres',                     'Texto visible de apoyo logístico del tercer producto del bloque de más vendidos.',              'string',  'Entrega en ciudades principales', 'Entrega en ciudades principales', 'obtener_envio_mas_vendido_tres_tienda_publica', 141),
      ('tienda_publica.mas_vendido_4_etiqueta',            'Etiqueta más vendido cuatro',                'Etiqueta visible del cuarto producto del bloque de más vendidos.',                              'string',  'Accesorio', 'Accesorio', 'obtener_etiqueta_mas_vendido_cuatro_tienda_publica', 142),
      ('tienda_publica.mas_vendido_4_nombre',              'Nombre más vendido cuatro',                  'Nombre visible del cuarto producto del bloque de más vendidos.',                                'string',  'Set de brochas soft pink', 'Set de brochas soft pink', 'obtener_nombre_mas_vendido_cuatro_tienda_publica', 143),
      ('tienda_publica.mas_vendido_4_descripcion',         'Descripción más vendido cuatro',             'Descripción visible del cuarto producto del bloque de más vendidos.',                           'string',  'Accesorio visual para cerrar campañas de maquillaje y aumentar el valor del carrito.', 'Accesorio visual para cerrar campañas de maquillaje y aumentar el valor del carrito.', 'obtener_descripcion_mas_vendido_cuatro_tienda_publica', 144),
      ('tienda_publica.mas_vendido_4_precio',              'Precio más vendido cuatro',                  'Precio visible del cuarto producto del bloque de más vendidos.',                                'string',  '$58.900', '$58.900', 'obtener_precio_mas_vendido_cuatro_tienda_publica', 145),
      ('tienda_publica.mas_vendido_4_precio_anterior',     'Precio anterior más vendido cuatro',         'Precio comparativo visible del cuarto producto del bloque de más vendidos.',                    'string',  '$69.900', '$69.900', 'obtener_precio_anterior_mas_vendido_cuatro_tienda_publica', 146),
      ('tienda_publica.mas_vendido_4_rating',              'Rating más vendido cuatro',                  'Valoración visible del cuarto producto del bloque de más vendidos.',                            'string',  '4.7', '4.7', 'obtener_rating_mas_vendido_cuatro_tienda_publica', 147),
      ('tienda_publica.mas_vendido_4_envio',               'Envío más vendido cuatro',                   'Texto visible de apoyo logístico del cuarto producto del bloque de más vendidos.',              'string',  'Ideal para regalo', 'Ideal para regalo', 'obtener_envio_mas_vendido_cuatro_tienda_publica', 148),
      ('tienda_publica.mas_vendido_5_etiqueta',            'Etiqueta más vendido cinco',                 'Etiqueta visible del quinto producto del bloque de más vendidos.',                              'string',  'Rutina noche', 'Rutina noche', 'obtener_etiqueta_mas_vendido_cinco_tienda_publica', 149),
      ('tienda_publica.mas_vendido_5_nombre',              'Nombre más vendido cinco',                   'Nombre visible del quinto producto del bloque de más vendidos.',                                'string',  'Crema reparadora facial', 'Crema reparadora facial', 'obtener_nombre_mas_vendido_cinco_tienda_publica', 150),
      ('tienda_publica.mas_vendido_5_descripcion',         'Descripción más vendido cinco',              'Descripción visible del quinto producto del bloque de más vendidos.',                           'string',  'Producto pensado para rutinas de noche, hidratación intensa y campañas de cuidado premium.', 'Producto pensado para rutinas de noche, hidratación intensa y campañas de cuidado premium.', 'obtener_descripcion_mas_vendido_cinco_tienda_publica', 151),
      ('tienda_publica.mas_vendido_5_precio',              'Precio más vendido cinco',                   'Precio visible del quinto producto del bloque de más vendidos.',                                'string',  '$64.900', '$64.900', 'obtener_precio_mas_vendido_cinco_tienda_publica', 152),
      ('tienda_publica.mas_vendido_5_precio_anterior',     'Precio anterior más vendido cinco',          'Precio comparativo visible del quinto producto del bloque de más vendidos.',                    'string',  '$77.900', '$77.900', 'obtener_precio_anterior_mas_vendido_cinco_tienda_publica', 153),
      ('tienda_publica.mas_vendido_5_rating',              'Rating más vendido cinco',                   'Valoración visible del quinto producto del bloque de más vendidos.',                            'string',  '4.8', '4.8', 'obtener_rating_mas_vendido_cinco_tienda_publica', 154),
      ('tienda_publica.mas_vendido_5_envio',               'Envío más vendido cinco',                    'Texto visible de apoyo logístico del quinto producto del bloque de más vendidos.',              'string',  'Campaña glow night', 'Campaña glow night', 'obtener_envio_mas_vendido_cinco_tienda_publica', 155),
      ('tienda_publica.mas_vendido_6_etiqueta',            'Etiqueta más vendido seis',                  'Etiqueta visible del sexto producto del bloque de más vendidos.',                               'string',  'Gift set', 'Gift set', 'obtener_etiqueta_mas_vendido_seis_tienda_publica', 156),
      ('tienda_publica.mas_vendido_6_nombre',              'Nombre más vendido seis',                    'Nombre visible del sexto producto del bloque de más vendidos.',                                 'string',  'Mini kit beauty rose', 'Mini kit beauty rose', 'obtener_nombre_mas_vendido_seis_tienda_publica', 157),
      ('tienda_publica.mas_vendido_6_descripcion',         'Descripción más vendido seis',               'Descripción visible del sexto producto del bloque de más vendidos.',                            'string',  'Kit visual para fechas especiales, campañas de regalo y cierre de compra por impulso.', 'Kit visual para fechas especiales, campañas de regalo y cierre de compra por impulso.', 'obtener_descripcion_mas_vendido_seis_tienda_publica', 158),
      ('tienda_publica.mas_vendido_6_precio',              'Precio más vendido seis',                    'Precio visible del sexto producto del bloque de más vendidos.',                                 'string',  '$82.900', '$82.900', 'obtener_precio_mas_vendido_seis_tienda_publica', 159),
      ('tienda_publica.mas_vendido_6_precio_anterior',     'Precio anterior más vendido seis',           'Precio comparativo visible del sexto producto del bloque de más vendidos.',                     'string',  '$96.900', '$96.900', 'obtener_precio_anterior_mas_vendido_seis_tienda_publica', 160),
      ('tienda_publica.mas_vendido_6_rating',              'Rating más vendido seis',                    'Valoración visible del sexto producto del bloque de más vendidos.',                             'string',  '4.9', '4.9', 'obtener_rating_mas_vendido_seis_tienda_publica', 161),
      ('tienda_publica.mas_vendido_6_envio',               'Envío más vendido seis',                     'Texto visible de apoyo logístico del sexto producto del bloque de más vendidos.',               'string',  'Regalo listo para despacho', 'Regalo listo para despacho', 'obtener_envio_mas_vendido_seis_tienda_publica', 162),
      ('tienda_publica.faq_titulo',                        'Título preguntas frecuentes',                'Título principal del bloque de preguntas frecuentes.',                                           'string',  'Preguntas que refuerzan confianza de compra', 'Preguntas que refuerzan confianza de compra', 'obtener_titulo_faq_tienda_publica', 163),
      ('tienda_publica.faq_descripcion',                   'Descripción preguntas frecuentes',           'Descripción principal del bloque de preguntas frecuentes.',                                      'string',  'Queda un bloque listo para dudas frecuentes sobre envíos, pagos y tiempos de despacho.', 'Queda un bloque listo para dudas frecuentes sobre envíos, pagos y tiempos de despacho.', 'obtener_descripcion_faq_tienda_publica', 164),
      ('tienda_publica.faq_1_pregunta',                    'Pregunta frecuente uno',                     'Pregunta visible número uno del bloque FAQ.',                                                    'string',  '¿Cuánto tarda el despacho?', '¿Cuánto tarda el despacho?', 'obtener_pregunta_faq_uno_tienda_publica', 165),
      ('tienda_publica.faq_1_respuesta',                   'Respuesta frecuente uno',                    'Respuesta visible número uno del bloque FAQ.',                                                   'string',  'Queda parametrizado para tiempos nacionales y mensajes por temporada según la operación comercial.', 'Queda parametrizado para tiempos nacionales y mensajes por temporada según la operación comercial.', 'obtener_respuesta_faq_uno_tienda_publica', 166),
      ('tienda_publica.faq_2_pregunta',                    'Pregunta frecuente dos',                     'Pregunta visible número dos del bloque FAQ.',                                                    'string',  '¿Los medios de pago pueden ampliarse?', '¿Los medios de pago pueden ampliarse?', 'obtener_pregunta_faq_dos_tienda_publica', 167),
      ('tienda_publica.faq_2_respuesta',                   'Respuesta frecuente dos',                    'Respuesta visible número dos del bloque FAQ.',                                                   'string',  'Sí, la base queda lista para conectar configuraciones y pasarelas sin tocar el frente público.', 'Sí, la base queda lista para conectar configuraciones y pasarelas sin tocar el frente público.', 'obtener_respuesta_faq_dos_tienda_publica', 168),
      ('tienda_publica.faq_3_pregunta',                    'Pregunta frecuente tres',                    'Pregunta visible número tres del bloque FAQ.',                                                   'string',  '¿Se pueden activar campañas o temporadas nuevas?', '¿Se pueden activar campañas o temporadas nuevas?', 'obtener_pregunta_faq_tres_tienda_publica', 169),
      ('tienda_publica.faq_3_respuesta',                   'Respuesta frecuente tres',                    'Respuesta visible número tres del bloque FAQ.',                                                  'string',  'Sí, la portada sigue pensada para crecer por tema, campañas visuales y futuros catálogos sin rehacer la base.', 'Sí, la portada sigue pensada para crecer por tema, campañas visuales y futuros catálogos sin rehacer la base.', 'obtener_respuesta_faq_tres_tienda_publica', 170)
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
    nombre                = EXCLUDED.nombre,
    descripcion           = EXCLUDED.descripcion,
    tipo_dato             = EXCLUDED.tipo_dato,
    valor_defecto         = EXCLUDED.valor_defecto,
    valor                 = EXCLUDED.valor,
    accion                = EXCLUDED.accion,
    orden                 = EXCLUDED.orden,
    estado                = B'1',
    borrado               = B'0',
    usuario_modificacion  = 1,
    fecha_modificacion    = NOW();

-- 2. Se insertan componentes visuales adicionales del tema PINK_NUDE para los nuevos bloques del catálogo.
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
      ('line.card', 'background_color', '#FFF7F8', 'string', 'Color de fondo de las cards de líneas principales del catálogo.', 50),
      ('line.card', 'border_color',     '#ECD9DE', 'string', 'Color de borde de las cards de líneas principales del catálogo.', 51),
      ('faq.card',  'background_color', '#FFF8FA', 'string', 'Color de fondo de las cards FAQ del frente público.', 52),
      ('faq.card',  'border_color',     '#EDD6DC', 'string', 'Color de borde de las cards FAQ del frente público.', 53)
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
