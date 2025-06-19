@echo off
echo Testing Microservices Integration...
echo.

echo Testing Health Checks...
echo.

echo Auth Service Health Check:
curl -s http://localhost:8000/health
echo.
echo.

echo Book Catalog Service Health Check:
curl -s http://localhost:8001/health
echo.
echo.

echo Borrowing Service Health Check:
curl -s http://localhost:8002/health
echo.
echo.

echo Review Service Health Check:
curl -s http://localhost:8003/health
echo.
echo.

echo Testing Service Integrations...
echo.

echo Auth Service Integration:
curl -s http://localhost:8000/test-integration
echo.
echo.

echo Book Catalog Service Integration:
curl -s http://localhost:8001/test-integration
echo.
echo.

echo Borrowing Service Integration:
curl -s http://localhost:8002/test-integration
echo.
echo.

echo Review Service Integration:
curl -s http://localhost:8003/test-integration
echo.
echo.

echo Integration testing completed!
echo.
pause 