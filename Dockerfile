FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Configure PHP
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN echo "memory_limit=2G" > /usr/local/etc/php/conf.d/memory-limit.ini

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . .

# Configure Git
RUN git config --global --add safe.directory /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 storage bootstrap/cache

# Install Composer dependencies
ENV COMPOSER_PROCESS_TIMEOUT=600
RUN composer config --global process-timeout 600 && \
    composer install --no-dev --no-scripts --prefer-dist --no-progress && \
    composer dump-autoload --optimize

EXPOSE 9000

CMD ["php-fpm"]
