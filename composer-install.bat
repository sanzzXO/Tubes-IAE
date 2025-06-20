@echo off
echo.
echo === Installing Composer Dependencies for All Services ===
echo.

echo [1/5] Installing dependencies for auth-service...
cd auth-service
call composer install
echo.

echo [2/5] Installing dependencies for book-catalog-service...
cd ..\book-catalog-service
call composer install
echo.

echo [3/5] Installing dependencies for borrowing-service...
cd ..\borrowing-service
call composer install
echo.

echo [4/5] Installing dependencies for review-service...
cd ..\review-service
call composer install
echo.

echo [5/5] Installing dependencies for user-interface...
cd ..\user-interface
call composer install
echo.

cd ..
echo === All dependencies installed successfully! ===
echo.
pause