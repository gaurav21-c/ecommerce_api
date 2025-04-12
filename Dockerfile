FROM php:8.3.10-fpm

# Install system dependencies
RUN apt-get update -y && apt-get install -y openssl zip unzip git

# Install Composer (PHP package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions for MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Check if mbstring extension is installed (for debugging purposes)
RUN php -m | grep mbstring

# Set working directory
WORKDIR /app

# Copy application files to container
COPY . /app

# Install PHP dependencies
RUN composer install

# Command to run the Laravel development server
CMD php artisan serve --host=0.0.0.0 --port=8000

# Expose port 8000
EXPOSE 8000
