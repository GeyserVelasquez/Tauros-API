FROM webdevops/php-nginx:8.4-alpine

# 1. Instalar dependencias del sistema y drivers
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql

# 2. Configurar el entorno nativo de la imagen
ENV WEB_DOCUMENT_ROOT=/app/public
ENV APP_ENV=production
ENV APP_DEBUG=false

WORKDIR /app

# 3. Preparar dependencias de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 4. Copiar código y generar autoloader definitivo
COPY . .
RUN composer run-script post-autoload-dump

# 5. Asegurar permisos para el usuario nativo de la imagen ('application')
RUN chown -R application:application /app/storage /app/bootstrap/cache

# Dejamos que la imagen use su puerto 80 por defecto, Render lo mapeará solo.
EXPOSE 80
