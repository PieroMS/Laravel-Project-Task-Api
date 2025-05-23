# Proyecto

Este proyecto abarcar el uso de Swagger para documentar y Laravel Telescope

## Swagger

- Repositorio de instalación: https://github.com/DarkaOnLine/L5-Swagger

### Instalar Swagger - darkaonline l5-swagger

- Ejecutar cada uno de los comandos:
```
composer require "darkaonline/l5-swagger"
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```

- En el .env

```
# SWAGGER
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000
```

- Ver el proyecto: http://localhost:8000/api/documentation (puedes ver la ruta en ```config/l5-swagger.php```)