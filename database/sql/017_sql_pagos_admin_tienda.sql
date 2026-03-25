-- 1. Se inserta el permiso para consultar pagos en el panel tienda.
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
    'TIENDA_PAGOS_VER',
    'Ver pagos tienda',
    'Permite consultar transacciones y referencias registradas por la pasarela base de la tienda.',
    'TIENDA_ADMIN',
    'consulta',
    40,
    B'1',
    B'0',
    1,
    NOW()
  WHERE NOT EXISTS
  (
    SELECT 1
    FROM public.permisos
    WHERE codigo = 'TIENDA_PAGOS_VER'
  );

-- 2. Se asigna el permiso de pagos al rol TIENDA_ADMIN.
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
    rol.rol_id,
    per.permiso_id,
    B'1',
    B'0',
    1,
    NOW()
  FROM public.roles rol
  INNER JOIN public.permisos per
    ON per.codigo = 'TIENDA_PAGOS_VER'
  WHERE rol.codigo = 'TIENDA_ADMIN'
    AND NOT EXISTS
    (
      SELECT 1
      FROM public.roles_permisos rpe
      WHERE rpe.rol_id = rol.rol_id
        AND rpe.permiso_id = per.permiso_id
    );
