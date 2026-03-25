-- ESTRUCTURA SQL DESARROLLO.
  -- 1. Se registra el tema VENUS para la tienda pública sin afectar el tema pink existente.
    -- General.
      INSERT INTO system.temas
      (
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
        'VENUS',
        'Venus',
        'Tema visual premium con paleta lila, rosa suave y durazno para la tienda pública.',
        '1.0.0',
        B'0',
        3,
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      )
      ON CONFLICT (codigo) DO UPDATE
      SET
        nombre               = EXCLUDED.nombre,
        descripcion          = EXCLUDED.descripcion,
        version              = EXCLUDED.version,
        sw_predeterminado    = EXCLUDED.sw_predeterminado,
        orden                = EXCLUDED.orden,
        estado               = B'1',
        borrado              = B'0',
        usuario_modificacion = 1,
        fecha_modificacion   = NOW();

  -- 2. Se clonan los tokens del tema PINK_NUDE hacia VENUS ajustando la nueva paleta visual.
    -- General.
      INSERT INTO system.tema_tokens
      (
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
      SELECT
        ven.tema_id,
        ttk.grupo,
        ttk.clave,
        CASE ttk.clave
          WHEN 'color.primary'         THEN '#D4B6CA'
          WHEN 'color.secondary'       THEN '#DDD4E7'
          WHEN 'color.accent'          THEN '#BFAFD0'
          WHEN 'color.background'      THEN '#F3D6D3'
          WHEN 'color.surface'         THEN '#F5CFC6'
          WHEN 'color.text'            THEN '#4F3F49'
          WHEN 'color.text.soft'       THEN '#826F7E'
          WHEN 'color.border'          THEN '#E7D5DF'
          WHEN 'shadow.card'           THEN '0 10px 30px rgba(191, 175, 208, 0.18)'
          ELSE ttk.valor
        END,
        ttk.tipo_dato,
        CASE ttk.clave
          WHEN 'color.primary'         THEN 'Color principal Venus para acciones y realces suaves.'
          WHEN 'color.secondary'       THEN 'Color secundario Venus para fondos suaves y bloques comerciales.'
          WHEN 'color.accent'          THEN 'Color de acento Venus para botones y puntos de atención.'
          WHEN 'color.background'      THEN 'Color de fondo general del tema Venus.'
          WHEN 'color.surface'         THEN 'Color de superficie del tema Venus para tarjetas y contenedores.'
          WHEN 'color.text'            THEN 'Color principal de texto del tema Venus.'
          WHEN 'color.text.soft'       THEN 'Color secundario de texto del tema Venus.'
          WHEN 'color.border'          THEN 'Color de borde suave del tema Venus.'
          WHEN 'shadow.card'           THEN 'Sombra principal de tarjetas del tema Venus.'
          ELSE REPLACE(REPLACE(ttk.descripcion, 'pink nude', 'Venus'), 'Pink Nude', 'Venus')
        END,
        ttk.orden,
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      FROM system.tema_tokens ttk
      INNER JOIN system.temas pin
        ON pin.tema_id = ttk.tema_id
      INNER JOIN system.temas ven
        ON ven.codigo = 'VENUS'
      WHERE pin.codigo = 'PINK_NUDE'
      ON CONFLICT (tema_id, grupo, clave) DO UPDATE
      SET
        valor                = EXCLUDED.valor,
        tipo_dato            = EXCLUDED.tipo_dato,
        descripcion          = EXCLUDED.descripcion,
        orden                = EXCLUDED.orden,
        estado               = B'1',
        borrado              = B'0',
        usuario_modificacion = 1,
        fecha_modificacion   = NOW();

  -- 3. Se clonan los componentes del tema PINK_NUDE hacia VENUS ajustando los colores visibles base.
    -- General.
      INSERT INTO system.tema_componentes
      (
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
      SELECT
        ven.tema_id,
        tco.componente,
        tco.propiedad,
        CASE tco.valor
          WHEN '#E8B7C0' THEN '#D4B6CA'
          WHEN '#F4DADF' THEN '#DDD4E7'
          WHEN '#D98C9A' THEN '#BFAFD0'
          WHEN '#FFF8FA' THEN '#F3D6D3'
          WHEN '#FFF8F8' THEN '#F3D6D3'
          WHEN '#FFF7FA' THEN '#F3D6D3'
          WHEN '#FFF7F9' THEN '#F3D6D3'
          WHEN '#FFF7F8' THEN '#F3D6D3'
          WHEN '#FFF5F7' THEN '#F3D6D3'
          WHEN '#FFF3F6' THEN '#F5CFC6'
          WHEN '#FFF2F5' THEN '#F5CFC6'
          WHEN '#FFF9F8' THEN '#F5CFC6'
          WHEN '#FDECEF' THEN '#F5CFC6'
          WHEN '#F8EDF0' THEN '#F5CFC6'
          WHEN '#F8E4E8' THEN '#F5CFC6'
          WHEN '#F7E4E8' THEN '#F5CFC6'
          WHEN '#F1D8DD' THEN '#E7D5DF'
          WHEN '#ECD9DE' THEN '#E7D5DF'
          WHEN '#ECD3DA' THEN '#E7D5DF'
          WHEN '#EED8DE' THEN '#E7D5DF'
          WHEN '#EDD6DC' THEN '#E7D5DF'
          WHEN '#EAD2D8' THEN '#E7D5DF'
          WHEN '#E9D3D9' THEN '#E7D5DF'
          WHEN '#E8C6CE' THEN '#D4B6CA'
          WHEN '#E7CED5' THEN '#D4B6CA'
          WHEN '#E2C3CB' THEN '#D4B6CA'
          WHEN '#D7A1AE' THEN '#BFAFD0'
          WHEN '#B96A7F' THEN '#9B85A8'
          WHEN '#8D5E69' THEN '#6F5A73'
          WHEN '#6E535C' THEN '#5D4A60'
          WHEN '#5F454E' THEN '#4F3F49'
          WHEN '#5C4B51' THEN '#4F3F49'
          WHEN '#8A747A' THEN '#826F7E'
          ELSE tco.valor
        END,
        tco.tipo_dato,
        REPLACE(REPLACE(tco.descripcion, 'pink nude', 'Venus'), 'Pink Nude', 'Venus'),
        tco.orden,
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      FROM system.tema_componentes tco
      INNER JOIN system.temas pin
        ON pin.tema_id = tco.tema_id
      INNER JOIN system.temas ven
        ON ven.codigo = 'VENUS'
      WHERE pin.codigo = 'PINK_NUDE'
      ON CONFLICT (tema_id, componente, propiedad) DO UPDATE
      SET
        valor                = EXCLUDED.valor,
        tipo_dato            = EXCLUDED.tipo_dato,
        descripcion          = EXCLUDED.descripcion,
        orden                = EXCLUDED.orden,
        estado               = B'1',
        borrado              = B'0',
        usuario_modificacion = 1,
        fecha_modificacion   = NOW();

  -- 4. Se actualiza la identidad comercial base para reflejar la nueva tienda VENUS.
    -- General.
      UPDATE system.branding
      SET
        nombre_comercial     = 'VENUS',
        mensaje_bienvenida   = 'Skincare & Accessories',
        texto_footer         = 'VENUS · Skincare & Accessories',
        usuario_modificacion = 1,
        fecha_modificacion   = NOW()
      WHERE codigo = 'TIENDA_PUBLICA_BASE'
        AND borrado = B'0';

  -- 5. Se ajusta el nombre base visible de la aplicación pública.
    -- General.
      UPDATE system.parametros
      SET
        valor_defecto        = 'VENUS',
        usuario_modificacion = 1,
        fecha_modificacion   = NOW()
      WHERE codigo = 'app.nombre'
        AND borrado = B'0';

  -- 6. Se actualizan textos comerciales base del módulo TIENDA_PUBLICA para la identidad VENUS.
    -- General.
      UPDATE system.modulo_configuraciones
      SET
        valor                = CASE codigo
                                 WHEN 'tienda_publica.tema_activo'         THEN 'VENUS'
                                 WHEN 'tienda_publica.topbar_texto'        THEN 'VENUS · Skincare & Accessories · Compra segura · Envíos a todo Colombia'
                                 WHEN 'tienda_publica.hero_etiqueta'       THEN 'Skincare & Accessories'
                                 WHEN 'tienda_publica.hero_titulo'         THEN 'Cosmética premium para tu rutina ideal'
                                 WHEN 'tienda_publica.hero_descripcion'    THEN 'Base visual VENUS con enfoque premium, femenina y parametrizable para catálogo, checkout y campañas comerciales.'
                                 WHEN 'tienda_publica.hero_boton_primario' THEN 'Comprar ahora'
                                 WHEN 'tienda_publica.hero_boton_secundario' THEN 'Ver catálogo'
                                 WHEN 'tienda_publica.hero_item_1'         THEN 'Envíos a todo Colombia'
                                 WHEN 'tienda_publica.hero_item_2'         THEN 'Compra segura y acompañamiento cercano'
                                 WHEN 'tienda_publica.hero_item_3'         THEN 'Tema visual VENUS parametrizable'
                                 WHEN 'tienda_publica.hero_panel_titulo'   THEN 'Base visual parametrizable para beauty ecommerce'
                                 WHEN 'tienda_publica.hero_panel_texto'    THEN 'La tienda toma branding, tema y configuraciones desde base de datos sin tocar la lógica comercial.'
                                 ELSE valor
                               END,
        usuario_modificacion = 1,
        fecha_modificacion   = NOW()
      WHERE modulo_id = (
              SELECT modulo_id
              FROM system.modulos
              WHERE codigo = 'TIENDA_PUBLICA'
              LIMIT 1
            )
        AND codigo IN
        (
          'tienda_publica.tema_activo',
          'tienda_publica.topbar_texto',
          'tienda_publica.hero_etiqueta',
          'tienda_publica.hero_titulo',
          'tienda_publica.hero_descripcion',
          'tienda_publica.hero_boton_primario',
          'tienda_publica.hero_boton_secundario',
          'tienda_publica.hero_item_1',
          'tienda_publica.hero_item_2',
          'tienda_publica.hero_item_3',
          'tienda_publica.hero_panel_titulo',
          'tienda_publica.hero_panel_texto'
        )
        AND borrado = B'0';
