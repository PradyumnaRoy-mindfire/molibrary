# Dockerfile
FROM php:8.3.19-apache

# Install system dependencies


# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html/molibrary

# Install Composer
COPY --from=composer:2.8.6 /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html/molibrary

# Change ownership
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]