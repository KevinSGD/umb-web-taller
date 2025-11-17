# Dockerfile
FROM php:8.2-apache

# Instalar dependencias para pdo_pgsql
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar API
COPY api/ /var/www/html/

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Permisos y exposición
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
