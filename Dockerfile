# --- Etapa 1: Construcción (Builder) ---
FROM php:8.5-fpm-alpine AS builder

# Instalar dependencias del sistema necesarias
RUN apk add --no-cache libpng-dev libzip-dev zip unzip git

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar todo el código fuente primero para que 'artisan' esté disponible
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# --- Etapa 2: Imagen Final (Runner) ---
FROM dunglas/frankenphp:latest-alpine

# Instalar extensiones necesarias en la imagen final
RUN install-php-extensions pdo_pgsql bcmath intl zip opcache

WORKDIR /app

# Copiar las dependencias y el código desde la etapa de construcción
COPY --from=builder /var/www/html/vendor /app/vendor
COPY --from=builder /var/www/html /app

# Ajustar permisos para que el servidor pueda escribir en los directorios de Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Configurar variables de entorno para producción
ENV APP_ENV=production
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# Exponer el puerto que usará Render
EXPOSE 8000

# Comando para arrancar el servidor con Octane y FrankenPHP
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
