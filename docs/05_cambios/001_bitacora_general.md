# Bitácora general del proyecto

## 2026-03-23 - Base técnica y SQL inicial
- Se creó la estructura base del repositorio.
- Se creó y ejecutó el SQL inicial de public y system.
- Se levantó PostgreSQL local con Docker.

## 2026-03-23 - Capa base backend sobre system
- Se creó configdb.php para centralizar conexión y logs.
- Se creó system_model.class.php para consultar tablas del esquema system.
- Se creó system_controller.php con validación de token y switch por acción.

## 2026-03-23 - Ajuste del bloque de parametrización
- Se corrigió parametrizacion_model.class.php para consumir configdb.php mediante funciones y no como arreglo.
- Se ajustó parametrizacion_controller.php con validación previa de token, cabecera JSON y acciones separadas por switch.
- Se creó parametrizacion_template.js para centralizar la impresión visual del módulo.
- Se reorganizó parametrizacion.js con objeto general, inicialización asíncrona, búsqueda local y renderizado por secciones.
- Se ajustó parametrizacion_peticiones.js con funciones asíncronas y petición centralizada al controlador.
- Se dejó actualizada la trazabilidad documental en README.md, alcance funcional y este archivo.

## Pendiente acordado
- Implementar login e inicio de sesión.
- Validar visualmente en navegador una vez exista acceso al aplicativo.
