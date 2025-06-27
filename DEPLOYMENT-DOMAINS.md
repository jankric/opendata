# 🌐 Multi-Domain Deployment Guide

Panduan deployment Portal Data Terbuka Kabupaten Gorontalo dengan konfigurasi multi-domain.

## 🏗️ Arsitektur Domain

### Domain Structure
```
📱 Frontend:  opendata.gorontalokab.go.id  (Port 3000)
🔧 Backend:   walidata.gorontalokab.go.id   (Port 8000)
🔗 API:       api-opendata.gorontalokab.go.id (Port 8080)
```

### Port Configuration
- **Frontend (React)**: Port 3000
- **Backend (Laravel)**: Port 8000
- **API Gateway**: Port 8080
- **Database**: Port 5432 (internal)
- **Redis**: Port 6379 (internal)

## 🚀 Quick Deployment

### 1. Clone & Setup
```bash
git clone https://github.com/your-repo/opendata-portal-gorontalo.git
cd opendata-portal-gorontalo
chmod +x deploy-domains.sh
```

### 2. Configure Environment
```bash
cp .env.production .env
nano .env  # Edit with your configurations
```

### 3. Deploy All Services
```bash
./deploy-domains.sh production
```

## 🔧 Manual Configuration

### DNS Records Setup
Configure A records for all domains pointing to your VPS IP:

```dns
opendata.gorontalokab.go.id      A    YOUR_VPS_IP
walidata.gorontalokab.go.id      A    YOUR_VPS_IP
api-opendata.gorontalokab.go.id  A    YOUR_VPS_IP
```

### SSL Certificates
```bash
# Install Certbot
sudo apt install certbot

# Generate certificates for each domain
sudo certbot certonly --standalone -d opendata.gorontalokab.go.id
sudo certbot certonly --standalone -d walidata.gorontalokab.go.id
sudo certbot certonly --standalone -d api-opendata.gorontalokab.go.id

# Copy certificates to project
sudo cp /etc/letsencrypt/live/opendata.gorontalokab.go.id/fullchain.pem ssl/opendata.gorontalokab.go.id.crt
sudo cp /etc/letsencrypt/live/opendata.gorontalokab.go.id/privkey.pem ssl/opendata.gorontalokab.go.id.key

sudo cp /etc/letsencrypt/live/walidata.gorontalokab.go.id/fullchain.pem ssl/walidata.gorontalokab.go.id.crt
sudo cp /etc/letsencrypt/live/walidata.gorontalokab.go.id/privkey.pem ssl/walidata.gorontalokab.go.id.key

sudo cp /etc/letsencrypt/live/api-opendata.gorontalokab.go.id/fullchain.pem ssl/api-opendata.gorontalokab.go.id.crt
sudo cp /etc/letsencrypt/live/api-opendata.gorontalokab.go.id/privkey.pem ssl/api-opendata.gorontalokab.go.id.key
```

### Firewall Configuration
```bash
# Allow HTTP/HTTPS
sudo ufw allow 80
sudo ufw allow 443

# Allow application ports
sudo ufw allow 3000  # Frontend
sudo ufw allow 8000  # Backend
sudo ufw allow 8080  # API

# Block database ports (internal only)
sudo ufw deny 5432   # PostgreSQL
sudo ufw deny 6379   # Redis
```

## 📊 Service Architecture

### Frontend Service (opendata.gorontalokab.go.id)
- **Technology**: React 18 + TypeScript
- **Port**: 3000
- **Purpose**: Public portal interface
- **Features**: Dataset browsing, search, download

### Backend Service (walidata.gorontalokab.go.id)
- **Technology**: Laravel 11 + Filament
- **Port**: 8000
- **Purpose**: Admin dashboard and management
- **Features**: User management, dataset CRUD, analytics

### API Service (api-opendata.gorontalokab.go.id)
- **Technology**: Laravel API + Nginx Proxy
- **Port**: 8080
- **Purpose**: RESTful API for data access
- **Features**: Public API, authentication, rate limiting

## 🔄 Container Services

### Docker Compose Services
```yaml
services:
  frontend:     # React application
  backend:      # Laravel application
  api:          # Nginx API proxy
  queue:        # Laravel queue worker
  scheduler:    # Laravel scheduler
  db:           # PostgreSQL database
  redis:        # Redis cache/queue
  nginx:        # Main web server
```

## 🧪 Testing Endpoints

### Health Checks
```bash
# Frontend
curl http://localhost:3000

# Backend Admin
curl http://localhost:8000/admin

# API Health
curl http://localhost:8080/health

# API Stats
curl http://localhost:8080/stats
```

### Production URLs
```bash
# Frontend
curl https://opendata.gorontalokab.go.id

# Backend Admin
curl https://walidata.gorontalokab.go.id/admin

# API
curl https://api-opendata.gorontalokab.go.id/health
```

## 📈 Monitoring

### Service Status
```bash
# Check all containers
docker-compose ps

# View logs
docker-compose logs -f frontend
docker-compose logs -f backend
docker-compose logs -f api
```

### Performance Monitoring
```bash
# Container resources
docker stats

# Nginx access logs
tail -f logs/nginx/frontend_access.log
tail -f logs/nginx/backend_access.log
tail -f logs/nginx/api_access.log
```

## 🔒 Security Configuration

### Rate Limiting
- **Frontend**: No rate limiting (static files)
- **Backend Admin**: 5 requests/minute for login
- **API**: 100 requests/minute per IP

### CORS Configuration
```php
'allowed_origins' => [
    'https://opendata.gorontalokab.go.id',
    'https://walidata.gorontalokab.go.id',
    'https://api-opendata.gorontalokab.go.id',
],
```

### Security Headers
- Strict-Transport-Security
- X-Frame-Options
- X-Content-Type-Options
- X-XSS-Protection

## 🔄 Updates & Maintenance

### Application Updates
```bash
# Pull latest changes
git pull origin main

# Rebuild and restart
docker-compose build --no-cache
docker-compose up -d

# Run migrations
docker-compose exec backend php artisan migrate --force
```

### SSL Certificate Renewal
```bash
# Auto-renewal setup
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -

# Manual renewal
sudo certbot renew
```

### Backup Strategy
```bash
# Database backup
docker-compose exec db pg_dump -U $DB_USERNAME opendata_portal > backups/db_$(date +%Y%m%d).sql

# Files backup
tar -czf backups/files_$(date +%Y%m%d).tar.gz backend/storage ssl
```

## 🆘 Troubleshooting

### Common Issues

1. **Domain not accessible**
   ```bash
   # Check DNS resolution
   nslookup opendata.gorontalokab.go.id
   
   # Check firewall
   sudo ufw status
   ```

2. **SSL certificate errors**
   ```bash
   # Verify certificate
   openssl x509 -in ssl/opendata.gorontalokab.go.id.crt -text -noout
   
   # Check certificate expiry
   openssl x509 -in ssl/opendata.gorontalokab.go.id.crt -noout -dates
   ```

3. **API CORS errors**
   ```bash
   # Check CORS configuration
   docker-compose exec backend php artisan config:show cors
   
   # Clear config cache
   docker-compose exec backend php artisan config:clear
   ```

4. **Container startup issues**
   ```bash
   # Check container logs
   docker-compose logs backend
   
   # Restart specific service
   docker-compose restart backend
   ```

## 📞 Support

- **Technical Support**: opendata@gorontalokab.go.id
- **Phone**: (0435) 881234
- **Documentation**: [GitHub Wiki](https://github.com/your-repo/wiki)

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.