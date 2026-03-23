# tienda_virtual

Proyecto base MVC en PHP con PostgreSQL para parametrización, acceso inicial y administración.

## Estado actual

- Login funcional con sesión en `public.usuarios_sesiones`
- Parametrización con CRUD base para:
  - temas
  - branding
  - parámetros
  - módulos
  - integraciones
  - menús
  - roles
  - usuarios
- Diseño administrativo minimalista para login y parametrización
- Login administrativo separado del acceso futuro de la tienda
- Ajustes responsive para escritorio, tableta y móvil
- Alertas informativas, de error y confirmación visual
- PostgreSQL local por Docker en `docker/docker-compose.postgres.local.yml`

## SQL pendientes del flujo actual

1. `database/sql/001_sql_parametrizacion_base_v2.sql`
2. `database/sql/003_sql_login_token_base.sql`

## Apertura local

```bash
php -S localhost:8080 -t backend
```

Abrir:

```text
http://localhost:8080/index.php
```
