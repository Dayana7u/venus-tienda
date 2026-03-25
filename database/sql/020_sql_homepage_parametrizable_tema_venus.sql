-- 1. Se agregan configuraciones del homepage VENUS para controlar producto hero, destacados y banners sin quemar contenido en la vista.
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
      ('tienda_publica.home_hero_producto_slug',   'Slug producto hero',              'Producto que se mostrará en el bloque hero principal del homepage.',                 'string', '', '', 'obtener_producto_hero_home_tienda_publica', 190),
      ('tienda_publica.home_destacado_1_slug',     'Slug destacado uno home',         'Primer producto parametrizable del bloque de destacados del homepage.',               'string', '', '', 'obtener_producto_destacado_uno_home_tienda_publica', 191),
      ('tienda_publica.home_destacado_2_slug',     'Slug destacado dos home',         'Segundo producto parametrizable del bloque de destacados del homepage.',              'string', '', '', 'obtener_producto_destacado_dos_home_tienda_publica', 192),
      ('tienda_publica.home_destacado_3_slug',     'Slug destacado tres home',        'Tercer producto parametrizable del bloque de destacados del homepage.',               'string', '', '', 'obtener_producto_destacado_tres_home_tienda_publica', 193),
      ('tienda_publica.home_destacado_4_slug',     'Slug destacado cuatro home',      'Cuarto producto parametrizable del bloque de destacados del homepage.',               'string', '', '', 'obtener_producto_destacado_cuatro_home_tienda_publica', 194),
      ('tienda_publica.home_banner_principal_url', 'Banner principal home',           'Ruta o URL opcional del banner principal del homepage para reemplazar la imagen base.','string', '', '', 'obtener_banner_principal_home_tienda_publica', 195),
      ('tienda_publica.home_banner_secundario_url','Banner secundario home',          'Ruta o URL opcional del banner secundario del homepage.',                             'string', '', '', 'obtener_banner_secundario_home_tienda_publica', 196)
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
  ) ON sys_mod.codigo = 'TIENDA_PUBLICA'
  WHERE NOT EXISTS (
    SELECT 1
    FROM system.modulo_configuraciones sys_mco
    WHERE sys_mco.modulo_id = sys_mod.modulo_id
      AND sys_mco.codigo = v.codigo
  );
