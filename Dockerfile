# Usa una imagen de PHP con FPM
FROM php:8.3-fpm-alpine

# Instala extensiones de PHP necesarias
RUN docker-php-ext-install pdo_mysql opcache