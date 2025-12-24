@echo off
REM Test Register - Should create user with role 'user'
echo === TEST 1: Register New User ===
powershell -Command "Invoke-WebRequest -Uri 'http://localhost:8000/api/auth/register' -Method POST -Headers @{'Content-Type'='application/json'} -Body '{\"name\": \"Test User\", \"email\": \"testuser@example.com\", \"password\": \"password123\", \"password_confirmation\": \"password123\"}' | ConvertTo-Json"

echo.
echo === TEST 2: Login as Regular User ===
powershell -Command "Invoke-WebRequest -Uri 'http://localhost:8000/api/auth/login' -Method POST -Headers @{'Content-Type'='application/json'} -Body '{\"email\": \"testuser@example.com\", \"password\": \"password123\"}' | Select-Object -ExpandProperty Content | ConvertFrom-Json | ConvertTo-Json"

echo.
echo === TEST 3: Login as Admin ===
powershell -Command "Invoke-WebRequest -Uri 'http://localhost:8000/api/auth/login' -Method POST -Headers @{'Content-Type'='application/json'} -Body '{\"email\": \"admin@thetazine.com\", \"password\": \"admin123\"}' | Select-Object -ExpandProperty Content | ConvertFrom-Json | ConvertTo-Json"
