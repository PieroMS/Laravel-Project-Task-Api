# Proyecto

## Descripción

Este proyecto implementa un sistema CRUD completo que incluye:

- Validaciones mediante **Form Request**.
- Consultas avanzadas.
- Migraciones de base de datos.
- Relaciones entre modelos.
- **Documentación Swagger** para una fácil integración y prueba de la API.

## Requisitos del Sistema

- **PHP** >= 8.0
- **Composer** (para la gestión de dependencias)
- **MySQL** o **MariaDB** (base de datos)
- **Laravel** 12

## Instalación Paso a Paso

```bash
git clone [URL del repositorio]
cd [nombre-del-proyecto]
composer install
```

- Copia el archivo .env.example y renombralo como .env

- Ejecuta los siguientes comandos:
```bash
php artisan key:generate
php artisan migrate
```
- Finalmente ejecuta el programa
```bash
php artisan serve
```

> **Nota:** Para abrir Swagger necesitas esta url: ```http://127.0.0.1:8000/api/documentation```