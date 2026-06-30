# Etapa 1: Construcción
FROM php:8.5-fpm-alpine AS builder

RUN apk add --no-cache libpng-dev libzip-dev zip unzip
RUN docker-php-ext-install pdo pdo_pgsql bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Etapa 2: Imagen Final
FROM php:8.5-fpm-alpine
RUN apk add --no-cache libpng libzip
RUN docker-php-ext-install pdo pdo_pgsql bcmath

WORKDIR /var/www/html
COPY --from=builder /var/www/html .

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000
CMD ["php-fpm"]
