-- 1. Se ajusta el tema VENUS hacia una linea premium editorial manteniendo la parametrizacion vigente.
  UPDATE system.tema_tokens
  SET
    valor                = CASE clave
                             WHEN 'color.primary'         THEN '#BFAFD0'
                             WHEN 'color.secondary'       THEN '#DDD4E7'
                             WHEN 'color.accent'          THEN '#D4B6CA'
                             WHEN 'color.background'      THEN '#F5F2F8'
                             WHEN 'color.surface'         THEN '#FFFFFF'
                             WHEN 'color.text'            THEN '#685666'
                             WHEN 'color.text.soft'       THEN '#8A7A88'
                             WHEN 'color.border'          THEN '#BFAFD0'
                             WHEN 'font.family.heading'   THEN 'Playfair Display'
                             WHEN 'font.family.body'      THEN 'Inter'
                             WHEN 'shadow.card'           THEN '0 18px 42px rgba(104, 86, 102, 0.10)'
                             WHEN 'border.radius.sm'      THEN '14px'
                             WHEN 'border.radius.md'      THEN '20px'
                             WHEN 'border.radius.lg'      THEN '28px'
                             ELSE valor
                           END,
    usuario_modificacion = 1,
    fecha_modificacion   = NOW()
  WHERE tema_id = (
    SELECT tema_id
    FROM system.temas
    WHERE codigo = 'VENUS'
    LIMIT 1
  )
    AND clave IN
    (
      'color.primary',
      'color.secondary',
      'color.accent',
      'color.background',
      'color.surface',
      'color.text',
      'color.text.soft',
      'color.border',
      'font.family.heading',
      'font.family.body',
      'shadow.card',
      'border.radius.sm',
      'border.radius.md',
      'border.radius.lg'
    );

-- 2. Se ajustan componentes visibles del frente publico para hero, cards, footer, botones, badges e inputs.
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
    v.componente,
    v.propiedad,
    v.valor,
    v.tipo_dato,
    v.descripcion,
    v.orden,
    B'1',
    B'0',
    1,
    NOW(),
    NULL,
    NULL,
    NULL,
    NULL
  FROM system.temas ven
  INNER JOIN
  (
    VALUES
      ('header',            'background',         'rgba(245, 242, 248, 0.96)',                                           'string', 'Fondo header Venus premium.',                                  310),
      ('header',            'border_color',       '#DDD4E7',                                                             'string', 'Borde header Venus premium.',                                  311),
      ('header',            'link_color',         '#685666',                                                             'string', 'Color links header Venus premium.',                            312),
      ('header',            'link_hover_color',   '#685666',                                                             'string', 'Color hover links header Venus premium.',                      313),
      ('button.primary',    'background',         '#BFAFD0',                                                             'string', 'Fondo boton principal Venus premium.',                         320),
      ('button.primary',    'color',              '#FFFFFF',                                                             'string', 'Texto boton principal Venus premium.',                         321),
      ('button.primary',    'border_radius',      '999px',                                                               'string', 'Radio boton principal Venus premium.',                         322),
      ('badge',             'background',         '#F5CFC6',                                                             'string', 'Fondo badge Venus premium.',                                   330),
      ('badge',             'color',              '#685666',                                                             'string', 'Texto badge Venus premium.',                                   331),
      ('badge',             'border_color',       '#BFAFD0',                                                             'string', 'Borde badge Venus premium.',                                   332),
      ('input.search',      'background',         '#FFFFFF',                                                             'string', 'Fondo input Venus premium.',                                   340),
      ('input.search',      'border_color',       '#BFAFD0',                                                             'string', 'Borde input Venus premium.',                                   341),
      ('card.product',      'background',         '#FFFFFF',                                                             'string', 'Fondo card producto Venus premium.',                           350),
      ('card.product',      'border_color',       '#DDD4E7',                                                             'string', 'Borde card producto Venus premium.',                           351),
      ('card.product',      'box_shadow',         '0 16px 36px rgba(104, 86, 102, 0.10)',                               'string', 'Sombra card producto Venus premium.',                          352),
      ('home.hero',         'background',         'linear-gradient(180deg, #F3D6D3 0%, #F5CFC6 100%)',                   'string', 'Fondo hero Venus premium.',                                    360),
      ('home.hero',         'background_alt',     'linear-gradient(180deg, #DDD4E7 0%, #F5F2F8 100%)',                   'string', 'Fondo alterno hero Venus premium.',                            361),
      ('home.hero',         'title_color',        '#685666',                                                             'string', 'Color titulo hero Venus premium.',                             362),
      ('home.hero',         'text_color',         '#8A7A88',                                                             'string', 'Color texto hero Venus premium.',                              363),
      ('home.surface',      'background',         'rgba(255, 255, 255, 0.86)',                                          'string', 'Fondo shell Venus premium.',                                   370),
      ('home.surface',      'border_color',       '#DDD4E7',                                                             'string', 'Borde shell Venus premium.',                                   371),
      ('home.card',         'background',         '#FFFFFF',                                                             'string', 'Fondo card home Venus premium.',                               380),
      ('home.card',         'border_color',       '#DDD4E7',                                                             'string', 'Borde card home Venus premium.',                               381),
      ('home.card',         'box_shadow',         '0 18px 42px rgba(104, 86, 102, 0.10)',                               'string', 'Sombra card home Venus premium.',                              382),
      ('home.cta',          'background',         'linear-gradient(135deg, #DDD4E7 0%, #F3D6D3 100%)',                   'string', 'Fondo CTA final Venus premium.',                               390),
      ('footer',            'background',         '#685666',                                                             'string', 'Fondo footer Venus premium.',                                  400),
      ('footer',            'background_alt',     '#5E4E5C',                                                             'string', 'Fondo alterno footer Venus premium.',                          401),
      ('footer',            'color',              '#F5F2F8',                                                             'string', 'Texto footer Venus premium.',                                  402),
      ('footer',            'title_color',        '#FFFFFF',                                                             'string', 'Titulo footer Venus premium.',                                 403),
      ('footer',            'link_color',         '#FFFFFF',                                                             'string', 'Link footer Venus premium.',                                   404),
      ('footer',            'border_color',       '#685666',                                                             'string', 'Borde footer Venus premium.',                                  405),
      ('footer',            'copy_border_color',  'rgba(255, 255, 255, 0.18)',                                          'string', 'Borde copy footer Venus premium.',                             406),
      ('footer',            'link_hover_background','rgba(255, 255, 255, 0.12)',                                       'string', 'Hover link footer Venus premium.',                             407),
      ('topbar',            'background',         'linear-gradient(90deg, #DDD4E7 0%, #F3D6D3 100%)',                    'string', 'Fondo topbar Venus premium.',                                  410),
      ('topbar',            'color',              '#685666',                                                             'string', 'Texto topbar Venus premium.',                                  411),
      ('collection.card',   'background',         '#F5F2F8',                                                             'string', 'Fondo cards de coleccion Venus premium.',                      420),
      ('collection.card',   'border_color',       '#DDD4E7',                                                             'string', 'Borde cards de coleccion Venus premium.',                      421),
      ('contact.highlight', 'background',         '#F3D6D3',                                                             'string', 'Fondo contacto destacado Venus premium.',                      430),
      ('contact.highlight', 'border_color',       '#BFAFD0',                                                             'string', 'Borde contacto destacado Venus premium.',                      431)
  ) AS v
  (
    componente,
    propiedad,
    valor,
    tipo_dato,
    descripcion,
    orden
  ) ON ven.codigo = 'VENUS'
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
