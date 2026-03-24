-- ESTRUCTURA SQL DESARROLLO.
  -- 1. Se ajusta la documentación de la columna clave en usuarios.
    -- Documentación.
      COMMENT ON COLUMN public.usuarios.clave IS 'Hash de la clave del usuario.';

  -- 2. Se habilita pgcrypto para migrar claves legadas a bcrypt.
    -- General.
      CREATE EXTENSION IF NOT EXISTS pgcrypto;

  -- 3. Se migran las claves sin hash a bcrypt.
    -- General.
      UPDATE public.usuarios
      SET
        clave = crypt(clave, gen_salt('bf')),
        usuario_modificacion = COALESCE(usuario_modificacion, usuario_creacion, usuario_id),
        fecha_modificacion = NOW()
      WHERE
        clave IS NOT NULL
        AND clave <> ''
        AND clave NOT LIKE '$2a$%'
        AND clave NOT LIKE '$2b$%'
        AND clave NOT LIKE '$2y$%';
