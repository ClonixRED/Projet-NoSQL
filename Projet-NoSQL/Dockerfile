FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y libpq-dev git unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Vérifier et installer l'extension MongoDB uniquement si elle n'est pas déjà installée
RUN pecl list | grep -q mongodb || pecl install mongodb && docker-php-ext-enable mongodb

# Copier Composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Activer mod_rewrite pour Apache
RUN a2enmod rewrite

# Copier le code source de l'application dans le conteneur
COPY . /var/www/html/

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances via Composer
RUN composer install

# S'assurer que les permissions sont correctes pour les fichiers
RUN chown -R www-data:www-data /var/www/html
