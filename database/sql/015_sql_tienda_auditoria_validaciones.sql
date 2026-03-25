-- 1. Se crea la tabla de auditoría del panel tienda.
  CREATE TABLE IF NOT EXISTS public.tienda_admin_auditoria (
    tienda_admin_auditoria_id  serial PRIMARY KEY          NOT NULL,
    modulo                     character varying(80)       NOT NULL,
    entidad                    character varying(80)       NOT NULL,
    registro_id                integer                     NOT NULL,
    accion                     character varying(40)       NOT NULL,
    descripcion                text                        NOT NULL,
    detalle_json               text,
    usuario_id                 integer                     NOT NULL,
    usuario_nombre             character varying(180)      NOT NULL,
    fecha_evento               timestamp without time zone NOT NULL DEFAULT NOW(),
    estado                     bit(1)                      NOT NULL DEFAULT B'1',
    borrado                    bit(1)                      NOT NULL DEFAULT B'0',
    usuario_creacion           integer                     NOT NULL,
    fecha_creacion             timestamp without time zone NOT NULL DEFAULT NOW(),
    usuario_modificacion       integer,
    fecha_modificacion         timestamp without time zone,
    usuario_borrado            integer,
    fecha_borrado              timestamp without time zone
  );

-- 2. Se documenta la tabla de auditoría del panel tienda.
  COMMENT ON TABLE public.tienda_admin_auditoria                            IS 'Tabla que almacena la trazabilidad de acciones del panel tienda.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.tienda_admin_auditoria_id IS '(PK) Identificador único del evento de auditoría.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.modulo                    IS 'Módulo del panel tienda donde se ejecutó la acción.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.entidad                   IS 'Entidad afectada por la acción.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.registro_id               IS 'Identificador del registro afectado.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.accion                    IS 'Acción ejecutada sobre el registro.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.descripcion               IS 'Descripción legible del cambio ejecutado.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.detalle_json              IS 'Detalle técnico serializado del evento.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.usuario_id                IS '(FK) Usuario que ejecuta la acción.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.usuario_nombre            IS 'Nombre visible del usuario que ejecuta la acción.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.fecha_evento              IS 'Fecha y hora exacta del evento.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.estado                    IS 'Estado del registro: (0) Inactivo, (1) Activo.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.borrado                   IS 'Borrado lógico: (0) No, (1) Sí.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.usuario_creacion          IS '(FK) Usuario que crea el registro.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.fecha_creacion            IS 'Fecha de creación del registro.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.usuario_modificacion      IS '(FK) Usuario que modifica el registro.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.fecha_modificacion        IS 'Fecha de modificación del registro.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.usuario_borrado           IS '(FK) Usuario que realiza el borrado lógico.';
  COMMENT ON COLUMN public.tienda_admin_auditoria.fecha_borrado             IS 'Fecha del borrado lógico.';

-- 3. Se agregan restricciones e índices de auditoría.
  DO $$
  BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_taa_usr_cre') THEN
      ALTER TABLE public.tienda_admin_auditoria
        ADD CONSTRAINT fk_pub_taa_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_taa_usr_mod') THEN
      ALTER TABLE public.tienda_admin_auditoria
        ADD CONSTRAINT fk_pub_taa_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_taa_usr_bor') THEN
      ALTER TABLE public.tienda_admin_auditoria
        ADD CONSTRAINT fk_pub_taa_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
    END IF;
  END $$;

  CREATE INDEX IF NOT EXISTS idx_pub_taa_modulo_fecha   ON public.tienda_admin_auditoria (modulo, fecha_evento DESC);
  CREATE INDEX IF NOT EXISTS idx_pub_taa_entidad_reg    ON public.tienda_admin_auditoria (entidad, registro_id);

-- 4. Se insertan permisos adicionales para auditoría y eliminación de categorías.
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
      ('TIENDA_CATEGORIAS_ELIMINAR', 'Eliminar categorías tienda', 'Permite ejecutar borrado lógico de categorías sin productos asociados.', 'TIENDA_ADMIN', 'borrar',   35),
      ('TIENDA_AUDITORIA_VER',       'Ver auditoría tienda',       'Permite consultar la trazabilidad del panel tienda.',                       'TIENDA_ADMIN', 'consulta', 36)
  ) AS per(codigo, nombre, descripcion, modulo, tipo_permiso, orden)
  WHERE NOT EXISTS
  (
    SELECT 1
    FROM public.permisos pub_per
    WHERE pub_per.codigo = per.codigo
  );

-- 5. Se asignan permisos al rol TIENDA_ADMIN.
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
      'TIENDA_CATEGORIAS_ELIMINAR',
      'TIENDA_AUDITORIA_VER'
    )
  WHERE pub_rol.codigo = 'TIENDA_ADMIN'
    AND NOT EXISTS
    (
      SELECT 1
      FROM public.roles_permisos pub_rpe
      WHERE pub_rpe.rol_id = pub_rol.rol_id
        AND pub_rpe.permiso_id = pub_per.permiso_id
    );
