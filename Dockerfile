# Use the official PHP image as the base image
FROM php:8.4-apache

# Install the mysqli extension and any dependencies

RUN apt-get update && apt-get install -y libjpeg-dev libpng-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd && docker-php-ext-install mysqli

# Enable mod_rewrite (if needed for your project)
RUN a2enmod rewrite

# Copy your app files into the container
COPY ./app /var/www/html

# Set proper permissions for the files
RUN chown -R www-data:www-data /var/www/html

# Expose the application port
EXPOSE 80
