-- 1. Se adiciona la columna accion a la tabla system.parametros.
  ALTER TABLE system.parametros
    ADD COLUMN IF NOT EXISTS accion character varying(120);

  COMMENT ON COLUMN system.parametros.accion IS 'Acción funcional asociada al parámetro.';

-- 2. Se adiciona la columna accion a la tabla system.modulo_configuraciones.
  ALTER TABLE system.modulo_configuraciones
    ADD COLUMN IF NOT EXISTS accion character varying(120);

  COMMENT ON COLUMN system.modulo_configuraciones.accion IS 'Acción funcional asociada a la configuración del módulo.';

-- 3. Se adiciona la columna accion a la tabla system.integracion_configuraciones.
  ALTER TABLE system.integracion_configuraciones
    ADD COLUMN IF NOT EXISTS accion character varying(120);

  COMMENT ON COLUMN system.integracion_configuraciones.accion IS 'Acción funcional asociada a la configuración técnica de la integración.';

-- 4. Se adiciona la columna accion a la tabla system.plantillas.
  ALTER TABLE system.plantillas
    ADD COLUMN IF NOT EXISTS accion character varying(120);

  COMMENT ON COLUMN system.plantillas.accion IS 'Acción funcional asociada a la plantilla reutilizable.';

-- 5. Se adiciona la columna accion a la tabla system.menus.
  ALTER TABLE system.menus
    ADD COLUMN IF NOT EXISTS accion character varying(120);

  COMMENT ON COLUMN system.menus.accion IS 'Acción funcional asociada a la opción de menú.';
