# 🚀 Deployment Guide - Open Data Portal Gorontalo

Panduan lengkap untuk deploy Portal Data Terbuka Kabupaten Gorontalo ke VPS.

## 📋 Prerequisites

### Server Requirements
- **OS**: Ubuntu 20.04+ / CentOS 8+ / Debian 11+
- **RAM**: Minimum 4GB (Recommended 8GB+)
- **Storage**: Minimum 50GB SSD
- **CPU**: 2+ cores
- **Network**: Public IP dengan akses internet

### Software Requirements
- Docker 20.10+
- Docker Compose 2.0+
- Git
- Nginx (optional, sudah include di container)

## 🛠️ Installation Steps

### 1. Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Logout and login again to apply docker group
```

### 2. Clone Repository

```bash
# Clone project
git clone https://github.com/your-repo/opendata-portal-gorontalo.git
cd opendata-portal-gorontalo

# Set permissions
chmod +x deploy.sh
```

### 3. Environment Configuration

```bash
# Copy production environment
cp .env.production .env

# Edit environment variables
nano .env
```

**Important Environment Variables:**
```env
# Database
DB_USERNAME=your_secure_username
DB_PASSWORD=your_very_secure_password

# App
APP_KEY=base64:your-generated-key
APP_URL=https://opendata.gorontalokab.go.id

# Mail
MAIL_HOST=your-smtp-server
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password

# AWS S3 (for file storage)
AWS_ACCESS_KEY_ID=your-aws-key
AWS_SECRET_ACCESS_KEY=your-aws-secret
AWS_BUCKET=your-s3-bucket
```

### 4. SSL Certificate Setup

```bash
# Create SSL directory
mkdir -p ssl

# Option 1: Let's Encrypt (Recommended)
sudo apt install certbot
sudo certbot certonly --standalone -d opendata.gorontalokab.go.id

# Copy certificates
sudo cp /etc/letsencrypt/live/opendata.gorontalokab.go.id/fullchain.pem ssl/opendata.gorontalokab.go.id.crt
sudo cp /etc/letsencrypt/live/opendata.gorontalokab.go.id/privkey.pem ssl/opendata.gorontalokab.go.id.key

# Option 2: Self-signed (Development only)
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout ssl/opendata.gorontalokab.go.id.key \
  -out ssl/opendata.gorontalokab.go.id.crt
```

### 5. Deploy Application

```bash
# Run deployment script
./deploy.sh production

# Or manual deployment
docker-compose -f docker-compose.prod.yml up -d
```

### 6. Post-Deployment Setup

```bash
# Create admin user
docker-compose -f docker-compose.prod.yml exec app php artisan make:filament-user

# Update dataset statistics
docker-compose -f docker-compose.prod.yml exec app php artisan datasets:update-stats

# Test API
curl https://opendata.gorontalokab.go.id/api/v1/health
```

## 🔧 Configuration

### Nginx Configuration

File: `nginx/sites/opendata.conf`

Key configurations:
- SSL/TLS settings
- Rate limiting
- CORS headers
- Security headers
- Static file caching

### Database Configuration

PostgreSQL with optimizations:
- Connection pooling
- Automated backups
- Performance tuning

### Redis Configuration

Used for:
- Session storage
- Cache
- Queue jobs

## 📊 Monitoring & Maintenance

### Health Checks

```bash
# Application health
curl https://opendata.gorontalokab.go.id/api/v1/health

# Database health
docker-compose exec db pg_isready

# Redis health
docker-compose exec redis redis-cli ping
```

### Log Monitoring

```bash
# Application logs
docker-compose logs -f app

# Nginx logs
tail -f logs/nginx/opendata_access.log

# Database logs
docker-compose logs -f db
```

### Backup Strategy

```bash
# Database backup
docker-compose exec db pg_dump -U $DB_USERNAME opendata_portal > backups/db_$(date +%Y%m%d_%H%M%S).sql

# File backup
tar -czf backups/files_$(date +%Y%m%d_%H%M%S).tar.gz backend/storage/app/public
```

### Automated Backups

Add to crontab:
```bash
# Daily database backup at 2 AM
0 2 * * * cd /path/to/project && docker-compose exec db pg_dump -U $DB_USERNAME opendata_portal > backups/daily_$(date +\%Y\%m\%d).sql

# Weekly file backup at 3 AM Sunday
0 3 * * 0 cd /path/to/project && tar -czf backups/files_$(date +\%Y\%m\%d).tar.gz backend/storage/app/public
```

## 🔒 Security

### Firewall Setup

```bash
# UFW firewall
sudo ufw enable
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443
sudo ufw deny 5432  # PostgreSQL (internal only)
sudo ufw deny 6379  # Redis (internal only)
```

### SSL/TLS Security

- TLS 1.2+ only
- Strong cipher suites
- HSTS headers
- Certificate auto-renewal

### Application Security

- Rate limiting
- CORS configuration
- Input validation
- SQL injection protection
- XSS protection

## 🚀 Performance Optimization

### Application Level

```bash
# Optimize Composer autoloader
docker-compose exec app composer install --optimize-autoloader --no-dev

# Cache configurations
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### Database Optimization

```sql
-- PostgreSQL optimizations
ALTER SYSTEM SET shared_buffers = '256MB';
ALTER SYSTEM SET effective_cache_size = '1GB';
ALTER SYSTEM SET maintenance_work_mem = '64MB';
SELECT pg_reload_conf();
```

### Redis Optimization

```bash
# Redis memory optimization
echo 'maxmemory 512mb' >> redis.conf
echo 'maxmemory-policy allkeys-lru' >> redis.conf
```

## 🔄 Updates & Maintenance

### Application Updates

```bash
# Pull latest changes
git pull origin main

# Rebuild containers
docker-compose -f docker-compose.prod.yml build --no-cache

# Update with zero downtime
docker-compose -f docker-compose.prod.yml up -d

# Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

### SSL Certificate Renewal

```bash
# Renew Let's Encrypt certificates
sudo certbot renew --dry-run

# Auto-renewal cron job
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -
```

## 🆘 Troubleshooting

### Common Issues

1. **Container won't start**
   ```bash
   docker-compose logs app
   docker-compose exec app php artisan config:clear
   ```

2. **Database connection failed**
   ```bash
   docker-compose exec app php artisan config:cache
   docker-compose restart db
   ```

3. **Permission denied**
   ```bash
   docker-compose exec app chown -R www-data:www-data /app/storage
   docker-compose exec app chmod -R 775 /app/storage
   ```

4. **SSL certificate issues**
   ```bash
   # Check certificate validity
   openssl x509 -in ssl/opendata.gorontalokab.go.id.crt -text -noout
   
   # Restart nginx
   docker-compose restart nginx
   ```

### Performance Issues

1. **Slow API responses**
   ```bash
   # Check Redis connection
   docker-compose exec redis redis-cli ping
   
   # Clear cache
   docker-compose exec app php artisan cache:clear
   ```

2. **High memory usage**
   ```bash
   # Monitor container resources
   docker stats
   
   # Optimize database
   docker-compose exec db psql -U $DB_USERNAME -d opendata_portal -c "VACUUM ANALYZE;"
   ```

## 📞 Support

- **Email**: opendata@gorontalokab.go.id
- **Documentation**: [GitHub Wiki](https://github.com/your-repo/wiki)
- **Issues**: [GitHub Issues](https://github.com/your-repo/issues)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.