-- ESTRUCTURA SQL DESARROLLO.
  -- 1. Se crea la tabla de categorías comerciales de la tienda.
    -- General.
      CREATE TABLE IF NOT EXISTS public.categorias (
        categoria_id           serial PRIMARY KEY          NOT NULL,
        codigo                 character varying(60)       NOT NULL,
        nombre                 character varying(150)      NOT NULL,
        slug                   character varying(160)      NOT NULL,
        linea                  character varying(60)       NOT NULL,
        descripcion            text,
        orden                  integer                     NOT NULL DEFAULT 1,
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
      COMMENT ON TABLE public.categorias                         IS 'Tabla que almacena las categorías comerciales de la tienda.';
      COMMENT ON COLUMN public.categorias.categoria_id          IS '(PK) Identificador único de la categoría.';
      COMMENT ON COLUMN public.categorias.codigo                IS 'Código interno de la categoría.';
      COMMENT ON COLUMN public.categorias.nombre                IS 'Nombre visible de la categoría.';
      COMMENT ON COLUMN public.categorias.slug                  IS 'Slug público de la categoría.';
      COMMENT ON COLUMN public.categorias.linea                 IS 'Línea comercial: maquillaje, skincare, accesorios u otra.';
      COMMENT ON COLUMN public.categorias.descripcion           IS 'Descripción comercial de la categoría.';
      COMMENT ON COLUMN public.categorias.orden                 IS 'Orden visual de la categoría en la tienda.';
      COMMENT ON COLUMN public.categorias.estado                IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.categorias.borrado               IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.categorias.usuario_creacion      IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.categorias.fecha_creacion        IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.categorias.usuario_modificacion  IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.categorias.fecha_modificacion    IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.categorias.usuario_borrado       IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.categorias.fecha_borrado         IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE public.categorias ADD CONSTRAINT uq_pub_cat_codigo UNIQUE (codigo);
      ALTER TABLE public.categorias ADD CONSTRAINT uq_pub_cat_slug   UNIQUE (slug);
      ALTER TABLE public.categorias ADD CONSTRAINT fk_pub_cat_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.categorias ADD CONSTRAINT fk_pub_cat_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.categorias ADD CONSTRAINT fk_pub_cat_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 2. Se crea la tabla de productos comerciales de la tienda.
    -- General.
      CREATE TABLE IF NOT EXISTS public.productos (
        producto_id            serial PRIMARY KEY          NOT NULL,
        categoria_id           integer                     NOT NULL,
        codigo                 character varying(60)       NOT NULL,
        nombre                 character varying(180)      NOT NULL,
        slug                   character varying(200)      NOT NULL,
        resumen                character varying(255),
        descripcion            text,
        etiqueta               character varying(80),
        precio_base            numeric(14, 2)              NOT NULL DEFAULT 0,
        precio_oferta          numeric(14, 2)              NOT NULL DEFAULT 0,
        rating_promedio        numeric(4, 2)               NOT NULL DEFAULT 0,
        stock                  integer                     NOT NULL DEFAULT 0,
        sw_destacado           bit(1)                      NOT NULL DEFAULT B'0',
        sw_oferta              bit(1)                      NOT NULL DEFAULT B'0',
        orden                  integer                     NOT NULL DEFAULT 1,
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
      COMMENT ON TABLE public.productos                          IS 'Tabla que almacena los productos visibles de la tienda.';
      COMMENT ON COLUMN public.productos.producto_id            IS '(PK) Identificador único del producto.';
      COMMENT ON COLUMN public.productos.categoria_id           IS '(FK) Categoría comercial del producto.';
      COMMENT ON COLUMN public.productos.codigo                 IS 'Código interno del producto.';
      COMMENT ON COLUMN public.productos.nombre                 IS 'Nombre visible del producto.';
      COMMENT ON COLUMN public.productos.slug                   IS 'Slug público del producto.';
      COMMENT ON COLUMN public.productos.resumen                IS 'Resumen corto visible en cards.';
      COMMENT ON COLUMN public.productos.descripcion            IS 'Descripción comercial del producto.';
      COMMENT ON COLUMN public.productos.etiqueta               IS 'Etiqueta corta visible en cards o badges.';
      COMMENT ON COLUMN public.productos.precio_base            IS 'Precio base antes de descuento.';
      COMMENT ON COLUMN public.productos.precio_oferta          IS 'Precio actual visible en la tienda.';
      COMMENT ON COLUMN public.productos.rating_promedio        IS 'Calificación promedio visible del producto.';
      COMMENT ON COLUMN public.productos.stock                  IS 'Stock disponible del producto.';
      COMMENT ON COLUMN public.productos.sw_destacado           IS 'Visible como destacado: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.productos.sw_oferta              IS 'Visible como oferta: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.productos.orden                  IS 'Orden visual del producto dentro de listados.';
      COMMENT ON COLUMN public.productos.estado                 IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.productos.borrado                IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.productos.usuario_creacion       IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.productos.fecha_creacion         IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.productos.usuario_modificacion   IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.productos.fecha_modificacion     IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.productos.usuario_borrado        IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.productos.fecha_borrado          IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE public.productos ADD CONSTRAINT uq_pub_pro_codigo UNIQUE (codigo);
      ALTER TABLE public.productos ADD CONSTRAINT uq_pub_pro_slug   UNIQUE (slug);
      ALTER TABLE public.productos ADD CONSTRAINT fk_pub_pro_cat     FOREIGN KEY (categoria_id) REFERENCES public.categorias (categoria_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.productos ADD CONSTRAINT fk_pub_pro_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.productos ADD CONSTRAINT fk_pub_pro_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.productos ADD CONSTRAINT fk_pub_pro_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 3. Se crea la tabla de imágenes de productos.
    -- General.
      CREATE TABLE IF NOT EXISTS public.producto_imagenes (
        producto_imagen_id      serial PRIMARY KEY          NOT NULL,
        producto_id             integer                     NOT NULL,
        imagen_url              text,
        recurso_visual          character varying(80),
        texto_alternativo       character varying(180),
        sw_principal            bit(1)                      NOT NULL DEFAULT B'1',
        orden                   integer                     NOT NULL DEFAULT 1,
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
      COMMENT ON TABLE public.producto_imagenes                         IS 'Tabla que almacena las imágenes o recursos visuales de cada producto.';
      COMMENT ON COLUMN public.producto_imagenes.producto_imagen_id    IS '(PK) Identificador único de la imagen.';
      COMMENT ON COLUMN public.producto_imagenes.producto_id           IS '(FK) Producto asociado a la imagen.';
      COMMENT ON COLUMN public.producto_imagenes.imagen_url            IS 'Ruta o URL visible de la imagen principal del producto.';
      COMMENT ON COLUMN public.producto_imagenes.recurso_visual        IS 'Clave del recurso visual usado como respaldo cuando no exista imagen.';
      COMMENT ON COLUMN public.producto_imagenes.texto_alternativo     IS 'Texto alternativo de la imagen.';
      COMMENT ON COLUMN public.producto_imagenes.sw_principal          IS 'Imagen principal: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.producto_imagenes.orden                 IS 'Orden visual de la imagen dentro del producto.';
      COMMENT ON COLUMN public.producto_imagenes.estado                IS 'Estado del registro: (0) Inactivo, (1) Activo.';
      COMMENT ON COLUMN public.producto_imagenes.borrado               IS 'Borrado lógico: (0) No, (1) Sí.';
      COMMENT ON COLUMN public.producto_imagenes.usuario_creacion      IS '(FK) Usuario que crea el registro.';
      COMMENT ON COLUMN public.producto_imagenes.fecha_creacion        IS 'Fecha de creación del registro.';
      COMMENT ON COLUMN public.producto_imagenes.usuario_modificacion  IS '(FK) Usuario que modifica el registro.';
      COMMENT ON COLUMN public.producto_imagenes.fecha_modificacion    IS 'Fecha de modificación del registro.';
      COMMENT ON COLUMN public.producto_imagenes.usuario_borrado       IS '(FK) Usuario que realiza el borrado lógico.';
      COMMENT ON COLUMN public.producto_imagenes.fecha_borrado         IS 'Fecha del borrado lógico.';
    -- Restricciones.
      ALTER TABLE public.producto_imagenes ADD CONSTRAINT fk_pub_pim_pro     FOREIGN KEY (producto_id) REFERENCES public.productos (producto_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.producto_imagenes ADD CONSTRAINT fk_pub_pim_usr_cre FOREIGN KEY (usuario_creacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.producto_imagenes ADD CONSTRAINT fk_pub_pim_usr_mod FOREIGN KEY (usuario_modificacion) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
      ALTER TABLE public.producto_imagenes ADD CONSTRAINT fk_pub_pim_usr_bor FOREIGN KEY (usuario_borrado) REFERENCES public.usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
  -- 4. Se agregan índices base para el catálogo comercial.
    -- General.
      CREATE INDEX IF NOT EXISTS idx_pub_cat_linea         ON public.categorias (linea);
      CREATE INDEX IF NOT EXISTS idx_pub_pro_cat           ON public.productos (categoria_id);
      CREATE INDEX IF NOT EXISTS idx_pub_pro_slug          ON public.productos (slug);
      CREATE INDEX IF NOT EXISTS idx_pub_pim_pro           ON public.producto_imagenes (producto_id, sw_principal);
  -- 5. Se registra el módulo administrativo de tienda.
    -- General.
      INSERT INTO system.modulos
      (
        codigo,
        nombre,
        descripcion,
        orden,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      VALUES
      (
        'TIENDA_ADMIN',
        'Tienda',
        'Panel administrativo del catálogo comercial de la tienda.',
        3,
        B'1',
        B'0',
        1,
        NOW()
      )
      ON CONFLICT (codigo) DO NOTHING;
  -- 6. Se registra el menú administrativo de tienda.
    -- General.
      INSERT INTO system.menus
      (
        modulo_id,
        codigo,
        nombre,
        ruta,
        icono,
        orden,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      SELECT
        sys_mod.modulo_id,
        'TIENDA_ADMIN',
        'Tienda',
        '/admin/tienda/',
        'storefront',
        3,
        B'1',
        B'0',
        1,
        NOW()
      FROM system.modulos sys_mod
      WHERE sys_mod.codigo = 'TIENDA_ADMIN'
        AND sys_mod.estado = B'1'
        AND sys_mod.borrado = B'0'
        AND NOT EXISTS (
          SELECT 1
          FROM system.menus sys_men
          WHERE sys_men.codigo = 'TIENDA_ADMIN'
        );
  -- 7. Se insertan categorías base para maquillaje, skincare y accesorios.
    -- General.
      INSERT INTO public.categorias
      (
        codigo,
        nombre,
        slug,
        linea,
        descripcion,
        orden,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      VALUES
        ('MAQ', 'Maquillaje', 'maquillaje', 'maquillaje', 'Categoría principal de maquillaje.', 1, B'1', B'0', 1, NOW()),
        ('SKIN', 'Skincare', 'skincare', 'skincare', 'Categoría principal de cuidado facial.', 2, B'1', B'0', 1, NOW()),
        ('ACC', 'Accesorios', 'accesorios', 'accesorios', 'Categoría principal de accesorios beauty.', 3, B'1', B'0', 1, NOW())
      ON CONFLICT (codigo) DO NOTHING;
  -- 8. Se insertan productos iniciales de la tienda.
    -- General.
      INSERT INTO public.productos
      (
        categoria_id,
        codigo,
        nombre,
        slug,
        resumen,
        descripcion,
        etiqueta,
        precio_base,
        precio_oferta,
        rating_promedio,
        stock,
        sw_destacado,
        sw_oferta,
        orden,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      SELECT
        sys_cat.categoria_id,
        v.codigo,
        v.nombre,
        v.slug,
        v.resumen,
        v.descripcion,
        v.etiqueta,
        v.precio_base,
        v.precio_oferta,
        v.rating_promedio,
        v.stock,
        v.sw_destacado,
        v.sw_oferta,
        v.orden,
        B'1',
        B'0',
        1,
        NOW()
      FROM public.categorias sys_cat
      INNER JOIN
      (
        VALUES
          ('SKIN', 'SERUM_GLOW_ROSE',        'Serum Glow Rose',         'serum-glow-rose',         'Suero hidratante con acabado glow para rutina de día.', 'Fórmula ligera para hidratar, iluminar y preparar la piel antes del maquillaje.', 'Skincare', 109900, 89900, 4.90, 25, B'1', B'1', 1),
          ('MAQ',  'BASE_SOFT_MATTE',        'Base Soft Matte',         'base-soft-matte',         'Base de cobertura media-alta con acabado uniforme.',     'Cobertura construible y sensación liviana para un look profesional.',            'Maquillaje', 89900, 74900, 4.80, 30, B'1', B'1', 2),
          ('MAQ',  'LIP_OIL_PEONY',          'Lip Oil Peony',           'lip-oil-peony',           'Aceite labial con brillo suave y sensación de hidratación.', 'Perfecto para looks limpios y combinaciones con delineado suave.',             'Glow lips', 45900, 45900, 4.70, 40, B'1', B'0', 3),
          ('ACC',  'BROCHAS_ESSENTIAL_SET',  'Brochas Essential Set',   'brochas-essential-set',   'Set base para rostro, ojos y detalles.',                 'Kit de brochas pensado para una rutina completa de maquillaje.',                'Accesorios', 84900, 69900, 4.90, 20, B'1', B'1', 4),
          ('SKIN', 'CREMA_BARRIER_NIGHT',    'Crema Barrier Night',     'crema-barrier-night',     'Crema de noche para reparar y suavizar la barrera cutánea.', 'Ideal para cerrar la rutina y dejar sensación de confort.',                   'Noche', 83900, 83900, 4.80, 18, B'0', B'0', 5),
          ('ACC',  'ORGANIZADOR_VANITY_MINI','Organizador Vanity Mini', 'organizador-vanity-mini', 'Organizador compacto para skincare y maquillaje.',      'Perfecto para tocador, viaje o kits de regalo.',                                'Vanity', 63900, 52900, 4.60, 16, B'0', B'1', 6),
          ('SKIN', 'MASK_HYDRA_CLOUD',       'Mask Hydra Cloud',        'mask-hydra-cloud',        'Mascarilla cremosa para hidratación profunda.',         'Se usa dos veces por semana para una piel más luminosa.',                       'Tratamiento', 69900, 58900, 4.70, 15, B'0', B'1', 7),
          ('MAQ',  'BLUSH_SOFT_PEACH',       'Blush Soft Peach',        'blush-soft-peach',        'Rubor de acabado natural para un look fresco.',         'Textura sedosa que se integra fácilmente a la piel.',                           'Color', 42900, 42900, 4.80, 32, B'1', B'0', 8),
          ('ACC',  'KIT_GIFT_BLOOM',         'Kit Gift Bloom',          'kit-gift-bloom',          'Set listo para regalo con cosmetiquera y selección glow.', 'Campaña comercial pensada para fechas especiales.',                           'Regalo', 149900, 119900, 4.90, 12, B'1', B'1', 9)
      ) AS v
      (
        codigo_categoria,
        codigo,
        nombre,
        slug,
        resumen,
        descripcion,
        etiqueta,
        precio_base,
        precio_oferta,
        rating_promedio,
        stock,
        sw_destacado,
        sw_oferta,
        orden
      ) ON sys_cat.codigo = v.codigo_categoria
      WHERE NOT EXISTS (
        SELECT 1
        FROM public.productos pub_pro
        WHERE pub_pro.codigo = v.codigo
      );
  -- 9. Se insertan recursos visuales base para los productos iniciales.
    -- General.
      INSERT INTO public.producto_imagenes
      (
        producto_id,
        imagen_url,
        recurso_visual,
        texto_alternativo,
        sw_principal,
        orden,
        estado,
        borrado,
        usuario_creacion,
        fecha_creacion
      )
      SELECT
        pub_pro.producto_id,
        NULL,
        v.recurso_visual,
        v.texto_alternativo,
        B'1',
        1,
        B'1',
        B'0',
        1,
        NOW()
      FROM public.productos pub_pro
      INNER JOIN
      (
        VALUES
          ('SERUM_GLOW_ROSE',         'serum_rose',    'Serum Glow Rose'),
          ('BASE_SOFT_MATTE',         'base_matte',    'Base Soft Matte'),
          ('LIP_OIL_PEONY',           'lip_oil',       'Lip Oil Peony'),
          ('BROCHAS_ESSENTIAL_SET',   'brochas_set',   'Brochas Essential Set'),
          ('CREMA_BARRIER_NIGHT',     'crema_noche',   'Crema Barrier Night'),
          ('ORGANIZADOR_VANITY_MINI', 'organizador',   'Organizador Vanity Mini'),
          ('MASK_HYDRA_CLOUD',        'mask_cloud',    'Mask Hydra Cloud'),
          ('BLUSH_SOFT_PEACH',        'blush_peach',   'Blush Soft Peach'),
          ('KIT_GIFT_BLOOM',          'gift_bloom',    'Kit Gift Bloom')
      ) AS v
      (
        codigo_producto,
        recurso_visual,
        texto_alternativo
      ) ON pub_pro.codigo = v.codigo_producto
      WHERE NOT EXISTS (
        SELECT 1
        FROM public.producto_imagenes pub_pim
        WHERE pub_pim.producto_id = pub_pro.producto_id
          AND pub_pim.sw_principal = B'1'
          AND pub_pim.borrado = B'0'
      );
