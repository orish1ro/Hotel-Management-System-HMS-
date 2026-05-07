#!/bin/bash

# Run database migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the Apache web server
apache2-foreground