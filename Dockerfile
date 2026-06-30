# Etapa 1: Construcción
FROM php:8.5-fpm-alpine AS builder

# Instalar dependencias del sistema necesarias para Composer
RUN apk add --no-cache libpng-dev libzip-dev zip unzip git

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos de dependencias e instalar
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copiar el resto del código
COPY . .

# Etapa 2: Imagen Final
FROM dunglas/frankenphp:latest-alpine

# Instalar extensiones necesarias en la imagen final
RUN install-php-extensions pdo_pgsql bcmath intl zip opcache

WORKDIR /app

# Copiar la carpeta vendor desde la etapa anterior ---
COPY --from=builder /var/www/html/vendor /app/vendor
COPY --from=builder /var/www/html /app

# Ajustar permisos
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Comando de inicio
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
