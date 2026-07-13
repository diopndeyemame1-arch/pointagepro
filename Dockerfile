FROM php:8.2-apache

# 1. On modifie la racine d'Apache pour pointer sur le dossier public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 2. On installe les dépendances système Linux PUIS les extensions (PostgreSQL, GD)
RUN apt-get update && apt-get install -y libpq-dev unzip git libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

# 3. On installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. On active le module rewrite d'Apache
RUN a2enmod rewrite

# 5. On copie le projet
COPY . /var/www/html/

# 6. On installe les dépendances PHP (vendor/)
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader --no-interaction

# 7. On applique les permissions
RUN chown -R www-data:www-data /var/www/html