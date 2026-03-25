-- 1. Se insertan permisos adicionales para la operación del panel tienda.
  INSERT INTO public.permisos
  (
    codigo,
    nombre,
    descripcion,
    modulo,
    tipo_permiso,
    orden,
    estado,
    borrado,
    usuario_creacion,
    fecha_creacion
  )
  SELECT
    per.codigo,
    per.nombre,
    per.descripcion,
    per.modulo,
    per.tipo_permiso,
    per.orden,
    B'1',
    B'0',
    1,
    NOW()
  FROM
  (
    VALUES
      ('TIENDA_DASHBOARD_VER',         'Ver dashboard tienda',              'Permite consultar el dashboard del panel tienda.',                           'TIENDA_ADMIN', 'consulta', 17),
      ('TIENDA_CATEGORIAS_VER',        'Ver categorías tienda',             'Permite consultar categorías en el panel tienda.',                           'TIENDA_ADMIN', 'consulta', 18),
      ('TIENDA_CATEGORIAS_EDITAR',     'Editar categorías tienda',          'Permite modificar categorías registradas.',                                  'TIENDA_ADMIN', 'editar',   19),
      ('TIENDA_CATEGORIAS_ACTIVAR',    'Activar categorías tienda',         'Permite activar categorías dentro del panel tienda.',                         'TIENDA_ADMIN', 'activar',  20),
      ('TIENDA_CATEGORIAS_INACTIVAR',  'Inactivar categorías tienda',       'Permite inactivar categorías dentro del panel tienda.',                       'TIENDA_ADMIN', 'inactivar',21),
      ('TIENDA_PRODUCTOS_VER',         'Ver productos tienda',              'Permite consultar productos en el panel tienda.',                            'TIENDA_ADMIN', 'consulta', 22),
      ('TIENDA_PRODUCTOS_EDITAR',      'Editar productos tienda',           'Permite modificar productos registrados.',                                   'TIENDA_ADMIN', 'editar',   23),
      ('TIENDA_PRODUCTOS_ACTIVAR',     'Activar productos tienda',          'Permite activar productos en el panel tienda.',                              'TIENDA_ADMIN', 'activar',  24),
      ('TIENDA_PRODUCTOS_INACTIVAR',   'Inactivar productos tienda',        'Permite inactivar productos en el panel tienda.',                            'TIENDA_ADMIN', 'inactivar',25),
      ('TIENDA_PRODUCTOS_ELIMINAR',    'Eliminar productos tienda',         'Permite ejecutar borrado lógico de productos.',                              'TIENDA_ADMIN', 'borrar',   26),
      ('TIENDA_IMAGENES_VER',          'Ver imágenes tienda',               'Permite consultar imágenes del catálogo.',                                   'TIENDA_ADMIN', 'consulta', 27),
      ('TIENDA_IMAGENES_EDITAR',       'Editar imágenes tienda',            'Permite editar imágenes registradas.',                                       'TIENDA_ADMIN', 'editar',   28),
      ('TIENDA_IMAGENES_ACTIVAR',      'Activar imágenes tienda',           'Permite activar imágenes del catálogo.',                                     'TIENDA_ADMIN', 'activar',  29),
      ('TIENDA_IMAGENES_INACTIVAR',    'Inactivar imágenes tienda',         'Permite inactivar imágenes del catálogo.',                                   'TIENDA_ADMIN', 'inactivar',30),
      ('TIENDA_CLIENTES_EDITAR',       'Editar clientes tienda',            'Permite actualizar información base de clientes.',                           'TIENDA_ADMIN', 'editar',   31),
      ('TIENDA_PEDIDOS_DETALLE',       'Ver detalle pedidos tienda',        'Permite abrir el detalle de pedidos en pantalla.',                           'TIENDA_ADMIN', 'consulta', 32),
      ('TIENDA_PEDIDOS_MARCAR_PAGADO', 'Marcar pago pedidos tienda',        'Permite marcar pedidos como pagados.',                                       'TIENDA_ADMIN', 'editar',   33),
      ('TIENDA_PEDIDOS_MARCAR_ENVIADO','Marcar envío pedidos tienda',       'Permite marcar pedidos como enviados.',                                      'TIENDA_ADMIN', 'editar',   34)
  ) AS per(codigo, nombre, descripcion, modulo, tipo_permiso, orden)
  WHERE NOT EXISTS
  (
    SELECT 1
    FROM public.permisos pub_per
    WHERE pub_per.codigo = per.codigo
  );

-- 2. Se asignan permisos del panel tienda al rol TIENDA_ADMIN.
  INSERT INTO public.roles_permisos
  (
    rol_id,
    permiso_id,
    estado,
    borrado,
    usuario_creacion,
    fecha_creacion
  )
  SELECT
    pub_rol.rol_id,
    pub_per.permiso_id,
    B'1',
    B'0',
    1,
    NOW()
  FROM public.roles pub_rol
  INNER JOIN public.permisos pub_per
    ON pub_per.codigo IN
    (
      'TIENDA_LOGIN',
      'TIENDA_DASHBOARD_VER',
      'TIENDA_CATEGORIAS_VER',
      'TIENDA_CATEGORIAS_GUARDAR',
      'TIENDA_CATEGORIAS_EDITAR',
      'TIENDA_CATEGORIAS_ACTIVAR',
      'TIENDA_CATEGORIAS_INACTIVAR',
      'TIENDA_PRODUCTOS_VER',
      'TIENDA_PRODUCTOS_GUARDAR',
      'TIENDA_PRODUCTOS_EDITAR',
      'TIENDA_PRODUCTOS_ACTIVAR',
      'TIENDA_PRODUCTOS_INACTIVAR',
      'TIENDA_PRODUCTOS_ELIMINAR',
      'TIENDA_IMAGENES_VER',
      'TIENDA_IMAGENES_GUARDAR',
      'TIENDA_IMAGENES_EDITAR',
      'TIENDA_IMAGENES_ACTIVAR',
      'TIENDA_IMAGENES_INACTIVAR',
      'TIENDA_CLIENTES_VER',
      'TIENDA_CLIENTES_EDITAR',
      'TIENDA_PEDIDOS_VER',
      'TIENDA_PEDIDOS_DETALLE',
      'TIENDA_PEDIDOS_MARCAR_PAGADO',
      'TIENDA_PEDIDOS_MARCAR_ENVIADO',
      'TIENDA_VENTAS_VER'
    )
  WHERE pub_rol.codigo = 'TIENDA_ADMIN'
    AND NOT EXISTS
    (
      SELECT 1
      FROM public.roles_permisos pub_rpe
      WHERE pub_rpe.rol_id = pub_rol.rol_id
        AND pub_rpe.permiso_id = pub_per.permiso_id
    );
