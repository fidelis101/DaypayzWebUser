# Step 1: Use an official PHP image with Apache
FROM php:8.1-apache

# Step 2: Enable Apache mod_rewrite (optional, often used for PHP apps)
RUN a2enmod rewrite

# Step 3: Install PHP extensions if needed (e.g., for MySQL or other services)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Step 4: Copy the PHP app code to the Apache web directory
COPY ./public /var/www/html/

# Step 5: Set file permissions (optional, but often needed for writeable directories)
RUN chown -R www-data:www-data /var/www/html

# Step 6: Expose port 80 to be accessed externally
EXPOSE 80

# Step 7: Command to run Apache in the foreground
CMD ["apache2-foreground"]
