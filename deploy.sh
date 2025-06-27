#!/bin/bash

# Open Data Portal Gorontalo - Deployment Script
# Usage: ./deploy.sh [environment]
# Environment: development, staging, production

set -e

ENVIRONMENT=${1:-production}
PROJECT_NAME="opendata-portal-gorontalo"

echo "🚀 Deploying Open Data Portal Gorontalo - Environment: $ENVIRONMENT"
echo "=================================================================="

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create necessary directories
echo "📁 Creating directories..."
mkdir -p logs/nginx
mkdir -p backups
mkdir -p ssl

# Set environment file
if [ "$ENVIRONMENT" = "production" ]; then
    ENV_FILE=".env.production"
    COMPOSE_FILE="docker-compose.prod.yml"
else
    ENV_FILE=".env"
    COMPOSE_FILE="docker-compose.yml"
fi

# Check if environment file exists
if [ ! -f "$ENV_FILE" ]; then
    echo "❌ Environment file $ENV_FILE not found!"
    echo "Please copy and configure the environment file:"
    echo "cp .env.production .env"
    exit 1
fi

# Copy environment file
cp "$ENV_FILE" backend/.env

# Generate application key if not exists
if ! grep -q "APP_KEY=base64:" backend/.env; then
    echo "🔑 Generating application key..."
    docker run --rm -v $(pwd)/backend:/app -w /app php:8.3-cli php artisan key:generate
fi

# Build and start containers
echo "🐳 Building and starting containers..."
docker-compose -f "$COMPOSE_FILE" down
docker-compose -f "$COMPOSE_FILE" build --no-cache
docker-compose -f "$COMPOSE_FILE" up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 30

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose -f "$COMPOSE_FILE" exec app php artisan migrate --force

# Seed database (only for development)
if [ "$ENVIRONMENT" != "production" ]; then
    echo "🌱 Seeding database..."
    docker-compose -f "$COMPOSE_FILE" exec app php artisan db:seed --class=DevelopmentDataSeeder
else
    echo "🌱 Seeding production data..."
    docker-compose -f "$COMPOSE_FILE" exec app php artisan db:seed --class=ProductionDataSeeder
fi

# Clear and cache configurations
echo "🧹 Clearing and caching configurations..."
docker-compose -f "$COMPOSE_FILE" exec app php artisan config:cache
docker-compose -f "$COMPOSE_FILE" exec app php artisan route:cache
docker-compose -f "$COMPOSE_FILE" exec app php artisan view:cache

# Set proper permissions
echo "🔐 Setting permissions..."
docker-compose -f "$COMPOSE_FILE" exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker-compose -f "$COMPOSE_FILE" exec app chmod -R 775 /app/storage /app/bootstrap/cache

# Test API endpoints
echo "🧪 Testing API endpoints..."
sleep 10

# Health check
HEALTH_CHECK=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/api/v1/health)
if [ "$HEALTH_CHECK" = "200" ]; then
    echo "✅ Health check passed"
else
    echo "❌ Health check failed (HTTP $HEALTH_CHECK)"
fi

# Stats endpoint
STATS_CHECK=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/api/v1/stats)
if [ "$STATS_CHECK" = "200" ]; then
    echo "✅ Stats endpoint working"
else
    echo "❌ Stats endpoint failed (HTTP $STATS_CHECK)"
fi

echo ""
echo "🎉 Deployment completed successfully!"
echo "=================================="
echo "📊 Admin Dashboard: http://localhost:8000/admin"
echo "🔗 API Base URL: http://localhost:8000/api/v1"
echo "📚 API Health: http://localhost:8000/api/v1/health"
echo ""

if [ "$ENVIRONMENT" != "production" ]; then
    echo "🔑 Default Admin Credentials:"
    echo "Email: admin@gorontalokab.go.id"
    echo "Password: admin123"
    echo ""
fi

echo "📋 Next Steps:"
echo "1. Configure SSL certificates (production)"
echo "2. Set up domain DNS (production)"
echo "3. Configure backup strategy"
echo "4. Set up monitoring"
echo "5. Update default passwords"
echo ""
echo "📖 View logs: docker-compose -f $COMPOSE_FILE logs -f"
echo "🛑 Stop services: docker-compose -f $COMPOSE_FILE down"