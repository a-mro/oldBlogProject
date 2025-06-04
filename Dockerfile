FROM php:8.3-apache
COPY . /var/www/html
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y libzip-dev zip && docker-php-ext-install pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev
EXPOSE 80
CMD ["apache2-foreground"]
