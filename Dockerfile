# --- Etapa 1: Construcción (Builder) ---
FROM php:8.5-fpm-alpine AS builder

# 1. Instalar dependencias del sistema Y las extensiones de PHP aquí también
RUN apk add --no-cache libpng-dev libzip-dev zip unzip git
# Esta utilidad es vital para instalar extensiones fácilmente en Docker
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql bcmath intl zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar archivos e instalar dependencias
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .
RUN composer run-script post-autoload-dump

# Ahora que bcmath ya está instalado, esto funcionará
RUN composer require laravel/octane --no-interaction

# --- Etapa 2: Imagen Final (Runner) ---
FROM dunglas/frankenphp:php8.4-alpine

# Instalar las mismas extensiones
RUN install-php-extensions pdo_pgsql bcmath intl zip opcache pcntl
WORKDIR /app

# Copiar todo desde el builder
COPY --from=builder /app /app

# Ajustar permisos
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN rm -f /app/bootstrap/cache/packages.php /app/bootstrap/cache/services.php

ENV APP_ENV=production
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
