# Postman Testing Guide - Complete Setup & Execution

## Step 1: Import Collection into Postman

### Option A: Import JSON File
1. Open Postman
2. Click **Import** (top left)
3. Click **Upload Files**
4. Select `Postman-Collection.json` from the project root
5. Click **Import**

### Option B: Manual Collection Creation
1. Open Postman
2. Create new collection named "Laravel Microservices - Auth & Notification"
3. Add requests manually (see payloads below)

---

## Step 2: Setup Environment Variables

1. In Postman, click **Environments** (left sidebar)
2. Click **Create** to create new environment
3. Name it: `Laravel Microservices Local`
4. Add these variables:

```
VARIABLE NAME    | TYPE   | VALUE
─────────────────┼────────┼─────────────────────
base_url         | string | http://localhost:8000
api_token        | string | (leave empty for now)
student_id       | string | (will fill later)
```

5. Click **Save**
6. Select this environment at the top right of Postman

---

## Step 3: Setup Pre-requisites

### Terminal 1: Start Kafka Listener
```bash
docker exec notification php artisan listen:student-events --timeout=180000
```

### Verify Gateway is Running
```bash
docker compose ps
```

All containers should show "Up" status.

---

## Step 4: Run Tests in Order

### Test 1️⃣: Student Signup
**Endpoint:** `POST /api/students/signup`

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

**Steps in Postman:**
1. Select `Student Service` → `01. Student Signup`
2. Click **Send**
3. Look for **201 Created** response

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

**What Happens:**
- ✅ Student created in database
- ✅ Event published to Kafka
- ✅ **Check Terminal 1 for email notification!**

---

### Test 2️⃣: Get All Students
**Endpoint:** `GET /api/students`

**Steps in Postman:**
1. Select `Student Service` → `02. Get All Students`
2. Click **Send**
3. Look for **200 OK** response

**Expected Response:**
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

### Test 3️⃣: Get Student by ID
**Endpoint:** `GET /api/students/1`

**Steps in Postman:**
1. Select `Student Service` → `03. Get Student by ID`
2. Click **Send**
3. Look for **200 OK** response

**Expected Response:**
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

### Test 4️⃣: Update Student
**Endpoint:** `PUT /api/students/1`

**Request:**
```json
{
  "name": "Alice Johnson Updated",
  "phone": "9876543210",
  "department": "Data Science"
}
```

**Steps in Postman:**
1. Select `Student Service` → `04. Update Student`
2. Click **Send**
3. Look for **200 OK** response

**Expected Response:**
```json
{
  "success": true,
  "message": "Student updated successfully",
  "data": {
    "id": 1,
    "name": "Alice Johnson Updated",
    "phone": "9876543210",
    "department": "Data Science",
    ...
  }
}
```

---

### Test 5️⃣: Auth Register
**Endpoint:** `POST /api/auth/register`

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Steps in Postman:**
1. Select `Auth Service` → `06. Auth Register`
2. Click **Send**
3. Look for **201 Created** response

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

### Test 6️⃣: Auth Login ⭐ (Triggers Email)
**Endpoint:** `POST /api/auth/login`

**Request:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Steps in Postman:**
1. Select `Auth Service` → `07. Auth Login`
2. Click **Send**
3. Look for **200 OK** response

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

**IMPORTANT - Save the Token:**
1. Copy the token from response
2. In Postman, go to **Environments** → `Laravel Microservices Local`
3. Set `api_token` = the token you just copied
4. Click **Save**

**What Happens:**
- ✅ User logged in
- ✅ Token generated
- ✅ Event published to Kafka
- ✅ **Check Terminal 1 for login email notification!**

---

### Test 7️⃣: Get Current User (Protected)
**Endpoint:** `GET /api/auth/me`

**Headers:**
```
Authorization: Bearer {{api_token}}
```

**Steps in Postman:**
1. Select `Auth Service` → `08. Get Current User`
2. Make sure you're using the environment with the token
3. Click **Send**
4. Look for **200 OK** response

**Expected Response:**
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

### Test 8️⃣: Auth Logout
**Endpoint:** `POST /api/auth/logout`

**Headers:**
```
Authorization: Bearer {{api_token}}
```

**Steps in Postman:**
1. Select `Auth Service` → `09. Auth Logout`
2. Click **Send**
3. Look for **200 OK** response

**Expected Response:**
```json
{
  "success": true,
  "message": "User logged out successfully"
}
```

---

