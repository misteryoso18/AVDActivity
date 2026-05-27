# Mas lightweight kaysa FPM – sapat na ang CLI server para sa Render
FROM php:8.4-cli

# Install system packages required for building PHP extensions used by PhpSpreadsheet
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql zip gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# I-cache muna ang dependencies bago kopyahin ang buong source
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Saka kopyahin ang buong code
COPY . .

# Patakbuhin ang artisan package:discover (ngayon nandito na ang artisan file)
RUN php artisan package:discover --ansi

# Tiyaking may tamang permission ang storage at cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Ilipat ang entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["start"]