# PHP 8.3 Alpine
FROM php:8.3-fpm-alpine

# Sistem bağımlılıkları
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    postgresql-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        intl \
        zip \
        exif \
        gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Çalışma dizini
WORKDIR /var/www/html

# Laravel yoksa kur
RUN if [ ! -f artisan ]; then composer create-project laravel/laravel ./; fi

# Laravel serve
CMD php artisan serve --host=0.0.0.0 --port=8000
