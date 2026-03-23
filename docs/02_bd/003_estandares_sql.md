# Estándares SQL aplicados

## Lineamientos base
Se evidencia que, el script SQL del proyecto mantiene comentarios de documentación sobre esquemas, tablas y columnas, trazabilidad obligatoria en los registros y consistencia en claves primarias, foráneas, únicos e índices.

## Convenciones
- Uso de `CREATE SCHEMA IF NOT EXISTS` para el esquema `system`.
- Uso de `DROP TABLE IF EXISTS` para reinstalación controlada.
- Uso de `serial` como clave primaria en tablas base.
- Uso de `bit(1)` para banderas lógicas como `estado`, `borrado`, `sw_predeterminado`, `sw_habilitado`, `sw_visible`, `sw_encriptado`.
- Uso de `NOW()` como valor por defecto para `fecha_creacion`.
- Uso de comentarios `COMMENT ON TABLE` y `COMMENT ON COLUMN`.
- Uso explícito de `ASC` en los ordenamientos donde aplica.
- Uso de restricciones `UNIQUE` para códigos internos y relaciones puente.
- Uso de llaves foráneas con `ON UPDATE CASCADE ON DELETE RESTRICT`.
- Inclusión de datos iniciales mínimos para seguridad y parametrización.

## Trazabilidad obligatoria
Las tablas base deben contemplar, cuando aplique:
- `estado`
- `borrado`
- `usuario_creacion`
- `fecha_creacion`
- `usuario_modificacion`
- `fecha_modificacion`
- `usuario_borrado`
- `fecha_borrado`

## Alcance actual
- Las tablas del esquema `system` ya están cerradas a nivel estructural.
- Los siguientes cambios deben concentrarse en código PHP, JavaScript y documentación.
