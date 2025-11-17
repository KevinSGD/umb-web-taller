FROM php:8.2-apache

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Crear carpeta api dentro de /var/www/html
RUN mkdir -p /var/www/html/api

# Copiar todos los archivos de la carpeta api/ del repo al contenedor
COPY api/ /var/www/html/api/

# Crear un index.php simple para la raíz
RUN echo "<?php echo 'API funcionando'; ?>" > /var/www/html/index.php
