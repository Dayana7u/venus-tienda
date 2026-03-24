-- ESTRUCTURA SQL DESARROLLO.
  -- 1. Se crea la tabla de sesiones para la administración de tienda.
    -- General.
      CREATE TABLE IF NOT EXISTS public.usuarios_sesiones_tienda (
        usuario_sesion_tienda_id  serial PRIMARY KEY          NOT NULL,
        usuario_id                integer                     NOT NULL,
        token                     character varying(120)      NOT NULL,
        fecha_inicio              timestamp without time zone NOT NULL DEFAULT NOW(),
        fecha_expiracion          timestamp without time zone NOT NULL,
        ip                        character varying(80),
        user_agent                text,
        estado                    bit(1)                      NOT NULL DEFAULT B'1',
        borrado                   bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion          integer                     NOT NULL,
        fecha_creacion            timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion      integer,
        fecha_modificacion        timestamp without time zone,
        usuario_borrado           integer,
        fecha_borrado             timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE public.usuarios_sesiones_tienda                            IS 'Tabla que almacena las sesiones activas del panel administrativo de tienda.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.usuario_sesion_tienda_id  IS '(PK) Identificador único de la sesión de tienda.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.usuario_id                IS '(FK) Usuario asociado a la sesión de tienda.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.token                     IS 'Token único generado para la sesión de tienda.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.fecha_inicio              IS 'Fecha de inicio de la sesión de tienda.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.fecha_expiracion          IS 'Fecha de expiración de la sesión de tienda.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.ip                        IS 'IP de origen de la sesión de tienda.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.user_agent                IS 'Navegador o cliente de la sesión de tienda.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.estado                    IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.borrado                   IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.usuario_creacion          IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.fecha_creacion            IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.usuario_modificacion      IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.fecha_modificacion        IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.usuario_borrado           IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.usuarios_sesiones_tienda.fecha_borrado             IS 'Fecha del borrado lógico.';
    -- Restricciones.
      DO $$
      BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_ust_usr') THEN
          ALTER TABLE public.usuarios_sesiones_tienda
            ADD CONSTRAINT fk_pub_ust_usr FOREIGN KEY (usuario_id) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_ust_usr_cre') THEN
          ALTER TABLE public.usuarios_sesiones_tienda
            ADD CONSTRAINT fk_pub_ust_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_ust_usr_mod') THEN
          ALTER TABLE public.usuarios_sesiones_tienda
            ADD CONSTRAINT fk_pub_ust_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_ust_usr_bor') THEN
          ALTER TABLE public.usuarios_sesiones_tienda
            ADD CONSTRAINT fk_pub_ust_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;
      END $$;
  -- 2. Se crean índices para la validación de sesiones de tienda.
    -- General.
      CREATE INDEX IF NOT EXISTS idx_pub_ust_usuario_token   ON public.usuarios_sesiones_tienda (usuario_id, token);
      CREATE INDEX IF NOT EXISTS idx_pub_ust_fecha_estado    ON public.usuarios_sesiones_tienda (fecha_expiracion, estado, borrado);
  -- 3. Se registra el rol base del panel administrativo de tienda.
    -- General.
      INSERT INTO public.roles
      (
        codigo,
        nombre,
        descripcion,
        sw_predeterminado,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      VALUES
      (
        'TIENDA_ADMIN',
        'Administrador de tienda',
        'Rol base para gestionar categorías, productos e imágenes de la tienda.',
        B'0',
        B'1',
        B'0',
        1,
        NOW()
      )
      ON CONFLICT (codigo) DO NOTHING;
  -- 4. Se registran permisos base para la administración de tienda.
    -- General.
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
      VALUES
        ('TIENDA_LOGIN',               'Acceso panel tienda',        'Permite ingresar al panel administrativo de tienda.',           'TIENDA_ADMIN', 'acceso',    10, B'1', B'0', 1, NOW()),
        ('TIENDA_CATEGORIAS_GUARDAR',  'Guardar categorías tienda',  'Permite crear categorías dentro del panel de tienda.',          'TIENDA_ADMIN', 'guardar',   11, B'1', B'0', 1, NOW()),
        ('TIENDA_PRODUCTOS_GUARDAR',   'Guardar productos tienda',   'Permite crear productos dentro del panel de tienda.',           'TIENDA_ADMIN', 'guardar',   12, B'1', B'0', 1, NOW()),
        ('TIENDA_IMAGENES_GUARDAR',    'Guardar imágenes tienda',    'Permite registrar imágenes para los productos de la tienda.',   'TIENDA_ADMIN', 'guardar',   13, B'1', B'0', 1, NOW())
      ON CONFLICT (codigo) DO NOTHING;
  -- 5. Se relaciona el rol de tienda con los permisos base.
    -- General.
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
        ON pub_per.codigo IN ('TIENDA_LOGIN', 'TIENDA_CATEGORIAS_GUARDAR', 'TIENDA_PRODUCTOS_GUARDAR', 'TIENDA_IMAGENES_GUARDAR')
      WHERE pub_rol.codigo = 'TIENDA_ADMIN'
        AND pub_rol.estado = B'1'
        AND pub_rol.borrado = B'0'
        AND NOT EXISTS (
          SELECT 1
          FROM public.roles_permisos pub_rpe
          WHERE pub_rpe.rol_id = pub_rol.rol_id
            AND pub_rpe.permiso_id = pub_per.permiso_id
        );
  -- 6. Se asigna el rol de tienda al usuario administrador inicial.
    -- General.
      INSERT INTO public.usuarios_roles
      (
        usuario_id,
        rol_id,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      SELECT
        1,
        pub_rol.rol_id,
        B'1',
        B'0',
        1,
        NOW()
      FROM public.roles pub_rol
      WHERE pub_rol.codigo = 'TIENDA_ADMIN'
        AND pub_rol.estado = B'1'
        AND pub_rol.borrado = B'0'
        AND NOT EXISTS (
          SELECT 1
          FROM public.usuarios_roles pub_uro
          WHERE pub_uro.usuario_id = 1
            AND pub_uro.rol_id = pub_rol.rol_id
        );
