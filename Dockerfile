# --- Etapa 1: Construcción ---
FROM php:8.5-fpm-alpine AS builder

# Instalar dependencias de desarrollo para compilar
RUN apk add --no-cache libpng-dev libzip-dev postgresql-dev icu-dev zip unzip git
RUN docker-php-ext-install pdo pdo_pgsql bcmath intl zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-scripts

# --- Etapa 2: Imagen Final ---
FROM php:8.5-fpm-alpine

# 1. Instalar Nginx y las librerías necesarias
RUN apk add --no-cache nginx libpng libzip libpq icu-libs

# 2. Instalar las dependencias de DESARROLLO solo para compilar las extensiones
# Usamos un bloque temporal para no inflar la imagen final
RUN apk add --no-cache --virtual .build-deps \
    libpng-dev \
    libzip-dev \
    postgresql-dev \
    icu-dev \
    && docker-php-ext-install pdo pdo_pgsql bcmath intl zip opcache \
    && apk del .build-deps

WORKDIR /var/www/html
COPY --from=builder /app .

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copiar configuración de Nginx (asegúrate de tenerla en la raíz)
COPY nginx.conf /etc/nginx/http.d/default.conf

EXPOSE 8000

# Script de inicio: levantamos PHP-FPM y luego Nginx
CMD php-fpm -D && nginx -g 'daemon off;'
