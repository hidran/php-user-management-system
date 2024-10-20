# Use the official PHP image as the base image
FROM php:8.3-apache

# Install the mysqli extension and any dependencies
RUN docker-php-ext-install mysqli

# Enable mod_rewrite (if needed for your project)
RUN a2enmod rewrite

# Copy your app files into the container
COPY ./app /var/www/html

# Set proper permissions for the files
RUN chown -R www-data:www-data /var/www/html

# Expose the application port
EXPOSE 80
