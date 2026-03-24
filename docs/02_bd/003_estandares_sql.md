# Estándares SQL

- Mantener trazabilidad con usuario y fecha de creación, modificación y borrado lógico.
- Manejar `estado` y `borrado` con `bit(1)`.
- Documentar tablas y columnas con `COMMENT ON`.
- Mantener claves administrativas almacenadas con hash.
- Los scripts incrementales deben quedar en `database/sql/` y ejecutarse en orden.
