@echo off
REM Kafka Testing Script for Laravel Microservices
REM This script opens multiple terminals and runs the producer/listeners

echo ========================================
echo Kafka Event Testing Setup
echo ========================================
echo.
echo This script will open 3 terminals:
echo  1. Notification Service Listener
echo  2. Admin Service Listener  
echo  3. Student Service Publisher
echo.
echo Press Enter to start the test...
pause

REM Open Terminal 1 - Notification Listener
echo Starting Notification Service Listener...
start "Notification Listener" cmd /k "cd D:\laravel-microservices-with-kafka && docker exec notification php artisan listen:student-events --timeout=120000"

REM Wait a bit
timeout /t 2 /nobreak

REM Open Terminal 2 - Admin Listener
echo Starting Admin Service Listener...
start "Admin Listener" cmd /k "cd D:\laravel-microservices-with-kafka && docker exec admin php artisan listen:student-events --timeout=120000"

REM Wait a bit
timeout /t 2 /nobreak

REM Open Terminal 3 - Student Publisher
echo Starting Student Service Publisher...
start "Student Publisher" cmd /k "cd D:\laravel-microservices-with-kafka && docker exec student php artisan student:publish-event"

echo.
echo ========================================
echo Setup complete!
echo ========================================
echo.
echo Listener terminals are waiting for events...
echo The publisher terminal will show the published event.
echo.
echo To publish more events, run in any terminal:
echo   docker exec student php artisan student:publish-event "Name" "email@example.com"
echo.
pause
