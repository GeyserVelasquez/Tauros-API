# Imagen optimizada para producción que ya incluye Nginx + PHP 8.4 + Extensiones básicas
FROM webdevops/php-nginx:8.4-alpine

# 1. Instalar SOLO el driver de PostgreSQL si no viene por defecto
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql

# 2. Configurar las variables de entorno nativas de la imagen
ENV WEB_DOCUMENT_ROOT=/app/public
ENV APP_ENV=production

WORKDIR /app

# 3. Instalar Composer y dependencias
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 4. Copiar el resto del proyecto
COPY . .
RUN composer run-script post-autoload-dump

# 5. Permisos limpios para los usuarios del servidor
RUN chown -R application:application /app/storage /app/bootstrap/cache

# El punto de entrada nativo de la imagen gestiona Nginx y FPM correctamente como PID 1
EXPOSE 80
