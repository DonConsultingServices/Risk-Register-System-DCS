@echo off
echo Stopping DCS Risk Register Docker containers...

REM Stop and remove containers
docker-compose down

echo.
echo Docker containers stopped successfully!
echo.
echo To start again, run: docker-start.bat
echo To remove all data: docker-compose down -v
pause
