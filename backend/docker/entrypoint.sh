#!/bin/sh

# Wait for database to be ready
echo "Waiting for database..."
while ! nc -z db 5432; do
  sleep 1
done
echo "Database is ready!"

# Wait for Redis to be ready
echo "Waiting for Redis..."
while ! nc -z redis 6379; do
  sleep 1
done
echo "Redis is ready!"

# Run Laravel setup commands
echo "Setting up Laravel..."

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link

# Set permissions
chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

# Determine container role
case ${CONTAINER_ROLE:-app} in
    "app")
        echo "Starting application server..."
        exec php artisan serve --host=0.0.0.0 --port=8000
        ;;
    "queue")
        echo "Starting queue worker..."
        exec php artisan queue:work --verbose --tries=3 --timeout=90
        ;;
    "scheduler")
        echo "Starting scheduler..."
        exec php artisan schedule:work
        ;;
    *)
        echo "Unknown container role: ${CONTAINER_ROLE}"
        exit 1
        ;;
esac