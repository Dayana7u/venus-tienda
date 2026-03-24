-- ESTRUCTURA SQL DESARROLLO.
  -- 1. Se agregan columnas de imagen a las categorías comerciales.
    -- General.
      ALTER TABLE public.categorias ADD COLUMN IF NOT EXISTS imagen_url text;
      ALTER TABLE public.categorias ADD COLUMN IF NOT EXISTS texto_alternativo character varying(180);
    -- Documentación.
      COMMENT ON COLUMN public.categorias.imagen_url        IS 'Ruta o URL visible de la imagen principal de la categoría.';
      COMMENT ON COLUMN public.categorias.texto_alternativo IS 'Texto alternativo de la imagen principal de la categoría.';
  -- 2. Se restablece la clave del usuario administrador base para acceso administrativo y administración de tienda.
    -- General.
      UPDATE public.usuarios
      SET
        clave               = '$2y$12$8SyF2vR.67LpONDpUM5KB.Vmu76IBvdLfPdAQ3JIf6OjsSLp6pKl2',
        usuario_modificacion = 1,
        fecha_modificacion   = NOW()
      WHERE login = 'admin'
        AND estado = B'1'
        AND borrado = B'0';
