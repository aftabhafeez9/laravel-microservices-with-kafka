# 🚀 Quick Start Guide - Authentication & Email Notification System

## One-Minute Setup

### Step 1: Ensure All Containers Are Running
```bash
docker compose up -d
```

### Step 2: Verify Routes Are Loaded
```bash
docker exec student php artisan route:list
docker exec auth php artisan route:list
```

Should show API endpoints like:
```
POST   api/students/signup
GET    api/students
POST   api/auth/login
POST   api/auth/register
```

### Step 3: Start Notification Listener (Terminal 1)
```bash
docker exec notification php artisan listen:student-events --timeout=180000
```

You should see:
```
╔════════════════════════════════════════════════════════════════╗
║       NOTIFICATION SERVICE - Student Events Listener           ║
╚════════════════════════════════════════════════════════════════╝

Listening for events:
  ✓ StudentSignedUp
  ✓ UserLoggedIn
  ...
```

---

## Testing (Terminal 2)

### Test 1: Student Signup (Triggers Email)

```bash
curl -X POST http://localhost:8000/api/students/signup \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "phone": "1234567890",
    "registration_number": "REG-2026-001",
    "department": "Computer Science"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Student registered successfully",
  "data": {
    "id": 1,
    "name": "Alice Johnson",
    "email": "alice@example.com"
  }
}
```

**Terminal 1 Shows:**
```
[NOTIFICATION SERVICE] StudentSignedUp Event Handler
Processing: Student Registration Signup
...
📧 Sending welcome email
   To: alice@example.com
   Subject: Welcome to Our Student Portal!
```

---

### Test 2: User Login (Triggers Email)

```bash
# First register a user
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Then login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "User logged in successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  }
}
```

**Terminal 1 Shows:**
```
[NOTIFICATION SERVICE] UserLoggedIn Event Handler
Processing: User Login Notification
...
📧 Sending login confirmation email
   To: john@example.com
   Subject: Login Confirmation
   From IP: [user_ip]
```

---

## Automated Testing

### PowerShell (Windows)
```bash
.\test-api.ps1
```

This runs the complete test sequence:
1. Student signup ✓
2. Get all students ✓
3. Get student details ✓
4. Update student ✓
5. Auth register ✓
6. Auth login ✓
7. Get current user ✓
8. Logout ✓

---

## Available API Endpoints

### Student Service

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/students/signup` | Register new student |
| GET | `/api/students` | List all students |
| GET | `/api/students/{id}` | Get student details |
| PUT | `/api/students/{id}` | Update student |
| DELETE | `/api/students/{id}` | Delete student |

### Auth Service

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/auth/register` | Register new user |
| POST | `/api/auth/login` | Login (returns token) |
| GET | `/api/auth/me` | Get current user (requires token) |
| POST | `/api/auth/logout` | Logout (requires token) |

---

## What Happens Behind the Scenes

### Student Signup Flow:
```
1. POST /api/students/signup
   ↓
2. StudentController validates input
   ↓
3. Create student record in database
   ↓
4. Dispatch StudentSignedUp event
   ↓
5. Publish event to Kafka (topic: student-events)
   ↓
6. Notification Service listener receives event
   ↓
7. Route to handleStudentSignedUp() handler
   ↓
8. Send welcome email (simulated)
   ↓
9. Log notification
   ↓
10. User sees email notification details in listener output
```

### User Login Flow:
```
1. POST /api/auth/login
   ↓
2. AuthController validates credentials
   ↓
3. Generate API token (Sanctum)
   ↓
4. Dispatch UserLoggedIn event
   ↓
5. Publish event to Kafka (topic: student-events)
   ↓
6. Notification Service listener receives event
   ↓
7. Route to handleUserLoggedIn() handler
   ↓
8. Send login confirmation email with IP info (simulated)
   ↓
9. Log notification
   ↓
10. User sees login email details in listener output
```

---

## Troubleshooting

### 404 Error on API Endpoints?
**Cause:** Gateway routing issue  
**Fix:**
```bash
docker compose restart gateway
```

### Listener not receiving events?
**Cause:** Kafka connection issue  
**Fix:**
```bash
docker compose restart kafka
```

### Database errors?
**Cause:** Migrations not applied  
**Fix:**
```bash
docker exec student php artisan migrate --force
docker exec auth php artisan migrate --force
```

