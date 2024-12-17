#!/bin/bash

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
/wait-for-it.sh db:3306 --timeout=60 --strict -- echo "MySQL is up and running!"

# Set file permissions for Laravel
echo "Setting file permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache to serve the Laravel public directory
echo "Configuring Apache for Laravel..."
echo '
<VirtualHost *:80>
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
' > /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite
echo "Enabling mod_rewrite..."
a2enmod rewrite

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run database seeding
echo "Running database seeding..."
php -d memory_limit=2G artisan db:seed --force

# Start Apache
echo "Starting Apache..."
apache2-foreground
