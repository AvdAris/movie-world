FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

COPY apache/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
