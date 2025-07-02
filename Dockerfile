FROM php:8.2-apache

# Install system dependencies
RUN apt-get update -y && \
    apt-get install -y \
        libpng-dev \
        unzip \
        libzip-dev \
        pkg-config \
        libicu-dev \
        redis-tools && \
    docker-php-ext-install pdo pdo_mysql gd zip intl && \
    pecl install redis && docker-php-ext-enable redis

# Optional: PCNTL (for CLI workers only, not needed for Apache)
RUN docker-php-ext-install pcntl && \
    docker-php-ext-configure pcntl --enable-pcntl

WORKDIR /var/www/html

# Install the composer.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install NodeJs
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Copy your Apache virtual host config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite macro

# Copy the Laravel app
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 1777 /tmp
RUN chmod -R 775 storage
RUN chmod -R 775 bootstrap/cache

# Start Apache
CMD ["apache2-foreground"]
