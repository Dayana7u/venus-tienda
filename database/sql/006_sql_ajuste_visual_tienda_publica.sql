-- 1. Se ajustan los colores visuales del tema PINK_NUDE para mejorar el contraste del footer y de las etiquetas tipo badge.
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
    sys_tem.tema_id,
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
  FROM system.temas sys_tem
  INNER JOIN
  (
    VALUES
      ('footer', 'background_color',       '#EED8DE',                 'string', 'Color de fondo principal del pie de página con mayor presencia visual.', 11),
      ('footer', 'background_alt_color',   '#E7CED5',                 'string', 'Color alterno del degradado del pie de página.', 12),
      ('footer', 'text_color',             '#5F454E',                 'string', 'Color de texto del pie de página.', 13),
      ('footer', 'link_color',             '#6E535C',                 'string', 'Color de enlaces del pie de página.', 14),
      ('footer', 'link_hover_background',  'rgba(255, 255, 255, 0.5)', 'string', 'Color de apoyo al pasar sobre enlaces del pie de página.', 15),
      ('footer', 'border_top_color',       '#E2C3CB',                 'string', 'Color superior de separación del pie de página.', 16),
      ('badge',  'background_color',       '#F7E4E8',                 'string', 'Color de fondo de etiquetas visuales tipo badge.', 17),
      ('badge',  'text_color',             '#8D5E69',                 'string', 'Color de texto de etiquetas visuales tipo badge.', 18),
      ('badge',  'border_color',           '#E8C6CE',                 'string', 'Color de borde de etiquetas visuales tipo badge.', 19)
  ) AS v
  (
    componente,
    propiedad,
    valor,
    tipo_dato,
    descripcion,
    orden
  )
    ON 1 = 1
  WHERE sys_tem.codigo = 'PINK_NUDE'
  ON CONFLICT (tema_id, componente, propiedad) DO UPDATE
  SET
    valor                 = EXCLUDED.valor,
    tipo_dato             = EXCLUDED.tipo_dato,
    descripcion           = EXCLUDED.descripcion,
    orden                 = EXCLUDED.orden,
    estado                = B'1',
    borrado               = B'0',
    usuario_modificacion  = 1,
    fecha_modificacion    = NOW();
