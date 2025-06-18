@echo off
echo Starting all microservices...
echo.

echo Starting Auth Service on port 8000...
start "Auth Service" cmd /k "cd auth-service && php artisan serve --port=8000"

echo Starting Book Catalog Service on port 8001...
start "Book Catalog Service" cmd /k "cd book-catalog-service && php artisan serve --port=8001"

echo Starting Borrowing Service on port 8002...
start "Borrowing Service" cmd /k "cd borrowing-service && php artisan serve --port=8002"

echo Starting Review Service on port 8003...
start "Review Service" cmd /k "cd review-service && php artisan serve --port=8003"

echo.
echo All services are starting...
echo.
echo Service URLs:
echo - Auth Service: http://localhost:8000
echo - Book Catalog Service: http://localhost:8001
echo - Borrowing Service: http://localhost:8002
echo - Review Service: http://localhost:8003
echo.
echo Press any key to exit...
pause > nul 