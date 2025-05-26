# 🚀 Proyecto

Este proyecto abarca el uso de Swagger para documentar, Laravel Telescope para debuggear la aplicación, Sanctum para generar tokens y Spatie para la gestión de roles y permisos.

## 📘 Swagger

- 🔗 Repositorio de instalación: https://github.com/DarkaOnLine/L5-Swagger

### 🧩 Instalar Swagger - darkaonline l5-swagger

- 👉 Ejecutar cada uno de los comandos:
```
composer require "darkaonline/l5-swagger"
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```

- 🛠️ En el archivo .env agrega las siguientes variables:

```
# SWAGGER
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000
```

- 🌍 Ver el proyecto: http://localhost:8000/api/documentation (puedes ver la ruta en ```config/l5-swagger.php```)

---

## 📘 Telescope

- 🔗 Documentación de Laravel: https://laravel.com/docs/12.x/telescope

### 🧩 Instalar Telescope

- 👉 Ejecutar cada uno de los comandos:
```
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

- ❌ Eliminar (o comentar) esta línea: App\Providers\TelescopeServiceProvider::class,

- Agregar en ```app/Providers/AppServiceProvider.php```:
```
public function register(): void
{
    if ($this->app->environment('local') && class_exists(TelescopeServiceProvider::class)) {
        $this->app->register(TelescopeServiceProvider::class);
        $this->app->register(LocalTelescopeServiceProvider::class);
    }
}
```

- ❗ No olvidar, en ```composer.json```: 
```
"extra": {
    "laravel": {
        "dont-discover": [
            "laravel/telescope"
        ]
    }
},
```

- 🌍 Ver el proyecto: http://localhost:8000/telescope

---

## 📘 Sanctum

- 🔗 Documentación de Laravel: https://laravel.com/docs/12.x/sanctum

### 🧩 Instalar Sanctum

- 👉 Ejecutar = Laravel 12:
```
php artisan install:api
php artisan migrate
```

- 👉 Ejecutar < Laravel 12:
```
composer require laravel/sanctum
php artisan vendor:publish --tag=sanctum-config
php artisan migrate
```

- ✅ Ágregar en el modelo User: 
```
use Laravel\Sanctum\HasApiTokens;
HasApiTokens
```

### Reiniciar

- php artisan optimize:clear