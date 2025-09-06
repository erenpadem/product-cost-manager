# PHP 8.3 FPM
FROM php:8.3-fpm

# Sistem bağımlılıkları ve PHP uzantıları
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libicu-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    && docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install pdo pdo_pgsql intl zip exif gd

# Composer yükle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Çalışma dizini
WORKDIR /var/www/html

# Eğer Laravel yoksa kur
RUN if [ ! -f artisan ]; then composer create-project laravel/laravel ./; fi

# Laravel serve
CMD php artisan serve --host=0.0.0.0 --port=8000