### Test 9️⃣: Delete Student
**Endpoint:** `DELETE /api/students/1`

**Steps in Postman:**
1. Select `Student Service` → `05. Delete Student`
2. Click **Send**
3. Look for **200 OK** response

**Expected Response:**
```json
{
  "success": true,
  "message": "Student deleted successfully"
}
```

---

## Step 5: Monitor Email Notifications

Keep **Terminal 1** visible while testing. You should see:

### After Student Signup:
```
[NOTIFICATION SERVICE] StudentSignedUp Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: Student Registration Signup
Student ID: 1
Name: Alice Johnson
Email: alice@example.com

Actions:
  ✓ Sending welcome email to new student
  ✓ Adding student to mailing list
  ✓ Creating notification preferences
  ✓ Scheduling orientation emails
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  📧 Sending welcome email
     To: alice@example.com
     Subject: Welcome to Our Student Portal!
  📝 Logged notification: student_signup for ID 1
```

### After User Login:
```
[NOTIFICATION SERVICE] UserLoggedIn Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: User Login Notification
User ID: 1
Name: John Doe
Email: john@example.com
Login Time: 2026-02-19 12:05:00
IP Address: 172.19.0.1

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

## Troubleshooting

### ❌ 404 Not Found
**Problem:** Endpoints returning 404  
**Solution:**
```bash
docker compose restart gateway
```

### ❌ Connection Refused
**Problem:** Cannot connect to localhost:8000  
**Solution:**
```bash
docker compose ps
# Check if gateway is running, if not:
docker compose restart gateway
```

### ❌ "Validation failed" or validation errors
**Problem:** Missing required fields  
**Solution:** Check payload matches exactly (case-sensitive, required fields)

### ❌ Token not working
**Problem:** 401 Unauthorized on protected endpoints  
**Solution:**
1. Run Test 6 (Auth Login) again
2. Copy the new token
3. Update environment variable `api_token`

### ❌ Email not showing in listener
**Problem:** StudentSignedUp or UserLoggedIn events not appearing  
**Solution:**
1. Check Terminal 1 listener is running
2. Check Kafka: `docker compose logs kafka`
3. Restart listener: `docker exec notification php artisan listen:student-events --timeout=180000`

---

## Complete Test Sequence

Run these tests in order for full system demonstration:

```
✓ Test 1: Student Signup              (Triggers email)
  ↓ Check Terminal 1 for email notification
✓ Test 2: Get All Students
✓ Test 3: Get Student by ID
✓ Test 4: Update Student
✓ Test 5: Auth Register
✓ Test 6: Auth Login                  (Triggers email)
  ↓ Check Terminal 1 for login email notification
  ↓ Save token to environment
✓ Test 7: Get Current User (protected)
✓ Test 8: Auth Logout
✓ Test 9: Delete Student
```

**Time to complete:** ~5 minutes

---

## Environment Setup Summary

### Postman Variables
```
base_url   = http://localhost:8000
api_token  = (from Auth Login response)
```

### Required Services
```
docker exec notification php artisan listen:student-events --timeout=180000
```

### Response Status Codes
```
201 Created  = Student signup successful
200 OK       = Auth login, student list, user details
422 Unprocessable Entity = Validation errors
401 Unauthorized = Token missing/invalid
404 Not Found = Endpoint not found
```

---

## Key Points

✅ **Import the collection** from `Postman-Collection.json`  
✅ **Setup environment variables** (base_url, api_token)  
✅ **Start listener** in Terminal 1 before testing  
✅ **Run tests in order** - Test 1, 2, 3, 4, 5, 6 (save token), 7, 8, 9  
✅ **Check Terminal 1** for email notifications after Test 1 & 6  
✅ **Save token** after Test 6 to use in protected endpoints  

---

## Headers Reference

### Public Endpoints (No Auth Required)
```
Content-Type: application/json
```

### Protected Endpoints (Auth Required)
```
Content-Type: application/json
Authorization: Bearer {{api_token}}
```

---

## Response Time Expectations

- **Signup:** 200-300ms
- **Login:** 150-200ms
- **Get endpoints:** 100-150ms
- **Email notification:** Instant (in Terminal 1)

---

## Next Steps After Testing

1. ✅ Run complete test sequence
2. ✅ Verify email notifications in Terminal 1
3. 🔄 Integrate real email service (SendGrid/Mailgun)
4. 🔄 Add email templates
5. 🔄 Store notifications in database

---

**Now you're ready to test! Follow the steps above.** 🚀
