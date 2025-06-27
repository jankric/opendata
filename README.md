# 🌐 Portal Data Terbuka Kabupaten Gorontalo

Portal resmi untuk mengakses data publik Kabupaten Gorontalo yang mendukung transparansi pemerintahan dan mendorong inovasi melalui keterbukaan informasi.

![Portal Data Terbuka](https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&w=1200&h=400&fit=crop)

## 🎯 Tentang Project

Portal Data Terbuka Kabupaten Gorontalo adalah platform digital yang menyediakan akses mudah dan gratis ke data publik berkualitas tinggi. Platform ini dikembangkan untuk:

- ✅ Meningkatkan transparansi pemerintahan
- ✅ Mendorong inovasi berbasis data
- ✅ Mendukung pengambilan keputusan yang tepat
- ✅ Memfasilitasi penelitian dan analisis
- ✅ Memperkuat partisipasi masyarakat

## 🌐 Arsitektur Multi-Domain

### Domain Structure
```
📱 Frontend:  opendata.gorontalokab.go.id  (Port 3000)
🔧 Backend:   walidata.gorontalokab.go.id   (Port 8000)  
🔗 API:       api-opendata.gorontalokab.go.id (Port 8080)
```

### Service Architecture
- **Frontend (React)**: Portal publik untuk browsing dan download data
- **Backend (Laravel)**: Admin dashboard untuk manajemen data
- **API (REST)**: Endpoint publik untuk akses data terstruktur

## 🏗️ Tech Stack

### Frontend (React + TypeScript)
- **Framework**: React 18 dengan TypeScript
- **Styling**: Tailwind CSS
- **Icons**: Lucide React
- **Routing**: React Router DOM
- **State Management**: React Hooks
- **API Client**: Custom fetch wrapper

### Backend (Laravel 11)
- **Framework**: Laravel 11 (PHP 8.3)
- **Database**: PostgreSQL 16
- **Cache**: Redis 7
- **Authentication**: Laravel Sanctum
- **Admin Panel**: Filament v3
- **File Storage**: Local/S3
- **Queue**: Redis
- **Search**: Full-text search

### Infrastructure
- **Containerization**: Docker & Docker Compose
- **Web Server**: Nginx
- **Process Manager**: Supervisor
- **SSL/TLS**: Let's Encrypt
- **Monitoring**: Built-in health checks

## 📊 Fitur Utama

### 🔍 Pencarian & Discovery
- Full-text search dengan auto-suggestions
- Filter berdasarkan kategori, organisasi, format
- Sorting berdasarkan popularitas, tanggal, relevansi
- Tag-based categorization

### 📈 Dataset Management
- Upload multiple format (CSV, JSON, Excel, PDF, XML, GeoJSON)
- Preview data untuk CSV dan JSON
- Metadata management
- Version control
- Approval workflow

### 👥 User Management
- Role-based access control (RBAC)
- Multi-organization support
- User authentication & authorization
- Activity logging

### 📊 Analytics & Reporting
- Real-time download & view statistics
- Popular datasets tracking
- User engagement metrics
- Automated weekly reports
- API usage analytics

### 🔐 Security & Performance
- API rate limiting
- File validation & security
- CORS configuration
- Request tracking
- Caching strategy
- SSL/TLS encryption

## 🚀 Quick Start

### Prerequisites
- Docker 20.10+
- Docker Compose 2.0+
- Git

### Installation

1. **Clone Repository**
   ```bash
   git clone https://github.com/your-repo/opendata-portal-gorontalo.git
   cd opendata-portal-gorontalo
   ```

2. **Environment Setup**
   ```bash
   cp .env.production .env
   # Edit .env with your configurations
   ```

3. **Deploy Multi-Domain Setup**
   ```bash
   chmod +x deploy-domains.sh
   ./deploy-domains.sh production
   ```

4. **Access Applications**
   - **Frontend**: http://localhost:3000
   - **Backend Admin**: http://localhost:8000/admin
   - **API**: http://localhost:8080

### Production URLs (after DNS setup)
- **Frontend**: https://opendata.gorontalokab.go.id
- **Backend Admin**: https://walidata.gorontalokab.go.id/admin
- **API**: https://api-opendata.gorontalokab.go.id

### Default Credentials
- **Email**: admin@gorontalokab.go.id
- **Password**: admin123

## 📚 API Documentation

### Base URLs
- **Development**: http://localhost:8080
- **Production**: https://api-opendata.gorontalokab.go.id

### Authentication
```bash
POST /auth/login
POST /auth/logout
GET  /auth/profile
POST /auth/refresh
```

### Public Endpoints
```bash
GET /datasets              # List datasets
GET /datasets/search       # Search datasets
GET /datasets/popular      # Popular datasets
GET /datasets/recent       # Recent datasets
GET /categories            # List categories
GET /organizations         # List organizations
GET /stats                 # Public statistics
```

### Dataset Management
```bash
POST   /datasets           # Create dataset
PUT    /datasets/{id}      # Update dataset
DELETE /datasets/{id}      # Delete dataset
POST   /datasets/{id}/publish    # Publish dataset
POST   /datasets/{id}/approve    # Approve dataset
```

### File Management
```bash
GET    /resources/{id}/download  # Download file
GET    /resources/{id}/preview   # Preview data
POST   /datasets/{id}/resources  # Upload file
```

