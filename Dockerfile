FROM php:8.2-apache

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Install MySQLi extension for database connectivity
RUN docker-php-ext-install mysqli

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Create uploads directory and set permissions for file uploads
RUN mkdir -p /var/www/html/uploads && chmod 777 /var/www/html/uploads

# Expose port
EXPOSE 80

CMD ["apache2-foreground"]