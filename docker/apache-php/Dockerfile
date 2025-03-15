FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    zlib1g-dev \
    libzip-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl pdo_mysql zip
RUN docker-php-ext-enable pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache modules
RUN a2enmod rewrite

# Copy Apache configuration
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache site
RUN a2ensite 000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www

# Expose port 80
EXPOSE 80
