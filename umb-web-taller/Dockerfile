# Usar una imagen oficial de PHP con Apache
FROM php:8.2-apache

# 1. Instalar las dependencias de PostgreSQL y la extensión PDO_PGSQL
# Libpq-dev es la librería cliente de PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# 2. Copiar los archivos de la API al directorio web de Apache
COPY api/ /var/www/html/

# 3. Habilitar mod_rewrite para URLs amigables (aunque no es estrictamente necesario para index.php)
RUN a2enmod rewrite

# 4. Establecer permisos (buena práctica de seguridad)
RUN chown -R www-data:www-data /var/www/html

# El puerto 80 ya está expuesto por defecto en la imagen base.