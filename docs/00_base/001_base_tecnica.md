# Base técnica del proyecto

## Nombre del proyecto
Tienda Virtual

## Objetivo general
Construir una aplicación web de tienda virtual bajo arquitectura MVC, reutilizable para distintos clientes mediante parametrización visual, funcional y de integración, conservando la misma lógica de negocio.

## Alcance inicial
La primera etapa corresponde a la definición de la base técnica, la estructura documental, el orden del repositorio y el modelo de parametrización general. En esta etapa no se crean tablas definitivas ni se implementan módulos funcionales.

## Arquitectura
La aplicación se manejará bajo el patrón Modelo Vista Controlador (MVC), separando responsabilidades de acceso a datos, lógica de negocio, controladores, vistas, configuración, rutas, documentación, scripts SQL y trazabilidad técnica.

## Stack base aprobado
### Backend
- PHP
- Arquitectura MVC
- Manejo de logs de error
- Manejo de configuración centralizada
- Rutas separadas
- Repositorios y servicios separados por responsabilidad

### Frontend
- TypeScript / Angular
- Consumo de endpoints del backend
- Parametrización visual mediante temas
- Componentes reutilizables

### Base de datos
- PostgreSQL

### Contenedores
- Docker Desktop
- WSL2
- Ubuntu como entorno principal de trabajo

### Control de versiones
- Git

## Estructura inicial del repositorio
```text
tienda_virtual/
├── backend/
│   ├── app/
│   │   ├── Controllers/
│   │   ├── Models/
│   │   ├── Views/
│   │   ├── Services/
│   │   └── Repositories/
│   ├── config/
│   ├── routes/
│   └── storage/
│       ├── logs/
│       └── cache/
├── frontend/
├── database/
│   ├── sql/
│   ├── migrations/
│   └── seeds/
├── docker/
├── docs/
│   ├── 00_base/
│   ├── 01_funcional/
│   ├── 02_bd/
│   ├── 03_api/
│   ├── 04_front/
│   └── 05_cambios/
├── .gitignore
└── README.md
