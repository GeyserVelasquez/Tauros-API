# --- Etapa 1: Construcción ---
FROM php:8.5-fpm-alpine AS builder

RUN apk add --no-cache libpng-dev libzip-dev zip unzip git
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .
RUN composer run-script post-autoload-dump

# Etapa 2: Imagen Final
FROM php:8.5-fpm-alpine

# Instalamos Nginx
RUN apk add --no-cache nginx libpng libzip postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql bcmath intl zip opcache

WORKDIR /var/www/html
COPY --from=builder /app .

# Copiamos nuestra configuración
COPY nginx.conf /etc/nginx/http.d/default.conf

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Puerto que Render monitorea
EXPOSE 8000

# El comando debe levantar ambos servicios en primer plano
# Creamos un archivo de PID para nginx para evitar problemas
RUN mkdir -p /run/nginx

CMD php-fpm -D && nginx -g 'daemon off;'