### Routes not showing?
**Cause:** Bootstrap app config not updated  
**Fix:**
```bash
docker compose restart student auth
```

---

## Project Files Structure

```
laravel-microservices-with-kafka/
├── student/
│   ├── app/
│   │   ├── Models/Student.php ✓
│   │   ├── Http/Controllers/StudentController.php ✓
│   │   └── Events/StudentSignedUp.php ✓
│   ├── routes/api.php ✓
│   ├── database/migrations/ ✓
│   └── bootstrap/app.php ✓
│
├── auth/
│   ├── app/
│   │   ├── Http/Controllers/AuthController.php ✓
│   │   ├── Events/UserLoggedIn.php ✓
│   │   └── Models/User.php ✓
│   ├── routes/api.php ✓
│   └── bootstrap/app.php ✓
│
├── notification/
│   ├── app/
│   │   ├── Services/NotificationEventHandler.php ✓
│   │   └── Console/Commands/ListenToStudentEvents.php ✓
│
├── gateway/
│   └── default.conf ✓
│
├── docker-compose.yml ✓
├── API_DOCUMENTATION.md ✓
├── COMPLETE_SETUP_SUMMARY.md ✓
├── PROJECT_COMPLETION_REPORT.md ✓
└── test-api.ps1 ✓
```

---

## Email Events Supported

### 1. StudentSignedUp
- **Trigger:** Student registers
- **Email:** Welcome email
- **Details:** Name, registration number, department

### 2. UserLoggedIn
- **Trigger:** User logs in
- **Email:** Login confirmation
- **Details:** Login time, IP address, user agent

### 3. StudentCreated
- **Trigger:** Student created (via Kafka event)
- **Email:** Account created notification

### 4. StudentUpdated
- **Trigger:** Student profile updated
- **Email:** Update confirmation

### 5. StudentDeleted
- **Trigger:** Student deleted
- **Email:** Deletion confirmation

### 6. StudentEnrolled
- **Trigger:** Student enrolled in course
- **Email:** Course enrollment confirmation

---

## Quick Command Reference

```bash
# Start containers
docker compose up -d

# View logs
docker compose logs -f [service]

# Run migrations
docker exec student php artisan migrate --force
docker exec auth php artisan migrate --force

# Start listener
docker exec notification php artisan listen:student-events --timeout=180000

# View routes
docker exec student php artisan route:list
docker exec auth php artisan route:list

# Stop containers
docker compose down

# Stop and remove volumes
docker compose down -v
```

---

## Expected Test Output Summary

| Test | Status | Response |
|------|--------|----------|
| Student Signup | ✅ 201 | `{"success": true, "message": "..."}` |
| Get Students | ✅ 200 | `{"success": true, "data": [...]}` |
| Auth Register | ✅ 201 | `{"success": true, "message": "..."}` |
| Auth Login | ✅ 200 | `{"success": true, "data": {..., "token": "..."}}` |
| Get Me | ✅ 200 | `{"success": true, "data": {...}}` |
| Logout | ✅ 200 | `{"success": true, "message": "..."}` |

**Listener Output:** Shows email notifications for each event

---

## What's Next?

1. ✅ **Test the system** (follow tests above)
2. 🔄 **Integrate real email service** (SendGrid, Mailgun, AWS SES)
3. 🔄 **Add email templates** (HTML emails)
4. 🔄 **Implement database logging** (store notifications)
5. 🔄 **Add admin dashboard** (view statistics)
6. 🔄 **Production deployment** (Docker Swarm/Kubernetes)

---

## Success Criteria ✓

- ✅ Student signup publishes event to Kafka
- ✅ Notification service receives StudentSignedUp event
- ✅ Welcome email is sent (simulated)
- ✅ User login publishes event to Kafka
- ✅ Notification service receives UserLoggedIn event
- ✅ Login confirmation email is sent (simulated)
- ✅ All API endpoints respond correctly
- ✅ Event routing works correctly

---

## Support

For issues:
1. Check container status: `docker compose ps`
2. View logs: `docker compose logs [service]`
3. Check Kafka: `docker exec kafka kafka-topics --list --bootstrap-server kafka:9092`
4. Review documentation in `API_DOCUMENTATION.md`

---

**Status: READY FOR PRODUCTION ENHANCEMENT! 🎉**

Happy testing and coding!
