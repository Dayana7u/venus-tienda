-- ESTRUCTURA SQL DESARROLLO.
  -- 1. Se crea la tabla de pagos de la tienda.
    -- General.
      CREATE TABLE IF NOT EXISTS public.pagos_tienda (
        pago_tienda_id          serial PRIMARY KEY          NOT NULL,
        pedido_tienda_id        integer                     NOT NULL,
        cliente_tienda_id       integer                     NOT NULL,
        codigo                  character varying(60)       NOT NULL,
        metodo_pago             character varying(40)       NOT NULL,
        estado_pago             character varying(40)       NOT NULL DEFAULT 'pendiente',
        monto                   numeric(14, 2)             NOT NULL DEFAULT 0,
        titular_pagador         character varying(160),
        documento_pagador       character varying(40),
        correo_pagador          character varying(160),
        entidad_pse             character varying(120),
        franquicia_tarjeta      character varying(60),
        ultimos_cuatro          character varying(4),
        referencia_pasarela     character varying(120),
        respuesta_pasarela      text,
        fecha_procesamiento     timestamp without time zone,
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
      COMMENT ON TABLE public.pagos_tienda                         IS 'Tabla que almacena la trazabilidad del pago asociado al pedido de la tienda.';
      COMMENT ON COLUMN public.pagos_tienda.pago_tienda_id        IS '(PK) Identificador único del pago.';
      COMMENT ON COLUMN public.pagos_tienda.pedido_tienda_id      IS '(FK) Pedido asociado al pago.';
      COMMENT ON COLUMN public.pagos_tienda.cliente_tienda_id     IS '(FK) Cliente que realiza el pago.';
      COMMENT ON COLUMN public.pagos_tienda.codigo                IS 'Código interno del pago.';
      COMMENT ON COLUMN public.pagos_tienda.metodo_pago           IS 'Método de pago seleccionado por el cliente.';
      COMMENT ON COLUMN public.pagos_tienda.estado_pago           IS 'Estado del pago dentro del flujo comercial.';
      COMMENT ON COLUMN public.pagos_tienda.monto                 IS 'Monto total procesado en el pago.';
      COMMENT ON COLUMN public.pagos_tienda.titular_pagador       IS 'Nombre del titular del pago.';
      COMMENT ON COLUMN public.pagos_tienda.documento_pagador     IS 'Documento del pagador.';
      COMMENT ON COLUMN public.pagos_tienda.correo_pagador        IS 'Correo del pagador.';
      COMMENT ON COLUMN public.pagos_tienda.entidad_pse           IS 'Entidad bancaria para pagos PSE.';
      COMMENT ON COLUMN public.pagos_tienda.franquicia_tarjeta    IS 'Franquicia detectada o seleccionada para tarjeta.';
      COMMENT ON COLUMN public.pagos_tienda.ultimos_cuatro        IS 'Últimos cuatro dígitos de la tarjeta.';
      COMMENT ON COLUMN public.pagos_tienda.referencia_pasarela   IS 'Referencia o número de aprobación devuelto por la pasarela.';
      COMMENT ON COLUMN public.pagos_tienda.respuesta_pasarela    IS 'Resumen de la respuesta técnica del pago.';
      COMMENT ON COLUMN public.pagos_tienda.fecha_procesamiento   IS 'Fecha y hora en la que se procesa el pago.';
      COMMENT ON COLUMN public.pagos_tienda.estado                IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.pagos_tienda.borrado               IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.pagos_tienda.usuario_creacion      IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.pagos_tienda.fecha_creacion        IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.pagos_tienda.usuario_modificacion  IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.pagos_tienda.fecha_modificacion    IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.pagos_tienda.usuario_borrado       IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.pagos_tienda.fecha_borrado         IS 'Fecha del borrado lógico.';
    -- Restricciones.
      DO $$
      BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'uq_pub_pag_tie_codigo') THEN
          ALTER TABLE public.pagos_tienda
            ADD CONSTRAINT uq_pub_pag_tie_codigo UNIQUE (codigo);
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pag_tie_ped') THEN
          ALTER TABLE public.pagos_tienda
            ADD CONSTRAINT fk_pub_pag_tie_ped FOREIGN KEY (pedido_tienda_id) REFERENCES public.pedidos_tienda (pedido_tienda_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pag_tie_cli') THEN
          ALTER TABLE public.pagos_tienda
            ADD CONSTRAINT fk_pub_pag_tie_cli FOREIGN KEY (cliente_tienda_id) REFERENCES public.clientes_tienda (cliente_tienda_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pag_tie_usr_cre') THEN
          ALTER TABLE public.pagos_tienda
            ADD CONSTRAINT fk_pub_pag_tie_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pag_tie_usr_mod') THEN
          ALTER TABLE public.pagos_tienda
            ADD CONSTRAINT fk_pub_pag_tie_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;

        IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_pub_pag_tie_usr_bor') THEN
          ALTER TABLE public.pagos_tienda
            ADD CONSTRAINT fk_pub_pag_tie_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
        END IF;
      END $$;
  -- 2. Se agregan índices de apoyo para pagos y checkout.
    -- General.
      CREATE INDEX IF NOT EXISTS idx_pub_pag_tie_pedido         ON public.pagos_tienda (pedido_tienda_id);
      CREATE INDEX IF NOT EXISTS idx_pub_pag_tie_cliente        ON public.pagos_tienda (cliente_tienda_id);
      CREATE INDEX IF NOT EXISTS idx_pub_pag_tie_metodo_estado  ON public.pagos_tienda (metodo_pago, estado_pago);
