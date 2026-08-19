FROM php:8.3-fpm

# =========================================================
# System dependencies
# =========================================================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && rm -rf /var/lib/apt/lists/*


# =========================================================
# Composer
# =========================================================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# =========================================================
# Working directory
# =========================================================
WORKDIR /var/www


# =========================================================
# Copy Laravel project
# =========================================================
COPY . .


# =========================================================
# Install Composer dependencies
# =========================================================
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader


# =========================================================
# Laravel permissions
# =========================================================
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache


# =========================================================
# OPcache - DEVELOPMENT
# =========================================================
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.enable_cli=0'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.revalidate_freq=0'; \
        echo 'opcache.validate_timestamps=1'; \
        echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache.ini


# =========================================================
# PHP-FPM pool
# =========================================================
RUN { \
        echo 'pm = dynamic'; \
        echo 'pm.max_children = 10'; \
        echo 'pm.start_servers = 2'; \
        echo 'pm.min_spare_servers = 2'; \
        echo 'pm.max_spare_servers = 5'; \
        echo 'pm.max_requests = 500'; \
    } >> /usr/local/etc/php-fpm.d/www.conf


# =========================================================
# PHP development configuration
# =========================================================
RUN { \
        echo 'display_errors=On'; \
        echo 'display_startup_errors=On'; \
        echo 'error_reporting=E_ALL'; \
        echo 'log_errors=On'; \
    } > /usr/local/etc/php/conf.d/development.ini


# =========================================================
# Expose PHP-FPM
# =========================================================
EXPOSE 9000


# =========================================================
# Start PHP-FPM
# =========================================================
CMD ["php-fpm"]