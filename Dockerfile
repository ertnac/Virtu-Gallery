FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libsqlite3-dev \
    sqlite3 \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Configure & install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_sqlite zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache DocumentRoot to /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides in Apache
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set recommended PHP settings
RUN { \
    echo 'upload_max_filesize = 64M'; \
    echo 'post_max_size = 64M'; \
    echo 'memory_limit = 256M'; \
    echo 'max_execution_time = 120'; \
    echo 'display_errors = Off'; \
    echo 'log_errors = On'; \
} > /usr/local/etc/php/conf.d/virtugallery.ini

# Copy application files
WORKDIR /var/www/html
COPY . /var/www/html/

# Create uploads directory and set permissions
RUN mkdir -p /var/www/html/uploads/artworks /var/www/html/uploads/avatars \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/uploads

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]
