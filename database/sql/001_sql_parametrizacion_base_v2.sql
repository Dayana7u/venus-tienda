-- ESTRUCTURA SQL DESARROLLO.
  -- 1. Se crea el esquema system.
    -- General.
      CREATE SCHEMA IF NOT EXISTS system;
    -- Documentación.
      COMMENT ON SCHEMA system IS 'Esquema que almacena la parametrización global y reusable de la tienda virtual.';
  -- 2. Se eliminan las tablas si existen para permitir la reinstalación controlada.
    -- General.
      DROP TABLE IF EXISTS system.logs_aplicacion;
      DROP TABLE IF EXISTS system.bitacora_cambios;
      DROP TABLE IF EXISTS system.menus;
      DROP TABLE IF EXISTS system.plantillas;
      DROP TABLE IF EXISTS system.integracion_configuraciones;
      DROP TABLE IF EXISTS system.integraciones;
      DROP TABLE IF EXISTS system.modulo_configuraciones;
      DROP TABLE IF EXISTS system.modulos;
      DROP TABLE IF EXISTS system.parametro_valores;
      DROP TABLE IF EXISTS system.parametros;
      DROP TABLE IF EXISTS system.parametro_grupos;
      DROP TABLE IF EXISTS system.branding;
      DROP TABLE IF EXISTS system.tema_componentes;
      DROP TABLE IF EXISTS system.tema_tokens;
      DROP TABLE IF EXISTS system.temas;
      DROP TABLE IF EXISTS public.usuarios_roles;
      DROP TABLE IF EXISTS public.roles_permisos;
      DROP TABLE IF EXISTS public.permisos;
      DROP TABLE IF EXISTS public.roles;
      DROP TABLE IF EXISTS public.usuarios;
  -- 3. Se crea la tabla usuarios en el esquema public.
    -- General.
      CREATE TABLE public.usuarios (
        usuario_id             serial PRIMARY KEY          NOT NULL,
        nombres                character varying(150)      NOT NULL,
        apellidos              character varying(150)      NOT NULL,
        login                  character varying(60)       NOT NULL,
        correo                 character varying(150)      NOT NULL,
        clave                  character varying(255)      NOT NULL,
        sw_superusuario        bit(1)                      NOT NULL DEFAULT B'0',
        ultimo_ingreso         timestamp without time zone,
        token_recuperacion     character varying(255),
        fecha_expiracion_token timestamp without time zone,
        estado                 bit(1)                      NOT NULL DEFAULT B'1',
        borrado                bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion       integer                     NOT NULL,
        fecha_creacion         timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion   integer,
        fecha_modificacion     timestamp without time zone,
        usuario_borrado        integer,
        fecha_borrado          timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE public.usuarios                           IS 'Tabla que almacena los usuarios de acceso de la aplicación.';
      COMMENT ON COLUMN public.usuarios.usuario_id              IS '(PK) Identificador único del usuario.';
      COMMENT ON COLUMN public.usuarios.nombres                 IS 'Nombres del usuario.';
      COMMENT ON COLUMN public.usuarios.apellidos               IS 'Apellidos del usuario.';
      COMMENT ON COLUMN public.usuarios.login                   IS 'Login del usuario.';
      COMMENT ON COLUMN public.usuarios.correo                  IS 'Correo electrónico del usuario.';
      COMMENT ON COLUMN public.usuarios.clave                   IS 'Hash de la clave del usuario.';
      COMMENT ON COLUMN public.usuarios.sw_superusuario         IS 'Superusuario: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.usuarios.ultimo_ingreso          IS 'Fecha del último ingreso del usuario.';
      COMMENT ON COLUMN public.usuarios.token_recuperacion      IS 'Token temporal para recuperación de acceso.';
      COMMENT ON COLUMN public.usuarios.fecha_expiracion_token  IS 'Fecha de expiración del token de recuperación.';
      COMMENT ON COLUMN public.usuarios.estado                  IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.usuarios.borrado                 IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.usuarios.usuario_creacion        IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.usuarios.fecha_creacion          IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.usuarios.usuario_modificacion    IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.usuarios.fecha_modificacion      IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.usuarios.usuario_borrado         IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.usuarios.fecha_borrado           IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE public.usuarios ADD CONSTRAINT uq_pub_usr_login  UNIQUE (login);
      ALTER TABLE public.usuarios ADD CONSTRAINT uq_pub_usr_correo UNIQUE (correo);
  -- 4. Se registra el usuario administrador base para habilitar las llaves foráneas de trazabilidad.
    -- General.
      INSERT INTO public.usuarios
      (
        usuario_id,
        nombres,
        apellidos,
        login,
        correo,
        clave,
        sw_superusuario,
        ultimo_ingreso,
        token_recuperacion,
        fecha_expiracion_token,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
      (
        1,
        'Administrador',
        'Inicial',
        'admin',
        'admin@localhost',
        '$2y$12$5AeD/e9B7B8400QW4DxaXO958ag4x.eu0f3iQ5XEZIuAjGYQsQryu',
        B'1',
        NULL,
        NULL,
        NULL,
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      );
  -- 5. Se agregan las restricciones de trazabilidad para la tabla usuarios.
    -- Restricciones.
      ALTER TABLE public.usuarios ADD CONSTRAINT fk_pub_usr_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.usuarios ADD CONSTRAINT fk_pub_usr_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.usuarios ADD CONSTRAINT fk_pub_usr_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 6. Se crea la tabla roles en el esquema public.
    -- General.
      CREATE TABLE public.roles (
        rol_id                 serial PRIMARY KEY          NOT NULL,
        codigo                 character varying(60)       NOT NULL,
        nombre                 character varying(150)      NOT NULL,
        descripcion            text,
        sw_predeterminado      bit(1)                      NOT NULL DEFAULT B'0',
        estado                 bit(1)                      NOT NULL DEFAULT B'1',
        borrado                bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion       integer                     NOT NULL,
        fecha_creacion         timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion   integer,
        fecha_modificacion     timestamp without time zone,
        usuario_borrado        integer,
        fecha_borrado          timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE public.roles                         IS 'Tabla que almacena los roles de acceso de la aplicación.';
      COMMENT ON COLUMN public.roles.rol_id                IS '(PK) Identificador único del rol.';
      COMMENT ON COLUMN public.roles.codigo                IS 'Código interno del rol.';
      COMMENT ON COLUMN public.roles.nombre                IS 'Nombre del rol.';
      COMMENT ON COLUMN public.roles.descripcion           IS 'Descripción funcional del rol.';
      COMMENT ON COLUMN public.roles.sw_predeterminado     IS 'Predeterminado: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.roles.estado                IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.roles.borrado               IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.roles.usuario_creacion      IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.roles.fecha_creacion        IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.roles.usuario_modificacion  IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.roles.fecha_modificacion    IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.roles.usuario_borrado       IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.roles.fecha_borrado         IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE public.roles ADD CONSTRAINT uq_pub_rol_codigo  UNIQUE (codigo);
      ALTER TABLE public.roles ADD CONSTRAINT fk_pub_rol_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.roles ADD CONSTRAINT fk_pub_rol_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.roles ADD CONSTRAINT fk_pub_rol_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 7. Se crea la tabla permisos en el esquema public.
    -- General.
      CREATE TABLE public.permisos (
        permiso_id              serial PRIMARY KEY          NOT NULL,
        codigo                  character varying(80)       NOT NULL,
        nombre                  character varying(150)      NOT NULL,
        descripcion             text,
        modulo                  character varying(80),
        tipo_permiso            character varying(40),
        orden                   integer                     NOT NULL DEFAULT 0,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE public.permisos                          IS 'Tabla que almacena los permisos funcionales de la aplicación.';
      COMMENT ON COLUMN public.permisos.permiso_id             IS '(PK) Identificador único del permiso.';
      COMMENT ON COLUMN public.permisos.codigo                 IS 'Código interno del permiso.';
      COMMENT ON COLUMN public.permisos.nombre                 IS 'Nombre del permiso.';
      COMMENT ON COLUMN public.permisos.descripcion            IS 'Descripción funcional del permiso.';
      COMMENT ON COLUMN public.permisos.modulo                 IS 'Módulo al que pertenece el permiso.';
      COMMENT ON COLUMN public.permisos.tipo_permiso           IS 'Tipo de permiso.';
      COMMENT ON COLUMN public.permisos.orden                  IS 'Orden visual del permiso.';
      COMMENT ON COLUMN public.permisos.estado                 IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.permisos.borrado                IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.permisos.usuario_creacion       IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.permisos.fecha_creacion         IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.permisos.usuario_modificacion   IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.permisos.fecha_modificacion     IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.permisos.usuario_borrado        IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.permisos.fecha_borrado          IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE public.permisos ADD CONSTRAINT uq_pub_per_codigo  UNIQUE (codigo);
      ALTER TABLE public.permisos ADD CONSTRAINT fk_pub_per_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.permisos ADD CONSTRAINT fk_pub_per_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.permisos ADD CONSTRAINT fk_pub_per_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 8. Se crea la tabla roles_permisos en el esquema public.
    -- General.
      CREATE TABLE public.roles_permisos (
        rol_permiso_id          serial PRIMARY KEY          NOT NULL,
        rol_id                  integer                     NOT NULL,
        permiso_id              integer                     NOT NULL,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE public.roles_permisos                        IS 'Tabla que relaciona roles con permisos.';
      COMMENT ON COLUMN public.roles_permisos.rol_permiso_id       IS '(PK) Identificador único de la relación rol permiso.';
      COMMENT ON COLUMN public.roles_permisos.rol_id               IS '(FK) Identificador del rol.';
      COMMENT ON COLUMN public.roles_permisos.permiso_id           IS '(FK) Identificador del permiso.';
      COMMENT ON COLUMN public.roles_permisos.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.roles_permisos.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.roles_permisos.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.roles_permisos.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.roles_permisos.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.roles_permisos.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.roles_permisos.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.roles_permisos.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE public.roles_permisos ADD CONSTRAINT uq_pub_rol_per UNIQUE (rol_id, permiso_id);
      ALTER TABLE public.roles_permisos ADD CONSTRAINT fk_pub_rpe_rol     FOREIGN KEY (rol_id)               REFERENCES public.roles (rol_id)       ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.roles_permisos ADD CONSTRAINT fk_pub_rpe_per     FOREIGN KEY (permiso_id)           REFERENCES public.permisos (permiso_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.roles_permisos ADD CONSTRAINT fk_pub_rpe_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.roles_permisos ADD CONSTRAINT fk_pub_rpe_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.roles_permisos ADD CONSTRAINT fk_pub_rpe_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 9. Se crea la tabla usuarios_roles en el esquema public.
    -- General.
      CREATE TABLE public.usuarios_roles (
        usuario_rol_id          serial PRIMARY KEY          NOT NULL,
        usuario_id              integer                     NOT NULL,
        rol_id                  integer                     NOT NULL,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE public.usuarios_roles                        IS 'Tabla que relaciona usuarios con roles.';
      COMMENT ON COLUMN public.usuarios_roles.usuario_rol_id       IS '(PK) Identificador único de la relación usuario rol.';
      COMMENT ON COLUMN public.usuarios_roles.usuario_id           IS '(FK) Identificador del usuario.';
      COMMENT ON COLUMN public.usuarios_roles.rol_id               IS '(FK) Identificador del rol.';
      COMMENT ON COLUMN public.usuarios_roles.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.usuarios_roles.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.usuarios_roles.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.usuarios_roles.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.usuarios_roles.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.usuarios_roles.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.usuarios_roles.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.usuarios_roles.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE public.usuarios_roles ADD CONSTRAINT uq_pub_usr_rol UNIQUE (usuario_id, rol_id);
      ALTER TABLE public.usuarios_roles ADD CONSTRAINT fk_pub_uro_usr     FOREIGN KEY (usuario_id)           REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.usuarios_roles ADD CONSTRAINT fk_pub_uro_rol     FOREIGN KEY (rol_id)               REFERENCES public.roles (rol_id)       ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.usuarios_roles ADD CONSTRAINT fk_pub_uro_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.usuarios_roles ADD CONSTRAINT fk_pub_uro_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.usuarios_roles ADD CONSTRAINT fk_pub_uro_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 10. Se crea la tabla temas en el esquema system.
    -- General.
      CREATE TABLE system.temas (
        tema_id                 serial PRIMARY KEY          NOT NULL,
        codigo                  character varying(60)       NOT NULL,
        nombre                  character varying(150)      NOT NULL,
        descripcion             text,
        version                 character varying(20)       NOT NULL DEFAULT '1.0.0',
        sw_predeterminado       bit(1)                      NOT NULL DEFAULT B'0',
        orden                   integer                     NOT NULL DEFAULT 0,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.temas                        IS 'Tabla que almacena los temas visuales de la aplicación.';
      COMMENT ON COLUMN system.temas.tema_id              IS '(PK) Identificador único del tema.';
      COMMENT ON COLUMN system.temas.codigo               IS 'Código interno del tema.';
      COMMENT ON COLUMN system.temas.nombre               IS 'Nombre del tema.';
      COMMENT ON COLUMN system.temas.descripcion          IS 'Descripción funcional del tema.';
      COMMENT ON COLUMN system.temas.version              IS 'Versión del tema.';
      COMMENT ON COLUMN system.temas.sw_predeterminado    IS 'Predeterminado: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.temas.orden                IS 'Orden visual del tema.';
      COMMENT ON COLUMN system.temas.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.temas.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.temas.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.temas.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.temas.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.temas.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.temas.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.temas.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.temas ADD CONSTRAINT uq_sys_tem_codigo  UNIQUE (codigo);
      ALTER TABLE system.temas ADD CONSTRAINT fk_sys_tem_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.temas ADD CONSTRAINT fk_sys_tem_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.temas ADD CONSTRAINT fk_sys_tem_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 11. Se crea la tabla tema_tokens en el esquema system.
    -- General.
      CREATE TABLE system.tema_tokens (
        tema_token_id           serial PRIMARY KEY          NOT NULL,
        tema_id                 integer                     NOT NULL,
        grupo                   character varying(80)       NOT NULL,
        clave                   character varying(120)      NOT NULL,
        valor                   text                        NOT NULL,
        tipo_dato               character varying(30)       NOT NULL,
        descripcion             text,
        orden                   integer                     NOT NULL DEFAULT 0,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.tema_tokens                        IS 'Tabla que almacena los tokens configurables de cada tema.';
      COMMENT ON COLUMN system.tema_tokens.tema_token_id        IS '(PK) Identificador único del token del tema.';
      COMMENT ON COLUMN system.tema_tokens.tema_id              IS '(FK) Identificador del tema.';
      COMMENT ON COLUMN system.tema_tokens.grupo                IS 'Grupo funcional del token.';
      COMMENT ON COLUMN system.tema_tokens.clave                IS 'Clave única del token.';
      COMMENT ON COLUMN system.tema_tokens.valor                IS 'Valor configurado para el token.';
      COMMENT ON COLUMN system.tema_tokens.tipo_dato            IS 'Tipo de dato del valor del token.';
      COMMENT ON COLUMN system.tema_tokens.descripcion          IS 'Descripción funcional del token.';
      COMMENT ON COLUMN system.tema_tokens.orden                IS 'Orden visual del token.';
      COMMENT ON COLUMN system.tema_tokens.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.tema_tokens.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.tema_tokens.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.tema_tokens.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.tema_tokens.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.tema_tokens.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.tema_tokens.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.tema_tokens.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.tema_tokens ADD CONSTRAINT uq_sys_ttk_tema_cla UNIQUE (tema_id, grupo, clave);
      ALTER TABLE system.tema_tokens ADD CONSTRAINT fk_sys_ttk_tema    FOREIGN KEY (tema_id)               REFERENCES system.temas (tema_id)      ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.tema_tokens ADD CONSTRAINT fk_sys_ttk_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.tema_tokens ADD CONSTRAINT fk_sys_ttk_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.tema_tokens ADD CONSTRAINT fk_sys_ttk_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 12. Se crea la tabla tema_componentes en el esquema system.
    -- General.
      CREATE TABLE system.tema_componentes (
        tema_componente_id      serial PRIMARY KEY          NOT NULL,
        tema_id                 integer                     NOT NULL,
        componente              character varying(120)      NOT NULL,
        propiedad               character varying(120)      NOT NULL,
        valor                   text                        NOT NULL,
        tipo_dato               character varying(30)       NOT NULL,
        descripcion             text,
        orden                   integer                     NOT NULL DEFAULT 0,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.tema_componentes                         IS 'Tabla que almacena la parametrización visual por componente de cada tema.';
      COMMENT ON COLUMN system.tema_componentes.tema_componente_id    IS '(PK) Identificador único del componente del tema.';
      COMMENT ON COLUMN system.tema_componentes.tema_id               IS '(FK) Identificador del tema.';
      COMMENT ON COLUMN system.tema_componentes.componente            IS 'Nombre del componente visual.';
      COMMENT ON COLUMN system.tema_componentes.propiedad             IS 'Propiedad parametrizable del componente.';
      COMMENT ON COLUMN system.tema_componentes.valor                 IS 'Valor configurado para la propiedad.';
      COMMENT ON COLUMN system.tema_componentes.tipo_dato             IS 'Tipo de dato del valor.';
      COMMENT ON COLUMN system.tema_componentes.descripcion           IS 'Descripción funcional del componente.';
      COMMENT ON COLUMN system.tema_componentes.orden                 IS 'Orden visual del componente.';
      COMMENT ON COLUMN system.tema_componentes.estado                IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.tema_componentes.borrado               IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.tema_componentes.usuario_creacion      IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.tema_componentes.fecha_creacion        IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.tema_componentes.usuario_modificacion  IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.tema_componentes.fecha_modificacion    IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.tema_componentes.usuario_borrado       IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.tema_componentes.fecha_borrado         IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.tema_componentes ADD CONSTRAINT uq_sys_tco_tema_cmp UNIQUE (tema_id, componente, propiedad);
      ALTER TABLE system.tema_componentes ADD CONSTRAINT fk_sys_tco_tema    FOREIGN KEY (tema_id)               REFERENCES system.temas (tema_id)      ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.tema_componentes ADD CONSTRAINT fk_sys_tco_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.tema_componentes ADD CONSTRAINT fk_sys_tco_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.tema_componentes ADD CONSTRAINT fk_sys_tco_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 13. Se crea la tabla branding en el esquema system.
    -- General.
      CREATE TABLE system.branding (
        branding_id             serial PRIMARY KEY          NOT NULL,
        codigo                  character varying(60)       NOT NULL,
        nombre_comercial        character varying(150)      NOT NULL,
        razon_social            character varying(200),
        nit                     character varying(50),
        correo_contacto         character varying(150),
        telefono_contacto       character varying(60),
        direccion               character varying(255),
        logo_principal          character varying(255),
        logo_secundario         character varying(255),
        favicon                 character varying(255),
        banner_principal        character varying(255),
        mensaje_bienvenida      text,
        texto_footer            text,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.branding                        IS 'Tabla que almacena la identidad visual y corporativa de la aplicación.';
      COMMENT ON COLUMN system.branding.branding_id          IS '(PK) Identificador único del branding.';
      COMMENT ON COLUMN system.branding.codigo               IS 'Código interno del branding.';
      COMMENT ON COLUMN system.branding.nombre_comercial     IS 'Nombre comercial visible.';
      COMMENT ON COLUMN system.branding.razon_social         IS 'Razón social.';
      COMMENT ON COLUMN system.branding.nit                  IS 'Identificación tributaria.';
      COMMENT ON COLUMN system.branding.correo_contacto      IS 'Correo de contacto principal.';
      COMMENT ON COLUMN system.branding.telefono_contacto    IS 'Teléfono de contacto principal.';
      COMMENT ON COLUMN system.branding.direccion            IS 'Dirección principal.';
      COMMENT ON COLUMN system.branding.logo_principal       IS 'Ruta o nombre del logo principal.';
      COMMENT ON COLUMN system.branding.logo_secundario      IS 'Ruta o nombre del logo secundario.';
      COMMENT ON COLUMN system.branding.favicon              IS 'Ruta o nombre del favicon.';
      COMMENT ON COLUMN system.branding.banner_principal     IS 'Ruta o nombre del banner principal.';
      COMMENT ON COLUMN system.branding.mensaje_bienvenida   IS 'Mensaje de bienvenida visible.';
      COMMENT ON COLUMN system.branding.texto_footer         IS 'Texto visible del pie de página.';
      COMMENT ON COLUMN system.branding.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.branding.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.branding.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.branding.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.branding.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.branding.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.branding.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.branding.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.branding ADD CONSTRAINT uq_sys_bra_codigo  UNIQUE (codigo);
      ALTER TABLE system.branding ADD CONSTRAINT fk_sys_bra_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.branding ADD CONSTRAINT fk_sys_bra_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.branding ADD CONSTRAINT fk_sys_bra_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 14. Se crea la tabla parametro_grupos en el esquema system.
    -- General.
      CREATE TABLE system.parametro_grupos (
        parametro_grupo_id      serial PRIMARY KEY          NOT NULL,
        codigo                  character varying(60)       NOT NULL,
        nombre                  character varying(150)      NOT NULL,
        descripcion             text,
        orden                   integer                     NOT NULL DEFAULT 0,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.parametro_grupos                         IS 'Tabla que almacena los grupos de parámetros de la aplicación.';
      COMMENT ON COLUMN system.parametro_grupos.parametro_grupo_id    IS '(PK) Identificador único del grupo de parámetros.';
      COMMENT ON COLUMN system.parametro_grupos.codigo                IS 'Código interno del grupo.';
      COMMENT ON COLUMN system.parametro_grupos.nombre                IS 'Nombre del grupo.';
      COMMENT ON COLUMN system.parametro_grupos.descripcion           IS 'Descripción funcional del grupo.';
      COMMENT ON COLUMN system.parametro_grupos.orden                 IS 'Orden visual del grupo.';
      COMMENT ON COLUMN system.parametro_grupos.estado                IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.parametro_grupos.borrado               IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.parametro_grupos.usuario_creacion      IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.parametro_grupos.fecha_creacion        IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.parametro_grupos.usuario_modificacion  IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.parametro_grupos.fecha_modificacion    IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.parametro_grupos.usuario_borrado       IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.parametro_grupos.fecha_borrado         IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.parametro_grupos ADD CONSTRAINT uq_sys_pgr_codigo  UNIQUE (codigo);
      ALTER TABLE system.parametro_grupos ADD CONSTRAINT fk_sys_pgr_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.parametro_grupos ADD CONSTRAINT fk_sys_pgr_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.parametro_grupos ADD CONSTRAINT fk_sys_pgr_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 15. Se crea la tabla parametros en el esquema system.
    -- General.
      CREATE TABLE system.parametros (
        parametro_id            serial PRIMARY KEY          NOT NULL,
        parametro_grupo_id      integer                     NOT NULL,
        codigo                  character varying(100)      NOT NULL,
        nombre                  character varying(150)      NOT NULL,
        descripcion             text,
        tipo_dato               character varying(30)       NOT NULL,
        valor_defecto           text,
        sw_requerido            bit(1)                      NOT NULL DEFAULT B'0',
        sw_publico              bit(1)                      NOT NULL DEFAULT B'0',
        orden                   integer                     NOT NULL DEFAULT 0,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.parametros                      IS 'Tabla que almacena el catálogo maestro de parámetros.';
      COMMENT ON COLUMN system.parametros.parametro_id        IS '(PK) Identificador único del parámetro.';
      COMMENT ON COLUMN system.parametros.parametro_grupo_id  IS '(FK) Identificador del grupo del parámetro.';
      COMMENT ON COLUMN system.parametros.codigo              IS 'Código interno del parámetro.';
      COMMENT ON COLUMN system.parametros.nombre              IS 'Nombre del parámetro.';
      COMMENT ON COLUMN system.parametros.descripcion         IS 'Descripción funcional del parámetro.';
      COMMENT ON COLUMN system.parametros.tipo_dato           IS 'Tipo de dato del parámetro.';
      COMMENT ON COLUMN system.parametros.valor_defecto       IS 'Valor por defecto del parámetro.';
      COMMENT ON COLUMN system.parametros.sw_requerido        IS 'Requerido: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.parametros.sw_publico          IS 'Público: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.parametros.orden               IS 'Orden visual del parámetro.';
      COMMENT ON COLUMN system.parametros.estado              IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.parametros.borrado             IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.parametros.usuario_creacion    IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.parametros.fecha_creacion      IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.parametros.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.parametros.fecha_modificacion  IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.parametros.usuario_borrado     IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.parametros.fecha_borrado       IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.parametros ADD CONSTRAINT uq_sys_par_codigo  UNIQUE (codigo);
      ALTER TABLE system.parametros ADD CONSTRAINT fk_sys_par_pgr     FOREIGN KEY (parametro_grupo_id)       REFERENCES system.parametro_grupos (parametro_grupo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.parametros ADD CONSTRAINT fk_sys_par_usr_cre FOREIGN KEY (usuario_creacion)        REFERENCES public.usuarios (usuario_id)                 ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.parametros ADD CONSTRAINT fk_sys_par_usr_mod FOREIGN KEY (usuario_modificacion)    REFERENCES public.usuarios (usuario_id)                 ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.parametros ADD CONSTRAINT fk_sys_par_usr_bor FOREIGN KEY (usuario_borrado)         REFERENCES public.usuarios (usuario_id)                 ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 16. Se crea la tabla parametro_valores en el esquema system.
    -- General.
      CREATE TABLE system.parametro_valores (
        parametro_valor_id      serial PRIMARY KEY          NOT NULL,
        parametro_id            integer                     NOT NULL,
        valor                   text,
        observacion             text,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.parametro_valores                        IS 'Tabla que almacena el valor efectivo de cada parámetro.';
      COMMENT ON COLUMN system.parametro_valores.parametro_valor_id    IS '(PK) Identificador único del valor del parámetro.';
      COMMENT ON COLUMN system.parametro_valores.parametro_id          IS '(FK) Identificador del parámetro.';
      COMMENT ON COLUMN system.parametro_valores.valor                 IS 'Valor configurado para el parámetro.';
      COMMENT ON COLUMN system.parametro_valores.observacion           IS 'Observación funcional del valor configurado.';
      COMMENT ON COLUMN system.parametro_valores.estado                IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.parametro_valores.borrado               IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.parametro_valores.usuario_creacion      IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.parametro_valores.fecha_creacion        IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.parametro_valores.usuario_modificacion  IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.parametro_valores.fecha_modificacion    IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.parametro_valores.usuario_borrado       IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.parametro_valores.fecha_borrado         IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.parametro_valores ADD CONSTRAINT uq_sys_pva_parametro UNIQUE (parametro_id);
      ALTER TABLE system.parametro_valores ADD CONSTRAINT fk_sys_pva_par     FOREIGN KEY (parametro_id)           REFERENCES system.parametros (parametro_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.parametro_valores ADD CONSTRAINT fk_sys_pva_usr_cre FOREIGN KEY (usuario_creacion)      REFERENCES public.usuarios (usuario_id)     ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.parametro_valores ADD CONSTRAINT fk_sys_pva_usr_mod FOREIGN KEY (usuario_modificacion)  REFERENCES public.usuarios (usuario_id)     ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.parametro_valores ADD CONSTRAINT fk_sys_pva_usr_bor FOREIGN KEY (usuario_borrado)       REFERENCES public.usuarios (usuario_id)     ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 17. Se crea la tabla modulos en el esquema system.
    -- General.
      CREATE TABLE system.modulos (
        modulo_id               serial PRIMARY KEY          NOT NULL,
        codigo                  character varying(80)       NOT NULL,
        nombre                  character varying(150)      NOT NULL,
        descripcion             text,
        ruta                    character varying(255),
        icono                   character varying(100),
        orden                   integer                     NOT NULL DEFAULT 0,
        sw_visible_menu         bit(1)                      NOT NULL DEFAULT B'1',
        sw_requiere_login       bit(1)                      NOT NULL DEFAULT B'1',
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.modulos                        IS 'Tabla que almacena los módulos habilitables de la aplicación.';
      COMMENT ON COLUMN system.modulos.modulo_id            IS '(PK) Identificador único del módulo.';
      COMMENT ON COLUMN system.modulos.codigo               IS 'Código interno del módulo.';
      COMMENT ON COLUMN system.modulos.nombre               IS 'Nombre del módulo.';
      COMMENT ON COLUMN system.modulos.descripcion          IS 'Descripción funcional del módulo.';
      COMMENT ON COLUMN system.modulos.ruta                 IS 'Ruta base del módulo.';
      COMMENT ON COLUMN system.modulos.icono                IS 'Ícono asociado al módulo.';
      COMMENT ON COLUMN system.modulos.orden                IS 'Orden visual del módulo.';
      COMMENT ON COLUMN system.modulos.sw_visible_menu      IS 'Visible en menú: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.modulos.sw_requiere_login    IS 'Requiere autenticación: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.modulos.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.modulos.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.modulos.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.modulos.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.modulos.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.modulos.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.modulos.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.modulos.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.modulos ADD CONSTRAINT uq_sys_mod_codigo  UNIQUE (codigo);
      ALTER TABLE system.modulos ADD CONSTRAINT fk_sys_mod_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.modulos ADD CONSTRAINT fk_sys_mod_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.modulos ADD CONSTRAINT fk_sys_mod_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 18. Se crea la tabla modulo_configuraciones en el esquema system.
    -- General.
      CREATE TABLE system.modulo_configuraciones (
        modulo_configuracion_id serial PRIMARY KEY          NOT NULL,
        modulo_id               integer                     NOT NULL,
        codigo                  character varying(100)      NOT NULL,
        nombre                  character varying(150)      NOT NULL,
        descripcion             text,
        tipo_dato               character varying(30)       NOT NULL,
        valor_defecto           text,
        valor                   text,
        orden                   integer                     NOT NULL DEFAULT 0,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.modulo_configuraciones                        IS 'Tabla que almacena la configuración particular de cada módulo.';
      COMMENT ON COLUMN system.modulo_configuraciones.modulo_configuracion_id IS '(PK) Identificador único de la configuración del módulo.';
      COMMENT ON COLUMN system.modulo_configuraciones.modulo_id             IS '(FK) Identificador del módulo.';
      COMMENT ON COLUMN system.modulo_configuraciones.codigo                IS 'Código interno de la configuración.';
      COMMENT ON COLUMN system.modulo_configuraciones.nombre                IS 'Nombre de la configuración.';
      COMMENT ON COLUMN system.modulo_configuraciones.descripcion           IS 'Descripción funcional de la configuración.';
      COMMENT ON COLUMN system.modulo_configuraciones.tipo_dato             IS 'Tipo de dato de la configuración.';
      COMMENT ON COLUMN system.modulo_configuraciones.valor_defecto         IS 'Valor por defecto.';
      COMMENT ON COLUMN system.modulo_configuraciones.valor                 IS 'Valor configurado.';
      COMMENT ON COLUMN system.modulo_configuraciones.orden                 IS 'Orden visual de la configuración.';
      COMMENT ON COLUMN system.modulo_configuraciones.estado                IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.modulo_configuraciones.borrado               IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.modulo_configuraciones.usuario_creacion      IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.modulo_configuraciones.fecha_creacion        IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.modulo_configuraciones.usuario_modificacion  IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.modulo_configuraciones.fecha_modificacion    IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.modulo_configuraciones.usuario_borrado       IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.modulo_configuraciones.fecha_borrado         IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.modulo_configuraciones ADD CONSTRAINT uq_sys_mco_mod_codigo UNIQUE (modulo_id, codigo);
      ALTER TABLE system.modulo_configuraciones ADD CONSTRAINT fk_sys_mco_mod     FOREIGN KEY (modulo_id)               REFERENCES system.modulos (modulo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.modulo_configuraciones ADD CONSTRAINT fk_sys_mco_usr_cre FOREIGN KEY (usuario_creacion)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.modulo_configuraciones ADD CONSTRAINT fk_sys_mco_usr_mod FOREIGN KEY (usuario_modificacion)  REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.modulo_configuraciones ADD CONSTRAINT fk_sys_mco_usr_bor FOREIGN KEY (usuario_borrado)       REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 19. Se crea la tabla integraciones en el esquema system.
    -- General.
      CREATE TABLE system.integraciones (
        integracion_id          serial PRIMARY KEY          NOT NULL,
        codigo                  character varying(80)       NOT NULL,
        nombre                  character varying(150)      NOT NULL,
        descripcion             text,
        tipo_autenticacion      character varying(40),
        base_url                character varying(255),
        sw_activa               bit(1)                      NOT NULL DEFAULT B'0',
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.integraciones                         IS 'Tabla que almacena las integraciones disponibles de la aplicación.';
      COMMENT ON COLUMN system.integraciones.integracion_id         IS '(PK) Identificador único de la integración.';
      COMMENT ON COLUMN system.integraciones.codigo                 IS 'Código interno de la integración.';
      COMMENT ON COLUMN system.integraciones.nombre                 IS 'Nombre de la integración.';
      COMMENT ON COLUMN system.integraciones.descripcion            IS 'Descripción funcional de la integración.';
      COMMENT ON COLUMN system.integraciones.tipo_autenticacion     IS 'Tipo de autenticación requerido.';
      COMMENT ON COLUMN system.integraciones.base_url               IS 'URL base de la integración.';
      COMMENT ON COLUMN system.integraciones.sw_activa              IS 'Activa: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.integraciones.estado                 IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.integraciones.borrado                IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.integraciones.usuario_creacion       IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.integraciones.fecha_creacion         IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.integraciones.usuario_modificacion   IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.integraciones.fecha_modificacion     IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.integraciones.usuario_borrado        IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.integraciones.fecha_borrado          IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.integraciones ADD CONSTRAINT uq_sys_int_codigo  UNIQUE (codigo);
      ALTER TABLE system.integraciones ADD CONSTRAINT fk_sys_int_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.integraciones ADD CONSTRAINT fk_sys_int_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.integraciones ADD CONSTRAINT fk_sys_int_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 20. Se crea la tabla integracion_configuraciones en el esquema system.
    -- General.
      CREATE TABLE system.integracion_configuraciones (
        integracion_configuracion_id serial PRIMARY KEY          NOT NULL,
        integracion_id               integer                     NOT NULL,
        codigo                       character varying(100)      NOT NULL,
        valor                        text,
        descripcion                  text,
        sw_encriptado                bit(1)                      NOT NULL DEFAULT B'0',
        estado                       bit(1)                      NOT NULL DEFAULT B'1',
        borrado                      bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion             integer                     NOT NULL,
        fecha_creacion               timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion         integer,
        fecha_modificacion           timestamp without time zone,
        usuario_borrado              integer,
        fecha_borrado                timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.integracion_configuraciones                          IS 'Tabla que almacena la configuración técnica de cada integración.';
      COMMENT ON COLUMN system.integracion_configuraciones.integracion_configuracion_id IS '(PK) Identificador único de la configuración de integración.';
      COMMENT ON COLUMN system.integracion_configuraciones.integracion_id           IS '(FK) Identificador de la integración.';
      COMMENT ON COLUMN system.integracion_configuraciones.codigo                   IS 'Código interno de la configuración.';
      COMMENT ON COLUMN system.integracion_configuraciones.valor                    IS 'Valor configurado.';
      COMMENT ON COLUMN system.integracion_configuraciones.descripcion              IS 'Descripción funcional de la configuración.';
      COMMENT ON COLUMN system.integracion_configuraciones.sw_encriptado            IS 'Encriptado: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.integracion_configuraciones.estado                   IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.integracion_configuraciones.borrado                  IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.integracion_configuraciones.usuario_creacion         IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.integracion_configuraciones.fecha_creacion           IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.integracion_configuraciones.usuario_modificacion     IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.integracion_configuraciones.fecha_modificacion       IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.integracion_configuraciones.usuario_borrado          IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.integracion_configuraciones.fecha_borrado            IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.integracion_configuraciones ADD CONSTRAINT uq_sys_ico_int_codigo UNIQUE (integracion_id, codigo);
      ALTER TABLE system.integracion_configuraciones ADD CONSTRAINT fk_sys_ico_int     FOREIGN KEY (integracion_id)           REFERENCES system.integraciones (integracion_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.integracion_configuraciones ADD CONSTRAINT fk_sys_ico_usr_cre FOREIGN KEY (usuario_creacion)        REFERENCES public.usuarios (usuario_id)          ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.integracion_configuraciones ADD CONSTRAINT fk_sys_ico_usr_mod FOREIGN KEY (usuario_modificacion)    REFERENCES public.usuarios (usuario_id)          ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.integracion_configuraciones ADD CONSTRAINT fk_sys_ico_usr_bor FOREIGN KEY (usuario_borrado)         REFERENCES public.usuarios (usuario_id)          ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 21. Se crea la tabla plantillas en el esquema system.
    -- General.
      CREATE TABLE system.plantillas (
        plantilla_id            serial PRIMARY KEY          NOT NULL,
        codigo                  character varying(80)       NOT NULL,
        nombre                  character varying(150)      NOT NULL,
        tipo                    character varying(40)       NOT NULL,
        asunto                  character varying(255),
        contenido               text                        NOT NULL,
        motor                   character varying(40)       NOT NULL DEFAULT 'html',
        sw_activa               bit(1)                      NOT NULL DEFAULT B'1',
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.plantillas                        IS 'Tabla que almacena las plantillas reutilizables de la aplicación.';
      COMMENT ON COLUMN system.plantillas.plantilla_id         IS '(PK) Identificador único de la plantilla.';
      COMMENT ON COLUMN system.plantillas.codigo               IS 'Código interno de la plantilla.';
      COMMENT ON COLUMN system.plantillas.nombre               IS 'Nombre de la plantilla.';
      COMMENT ON COLUMN system.plantillas.tipo                 IS 'Tipo de plantilla.';
      COMMENT ON COLUMN system.plantillas.asunto               IS 'Asunto asociado a la plantilla.';
      COMMENT ON COLUMN system.plantillas.contenido            IS 'Contenido de la plantilla.';
      COMMENT ON COLUMN system.plantillas.motor                IS 'Motor de render de la plantilla.';
      COMMENT ON COLUMN system.plantillas.sw_activa            IS 'Activa: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.plantillas.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.plantillas.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.plantillas.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.plantillas.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.plantillas.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.plantillas.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.plantillas.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.plantillas.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.plantillas ADD CONSTRAINT uq_sys_pla_codigo  UNIQUE (codigo);
      ALTER TABLE system.plantillas ADD CONSTRAINT fk_sys_pla_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.plantillas ADD CONSTRAINT fk_sys_pla_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.plantillas ADD CONSTRAINT fk_sys_pla_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 22. Se crea la tabla menus en el esquema system.
    -- General.
      CREATE TABLE system.menus (
        menu_id                 serial PRIMARY KEY          NOT NULL,
        modulo_id               integer,
        menu_padre_id           integer,
        codigo                  character varying(80)       NOT NULL,
        nombre                  character varying(150)      NOT NULL,
        ruta                    character varying(255),
        icono                   character varying(100),
        orden                   integer                     NOT NULL DEFAULT 0,
        nivel                   integer                     NOT NULL DEFAULT 1,
        sw_visible              bit(1)                      NOT NULL DEFAULT B'1',
        sw_publico              bit(1)                      NOT NULL DEFAULT B'0',
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.menus                        IS 'Tabla que almacena la configuración de los menús de navegación.';
      COMMENT ON COLUMN system.menus.menu_id              IS '(PK) Identificador único del menú.';
      COMMENT ON COLUMN system.menus.modulo_id            IS '(FK) Identificador del módulo asociado.';
      COMMENT ON COLUMN system.menus.menu_padre_id        IS '(FK) Identificador del menú padre.';
      COMMENT ON COLUMN system.menus.codigo               IS 'Código interno del menú.';
      COMMENT ON COLUMN system.menus.nombre               IS 'Nombre visible del menú.';
      COMMENT ON COLUMN system.menus.ruta                 IS 'Ruta asociada al menú.';
      COMMENT ON COLUMN system.menus.icono                IS 'Ícono asociado al menú.';
      COMMENT ON COLUMN system.menus.orden                IS 'Orden visual del menú.';
      COMMENT ON COLUMN system.menus.nivel                IS 'Nivel jerárquico del menú.';
      COMMENT ON COLUMN system.menus.sw_visible           IS 'Visible: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.menus.sw_publico           IS 'Público: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.menus.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.menus.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.menus.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.menus.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.menus.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.menus.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.menus.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.menus.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.menus ADD CONSTRAINT uq_sys_men_codigo  UNIQUE (codigo);
      ALTER TABLE system.menus ADD CONSTRAINT fk_sys_men_mod     FOREIGN KEY (modulo_id)               REFERENCES system.modulos (modulo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.menus ADD CONSTRAINT fk_sys_men_padre   FOREIGN KEY (menu_padre_id)           REFERENCES system.menus (menu_id)     ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.menus ADD CONSTRAINT fk_sys_men_usr_cre FOREIGN KEY (usuario_creacion)       REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.menus ADD CONSTRAINT fk_sys_men_usr_mod FOREIGN KEY (usuario_modificacion)   REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.menus ADD CONSTRAINT fk_sys_men_usr_bor FOREIGN KEY (usuario_borrado)        REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 23. Se crea la tabla bitacora_cambios en el esquema system.
    -- General.
      CREATE TABLE system.bitacora_cambios (
        bitacora_cambio_id      serial PRIMARY KEY          NOT NULL,
        tabla_afectada          character varying(150)      NOT NULL,
        registro_id             integer,
        accion                  character varying(40)       NOT NULL,
        descripcion             text,
        datos_antes             jsonb,
        datos_despues           jsonb,
        ip_origen               character varying(45),
        origen                  character varying(120),
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.bitacora_cambios                        IS 'Tabla que almacena la bitácora funcional y técnica de cambios de la aplicación.';
      COMMENT ON COLUMN system.bitacora_cambios.bitacora_cambio_id   IS '(PK) Identificador único del evento de bitácora.';
      COMMENT ON COLUMN system.bitacora_cambios.tabla_afectada       IS 'Tabla afectada por el evento.';
      COMMENT ON COLUMN system.bitacora_cambios.registro_id          IS 'Identificador del registro afectado.';
      COMMENT ON COLUMN system.bitacora_cambios.accion               IS 'Acción ejecutada.';
      COMMENT ON COLUMN system.bitacora_cambios.descripcion          IS 'Descripción del evento.';
      COMMENT ON COLUMN system.bitacora_cambios.datos_antes          IS 'Datos antes del cambio.';
      COMMENT ON COLUMN system.bitacora_cambios.datos_despues        IS 'Datos después del cambio.';
      COMMENT ON COLUMN system.bitacora_cambios.ip_origen            IS 'IP origen del evento.';
      COMMENT ON COLUMN system.bitacora_cambios.origen               IS 'Origen funcional o técnico del evento.';
      COMMENT ON COLUMN system.bitacora_cambios.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.bitacora_cambios.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.bitacora_cambios.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.bitacora_cambios.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.bitacora_cambios.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.bitacora_cambios.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.bitacora_cambios.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.bitacora_cambios.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.bitacora_cambios ADD CONSTRAINT fk_sys_bic_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.bitacora_cambios ADD CONSTRAINT fk_sys_bic_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.bitacora_cambios ADD CONSTRAINT fk_sys_bic_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 24. Se crea la tabla logs_aplicacion en el esquema system.
    -- General.
      CREATE TABLE system.logs_aplicacion (
        log_aplicacion_id       serial PRIMARY KEY          NOT NULL,
        nivel                   character varying(20)       NOT NULL,
        modulo                  character varying(120),
        archivo                 character varying(255),
        funcion                 character varying(150),
        linea                   integer,
        mensaje                 text                        NOT NULL,
        detalle                 text,
        contexto                jsonb,
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentación.
      COMMENT ON TABLE system.logs_aplicacion                        IS 'Tabla que almacena eventos de error, advertencia o diagnóstico de la aplicación.';
      COMMENT ON COLUMN system.logs_aplicacion.log_aplicacion_id    IS '(PK) Identificador único del log.';
      COMMENT ON COLUMN system.logs_aplicacion.nivel                IS 'Nivel del log.';
      COMMENT ON COLUMN system.logs_aplicacion.modulo               IS 'Módulo asociado al evento.';
      COMMENT ON COLUMN system.logs_aplicacion.archivo              IS 'Archivo origen del evento.';
      COMMENT ON COLUMN system.logs_aplicacion.funcion              IS 'Función origen del evento.';
      COMMENT ON COLUMN system.logs_aplicacion.linea                IS 'Línea origen del evento.';
      COMMENT ON COLUMN system.logs_aplicacion.mensaje              IS 'Mensaje principal del log.';
      COMMENT ON COLUMN system.logs_aplicacion.detalle              IS 'Detalle ampliado del log.';
      COMMENT ON COLUMN system.logs_aplicacion.contexto             IS 'Contexto serializado del evento.';
      COMMENT ON COLUMN system.logs_aplicacion.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN system.logs_aplicacion.borrado              IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN system.logs_aplicacion.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN system.logs_aplicacion.fecha_creacion       IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN system.logs_aplicacion.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN system.logs_aplicacion.fecha_modificacion   IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN system.logs_aplicacion.usuario_borrado      IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN system.logs_aplicacion.fecha_borrado        IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE system.logs_aplicacion ADD CONSTRAINT fk_sys_log_usr_cre FOREIGN KEY (usuario_creacion)     REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.logs_aplicacion ADD CONSTRAINT fk_sys_log_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE system.logs_aplicacion ADD CONSTRAINT fk_sys_log_usr_bor FOREIGN KEY (usuario_borrado)      REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 25. Se crean los índices base de apoyo.
    -- General.
      CREATE INDEX idx_pub_usr_estado       ON public.usuarios (estado, borrado);
      CREATE INDEX idx_pub_rol_estado       ON public.roles (estado, borrado);
      CREATE INDEX idx_pub_per_estado       ON public.permisos (estado, borrado);
      CREATE INDEX idx_sys_tem_estado       ON system.temas (estado, borrado);
      CREATE INDEX idx_sys_ttk_tema         ON system.tema_tokens (tema_id);
      CREATE INDEX idx_sys_tco_tema         ON system.tema_componentes (tema_id);
      CREATE INDEX idx_sys_par_grupo        ON system.parametros (parametro_grupo_id);
      CREATE INDEX idx_sys_pva_parametro    ON system.parametro_valores (parametro_id);
      CREATE INDEX idx_sys_mco_modulo       ON system.modulo_configuraciones (modulo_id);
      CREATE INDEX idx_sys_ico_integracion  ON system.integracion_configuraciones (integracion_id);
      CREATE INDEX idx_sys_men_padre        ON system.menus (menu_padre_id);
      CREATE INDEX idx_sys_bic_tabla_reg    ON system.bitacora_cambios (tabla_afectada, registro_id);
      CREATE INDEX idx_sys_log_nivel_fecha  ON system.logs_aplicacion (nivel, fecha_creacion);
  -- 26. Se insertan los registros base de seguridad.
    -- General.
      INSERT INTO public.roles
      (
        rol_id,
        codigo,
        nombre,
        descripcion,
        sw_predeterminado,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
      (
        1,
        'ADMIN',
        'Administrador',
        'Rol administrador base de la aplicación.',
        B'1',
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      );

      INSERT INTO public.permisos
      (
        permiso_id,
        codigo,
        nombre,
        descripcion,
        modulo,
        tipo_permiso,
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
      VALUES
        (
          1,
          'SEG_USUARIOS_VER',
          'Ver usuarios',
          'Permite consultar usuarios.',
          'seguridad',
          'consulta',
          1,
          B'1',
          B'0',
          1,
          NOW(),
          NULL,
          NULL,
          NULL,
          NULL
        ),
        (
          2,
          'SEG_USUARIOS_GUARDAR',
          'Guardar usuarios',
          'Permite crear y actualizar usuarios.',
          'seguridad',
          'edicion',
          2,
          B'1',
          B'0',
          1,
          NOW(),
          NULL,
          NULL,
          NULL,
          NULL
        ),
        (
          3,
          'SYS_PARAMETROS_VER',
          'Ver parámetros',
          'Permite consultar la parametrización.',
          'parametrizacion',
          'consulta',
          3,
          B'1',
          B'0',
          1,
          NOW(),
          NULL,
          NULL,
          NULL,
          NULL
        ),
        (
          4,
          'SYS_PARAMETROS_GUARDAR',
          'Guardar parámetros',
          'Permite crear y actualizar la parametrización.',
          'parametrizacion',
          'edicion',
          4,
          B'1',
          B'0',
          1,
          NOW(),
          NULL,
          NULL,
          NULL,
          NULL
        );

      INSERT INTO public.roles_permisos
      (
        rol_permiso_id,
        rol_id,
        permiso_id,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
        (1, 1, 1, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (2, 1, 2, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (3, 1, 3, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (4, 1, 4, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL);

      INSERT INTO public.usuarios_roles
      (
        usuario_rol_id,
        usuario_id,
        rol_id,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
      (
        1,
        1,
        1,
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      );
  -- 27. Se insertan los registros base de parametrización.
    -- General.
      INSERT INTO system.temas
      (
        tema_id,
        codigo,
        nombre,
        descripcion,
        version,
        sw_predeterminado,
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
      VALUES
      (
        1,
        'BASE',
        'Tema base',
        'Tema visual base de la aplicación.',
        '1.0.0',
        B'1',
        1,
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      );

      INSERT INTO system.tema_tokens
      (
        tema_token_id,
        tema_id,
        grupo,
        clave,
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
      VALUES
        (
          1,
          1,
          'colores',
          'color.primary',
          '#1D4ED8',
          'string',
          'Color principal de la aplicación.',
          1,
          B'1',
          B'0',
          1,
          NOW(),
          NULL,
          NULL,
          NULL,
          NULL
        ),
        (
          2,
          1,
          'colores',
          'color.secondary',
          '#0F172A',
          'string',
          'Color secundario de la aplicación.',
          2,
          B'1',
          B'0',
          1,
          NOW(),
          NULL,
          NULL,
          NULL,
          NULL
        ),
        (
          3,
          1,
          'fuentes',
          'font.family.base',
          'Inter',
          'string',
          'Fuente base de la aplicación.',
          3,
          B'1',
          B'0',
          1,
          NOW(),
          NULL,
          NULL,
          NULL,
          NULL
        );

      INSERT INTO system.tema_componentes
      (
        tema_componente_id,
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
      VALUES
        (
          1,
          1,
          'header',
          'sticky',
          '1',
          'bit',
          'Define si el encabezado es fijo.',
          1,
          B'1',
          B'0',
          1,
          NOW(),
          NULL,
          NULL,
          NULL,
          NULL
        ),
        (
          2,
          1,
          'button.primary',
          'border_radius',
          '12px',
          'string',
          'Radio del botón principal.',
          2,
          B'1',
          B'0',
          1,
          NOW(),
          NULL,
          NULL,
          NULL,
          NULL
        );

      INSERT INTO system.branding
      (
        branding_id,
        codigo,
        nombre_comercial,
        razon_social,
        nit,
        correo_contacto,
        telefono_contacto,
        direccion,
        logo_principal,
        logo_secundario,
        favicon,
        banner_principal,
        mensaje_bienvenida,
        texto_footer,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
      (
        1,
        'PRINCIPAL',
        'Tienda Virtual',
        NULL,
        NULL,
        'soporte@localhost',
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        'Bienvenido a la tienda virtual.',
        'Todos los derechos reservados.',
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      );

      INSERT INTO system.parametro_grupos
      (
        parametro_grupo_id,
        codigo,
        nombre,
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
      VALUES
        (1, 'GENERAL', 'General', 'Parámetros generales de la aplicación.', 1, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (2, 'CATALOGO', 'Catálogo', 'Parámetros funcionales del catálogo.', 2, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (3, 'CHECKOUT', 'Checkout', 'Parámetros funcionales del checkout.', 3, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL);

      INSERT INTO system.parametros
      (
        parametro_id,
        parametro_grupo_id,
        codigo,
        nombre,
        descripcion,
        tipo_dato,
        valor_defecto,
        sw_requerido,
        sw_publico,
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
      VALUES
        (1, 1, 'app.nombre', 'Nombre de la aplicación', 'Nombre principal visible de la aplicación.', 'string', 'Tienda Virtual', B'1', B'1', 1, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (2, 1, 'app.moneda', 'Moneda', 'Moneda principal de operación.', 'string', 'COP', B'1', B'1', 2, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (3, 2, 'catalogo.mostrar_stock', 'Mostrar stock', 'Define si el catálogo muestra disponibilidad.', 'bit', '1', B'1', B'1', 3, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (4, 3, 'checkout.permitir_invitado', 'Permitir invitado', 'Define si el checkout permite comprar sin autenticación.', 'bit', '1', B'1', B'1', 4, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL);

      INSERT INTO system.parametro_valores
      (
        parametro_valor_id,
        parametro_id,
        valor,
        observacion,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
        (1, 1, 'Tienda Virtual', 'Valor inicial de nombre.', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (2, 2, 'COP', 'Valor inicial de moneda.', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (3, 3, '1', 'Valor inicial para mostrar stock.', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (4, 4, '1', 'Valor inicial para permitir checkout invitado.', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL);

      INSERT INTO system.modulos
      (
        modulo_id,
        codigo,
        nombre,
        descripcion,
        ruta,
        icono,
        orden,
        sw_visible_menu,
        sw_requiere_login,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
        (1, 'SEGURIDAD', 'Seguridad', 'Administración de usuarios, roles y permisos.', '/seguridad', 'shield', 1, B'1', B'1', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (2, 'PARAMETRIZACION', 'Parametrización', 'Administración de temas, branding y parámetros.', '/parametrizacion', 'settings', 2, B'1', B'1', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL);

      INSERT INTO system.modulo_configuraciones
      (
        modulo_configuracion_id,
        modulo_id,
        codigo,
        nombre,
        descripcion,
        tipo_dato,
        valor_defecto,
        valor,
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
      VALUES
        (1, 2, 'parametrizacion.cache_activa', 'Cache activa', 'Define si la parametrización usa cache.', 'bit', '0', '0', 1, B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL);

      INSERT INTO system.integraciones
      (
        integracion_id,
        codigo,
        nombre,
        descripcion,
        tipo_autenticacion,
        base_url,
        sw_activa,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
        (1, 'CORREO', 'Correo', 'Integración base para envío de correos.', 'smtp', NULL, B'0', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (2, 'PASARELA_PAGO', 'Pasarela de pago', 'Integración base para pasarela de pago.', 'token', NULL, B'0', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL);

      INSERT INTO system.integracion_configuraciones
      (
        integracion_configuracion_id,
        integracion_id,
        codigo,
        valor,
        descripcion,
        sw_encriptado,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
        (1, 1, 'host', NULL, 'Host del servicio de correo.', B'0', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (2, 1, 'puerto', NULL, 'Puerto del servicio de correo.', B'0', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (3, 2, 'api_key', NULL, 'Llave de autenticación de la pasarela.', B'1', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL);

      INSERT INTO system.plantillas
      (
        plantilla_id,
        codigo,
        nombre,
        tipo,
        asunto,
        contenido,
        motor,
        sw_activa,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
      (
        1,
        'CORREO_BIENVENIDA',
        'Correo de bienvenida',
        'correo',
        'Bienvenido a la tienda virtual',
        '<p>Bienvenido a la tienda virtual.</p>',
        'html',
        B'1',
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      );

      INSERT INTO system.menus
      (
        menu_id,
        modulo_id,
        menu_padre_id,
        codigo,
        nombre,
        ruta,
        icono,
        orden,
        nivel,
        sw_visible,
        sw_publico,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion,
        usuario_modificacion,
        fecha_modificacion,
        usuario_borrado,
        fecha_borrado
      )
      VALUES
        (1, 1, NULL, 'MENU_SEGURIDAD', 'Seguridad', '/seguridad', 'shield', 1, 1, B'1', B'0', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL),
        (2, 2, NULL, 'MENU_PARAMETRIZACION', 'Parametrización', '/parametrizacion', 'settings', 2, 1, B'1', B'0', B'1', B'0', 1, NOW(), NULL, NULL, NULL, NULL);
  -- 28. Se sincronizan las secuencias de las tablas con datos iniciales.
    -- General.
      SELECT setval(pg_get_serial_sequence('public.usuarios', 'usuario_id'), COALESCE(MAX(usuario_id), 1), TRUE) FROM public.usuarios;
      SELECT setval(pg_get_serial_sequence('public.roles', 'rol_id'), COALESCE(MAX(rol_id), 1), TRUE) FROM public.roles;
      SELECT setval(pg_get_serial_sequence('public.permisos', 'permiso_id'), COALESCE(MAX(permiso_id), 1), TRUE) FROM public.permisos;
      SELECT setval(pg_get_serial_sequence('public.roles_permisos', 'rol_permiso_id'), COALESCE(MAX(rol_permiso_id), 1), TRUE) FROM public.roles_permisos;
      SELECT setval(pg_get_serial_sequence('public.usuarios_roles', 'usuario_rol_id'), COALESCE(MAX(usuario_rol_id), 1), TRUE) FROM public.usuarios_roles;
      SELECT setval(pg_get_serial_sequence('system.temas', 'tema_id'), COALESCE(MAX(tema_id), 1), TRUE) FROM system.temas;
      SELECT setval(pg_get_serial_sequence('system.tema_tokens', 'tema_token_id'), COALESCE(MAX(tema_token_id), 1), TRUE) FROM system.tema_tokens;
      SELECT setval(pg_get_serial_sequence('system.tema_componentes', 'tema_componente_id'), COALESCE(MAX(tema_componente_id), 1), TRUE) FROM system.tema_componentes;
      SELECT setval(pg_get_serial_sequence('system.branding', 'branding_id'), COALESCE(MAX(branding_id), 1), TRUE) FROM system.branding;
      SELECT setval(pg_get_serial_sequence('system.parametro_grupos', 'parametro_grupo_id'), COALESCE(MAX(parametro_grupo_id), 1), TRUE) FROM system.parametro_grupos;
      SELECT setval(pg_get_serial_sequence('system.parametros', 'parametro_id'), COALESCE(MAX(parametro_id), 1), TRUE) FROM system.parametros;
      SELECT setval(pg_get_serial_sequence('system.parametro_valores', 'parametro_valor_id'), COALESCE(MAX(parametro_valor_id), 1), TRUE) FROM system.parametro_valores;
      SELECT setval(pg_get_serial_sequence('system.modulos', 'modulo_id'), COALESCE(MAX(modulo_id), 1), TRUE) FROM system.modulos;
      SELECT setval(pg_get_serial_sequence('system.modulo_configuraciones', 'modulo_configuracion_id'), COALESCE(MAX(modulo_configuracion_id), 1), TRUE) FROM system.modulo_configuraciones;
      SELECT setval(pg_get_serial_sequence('system.integraciones', 'integracion_id'), COALESCE(MAX(integracion_id), 1), TRUE) FROM system.integraciones;
      SELECT setval(pg_get_serial_sequence('system.integracion_configuraciones', 'integracion_configuracion_id'), COALESCE(MAX(integracion_configuracion_id), 1), TRUE) FROM system.integracion_configuraciones;
      SELECT setval(pg_get_serial_sequence('system.plantillas', 'plantilla_id'), COALESCE(MAX(plantilla_id), 1), TRUE) FROM system.plantillas;
      SELECT setval(pg_get_serial_sequence('system.menus', 'menu_id'), COALESCE(MAX(menu_id), 1), TRUE) FROM system.menus;
