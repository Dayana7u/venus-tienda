# Estándares SQL

## Lineamientos aplicados
- Mantener tabulación consistente.
- Documentar tablas y columnas.
- Registrar ajustes posteriores mediante scripts incrementales para conservar trazabilidad.
- Evitar modificar estructuras cerradas sin un patch específico.

## Aplicación actual
El ajuste de la columna `accion` se deja en un script incremental para no mezclar la base inicial con cambios posteriores del frente de parametrización.
