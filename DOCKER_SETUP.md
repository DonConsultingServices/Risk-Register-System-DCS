# Docker Setup Guide for DCS Risk Register

This guide will help you set up and run the DCS Risk Register application using Docker containers.

## Prerequisites

- Docker Desktop installed on your system
- Docker Compose (included with Docker Desktop)
- Git (for cloning the repository)

## Quick Start

1. **Clone the repository** (if not already done):
   ```bash
   git clone <your-repository-url>
   cd DCS-Best
   ```

2. **Copy the Docker environment file**:
   ```bash
   cp docker.env .env
   ```

3. **Generate application key**:
   ```bash
   docker-compose run --rm app php artisan key:generate
   ```

4. **Run the application**:
   ```bash
   docker-compose up -d
   ```

5. **Run database migrations**:
   ```bash
   docker-compose exec app php artisan migrate
   ```

6. **Seed the database** (optional):
   ```bash
   docker-compose exec app php artisan db:seed
   ```

## Accessing the Application

- **Main Application**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8080
- **Mailhog (Email Testing)**: http://localhost:8025
- **Nginx (if enabled)**: http://localhost:80

## Docker Services

The Docker setup includes the following services:

### Core Services
- **app**: Laravel application with PHP 8.1 and Apache
- **mysql**: MySQL 8.0 database
- **redis**: Redis cache and session storage

### Optional Services
- **nginx**: Nginx reverse proxy (for production-like setup)
- **phpmyadmin**: Database management interface
- **mailhog**: Email testing tool

## Configuration

### Environment Variables

The main configuration is in the `.env` file. Key Docker-specific settings:

```env
# Database (Docker MySQL)
DB_HOST=mysql
DB_USERNAME=dcs_user
DB_PASSWORD=dcs_password

# Redis (Docker Redis)
REDIS_HOST=redis
REDIS_PASSWORD=redis_password

# Mail (Docker Mailhog)
MAIL_HOST=mailhog
MAIL_PORT=1025
```

### Service Ports

| Service | Internal Port | External Port | Description |
|---------|---------------|---------------|-------------|
| app | 80 | 8000 | Laravel application |
| mysql | 3306 | 3306 | MySQL database |
| redis | 6379 | 6379 | Redis cache |
| nginx | 80 | 80 | Nginx proxy |
| phpmyadmin | 80 | 8080 | Database admin |
| mailhog | 8025 | 8025 | Email interface |

## Common Commands

### Starting Services
```bash
# Start all services
docker-compose up -d

# Start specific services
docker-compose up -d app mysql redis

# View logs
docker-compose logs -f app
```

### Stopping Services
```bash
# Stop all services
docker-compose down

# Stop and remove volumes
docker-compose down -v
```

### Laravel Commands
```bash
# Run artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache

# Access container shell
docker-compose exec app bash
```

### Database Operations
```bash
# Access MySQL shell
docker-compose exec mysql mysql -u dcs_user -p dcs_risk_register

# Backup database
docker-compose exec mysql mysqldump -u dcs_user -p dcs_risk_register > backup.sql

# Restore database
docker-compose exec -T mysql mysql -u dcs_user -p dcs_risk_register < backup.sql
```

## Development Workflow

### Code Changes
Since the application code is mounted as a volume, changes are reflected immediately without rebuilding the container.

### Database Changes
After making changes to migrations or models:
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan migrate:refresh --seed
```

### Asset Compilation
For frontend assets (if using Laravel Mix/Vite):
```bash
docker-compose exec app npm install
docker-compose exec app npm run build
```

## Production Considerations

### Security
1. Change default passwords in `.env`
2. Use strong database passwords
3. Enable SSL/TLS certificates
4. Configure proper firewall rules

### Performance
1. Use Nginx as reverse proxy
2. Enable Redis for caching
3. Configure proper MySQL settings
4. Use Docker volumes for persistent data

### Monitoring
1. Set up log aggregation
2. Monitor container health
3. Configure backup strategies
4. Set up monitoring dashboards

## Troubleshooting

### Common Issues

1. **Port conflicts**: Change external ports in `docker-compose.yml`
2. **Permission issues**: Ensure proper file permissions
3. **Database connection**: Check MySQL service is running
4. **Cache issues**: Clear Laravel and Redis cache

### Debugging Commands
```bash
# Check container status
docker-compose ps

# View container logs
docker-compose logs app

# Access container
docker-compose exec app bash

# Check MySQL connection
docker-compose exec app php artisan tinker
```

### Reset Everything
```bash
# Stop and remove everything
docker-compose down -v --rmi all

# Rebuild and start
docker-compose up -d --build
```

## File Structure

```
docker/
├── apache/
│   └── 000-default.conf
├── nginx/
│   ├── nginx.conf
│   └── default.conf
├── php/
│   └── local.ini
├── mysql/
│   └── my.cnf
├── supervisor/
│   └── supervisord.conf
└── cron/
    └── laravel-cron
```

## Support

For issues related to Docker setup, please check:
1. Docker Desktop is running
2. Ports are not in use by other applications
3. Environment variables are correctly set
4. All services are healthy (`docker-compose ps`)

For application-specific issues, refer to the main application documentation.
