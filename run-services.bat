@echo off
echo Starting all services...

start cmd /k "cd auth-service && php artisan serve --port=8000"
timeout /t 5
start cmd /k "cd book-catalog-service && php artisan serve --port=8001"
timeout /t 5
start cmd /k "cd review-service && php artisan serve --port=8002"

echo All services started!
echo Auth Service: http://localhost:8000
echo Book Catalog Service: http://localhost:8001
echo Review Service: http://localhost:8002 