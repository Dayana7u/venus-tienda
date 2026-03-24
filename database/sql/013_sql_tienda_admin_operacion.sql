-- ESTRUCTURA SQL DESARROLLO.
  -- 1. Se crea la tabla de clientes de la tienda.
    -- General.
      CREATE TABLE IF NOT EXISTS public.clientes_tienda (
        cliente_tienda_id      serial PRIMARY KEY          NOT NULL,
        codigo                 character varying(60)       NOT NULL,
        nombres                character varying(120)      NOT NULL,
        apellidos              character varying(120)      NOT NULL,
        correo                 character varying(160)      NOT NULL,
        celular                character varying(30),
        clave                  character varying(255)      NOT NULL,
        ultimo_ingreso         timestamp without time zone,
        estado                 bit(1)                      NOT NULL DEFAULT B'1',
        borrado                bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion       integer                     NOT NULL,
        fecha_creacion         timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion   integer,
        fecha_modificacion     timestamp without time zone,
        usuario_borrado        integer,
        fecha_borrado          timestamp without time zone
      );
    -- Documentacion.
      COMMENT ON TABLE public.clientes_tienda                        IS 'Tabla que almacena los clientes del ecommerce.';
      COMMENT ON COLUMN public.clientes_tienda.cliente_tienda_id    IS '(PK) Identificador unico del cliente.';
      COMMENT ON COLUMN public.clientes_tienda.codigo               IS 'Codigo interno del cliente.';
      COMMENT ON COLUMN public.clientes_tienda.nombres              IS 'Nombres del cliente.';
      COMMENT ON COLUMN public.clientes_tienda.apellidos            IS 'Apellidos del cliente.';
      COMMENT ON COLUMN public.clientes_tienda.correo               IS 'Correo principal del cliente.';
      COMMENT ON COLUMN public.clientes_tienda.celular              IS 'Telefono principal del cliente.';
      COMMENT ON COLUMN public.clientes_tienda.clave                IS 'Clave cifrada del cliente para acceso futuro.';
      COMMENT ON COLUMN public.clientes_tienda.ultimo_ingreso       IS 'Fecha del ultimo ingreso del cliente.';
      COMMENT ON COLUMN public.clientes_tienda.estado               IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.clientes_tienda.borrado              IS 'Borrado logico: (0) No, (1) Si.';
      COMMENT ON COLUMN public.clientes_tienda.usuario_creacion     IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.clientes_tienda.fecha_creacion       IS 'Fecha de creacion del registro.';
      COMMENT ON COLUMN public.clientes_tienda.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.clientes_tienda.fecha_modificacion   IS 'Fecha de modificacion del registro.';
      COMMENT ON COLUMN public.clientes_tienda.usuario_borrado      IS '(FK) Usuario que realiza el borrado logico.';
      COMMENT ON COLUMN public.clientes_tienda.fecha_borrado        IS 'Fecha del borrado logico.';
    -- Restricciones.
      DO $$
      BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'uq_pub_cli_tie_codigo') THEN
          ALTER TABLE public.clientes_tienda
            ADD CONSTRAINT uq_pub_cli_tie_codigo UNIQUE (codigo);
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'uq_pub_cli_tie_correo') THEN
          ALTER TABLE public.clientes_tienda
            ADD CONSTRAINT uq_pub_cli_tie_correo UNIQUE (correo);
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_cli_tie_usr_cre') THEN
          ALTER TABLE public.clientes_tienda
            ADD CONSTRAINT fk_pub_cli_tie_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_cli_tie_usr_mod') THEN
          ALTER TABLE public.clientes_tienda
            ADD CONSTRAINT fk_pub_cli_tie_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_cli_tie_usr_bor') THEN
          ALTER TABLE public.clientes_tienda
            ADD CONSTRAINT fk_pub_cli_tie_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;
      END $$;
  -- 2. Se crea la tabla de direcciones de clientes.
    -- General.
      CREATE TABLE IF NOT EXISTS public.clientes_tienda_direcciones (
        cliente_tienda_direccion_id serial PRIMARY KEY          NOT NULL,
        cliente_tienda_id           integer                     NOT NULL,
        alias                       character varying(80),
        destinatario                character varying(160)      NOT NULL,
        telefono                    character varying(30),
        direccion_linea_1           character varying(200)      NOT NULL,
        direccion_linea_2           character varying(200),
        ciudad                      character varying(100)      NOT NULL,
        departamento                character varying(100),
        codigo_postal               character varying(20),
        referencia                  character varying(255),
        sw_principal                bit(1)                      NOT NULL DEFAULT B'1',
        estado                      bit(1)                      NOT NULL DEFAULT B'1',
        borrado                     bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion            integer                     NOT NULL,
        fecha_creacion              timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion        integer,
        fecha_modificacion          timestamp without time zone,
        usuario_borrado             integer,
        fecha_borrado               timestamp without time zone
      );
    -- Documentacion.
      COMMENT ON TABLE public.clientes_tienda_direcciones                                   IS 'Tabla que almacena direcciones del cliente.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.cliente_tienda_direccion_id      IS '(PK) Identificador unico de la direccion.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.cliente_tienda_id                IS '(FK) Cliente asociado a la direccion.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.alias                            IS 'Alias corto para identificar la direccion.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.destinatario                     IS 'Nombre de quien recibe el pedido.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.telefono                         IS 'Telefono de contacto de la direccion.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.direccion_linea_1                IS 'Linea principal de direccion.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.direccion_linea_2                IS 'Complemento de direccion.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.ciudad                           IS 'Ciudad principal de entrega.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.departamento                     IS 'Departamento o region.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.codigo_postal                    IS 'Codigo postal.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.referencia                       IS 'Referencia adicional de ubicacion.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.sw_principal                     IS 'Direccion principal del cliente: (0) No, (1) Si.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.estado                           IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.borrado                          IS 'Borrado logico: (0) No, (1) Si.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.usuario_creacion                 IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.fecha_creacion                   IS 'Fecha de creacion del registro.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.usuario_modificacion             IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.fecha_modificacion               IS 'Fecha de modificacion del registro.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.usuario_borrado                  IS '(FK) Usuario que realiza el borrado logico.';
      COMMENT ON COLUMN public.clientes_tienda_direcciones.fecha_borrado                    IS 'Fecha del borrado logico.';
    -- Restricciones.
      DO $$
      BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_cld_cli') THEN
          ALTER TABLE public.clientes_tienda_direcciones
            ADD CONSTRAINT fk_pub_cld_cli FOREIGN KEY (cliente_tienda_id) REFERENCES public.clientes_tienda (cliente_tienda_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_cld_usr_cre') THEN
          ALTER TABLE public.clientes_tienda_direcciones
            ADD CONSTRAINT fk_pub_cld_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_cld_usr_mod') THEN
          ALTER TABLE public.clientes_tienda_direcciones
            ADD CONSTRAINT fk_pub_cld_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_cld_usr_bor') THEN
          ALTER TABLE public.clientes_tienda_direcciones
            ADD CONSTRAINT fk_pub_cld_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;
      END $$;
  -- 3. Se crea la tabla de pedidos de la tienda.
    -- General.
      CREATE TABLE IF NOT EXISTS public.pedidos_tienda (
        pedido_tienda_id        serial PRIMARY KEY          NOT NULL,
        cliente_tienda_id       integer                     NOT NULL,
        codigo                  character varying(60)       NOT NULL,
        estado_pedido           character varying(40)       NOT NULL DEFAULT 'pendiente',
        estado_pago             character varying(40)       NOT NULL DEFAULT 'pendiente',
        metodo_pago             character varying(60),
        cantidad_items          integer                     NOT NULL DEFAULT 0,
        subtotal                numeric(14, 2)             NOT NULL DEFAULT 0,
        descuento_total         numeric(14, 2)             NOT NULL DEFAULT 0,
        envio_total             numeric(14, 2)             NOT NULL DEFAULT 0,
        total                   numeric(14, 2)             NOT NULL DEFAULT 0,
        direccion_resumen       text,
        observacion             text,
        fecha_pedido            timestamp without time zone NOT NULL DEFAULT NOW(),
        estado                  bit(1)                      NOT NULL DEFAULT B'1',
        borrado                 bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion        integer                     NOT NULL,
        fecha_creacion          timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion    integer,
        fecha_modificacion      timestamp without time zone,
        usuario_borrado         integer,
        fecha_borrado           timestamp without time zone
      );
    -- Documentacion.
      COMMENT ON TABLE public.pedidos_tienda                       IS 'Tabla que almacena los pedidos del ecommerce.';
      COMMENT ON COLUMN public.pedidos_tienda.pedido_tienda_id    IS '(PK) Identificador unico del pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.cliente_tienda_id   IS '(FK) Cliente asociado al pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.codigo              IS 'Codigo interno del pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.estado_pedido       IS 'Estado comercial del pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.estado_pago         IS 'Estado del pago del pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.metodo_pago         IS 'Metodo de pago usado en el pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.cantidad_items      IS 'Cantidad total de items del pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.subtotal            IS 'Subtotal del pedido antes de descuentos.';
      COMMENT ON COLUMN public.pedidos_tienda.descuento_total     IS 'Valor total del descuento aplicado.';
      COMMENT ON COLUMN public.pedidos_tienda.envio_total         IS 'Valor total del envio.';
      COMMENT ON COLUMN public.pedidos_tienda.total               IS 'Valor final del pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.direccion_resumen   IS 'Resumen textual de la direccion de entrega.';
      COMMENT ON COLUMN public.pedidos_tienda.observacion         IS 'Observacion comercial del pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.fecha_pedido        IS 'Fecha de creacion del pedido.';
      COMMENT ON COLUMN public.pedidos_tienda.estado              IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.pedidos_tienda.borrado             IS 'Borrado logico: (0) No, (1) Si.';
      COMMENT ON COLUMN public.pedidos_tienda.usuario_creacion    IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.pedidos_tienda.fecha_creacion      IS 'Fecha de creacion del registro.';
      COMMENT ON COLUMN public.pedidos_tienda.usuario_modificacion IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.pedidos_tienda.fecha_modificacion   IS 'Fecha de modificacion del registro.';
      COMMENT ON COLUMN public.pedidos_tienda.usuario_borrado      IS '(FK) Usuario que realiza el borrado logico.';
      COMMENT ON COLUMN public.pedidos_tienda.fecha_borrado        IS 'Fecha del borrado logico.';
    -- Restricciones.
      DO $$
      BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'uq_pub_ped_tie_codigo') THEN
          ALTER TABLE public.pedidos_tienda
            ADD CONSTRAINT uq_pub_ped_tie_codigo UNIQUE (codigo);
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_ped_tie_cli') THEN
          ALTER TABLE public.pedidos_tienda
            ADD CONSTRAINT fk_pub_ped_tie_cli FOREIGN KEY (cliente_tienda_id) REFERENCES public.clientes_tienda (cliente_tienda_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_ped_tie_usr_cre') THEN
          ALTER TABLE public.pedidos_tienda
            ADD CONSTRAINT fk_pub_ped_tie_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_ped_tie_usr_mod') THEN
          ALTER TABLE public.pedidos_tienda
            ADD CONSTRAINT fk_pub_ped_tie_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_ped_tie_usr_bor') THEN
          ALTER TABLE public.pedidos_tienda
            ADD CONSTRAINT fk_pub_ped_tie_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;
      END $$;
  -- 4. Se crea la tabla de detalle de pedidos de la tienda.
    -- General.
      CREATE TABLE IF NOT EXISTS public.pedido_tienda_detalles (
        pedido_tienda_detalle_id serial PRIMARY KEY          NOT NULL,
        pedido_tienda_id         integer                     NOT NULL,
        producto_id              integer                     NOT NULL,
        producto_codigo          character varying(60)       NOT NULL,
        producto_nombre          character varying(180)      NOT NULL,
        producto_slug            character varying(200),
        imagen_url               text,
        cantidad                 integer                     NOT NULL DEFAULT 1,
        precio_unitario          numeric(14, 2)             NOT NULL DEFAULT 0,
        descuento_unitario       numeric(14, 2)             NOT NULL DEFAULT 0,
        total_linea              numeric(14, 2)             NOT NULL DEFAULT 0,
        estado                   bit(1)                      NOT NULL DEFAULT B'1',
        borrado                  bit(1)                      NOT NULL DEFAULT B'0',
        usuario_creacion         integer                     NOT NULL,
        fecha_creacion           timestamp without time zone NOT NULL DEFAULT NOW(),
        usuario_modificacion     integer,
        fecha_modificacion       timestamp without time zone,
        usuario_borrado          integer,
        fecha_borrado            timestamp without time zone
      );
    -- Documentacion.
      COMMENT ON TABLE public.pedido_tienda_detalles                          IS 'Tabla que almacena las lineas de cada pedido.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.pedido_tienda_detalle_id IS '(PK) Identificador unico del detalle.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.pedido_tienda_id         IS '(FK) Pedido asociado al detalle.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.producto_id              IS '(FK) Producto asociado al detalle.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.producto_codigo          IS 'Codigo del producto al momento de la compra.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.producto_nombre          IS 'Nombre del producto al momento de la compra.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.producto_slug            IS 'Slug publico del producto al momento de la compra.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.imagen_url               IS 'Imagen usada en la linea del pedido.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.cantidad                 IS 'Cantidad comprada del producto.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.precio_unitario          IS 'Precio unitario de la linea.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.descuento_unitario       IS 'Descuento unitario de la linea.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.total_linea              IS 'Valor total de la linea.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.estado                   IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.borrado                  IS 'Borrado logico: (0) No, (1) Si.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.usuario_creacion         IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.fecha_creacion           IS 'Fecha de creacion del registro.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.usuario_modificacion     IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.fecha_modificacion       IS 'Fecha de modificacion del registro.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.usuario_borrado          IS '(FK) Usuario que realiza el borrado logico.';
      COMMENT ON COLUMN public.pedido_tienda_detalles.fecha_borrado            IS 'Fecha del borrado logico.';
    -- Restricciones.
      DO $$
      BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pdd_ped') THEN
          ALTER TABLE public.pedido_tienda_detalles
            ADD CONSTRAINT fk_pub_pdd_ped FOREIGN KEY (pedido_tienda_id) REFERENCES public.pedidos_tienda (pedido_tienda_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pdd_pro') THEN
          ALTER TABLE public.pedido_tienda_detalles
            ADD CONSTRAINT fk_pub_pdd_pro FOREIGN KEY (producto_id) REFERENCES public.productos (producto_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pdd_usr_cre') THEN
          ALTER TABLE public.pedido_tienda_detalles
            ADD CONSTRAINT fk_pub_pdd_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pdd_usr_mod') THEN
          ALTER TABLE public.pedido_tienda_detalles
            ADD CONSTRAINT fk_pub_pdd_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pdd_usr_bor') THEN
          ALTER TABLE public.pedido_tienda_detalles
            ADD CONSTRAINT fk_pub_pdd_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;
      END $$;
  -- 5. Se crean indices operativos para clientes y pedidos.
    -- General.
      CREATE INDEX IF NOT EXISTS idx_pub_cli_tie_correo         ON public.clientes_tienda (correo, estado, borrado);
      CREATE INDEX IF NOT EXISTS idx_pub_cld_cliente_principal  ON public.clientes_tienda_direcciones (cliente_tienda_id, sw_principal, estado, borrado);
      CREATE INDEX IF NOT EXISTS idx_pub_ped_tie_cliente_fecha  ON public.pedidos_tienda (cliente_tienda_id, fecha_pedido DESC);
      CREATE INDEX IF NOT EXISTS idx_pub_ped_tie_estado_pago    ON public.pedidos_tienda (estado_pago, estado_pedido, estado, borrado);
      CREATE INDEX IF NOT EXISTS idx_pub_pdd_pedido_producto    ON public.pedido_tienda_detalles (pedido_tienda_id, producto_id, estado, borrado);
  -- 6. Se registran permisos basicos para clientes, pedidos y ventas del panel tienda.
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
        ('TIENDA_CLIENTES_VER', 'Ver clientes tienda', 'Permite consultar clientes de la tienda.', 'TIENDA_ADMIN', 'consulta', 14, B'1', B'0', 1, NOW()),
        ('TIENDA_PEDIDOS_VER',  'Ver pedidos tienda',  'Permite consultar pedidos de la tienda.',  'TIENDA_ADMIN', 'consulta', 15, B'1', B'0', 1, NOW()),
        ('TIENDA_VENTAS_VER',   'Ver ventas tienda',   'Permite consultar resumen de ventas.',      'TIENDA_ADMIN', 'consulta', 16, B'1', B'0', 1, NOW())
      ON CONFLICT (codigo) DO NOTHING;
  -- 7. Se asignan permisos al rol administrador de tienda.
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
        ON pub_per.codigo IN ('TIENDA_CLIENTES_VER', 'TIENDA_PEDIDOS_VER', 'TIENDA_VENTAS_VER')
      WHERE pub_rol.codigo = 'TIENDA_ADMIN'
        AND pub_rol.estado = B'1'
        AND pub_rol.borrado = B'0'
        AND NOT EXISTS (
          SELECT 1
          FROM public.roles_permisos pub_rpe
          WHERE pub_rpe.rol_id = pub_rol.rol_id
            AND pub_rpe.permiso_id = pub_per.permiso_id
        );
  -- 8. Se insertan clientes base de la tienda para pruebas funcionales.
    -- General.
      INSERT INTO public.clientes_tienda
      (
        codigo,
        nombres,
        apellidos,
        correo,
        celular,
        clave,
        ultimo_ingreso,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      VALUES
        ('CLT001', 'Paula',   'Rojas',  'paula.rojas@beauty.test',   '3001112233', '$2y$12$pPksEFXtC6rL4nX8DNdGOuxp/hqYsz.FWZXYwOfHwx.yPakTXGXKO', NOW(), B'1', B'0', 1, NOW()),
        ('CLT002', 'Juliana', 'Gomez',  'juliana.gomez@beauty.test', '3012223344', '$2y$12$pPksEFXtC6rL4nX8DNdGOuxp/hqYsz.FWZXYwOfHwx.yPakTXGXKO', NOW(), B'1', B'0', 1, NOW()),
        ('CLT003', 'Natalia', 'Marin',  'natalia.marin@beauty.test', '3023334455', '$2y$12$pPksEFXtC6rL4nX8DNdGOuxp/hqYsz.FWZXYwOfHwx.yPakTXGXKO', NOW(), B'1', B'0', 1, NOW())
      ON CONFLICT (codigo) DO NOTHING;
  -- 9. Se insertan direcciones base para clientes.
    -- General.
      INSERT INTO public.clientes_tienda_direcciones
      (
        cliente_tienda_id,
        alias,
        destinatario,
        telefono,
        direccion_linea_1,
        direccion_linea_2,
        ciudad,
        departamento,
        codigo_postal,
        referencia,
        sw_principal,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      SELECT
        cli.cliente_tienda_id,
        val.alias,
        val.destinatario,
        val.telefono,
        val.direccion_linea_1,
        val.direccion_linea_2,
        val.ciudad,
        val.departamento,
        val.codigo_postal,
        val.referencia,
        B'1',
        B'1',
        B'0',
        1,
        NOW()
      FROM public.clientes_tienda cli
      INNER JOIN (
        VALUES
          ('CLT001', 'Casa',    'Paula Rojas',   '3001112233', 'Cra 15 # 93 - 40',   'Apto 402', 'Bogota',    'Cundinamarca', '110221', 'Porteria torre 2'),
          ('CLT002', 'Oficina', 'Juliana Gomez', '3012223344', 'Calle 10 # 43A - 15', 'Piso 5',   'Medellin',  'Antioquia',    '050021', 'Edificio zona rosa'),
          ('CLT003', 'Casa',    'Natalia Marin', '3023334455', 'Av 6N # 28 - 80',    '',         'Cali',      'Valle',        '760045', 'Casa esquinera')
      ) AS val(codigo_cliente, alias, destinatario, telefono, direccion_linea_1, direccion_linea_2, ciudad, departamento, codigo_postal, referencia)
        ON val.codigo_cliente = cli.codigo
      WHERE NOT EXISTS (
        SELECT 1
        FROM public.clientes_tienda_direcciones dir
        WHERE dir.cliente_tienda_id = cli.cliente_tienda_id
          AND dir.estado = B'1'
          AND dir.borrado = B'0'
      );
  -- 10. Se insertan pedidos base para pruebas del panel tienda.
    -- General.
      INSERT INTO public.pedidos_tienda
      (
        cliente_tienda_id,
        codigo,
        estado_pedido,
        estado_pago,
        metodo_pago,
        cantidad_items,
        subtotal,
        descuento_total,
        envio_total,
        total,
        direccion_resumen,
        observacion,
        fecha_pedido,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      SELECT
        cli.cliente_tienda_id,
        val.codigo,
        val.estado_pedido,
        val.estado_pago,
        val.metodo_pago,
        val.cantidad_items,
        val.subtotal,
        val.descuento_total,
        val.envio_total,
        val.total,
        val.direccion_resumen,
        val.observacion,
        val.fecha_pedido,
        B'1',
        B'0',
        1,
        NOW()
      FROM public.clientes_tienda cli
      INNER JOIN (
        VALUES
          ('CLT001', 'PED-2026-0001', 'pendiente',  'pendiente', 'contra_entrega', 2, 199800, 15000, 12000, 196800, 'Cra 15 # 93 - 40, Bogota',   'Pedido nuevo en validacion comercial.', NOW() - INTERVAL '2 day'),
          ('CLT002', 'PED-2026-0002', 'alistando',  'pagado',    'pse',             2, 179800, 10000, 12000, 181800, 'Calle 10 # 43A - 15, Medellin', 'Pedido listo para preparacion.', NOW() - INTERVAL '1 day'),
          ('CLT003', 'PED-2026-0003', 'enviado',    'pagado',    'tarjeta',         1, 109900, 20000, 12000, 101900, 'Av 6N # 28 - 80, Cali',      'Pedido con guia generada.', NOW() - INTERVAL '6 hour')
      ) AS val(codigo_cliente, codigo, estado_pedido, estado_pago, metodo_pago, cantidad_items, subtotal, descuento_total, envio_total, total, direccion_resumen, observacion, fecha_pedido)
        ON val.codigo_cliente = cli.codigo
      WHERE NOT EXISTS (
        SELECT 1
        FROM public.pedidos_tienda ped
        WHERE ped.codigo = val.codigo
      );
  -- 11. Se insertan detalles base de pedidos.
    -- General.
      INSERT INTO public.pedido_tienda_detalles
      (
        pedido_tienda_id,
        producto_id,
        producto_codigo,
        producto_nombre,
        producto_slug,
        imagen_url,
        cantidad,
        precio_unitario,
        descuento_unitario,
        total_linea,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      SELECT
        ped.pedido_tienda_id,
        pro.producto_id,
        pro.codigo,
        pro.nombre,
        pro.slug,
        img.imagen_url,
        val.cantidad,
        val.precio_unitario,
        val.descuento_unitario,
        val.total_linea,
        B'1',
        B'0',
        1,
        NOW()
      FROM public.pedidos_tienda ped
      INNER JOIN (
        VALUES
          ('PED-2026-0001', 'SERUM_GLOW_ROSE',      1,  89900, 10000,  79900),
          ('PED-2026-0001', 'BROCHAS_ESSENTIAL_SET',1,  69900,  5000,  64900),
          ('PED-2026-0002', 'BASE_SOFT_MATTE',      1, 129900, 10000, 119900),
          ('PED-2026-0002', 'BLUSH_SOFT_PEACH',     1,  49900,     0,  49900),
          ('PED-2026-0003', 'SERUM_GLOW_ROSE',      1, 109900, 20000,  89900)
      ) AS val(codigo_pedido, codigo_producto, cantidad, precio_unitario, descuento_unitario, total_linea)
        ON val.codigo_pedido = ped.codigo
      INNER JOIN public.productos pro
        ON pro.codigo = val.codigo_producto
      LEFT JOIN LATERAL (
        SELECT
          pim.imagen_url
        FROM public.producto_imagenes pim
        WHERE pim.producto_id = pro.producto_id
          AND pim.estado = B'1'
          AND pim.borrado = B'0'
        ORDER BY
          pim.sw_principal DESC,
          pim.orden ASC,
          pim.producto_imagen_id ASC
        LIMIT 1
      ) img ON TRUE
      WHERE NOT EXISTS (
        SELECT 1
        FROM public.pedido_tienda_detalles det
        WHERE det.pedido_tienda_id = ped.pedido_tienda_id
          AND det.producto_id = pro.producto_id
      );
