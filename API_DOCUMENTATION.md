# Complete Authentication & Notification System

## Overview

Complete microservices authentication and email notification system with Kafka event streaming.

**Architecture:**
- **Student Service**: Student registration (signup)
- **Auth Service**: User authentication (login)
- **Notification Service**: Email notifications for signup and login events

---

## API Endpoints

### Student Service

**Base URL:** `http://localhost:8000/api/students`

#### 1. Student Signup

**Endpoint:** `POST /students/signup`

**Request:**
```json
{
  "name": "Alice Johnson",
  "email": "alice@example.com",
  "phone": "1234567890",
  "registration_number": "REG-2026-001",
  "department": "Computer Science"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Student registered successfully",
  "data": {
    "id": 1,
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "registration_number": "REG-2026-001"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

#### 2. Get All Students

**Endpoint:** `GET /students`

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Alice Johnson",
        "email": "alice@example.com",
        "registration_number": "REG-2026-001",
        "department": "Computer Science",
        "status": "active",
        "created_at": "2026-02-19 12:00:00",
        "updated_at": "2026-02-19 12:00:00"
      }
    ],
    "current_page": 1,
    "total": 1
  }
}
```

---

#### 3. Get Student Details

**Endpoint:** `GET /students/{id}`

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "registration_number": "REG-2026-001",
    "department": "Computer Science",
    "status": "active",
    "email_verified_at": null,
    "created_at": "2026-02-19 12:00:00",
    "updated_at": "2026-02-19 12:00:00"
  }
}
```

---

#### 4. Update Student

**Endpoint:** `PUT /students/{id}`

**Request:**
```json
{
  "name": "Alice Johnson Updated",
  "phone": "9876543210",
  "department": "Data Science"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Student updated successfully",
  "data": { ... }
}
```

---

#### 5. Delete Student

**Endpoint:** `DELETE /students/{id}`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Student deleted successfully"
}
```

---

### Auth Service

**Base URL:** `http://localhost:8000/api/auth`

#### 1. User Register

**Endpoint:** `POST /auth/register`

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

---

#### 2. User Login

**Endpoint:** `POST /auth/login`

**Request:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Success Response (200):**
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

**Error Response (401):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

#### 3. Get Current User

**Endpoint:** `GET /auth/me`

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

---

#### 4. User Logout

**Endpoint:** `POST /auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "User logged out successfully"
}
```

---

## Complete Testing Workflow

### Step 1: Start Notification Listener

```bash
docker exec notification php artisan listen:student-events --timeout=180000
```

**Expected Output:**
```
╔════════════════════════════════════════════════════════════════╗
║       NOTIFICATION SERVICE - Student Events Listener           ║
╚════════════════════════════════════════════════════════════════╝

Configuration:
  • Kafka Broker: kafka:9092
  • Topic: student-events
  • Consumer Group: notification-service
  • Timeout: 180000ms (180s)

Listening for events:
  ✓ StudentSignedUp
  ✓ UserLoggedIn
  ✓ StudentCreated
  ✓ StudentUpdated
  ✓ StudentDeleted
  ✓ StudentEnrolled
```

---

### Step 2: Student Signup

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
    "email": "alice@example.com",
    "registration_number": "REG-2026-001"
  }
}
```

**Listener Output:**
```
[NOTIFICATION SERVICE] StudentSignedUp Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: Student Registration Signup
Student ID: 1
Name: Alice Johnson
Email: alice@example.com
Registration Number: REG-2026-001
Department: Computer Science
Time: 2026-02-19 12:00:00

Actions:
  ✓ Sending welcome email to new student
  ✓ Adding student to mailing list
  ✓ Creating notification preferences
  ✓ Scheduling orientation emails
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  📧 Sending welcome email
     To: alice@example.com
     Subject: Welcome to Our Student Portal!
     Dear Alice Johnson, welcome to our platform. Your account has been successfully created.
  📝 Logged notification: student_signup for ID 1
```

---

### Step 3: User Register

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

---

### Step 4: User Login

```bash
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

**Listener Output:**
```
[NOTIFICATION SERVICE] UserLoggedIn Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: User Login Notification
User ID: 1
Name: John Doe
Email: john@example.com
Login Time: 2026-02-19 12:05:00
IP Address: 172.19.0.1
Time: 2026-02-19 12:05:00

Actions:
  ✓ Sending login confirmation email
  ✓ Checking for suspicious activity
  ✓ Logging login event
  ✓ Updating user activity status
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  📧 Sending login confirmation email
     To: john@example.com
     Subject: Login Confirmation
     Dear John Doe, your account was accessed at 2026-02-19 12:05:00 from IP: 172.19.0.1
  📝 Logged notification: user_login for ID 1
```

