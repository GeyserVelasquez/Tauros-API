FROM webdevops/php-nginx:8.4-alpine

# 1. Instalar dependencias del sistema y drivers de PostgreSQL
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

# 4. Copiar el código del proyecto
COPY . .
RUN composer run-script post-autoload-dump

# 5. Crear el script de automatización para Artisan
# Este script se ejecuta en tiempo de arranque, garantizando acceso a la BD y variables.
RUN echo '#!/bin/sh' > /opt/docker/provision/entrypoint.d/30-laravel-setup.sh && \
    echo 'echo "==> Optimizando configuraciones de Laravel..."' >> /opt/docker/provision/entrypoint.d/30-laravel-setup.sh && \
    echo 'php artisan config:cache' >> /opt/docker/provision/entrypoint.d/30-laravel-setup.sh && \
    echo 'php artisan route:cache' >> /opt/docker/provision/entrypoint.d/30-laravel-setup.sh && \
    echo 'php artisan view:cache' >> /opt/docker/provision/entrypoint.d/30-laravel-setup.sh && \
    echo 'echo "==> Ejecutando migraciones y seeders de la base de datos..."' >> /opt/docker/provision/entrypoint.d/30-laravel-setup.sh && \
    echo 'php artisan migrate --seed --force' >> /opt/docker/provision/entrypoint.d/30-laravel-setup.sh && \
    chmod +x /opt/docker/provision/entrypoint.d/30-laravel-setup.sh

# 6. Asegurar permisos para el usuario nativo 'application'
RUN chown -R application:application /app /opt/docker/provision/entrypoint.d/30-laravel-setup.sh

EXPOSE 80
