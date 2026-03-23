-- 1. Se adiciona la columna fecha_ultimo_cierre_sesion a la tabla public.usuarios.
  ALTER TABLE public.usuarios
    ADD COLUMN IF NOT EXISTS fecha_ultimo_cierre_sesion timestamp without time zone;

  COMMENT ON COLUMN public.usuarios.fecha_ultimo_cierre_sesion IS 'Fecha del último cierre de sesión del usuario.';

-- 2. Se crea la tabla usuarios_sesiones en el esquema public.
  CREATE TABLE IF NOT EXISTS public.usuarios_sesiones (
    usuario_sesion_id        serial PRIMARY KEY          NOT NULL,
    usuario_id               integer                     NOT NULL,
    token                    character varying(255)      NOT NULL,
    fecha_inicio             timestamp without time zone NOT NULL DEFAULT NOW(),
    fecha_expiracion         timestamp without time zone NOT NULL,
    ip                       character varying(45),
    user_agent               character varying(255),
    estado                   bit(1)                      NOT NULL DEFAULT B'1',
    borrado                  bit(1)                      NOT NULL DEFAULT B'0',
    usuario_creacion         integer                     NOT NULL,
    fecha_creacion           timestamp without time zone NOT NULL DEFAULT NOW(),
    usuario_modificacion     integer,
    fecha_modificacion       timestamp without time zone,
    usuario_borrado          integer,
    fecha_borrado            timestamp without time zone
  );

  COMMENT ON TABLE public.usuarios_sesiones                         IS 'Tabla que almacena las sesiones activas e históricas del inicio de sesión.';
  COMMENT ON COLUMN public.usuarios_sesiones.usuario_sesion_id     IS '(PK) Identificador único de la sesión del usuario.';
  COMMENT ON COLUMN public.usuarios_sesiones.usuario_id            IS '(FK) Identificador del usuario.';
  COMMENT ON COLUMN public.usuarios_sesiones.token                 IS 'Token único asociado a la sesión.';
  COMMENT ON COLUMN public.usuarios_sesiones.fecha_inicio          IS 'Fecha de inicio de la sesión.';
  COMMENT ON COLUMN public.usuarios_sesiones.fecha_expiracion      IS 'Fecha de expiración del token de sesión.';
  COMMENT ON COLUMN public.usuarios_sesiones.ip                    IS 'Dirección IP desde la cual se inició la sesión.';
  COMMENT ON COLUMN public.usuarios_sesiones.user_agent            IS 'User agent del navegador o cliente que inició la sesión.';
  COMMENT ON COLUMN public.usuarios_sesiones.estado                IS 'Estado del registro: (0) Inactivo, (1) Activo.';
  COMMENT ON COLUMN public.usuarios_sesiones.borrado               IS 'Borrado lógico: (0) No, (1) Sí.';
  COMMENT ON COLUMN public.usuarios_sesiones.usuario_creacion      IS '(FK) Usuario que crea el registro.';
  COMMENT ON COLUMN public.usuarios_sesiones.fecha_creacion        IS 'Fecha de creación del registro.';
  COMMENT ON COLUMN public.usuarios_sesiones.usuario_modificacion  IS '(FK) Usuario que modifica el registro.';
  COMMENT ON COLUMN public.usuarios_sesiones.fecha_modificacion    IS 'Fecha de modificación del registro.';
  COMMENT ON COLUMN public.usuarios_sesiones.usuario_borrado       IS '(FK) Usuario que realiza el borrado lógico.';
  COMMENT ON COLUMN public.usuarios_sesiones.fecha_borrado         IS 'Fecha del borrado lógico.';

-- 3. Se agregan las restricciones de la tabla public.usuarios_sesiones.
  ALTER TABLE public.usuarios_sesiones ADD CONSTRAINT uq_pub_use_token   UNIQUE (token);
  ALTER TABLE public.usuarios_sesiones ADD CONSTRAINT fk_pub_use_usr      FOREIGN KEY (usuario_id)           REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  ALTER TABLE public.usuarios_sesiones ADD CONSTRAINT fk_pub_use_usr_cre  FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  ALTER TABLE public.usuarios_sesiones ADD CONSTRAINT fk_pub_use_usr_mod  FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  ALTER TABLE public.usuarios_sesiones ADD CONSTRAINT fk_pub_use_usr_bor  FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

-- 4. Se crean los índices base del inicio de sesión.
  CREATE INDEX IF NOT EXISTS idx_pub_use_usuario       ON public.usuarios_sesiones (usuario_id);
  CREATE INDEX IF NOT EXISTS idx_pub_use_token_estado  ON public.usuarios_sesiones (token, estado, borrado);

-- 5. Se sincroniza la secuencia de la tabla public.usuarios_sesiones.
  SELECT setval(pg_get_serial_sequence('public.usuarios_sesiones', 'usuario_sesion_id'), COALESCE(MAX(usuario_sesion_id), 1), TRUE)
  FROM public.usuarios_sesiones;
