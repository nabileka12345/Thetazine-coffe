@echo off
echo ========================================
echo Thetazine Coffee - Setup Script
echo ========================================
echo.

echo [1/5] Generating Application Key...
php artisan key:generate
echo.

echo [2/5] Publishing JWT Configuration...
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
echo.

echo [3/5] Generating JWT Secret...
php artisan jwt:secret
echo.

echo [4/5] Running Database Migrations...
php artisan migrate:fresh
echo.

echo [5/5] Seeding Database with Sample Data...
php artisan db:seed
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Default Login Credentials:
echo Email: admin@thetazine.com
echo Password: password123
echo.
echo To start the server, run:
echo php artisan serve
echo.
echo Then visit: http://localhost:8000
echo.
pause
