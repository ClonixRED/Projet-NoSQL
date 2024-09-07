FROM php:8.2-apache

# Installer l'extension PostgreSQL pour PHP
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copier le code source de l'application dans le conteneur
COPY . /var/www/html/
