FROM php:8.3-apache
COPY . /var/www/html
WORKDIR /var/www/html
RUN apt-get update \
    && apt-get install -y libzip-dev zip \
    && docker-php-ext-install pdo_mysql \
    && rm -rf /var/lib/apt/lists/*
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev
RUN a2enmod rewrite \
    && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
EXPOSE 80
CMD ["apache2-foreground"]
