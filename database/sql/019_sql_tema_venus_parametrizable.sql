-- ESTRUCTURA SQL DESARROLLO.
  -- 1. Se crea el tema VENUS a partir de la base visual existente y se deja activo para la tienda pública.
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
      SELECT
        COALESCE(MAX(tem.tema_id), 0) + 1,
        'VENUS',
        'Venus',
        'Tema visual parametrizable con paleta lavanda, rosa suave y crema para la tienda pública y el panel administrativo.',
        '1.0.0',
        B'0',
        COALESCE(MAX(tem.orden), 0) + 1,
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      FROM
        system.temas tem
      WHERE NOT EXISTS
      (
        SELECT
          1
        FROM
          system.temas tem_exi
        WHERE
          tem_exi.codigo = 'VENUS'
      );

  -- 2. Se clonan los tokens del tema PINK_NUDE al nuevo tema VENUS.
    -- General.
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
      SELECT
        COALESCE((SELECT MAX(ttk.tema_token_id) FROM system.tema_tokens ttk), 0) + ROW_NUMBER() OVER(ORDER BY ttk_base.orden, ttk_base.tema_token_id),
        tem_venus.tema_id,
        ttk_base.grupo,
        ttk_base.clave,
        ttk_base.valor,
        ttk_base.tipo_dato,
        REPLACE(ttk_base.descripcion, 'pink nude', 'venus'),
        ttk_base.orden,
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      FROM
        system.tema_tokens ttk_base
        INNER JOIN system.temas tem_base
          ON tem_base.tema_id = ttk_base.tema_id
        INNER JOIN system.temas tem_venus
          ON tem_venus.codigo = 'VENUS'
      WHERE
        tem_base.codigo = 'PINK_NUDE'
        AND NOT EXISTS
        (
          SELECT
            1
          FROM
            system.tema_tokens ttk_val
          WHERE
            ttk_val.tema_id = tem_venus.tema_id
            AND ttk_val.clave = ttk_base.clave
            AND ttk_val.borrado = B'0'
        );

  -- 3. Se clonan los componentes visuales del tema PINK_NUDE al nuevo tema VENUS.
    -- General.
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
      SELECT
        COALESCE((SELECT MAX(tco.tema_componente_id) FROM system.tema_componentes tco), 0) + ROW_NUMBER() OVER(ORDER BY tco_base.orden, tco_base.tema_componente_id),
        tem_venus.tema_id,
        tco_base.componente,
        tco_base.propiedad,
        tco_base.valor,
        tco_base.tipo_dato,
        REPLACE(tco_base.descripcion, 'pink nude', 'venus'),
        tco_base.orden,
        B'1',
        B'0',
        1,
        NOW(),
        NULL,
        NULL,
        NULL,
        NULL
      FROM
        system.tema_componentes tco_base
        INNER JOIN system.temas tem_base
          ON tem_base.tema_id = tco_base.tema_id
        INNER JOIN system.temas tem_venus
          ON tem_venus.codigo = 'VENUS'
      WHERE
        tem_base.codigo = 'PINK_NUDE'
        AND NOT EXISTS
        (
          SELECT
            1
          FROM
            system.tema_componentes tco_val
          WHERE
            tco_val.tema_id = tem_venus.tema_id
            AND tco_val.componente = tco_base.componente
            AND tco_val.propiedad = tco_base.propiedad
            AND tco_val.borrado = B'0'
        );

  -- 4. Se actualizan los tokens visuales principales del tema VENUS.
    -- General.
      UPDATE system.tema_tokens ttk
      SET
        valor                = ven.valor,
        descripcion          = ven.descripcion,
        usuario_modificacion = 1,
        fecha_modificacion   = NOW()
      FROM
      (
        VALUES
          ('color.primary',         '#DDD4E7', 'Color principal lavanda clara del tema venus.'),
          ('color.secondary',       '#BFAFD0', 'Color secundario malva suave del tema venus.'),
          ('color.accent',          '#D4B6CA', 'Color de acento rosa malva del tema venus.'),
          ('color.background',      '#FFFDFC', 'Color de fondo principal crema clara del tema venus.'),
          ('color.surface',         '#FFFFFF', 'Color de superficie blanca del tema venus.'),
          ('color.text',            '#685666', 'Color principal de texto del tema venus.'),
          ('color.text.soft',       '#8A7A88', 'Color auxiliar de texto del tema venus.'),
          ('color.border',          '#E8D9E3', 'Color de borde suave del tema venus.'),
          ('font.family.heading',   'Cormorant Garamond', 'Fuente de títulos del tema venus.'),
          ('font.family.body',      'Inter', 'Fuente base del tema venus.'),
          ('border.radius.sm',      '14px', 'Radio pequeño del tema venus.'),
          ('border.radius.md',      '22px', 'Radio mediano del tema venus.'),
          ('border.radius.lg',      '32px', 'Radio grande del tema venus.'),
          ('shadow.card',           '0 24px 55px rgba(191, 175, 208, 0.14)', 'Sombra principal de tarjetas del tema venus.')
      ) AS ven(clave, valor, descripcion)
        INNER JOIN system.temas tem
          ON tem.codigo = 'VENUS'
      WHERE
        ttk.tema_id = tem.tema_id
        AND ttk.clave = ven.clave
        AND ttk.borrado = B'0';

  -- 5. Se actualizan componentes visuales principales del tema VENUS.
    -- General.
      UPDATE system.tema_componentes tco
      SET
        valor                = ven.valor,
        usuario_modificacion = 1,
        fecha_modificacion   = NOW()
      FROM
      (
        VALUES
          ('topbar',             'background',                 '#BFAFD0'),
          ('topbar',             'color',                      '#FFFDFD'),
          ('button.primary',     'background',                 '#C59AB8'),
          ('button.primary',     'color',                      '#FFFDFD'),
          ('badge',              'background',                 '#EEE6F3'),
          ('badge',              'color',                      '#8D6F8B'),
          ('badge',              'border_color',               '#D7C7D8'),
          ('card.product',       'background',                 '#FFFFFF'),
          ('card.product',       'border_color',               '#E7DAE2'),
          ('card.product',       'box_shadow',                 '0 24px 55px rgba(191, 175, 208, 0.14)'),
          ('input.search',       'background',                 '#FFFFFF'),
          ('input.search',       'border_color',               '#DCCEDD'),
          ('collection.card',    'background',                 '#FAF5F8'),
          ('collection.card',    'border_color',               '#E7D8E3'),
          ('contact.highlight',  'background',                 '#FFF7F6'),
          ('contact.highlight',  'border_color',               '#E9DADF'),
          ('footer',             'background',                 '#F3EAF0'),
          ('footer',             'background_alt',             '#EDE2EA'),
          ('footer',             'color',                      '#6D5968'),
          ('footer',             'link_color',                 '#8A6C88'),
          ('footer',             'link_hover_background',      'rgba(221, 212, 231, 0.36)'),
          ('footer',             'border_color',               '#E4D6E2')
      ) AS ven(componente, propiedad, valor)
        INNER JOIN system.temas tem
          ON tem.codigo = 'VENUS'
      WHERE
        tco.tema_id = tem.tema_id
        AND tco.componente = ven.componente
        AND tco.propiedad = ven.propiedad
        AND tco.borrado = B'0';

  -- 6. Se activa el tema VENUS para la tienda pública.
    -- General.
      UPDATE system.modulo_configuraciones mco
      SET
        valor                = 'VENUS',
        valor_defecto        = 'VENUS',
        usuario_modificacion = 1,
        fecha_modificacion   = NOW()
      WHERE
        mco.codigo = 'tienda_publica.tema_activo'
        AND mco.borrado = B'0';

  -- 7. Se ajusta el contador de secuencias.
    -- General.
      SELECT setval(pg_get_serial_sequence('system.temas', 'tema_id'), COALESCE(MAX(tema_id), 1), TRUE) FROM system.temas;
      SELECT setval(pg_get_serial_sequence('system.tema_tokens', 'tema_token_id'), COALESCE(MAX(tema_token_id), 1), TRUE) FROM system.tema_tokens;
      SELECT setval(pg_get_serial_sequence('system.tema_componentes', 'tema_componente_id'), COALESCE(MAX(tema_componente_id), 1), TRUE) FROM system.tema_componentes;