---

### Step 5: Get All Students

```bash
curl http://localhost:8000/api/students
```

---

### Step 6: Update Student

```bash
curl -X PUT http://localhost:8000/api/students/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson Updated",
    "phone": "9876543210"
  }'
```

---

### Step 7: Get Current User (Protected)

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/auth/me
```

---

## Files Created

### Student Service
- ✅ `app/Models/Student.php` - Student model
- ✅ `app/Http/Controllers/StudentController.php` - Signup and CRUD
- ✅ `app/Events/StudentSignedUp.php` - Signup event
- ✅ `routes/api.php` - Student routes
- ✅ `database/migrations/2026_02_19_174651_create_students_table.php` - Students table

### Auth Service
- ✅ `app/Http/Controllers/AuthController.php` - Login and register
- ✅ `app/Events/UserLoggedIn.php` - Login event
- ✅ `routes/api.php` - Auth routes
- ✅ `app/Models/User.php` - Updated with Sanctum

### Notification Service
- ✅ `app/Services/NotificationEventHandler.php` - Updated with signup/login handlers
- ✅ `app/Console/Commands/ListenToStudentEvents.php` - Updated listener

---

## Database Schema

### Students Table
```sql
CREATE TABLE students (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  phone VARCHAR(20),
  registration_number VARCHAR(255) UNIQUE NOT NULL,
  department VARCHAR(255),
  status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
  email_verified_at TIMESTAMP,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Users Table (Auth)
```sql
CREATE TABLE users (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  email_verified_at TIMESTAMP,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

---

## Event Flow

```
Student Service (Signup)
    ↓
StudentSignedUp Event
    ↓
Kafka Topic (student-events)
    ↓
Notification Service (Listener)
    ↓
NotificationEventHandler::handleStudentSignedUp()
    ↓
Send Welcome Email + Log Notification


Auth Service (Login)
    ↓
UserLoggedIn Event
    ↓
Kafka Topic (student-events)
    ↓
Notification Service (Listener)
    ↓
NotificationEventHandler::handleUserLoggedIn()
    ↓
Send Login Confirmation Email + Log Notification
```

---

## Testing with Postman

### Collection Template

**Base URL:** `{{base_url}}`

**Variables:**
- `base_url` = `http://localhost:8000`
- `api_token` = (from login response)

**Requests:**

1. Student Signup
   - POST `/api/students/signup`

2. Get Students
   - GET `/api/students`

3. Get Student
   - GET `/api/students/1`

4. Update Student
   - PUT `/api/students/1`

5. Delete Student
   - DELETE `/api/students/1`

6. Auth Register
   - POST `/api/auth/register`

7. Auth Login
   - POST `/api/auth/login`

8. Get Me
   - GET `/api/auth/me`
   - Header: `Authorization: Bearer {{api_token}}`

9. Logout
   - POST `/api/auth/logout`
   - Header: `Authorization: Bearer {{api_token}}`

---

## What Happens Behind the Scenes

### On Student Signup:
1. Validate student data
2. Create student in database
3. Dispatch `StudentSignedUp` event
4. Publish event to Kafka
5. Notification service receives event
6. Send welcome email
7. Log notification
8. Schedule onboarding emails

### On User Login:
1. Validate credentials
2. Generate API token
3. Dispatch `UserLoggedIn` event
4. Publish event to Kafka
5. Notification service receives event
6. Send login confirmation email
7. Log notification
8. Check for suspicious activity

---

## Error Handling

### Validation Errors (422)
- Missing required fields
- Invalid email format
- Duplicate email/registration number

### Authentication Errors (401)
- Invalid credentials
- Expired token
- Missing token

### Server Errors (500)
- Database connection issues
- Kafka publishing failures
- Email service errors

---

## Next Steps

1. ✅ Test complete flow (signup → email)
2. ✅ Test login flow (login → email)
3. Implement real email service (SendGrid, Mailgun)
4. Add email templates
5. Implement forgot password
6. Add 2FA
7. Add rate limiting
8. Add API logging and monitoring

---

## Support

For issues or questions:
1. Check container logs: `docker compose logs [service]`
2. Check Kafka consumer: Is listener running?
3. Verify events in Kafka: `docker exec kafka kafka-topics --list --bootstrap-server kafka:9092`

Happy testing!
