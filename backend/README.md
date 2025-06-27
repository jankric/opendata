# Open Data Portal Gorontalo - Backend

Backend API untuk Portal Data Terbuka Kabupaten Gorontalo yang dibangun dengan Laravel 11.

## 🚀 Fitur Utama

### 📊 Dataset Management
- CRUD dataset dengan approval workflow
- Multiple file format support (CSV, JSON, Excel, PDF, XML, GeoJSON)
- File preview untuk CSV dan JSON
- Kategorisasi dan tagging
- Search dan filtering advanced
- Download tracking dan analytics

### 👥 User Management
- Role-based access control (RBAC)
- Multi-organization support
- User authentication dengan Sanctum
- Permission management

### 📈 Analytics & Reporting
- Real-time statistics
- Download dan view tracking
- Search analytics
- Weekly automated reports
- API usage monitoring

### 🔍 Search & Discovery
- Full-text search
- Auto-suggestions
- Popular search terms
- Advanced filtering

### 🔐 Security
- API rate limiting
- File validation
- CORS configuration
- Request tracking
- Security logging

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Database**: PostgreSQL
- **Cache**: Redis
- **Queue**: Redis
- **Storage**: Local/S3
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **File Processing**: Spatie Media Library
- **Search**: Laravel Scout (optional)

## 📋 Requirements

- PHP 8.2+
- PostgreSQL 13+
- Redis 6+
- Composer
- Node.js 18+ (untuk asset compilation)

## 🚀 Installation

### 1. Clone Repository
```bash
git clone https://github.com/your-repo/opendata-portal-backend.git
cd opendata-portal-backend
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
# Configure database in .env
php artisan migrate
php artisan db:seed --class=ProductionDataSeeder
```

### 5. Storage Setup
```bash
php artisan storage:link
```

### 6. Cache & Queue
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔧 Configuration

### Database Configuration
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=opendata_portal
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### Redis Configuration
```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### File Storage
```env
# Local storage
FILESYSTEM_DISK=local

# S3 storage (production)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=your_bucket
```

## 🏃‍♂️ Running the Application

### Development
```bash
php artisan serve
```

### Production (with Docker)
```bash
docker-compose up -d
```

### Queue Worker
```bash
php artisan queue:work
```

### Scheduler
```bash
php artisan schedule:work
```

## 📚 API Documentation

### Authentication
```bash
POST /api/v1/auth/login
POST /api/v1/auth/logout
GET  /api/v1/auth/profile
POST /api/v1/auth/refresh
```

### Datasets
```bash
GET    /api/v1/datasets
POST   /api/v1/datasets
GET    /api/v1/datasets/{id}
PUT    /api/v1/datasets/{id}
DELETE /api/v1/datasets/{id}
GET    /api/v1/datasets/search
GET    /api/v1/datasets/popular
GET    /api/v1/datasets/recent
```

### Categories
```bash
GET    /api/v1/categories
POST   /api/v1/categories
GET    /api/v1/categories/{id}
PUT    /api/v1/categories/{id}
DELETE /api/v1/categories/{id}
```

### Resources
```bash
GET    /api/v1/datasets/{dataset}/resources
POST   /api/v1/datasets/{dataset}/resources
GET    /api/v1/resources/{id}
PUT    /api/v1/resources/{id}
DELETE /api/v1/resources/{id}
GET    /api/v1/resources/{id}/download
GET    /api/v1/resources/{id}/preview
```

### Statistics
```bash
GET /api/v1/stats
GET /api/v1/stats/dashboard
GET /api/v1/stats/downloads
GET /api/v1/stats/views
GET /api/v1/stats/trends
```

## 🔨 Artisan Commands

### Dataset Management
```bash
# Update dataset statistics
php artisan datasets:update-stats

# Generate weekly reports
php artisan reports:weekly

# Cleanup old files
php artisan cleanup:files --dry-run
```

### Cache Management
```bash
# Clear all caches
php artisan cache:clear

# Clear specific cache tags
php artisan cache:forget portal_stats
```

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### Feature Tests
```bash
php artisan test --filter=DatasetTest
php artisan test --filter=AuthTest
```

## 📊 Monitoring

### Logs
- Application logs: `storage/logs/laravel.log`
- API logs: `storage/logs/api.log`
- Security logs: `storage/logs/security.log`

### Performance
- Slow query monitoring
- API response time tracking
- Cache hit/miss ratios

## 🔒 Security

### File Upload Security
- MIME type validation
- File size limits
- Virus scanning (optional)
- Secure file storage

### API Security
- Rate limiting
- Request validation
- CORS configuration
- Authentication required for sensitive endpoints

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure secure database credentials
- [ ] Set up SSL certificates
- [ ] Configure file storage (S3)
- [ ] Set up monitoring
- [ ] Configure backup strategy
- [ ] Set up queue workers
- [ ] Configure scheduler

### Docker Deployment
```bash
docker-compose -f docker-compose.prod.yml up -d
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support, email opendata@gorontalokab.go.id or create an issue in the repository.

## 🙏 Acknowledgments

- Laravel Framework
- Spatie packages
- PostgreSQL
- Redis
- All contributors