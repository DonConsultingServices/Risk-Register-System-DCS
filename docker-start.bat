@echo off
echo Starting DCS Risk Register with Docker...

REM Check if .env exists, if not copy from docker.env
if not exist .env (
    echo Copying Docker environment configuration...
    copy docker.env .env
)

REM Build and start containers
echo Building and starting Docker containers...
docker-compose up -d --build

REM Wait a moment for services to start
echo Waiting for services to start...
timeout /t 10 /nobreak > nul

REM Generate application key if not exists
echo Generating application key...
docker-compose run --rm app php artisan key:generate

REM Run migrations
echo Running database migrations...
docker-compose exec app php artisan migrate

echo.
echo ========================================
echo Docker setup completed!
echo.
echo Access your application at:
echo - Main App: http://localhost:8000
echo - PHPMyAdmin: http://localhost:8080
echo - Mailhog: http://localhost:8025
echo.
echo To view logs: docker-compose logs -f
echo To stop: docker-compose down
echo ========================================
pause
