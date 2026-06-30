# Usamos la imagen oficial de FrankenPHP
FROM dunglas/frankenphp:latest-alpine

# Instalamos las extensiones necesarias
RUN install-php-extensions \
    pdo_pgsql \
    bcmath \
    intl \
    zip \
    opcache

# Configuramos el entorno de producción
ENV APP_ENV=production
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

WORKDIR /app

# Copiamos el código y las dependencias (ya construidas)
COPY . .

# Ajustamos permisos (importante para Laravel)
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Comando para arrancar el servidor
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
