# Usar una imagen oficial de PHP con Apache
FROM php:8.2-apache

# 1. Instalar las dependencias de PostgreSQL y la extensión PDO_PGSQL
# RUN apt-get update && apt-get install -y \
#     libpq-dev \
#     && docker-php-ext-install pdo pdo_pgsql \
#     && rm -rf /var/lib/apt/lists/* <-- Este comando de limpieza a veces causa problemas, puedes omitirlo para el diagnóstico.
    
# **VERSIÓN RECOMENDADA MÁS SEGURA PARA DEPLIEGUE**
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Copiar los archivos de la API al directorio web de Apache
COPY api/ /var/www/html/

# 3. Habilitar mod_rewrite
RUN a2enmod rewrite

# 4. Establecer permisos
RUN chown -R www-data:www-data /var/www/html