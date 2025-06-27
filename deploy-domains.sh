#!/bin/bash

# Open Data Portal Gorontalo - Multi-Domain Deployment Script
# Usage: ./deploy-domains.sh [environment]
# Environment: development, staging, production

set -e

ENVIRONMENT=${1:-production}
PROJECT_NAME="opendata-portal-gorontalo"

echo "🚀 Deploying Open Data Portal Gorontalo - Multi-Domain Setup"
echo "============================================================"
echo "Environment: $ENVIRONMENT"
echo ""
echo "📍 Domain Configuration:"
echo "  Frontend: opendata.gorontalokab.go.id (Port 3000)"
echo "  Backend:  walidata.gorontalokab.go.id (Port 8000)"
echo "  API:      api-opendata.gorontalokab.go.id (Port 8080)"
echo ""

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

# SSL Certificate Setup
echo "🔐 Setting up SSL certificates..."

# Check if SSL certificates exist
DOMAINS=("opendata.gorontalokab.go.id" "walidata.gorontalokab.go.id" "api-opendata.gorontalokab.go.id")

for domain in "${DOMAINS[@]}"; do
    if [ ! -f "ssl/${domain}.crt" ] || [ ! -f "ssl/${domain}.key" ]; then
        echo "⚠️  SSL certificate for ${domain} not found!"
        echo "Creating self-signed certificate for development..."
        
        openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
            -keyout "ssl/${domain}.key" \
            -out "ssl/${domain}.crt" \
            -subj "/C=ID/ST=Gorontalo/L=Limboto/O=Kabupaten Gorontalo/CN=${domain}"
        
        echo "✅ Self-signed certificate created for ${domain}"
    else
        echo "✅ SSL certificate found for ${domain}"
    fi
done

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
docker-compose -f "$COMPOSE_FILE" exec backend php artisan migrate --force

# Seed database (only for development)
if [ "$ENVIRONMENT" != "production" ]; then
    echo "🌱 Seeding database..."
    docker-compose -f "$COMPOSE_FILE" exec backend php artisan db:seed --class=DevelopmentDataSeeder
else
    echo "🌱 Seeding production data..."
    docker-compose -f "$COMPOSE_FILE" exec backend php artisan db:seed --class=ProductionDataSeeder
fi

# Clear and cache configurations
echo "🧹 Clearing and caching configurations..."
docker-compose -f "$COMPOSE_FILE" exec backend php artisan config:cache
docker-compose -f "$COMPOSE_FILE" exec backend php artisan route:cache
docker-compose -f "$COMPOSE_FILE" exec backend php artisan view:cache

# Set proper permissions
echo "🔐 Setting permissions..."
docker-compose -f "$COMPOSE_FILE" exec backend chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker-compose -f "$COMPOSE_FILE" exec backend chmod -R 775 /app/storage /app/bootstrap/cache

# Test all endpoints
echo "🧪 Testing all endpoints..."
sleep 10

# Test Frontend
echo "Testing Frontend (opendata.gorontalokab.go.id)..."
FRONTEND_CHECK=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:3000)
if [ "$FRONTEND_CHECK" = "200" ]; then
    echo "✅ Frontend is running"
else
    echo "❌ Frontend check failed (HTTP $FRONTEND_CHECK)"
fi

# Test Backend Admin
echo "Testing Backend Admin (walidata.gorontalokab.go.id)..."
BACKEND_CHECK=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/admin)
if [ "$BACKEND_CHECK" = "200" ]; then
    echo "✅ Backend admin is running"
else
    echo "❌ Backend admin check failed (HTTP $BACKEND_CHECK)"
fi

# Test API
echo "Testing API (api-opendata.gorontalokab.go.id)..."
API_HEALTH_CHECK=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/health)
if [ "$API_HEALTH_CHECK" = "200" ]; then
    echo "✅ API health check passed"
else
    echo "❌ API health check failed (HTTP $API_HEALTH_CHECK)"
fi

API_STATS_CHECK=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/stats)
if [ "$API_STATS_CHECK" = "200" ]; then
    echo "✅ API stats endpoint working"
else
    echo "❌ API stats endpoint failed (HTTP $API_STATS_CHECK)"
fi

echo ""
echo "🎉 Multi-Domain Deployment completed successfully!"
echo "=================================================="
echo ""
echo "🌐 Access URLs:"
echo "  📱 Frontend:      http://localhost:3000"
echo "  🔧 Backend Admin: http://localhost:8000/admin"
echo "  🔗 API:           http://localhost:8080"
echo ""
echo "🌍 Production URLs (after DNS setup):"
echo "  📱 Frontend:      https://opendata.gorontalokab.go.id"
echo "  🔧 Backend Admin: https://walidata.gorontalokab.go.id/admin"
echo "  🔗 API:           https://api-opendata.gorontalokab.go.id"
echo ""

if [ "$ENVIRONMENT" != "production" ]; then
    echo "🔑 Default Admin Credentials:"
    echo "Email: admin@gorontalokab.go.id"
    echo "Password: admin123"
    echo ""
fi

echo "📋 Next Steps for Production:"
echo "1. 🌐 Configure DNS records:"
echo "   - opendata.gorontalokab.go.id → Your VPS IP"
echo "   - walidata.gorontalokab.go.id → Your VPS IP"
echo "   - api-opendata.gorontalokab.go.id → Your VPS IP"
echo ""
echo "2. 🔐 Install proper SSL certificates:"
echo "   sudo certbot certonly --standalone -d opendata.gorontalokab.go.id"
echo "   sudo certbot certonly --standalone -d walidata.gorontalokab.go.id"
echo "   sudo certbot certonly --standalone -d api-opendata.gorontalokab.go.id"
echo ""
echo "3. 🔥 Configure firewall:"
echo "   sudo ufw allow 80"
echo "   sudo ufw allow 443"
echo "   sudo ufw allow 3000"
echo "   sudo ufw allow 8000"
echo "   sudo ufw allow 8080"
echo ""
echo "4. 📊 Set up monitoring and backups"
echo "5. 🔒 Update default passwords"
echo ""
echo "📖 Commands:"
echo "  View logs: docker-compose -f $COMPOSE_FILE logs -f"
echo "  Stop services: docker-compose -f $COMPOSE_FILE down"
echo "  Restart services: docker-compose -f $COMPOSE_FILE restart"