## 🗂️ Struktur Project

```
opendata-portal-gorontalo/
├── backend/                 # Laravel Backend
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   ├── Services/
│   │   ├── Filament/        # Admin Panel
│   │   └── ...
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   └── ...
├── src/                     # React Frontend
│   ├── components/
│   ├── pages/
│   ├── api/
│   └── ...
├── nginx/                   # Nginx Configuration
│   ├── frontend.conf        # Frontend domain config
│   ├── backend.conf         # Backend domain config
│   └── api.conf            # API domain config
├── docker-compose.prod.yml  # Production Docker Compose
├── deploy-domains.sh       # Multi-domain deployment script
└── README.md
```

## 🎨 Screenshots

### Frontend Portal
![Frontend Portal](https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=800&h=400&fit=crop)

### Admin Dashboard
![Admin Dashboard](https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=800&h=400&fit=crop)

### API Documentation
![API Documentation](https://images.pexels.com/photos/3184317/pexels-photo-3184317.jpeg?auto=compress&cs=tinysrgb&w=800&h=400&fit=crop)

## 📈 Statistik Project

- **Total Datasets**: 150+ dataset tersedia
- **Categories**: 8 kategori utama
- **Organizations**: 15+ instansi pemerintah
- **File Formats**: 7 format file didukung
- **API Endpoints**: 25+ endpoint tersedia
- **Download Count**: 25,000+ unduhan per bulan

## 🛠️ Development

### Backend Development
```bash
cd backend
composer install
php artisan serve --port=8000
```

### Frontend Development
```bash
npm install
npm run dev  # Runs on port 3000
```

### Database Setup
```bash
php artisan migrate
php artisan db:seed --class=DevelopmentDataSeeder
```

### Testing
```bash
# Backend tests
php artisan test

# API testing
php test-api.php
```

## 🚀 Deployment

### Multi-Domain Production Deployment
```bash
./deploy-domains.sh production
```

### Single Domain Deployment
```bash
./deploy.sh production
```

Lihat [DEPLOYMENT-DOMAINS.md](DEPLOYMENT-DOMAINS.md) untuk panduan lengkap multi-domain deployment.

## 🔧 Configuration

### Environment Variables
```env
# Application URLs
APP_URL=https://walidata.gorontalokab.go.id
FRONTEND_URL=https://opendata.gorontalokab.go.id
API_URL=https://api-opendata.gorontalokab.go.id

# Database
DB_CONNECTION=pgsql
DB_HOST=db
DB_DATABASE=opendata_portal

# Cache & Queue
CACHE_STORE=redis
QUEUE_CONNECTION=redis

# File Storage
FILESYSTEM_DISK=s3
AWS_BUCKET=opendata-gorontalo-files
```

### Docker Configuration
- **Frontend Container**: React application (Port 3000)
- **Backend Container**: Laravel application (Port 8000)
- **API Container**: Nginx proxy to backend API (Port 8080)
- **Database**: PostgreSQL 16
- **Cache**: Redis 7
- **Web Server**: Nginx

## 📊 Monitoring & Analytics

### Health Monitoring
```bash
# Check all services
curl http://localhost:3000      # Frontend
curl http://localhost:8000/admin # Backend
curl http://localhost:8080/health # API
```

### Analytics Dashboard
- Dataset download statistics
- User engagement metrics
- Popular content tracking
- API usage analytics
- Growth trend analysis

## 🔒 Security

### Domain-Specific Security
- **Frontend**: Static file serving with caching
- **Backend**: Admin authentication with rate limiting
- **API**: Public API with rate limiting and CORS

### Security Features
- SSL/TLS for all domains
- Rate limiting per domain
- CORS configuration
- Input validation
- File upload security

## 🔄 Updates & Maintenance

### Application Updates
```bash
# Pull latest changes
git pull origin main

# Rebuild all services
docker-compose -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.prod.yml up -d

# Run migrations
docker-compose exec backend php artisan migrate --force
```

### SSL Certificate Management
```bash
# Renew all certificates
sudo certbot renew

# Auto-renewal setup
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation
- Use conventional commits

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Framework** - Backend framework
- **React** - Frontend library
- **Filament** - Admin panel
- **Tailwind CSS** - Styling framework
- **PostgreSQL** - Database
- **Redis** - Caching & queues
- **Docker** - Containerization

## 📞 Support & Contact

### Technical Support
- **Email**: opendata@gorontalokab.go.id
- **Phone**: (0435) 881234
- **Address**: Jl. 23 Januari No. 43, Limboto, Gorontalo 96212

### Development Team
- **Project Lead**: Ahmad Wijaya
- **Backend Developer**: Siti Nurhaliza
- **Frontend Developer**: Budi Santoso

### Links
- **Frontend**: https://opendata.gorontalokab.go.id
- **Backend Admin**: https://walidata.gorontalokab.go.id/admin
- **API**: https://api-opendata.gorontalokab.go.id
- **GitHub**: https://github.com/your-repo/opendata-portal-gorontalo
- **Issues**: https://github.com/your-repo/opendata-portal-gorontalo/issues

---

**Portal Data Terbuka Kabupaten Gorontalo** - Membangun masa depan yang lebih transparan dan inovatif melalui keterbukaan data publik.

© 2024 Pemerintah Kabupaten Gorontalo. All rights reserved.