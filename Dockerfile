# --- Etapa 1: Construcción (Builder) ---
FROM php:8.5-fpm-alpine AS builder

# Instalar dependencias del sistema necesarias
RUN apk add --no-cache libpng-dev libzip-dev zip unzip git

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos de dependencias
COPY composer.json composer.lock ./

# Instalar dependencias base, luego requerir Octane específicamente aquí
RUN composer install --no-dev --optimize-autoloader --no-interaction && \
    composer require laravel/octane --no-interaction

# Copiar el resto del código fuente
COPY . .

# --- Etapa 2: Imagen Final (Runner) ---
# Usamos la imagen con PHP 8.4+ para cumplir con tus requisitos de plataforma
FROM dunglas/frankenphp:php8.4-alpine

# Instalar extensiones necesarias en la imagen final
RUN install-php-extensions pdo_pgsql bcmath intl zip opcache

WORKDIR /app

# Copiar dependencias (incluyendo Octane) y código desde la etapa de construcción
COPY --from=builder /var/www/html/vendor /app/vendor
COPY --from=builder /var/www/html /app

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Limpieza preventiva de cachés de Laravel
RUN rm -f /app/bootstrap/cache/packages.php /app/bootstrap/cache/services.php

# Configurar variables de entorno para producción
ENV APP_ENV=production
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# Exponer el puerto
EXPOSE 8000

# Comando para arrancar el servidor
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
