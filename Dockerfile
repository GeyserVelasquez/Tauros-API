# --- Etapa 1: Construcción (Builder) ---
FROM php:8.5-fpm-alpine AS builder

RUN apk add --no-cache libpng-dev libzip-dev zip unzip git
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Usamos una ruta absoluta fija
WORKDIR /app

# 1. Copiamos los archivos de dependencias
COPY composer.json composer.lock ./

# 2. Instalamos dependencias SIN ejecutar scripts todavía (evita el error de artisan)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 3. Copiamos el resto del código fuente ahora que el entorno está listo
COPY . .

# 4. Ahora sí ejecutamos los scripts de Laravel, porque el archivo 'artisan' ya está en /app
RUN composer run-script post-autoload-dump

# 5. Instalamos Octane después de que el entorno está completo
RUN composer require laravel/octane --no-interaction

# --- Etapa 2: Imagen Final (Runner) ---
FROM dunglas/frankenphp:php8.4-alpine

RUN install-php-extensions pdo_pgsql bcmath intl zip opcache

WORKDIR /app

# Copiamos todo desde el builder (vendor y código fuente)
COPY --from=builder /app /app

# Ajustar permisos
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Limpieza
RUN rm -f /app/bootstrap/cache/packages.php /app/bootstrap/cache/services.php

ENV APP_ENV=production
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
