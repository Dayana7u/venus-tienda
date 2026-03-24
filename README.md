# tienda_virtual

Proyecto base MVC en PHP con PostgreSQL para parametrización, acceso inicial y seguridad administrativa.

## Estado actual

- Login funcional con sesión en `public.usuarios_sesiones`
- Validación de sesión administrativa contra base de datos en login, parametrización y seguridad
- Parametrización con CRUD base para:
  - temas
  - branding
  - parámetros
  - módulos
  - integraciones
  - menús
  - roles
  - usuarios
- Seguridad administrativa con:
  - sesiones activas
  - accesos recientes
  - cierre de otras sesiones
  - cambio de claves de usuarios
- Claves nuevas y actualizadas almacenadas con hash bcrypt
- Compatibilidad temporal para claves legadas sin hash y migración sugerida por SQL
- Diseño administrativo responsive para login, parametrización y seguridad
- PostgreSQL local por Docker en `docker/docker-compose.postgres.local.yml`

## SQL pendientes del flujo actual

1. `database/sql/001_sql_parametrizacion_base_v2.sql`
2. `database/sql/003_sql_login_token_base.sql`
3. `database/sql/004_sql_seguridad_hash_claves.sql`
4. `database/sql/010_sql_tienda_admin_catalogo.sql`

## Apertura local

```bash
php -S localhost:8080 -t backend
```

Abrir:

```text
http://localhost:8080/index.php
```


## Rutas actuales

- `http://localhost:8080/` tienda pública
- `http://localhost:8080/catalogo/` catálogo
- `http://localhost:8080/producto/?slug=serum-glow-rose` detalle de producto
- `http://localhost:8080/carrito/` carrito
- `http://localhost:8080/admin/` acceso administrativo
- `http://localhost:8080/admin/tienda/` panel comercial de productos
