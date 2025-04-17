FROM php:8.2-fpm

# Install dependencies for PHP and other tools
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    default-mysql-client \
    iputils-ping \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Install Node.js and npm using NodeSource
RUN apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@9

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

COPY wait-for-mysql.sh /usr/local/bin/wait-for-mysql.sh
RUN chmod +x /usr/local/bin/wait-for-mysql.sh

ENTRYPOINT ["/usr/local/bin/wait-for-mysql.sh"]
CMD ["php-fpm"]