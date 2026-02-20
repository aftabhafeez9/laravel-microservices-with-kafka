$base_url = "http://localhost:8000/api"

Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║   Complete Authentication & Notification System Testing       ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Test 1: Student Signup
Write-Host "Test 1: Student Signup" -ForegroundColor Yellow
Write-Host "POST /api/students/signup" -ForegroundColor Blue

$studentResponse = curl.exe -s -X POST "$base_url/students/signup" `
  -H "Content-Type: application/json" `
  -d '{
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "phone": "1234567890",
    "registration_number": "REG-2026-001",
    "department": "Computer Science"
  }'

$studentResponse | ConvertFrom-Json | ConvertTo-Json
$studentId = ($studentResponse | ConvertFrom-Json).data.id
Write-Host "✓ Student created with ID: $studentId" -ForegroundColor Green
Write-Host ""
Start-Sleep -Seconds 3

# Test 2: Get All Students
Write-Host "Test 2: Get All Students" -ForegroundColor Yellow
Write-Host "GET /api/students" -ForegroundColor Blue

curl.exe -s -X GET "$base_url/students" | ConvertFrom-Json | ConvertTo-Json
Write-Host ""
Start-Sleep -Seconds 2

# Test 3: Get Student Details
Write-Host "Test 3: Get Student Details" -ForegroundColor Yellow
Write-Host "GET /api/students/$studentId" -ForegroundColor Blue

curl.exe -s -X GET "$base_url/students/$studentId" | ConvertFrom-Json | ConvertTo-Json
Write-Host ""
Start-Sleep -Seconds 2

# Test 4: Update Student
Write-Host "Test 4: Update Student" -ForegroundColor Yellow
Write-Host "PUT /api/students/$studentId" -ForegroundColor Blue

curl.exe -s -X PUT "$base_url/students/$studentId" `
  -H "Content-Type: application/json" `
  -d '{
    "name": "Alice Johnson Updated",
    "phone": "9876543210"
  }' | ConvertFrom-Json | ConvertTo-Json
Write-Host ""
Start-Sleep -Seconds 2

# Test 5: Auth Register
Write-Host "Test 5: Auth Register" -ForegroundColor Yellow
Write-Host "POST /api/auth/register" -ForegroundColor Blue

$registerResponse = curl.exe -s -X POST "$base_url/auth/register" `
  -H "Content-Type: application/json" `
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

$registerResponse | ConvertFrom-Json | ConvertTo-Json
Write-Host "✓ User registered" -ForegroundColor Green
Write-Host ""
Start-Sleep -Seconds 3

# Test 6: Auth Login
Write-Host "Test 6: Auth Login" -ForegroundColor Yellow
Write-Host "POST /api/auth/login" -ForegroundColor Blue

$loginResponse = curl.exe -s -X POST "$base_url/auth/login" `
  -H "Content-Type: application/json" `
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'

$loginResponse | ConvertFrom-Json | ConvertTo-Json
$loginJson = $loginResponse | ConvertFrom-Json
$apiToken = $loginJson.data.token
$tokenPreview = $apiToken.Substring(0, 20)
Write-Host "✓ User logged in" -ForegroundColor Green
Write-Host "✓ API Token: ${tokenPreview}..." -ForegroundColor Green
Write-Host ""
Start-Sleep -Seconds 3

# Test 7: Get Me (Protected)
Write-Host "Test 7: Get Current User (Protected)" -ForegroundColor Yellow
Write-Host "GET /api/auth/me" -ForegroundColor Blue

curl.exe -s -X GET "$base_url/auth/me" `
  -H "Authorization: Bearer $apiToken" | ConvertFrom-Json | ConvertTo-Json
Write-Host ""
Start-Sleep -Seconds 2

# Test 8: Logout
Write-Host "Test 8: Logout" -ForegroundColor Yellow
Write-Host "POST /api/auth/logout" -ForegroundColor Blue

curl.exe -s -X POST "$base_url/auth/logout" `
  -H "Authorization: Bearer $apiToken" | ConvertFrom-Json | ConvertTo-Json
Write-Host ""

Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║               Testing Complete!                              ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""
Write-Host "Check the Notification Service listener for email notifications!" -ForegroundColor Yellow
