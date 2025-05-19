# Dockerfile
FROM php:8.3.19-apache

# Install system dependencies
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y tzdata \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    fontconfig \
    && ln -sf /usr/share/zoneinfo/Asia/Kolkata /etc/localtime \
    && dpkg-reconfigure --frontend noninteractive tzdata \
    && docker-php-ext-install pdo_mysql mbstring pcntl bcmath gd



# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html/molibrary

# Install Composer
COPY --from=composer:2.8.6 /usr/bin/composer /usr/bin/composer

# Copies the entire Laravel project from the local machine into the container.
COPY . /var/www/html/molibrary

# Copy Apache configuration
COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf


# Change ownership
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose the containers on port 80
EXPOSE 80

# tells Docker to start Apache when the container runs.
CMD ["apache2-foreground"]