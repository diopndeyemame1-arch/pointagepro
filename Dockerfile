FROM php:8.2-apache

# 1. On modifie la racine d'Apache pour pointer sur le dossier public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 2. On installe les dépendances système Linux PUIS l'extension PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# 3. On active le module rewrite d'Apache
RUN a2enmod rewrite

# 4. On copie le projet et on applique les permissions
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html