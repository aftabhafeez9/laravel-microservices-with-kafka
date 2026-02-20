# Postman Testing - Complete Documentation

## 📚 Available Guides

### For Beginners
👉 **[POSTMAN_STEP_BY_STEP.md](POSTMAN_STEP_BY_STEP.md)** ← START HERE
- Visual step-by-step guide
- Screenshots and diagrams
- Copy-paste ready payloads
- Troubleshooting included

### For Quick Reference
👉 **[POSTMAN_PAYLOADS.md](POSTMAN_PAYLOADS.md)**
- All payloads ready to copy
- All expected responses
- Error scenarios
- Status code reference

### For Complete Setup
👉 **[POSTMAN_TESTING_GUIDE.md](POSTMAN_TESTING_GUIDE.md)**
- Import instructions
- Environment setup
- Complete test workflow
- Terminal monitoring

### For Collection Import
📦 **[Postman-Collection.json](Postman-Collection.json)**
- Ready-to-import collection
- All 9 endpoints configured
- Proper folder structure

---

## Quick Start (Choose One)

### Option A: Super Quick (2 minutes)
```
1. Import: Postman-Collection.json
2. Read: POSTMAN_PAYLOADS.md
3. Start testing!
```

### Option B: Step-by-Step (10 minutes)
```
1. Follow: POSTMAN_STEP_BY_STEP.md
2. Import collection
3. Setup environment
4. Run all 9 tests
5. Check Terminal 1 for emails
```

### Option C: Complete Guide (15 minutes)
```
1. Read: POSTMAN_TESTING_GUIDE.md (full setup)
2. Import: Postman-Collection.json
3. Setup: Environment variables
4. Monitor: Terminal 1 listener
5. Execute: All 9 tests in order
6. Verify: Email notifications
```

---

## Files at a Glance

| File | Purpose | Time |
|------|---------|------|
| Postman-Collection.json | Import into Postman | 1 min |
| POSTMAN_STEP_BY_STEP.md | Visual guide with steps | 10 min |
| POSTMAN_PAYLOADS.md | Copy-paste payloads | 2 min |
| POSTMAN_TESTING_GUIDE.md | Complete setup guide | 15 min |

---

## What You'll Test

```
✓ 9 API Endpoints
  ├─ Student Service (5 endpoints)
  ├─ Auth Service (4 endpoints)
  └─ Email Notifications (2 triggers)

✓ 2 Email Notifications
  ├─ Student Signup Email ✉️
  └─ User Login Email ✉️

✓ 3 Success Responses
  ├─ 201 Created (signup, register)
  ├─ 200 OK (login, get, update, delete)
  └─ 200 OK (protected routes with token)

✓ Error Handling
  ├─ 422 Validation errors
  ├─ 401 Unauthorized
  └─ 404 Not found
```

---

## Postman Collection Structure

```
📦 Laravel Microservices - Auth & Notification

📁 Student Service
  ├─ 01. Student Signup           [POST] ✉️ Email
  ├─ 02. Get All Students         [GET]
  ├─ 03. Get Student by ID        [GET]
  ├─ 04. Update Student           [PUT]
  └─ 05. Delete Student           [DELETE]

📁 Auth Service
  ├─ 06. Auth Register            [POST]
  ├─ 07. Auth Login               [POST] ✉️ Email
  ├─ 08. Get Current User         [GET] 🔐 Protected
  └─ 09. Auth Logout              [POST] 🔐 Protected

📁 Kafka Events
  └─ 10. Publish Student Event    [Terminal Only]

📊 Variables
  ├─ base_url (http://localhost:8000)
  └─ api_token (saved from login)
```

---

## Step 1: Import Collection

1. Open Postman
2. Click **Import** (top-left)
3. Upload `Postman-Collection.json`
4. Done! All 9 endpoints ready

---

## Step 2: Setup Environment

1. Click **Environments** (left sidebar)
2. Click **Create**
3. Name: `Laravel Microservices Local`
4. Add variables:
   ```
   base_url  = http://localhost:8000
   api_token = (leave empty, fill after login)
   ```
5. Click **Save**
6. Select this environment (top-right dropdown)

---

## Step 3: Start Listener

In Terminal 1:
```bash
docker exec notification php artisan listen:student-events --timeout=180000
```

Keep this running while testing!

---

## Step 4: Run Tests

### Test 1: Student Signup
```
Postman → Student Service → 01. Student Signup
Click Send
Expected: 201 Created ✅
Check Terminal 1: You should see email notification ✉️
```

### Test 2-5: Other Student Operations
```
Run Tests 2-5 for CRUD operations
```

### Test 6: Auth Register
```
Postman → Auth Service → 06. Auth Register
Click Send
Expected: 201 Created ✅
```

### Test 7: Auth Login (KEY!)
```
Postman → Auth Service → 07. Auth Login
Click Send
Expected: 200 OK ✅
COPY the "token" from response
Paste into Environment variable "api_token"
Save
Check Terminal 1: You should see login email ✉️
```

### Tests 8-9: Protected Routes
```
Tests 8-9 now work with saved token
```

---

## Terminal 1 Monitoring

You should see:

**After Test 1 (Signup):**
```
[NOTIFICATION SERVICE] StudentSignedUp Event Handler
...
📧 Sending welcome email
   To: alice@example.com
```

**After Test 7 (Login):**
```
[NOTIFICATION SERVICE] UserLoggedIn Event Handler
...
📧 Sending login confirmation email
   To: john@example.com
   IP: [address]
```

---

## Expected Response Codes

| Test | Method | Endpoint | Code | Meaning |
|------|--------|----------|------|---------|
| 1 | POST | /students/signup | 201 | Created ✅ |
| 2 | GET | /students | 200 | OK ✅ |
| 3 | GET | /students/1 | 200 | OK ✅ |
| 4 | PUT | /students/1 | 200 | OK ✅ |
| 5 | DELETE | /students/1 | 200 | OK ✅ |
| 6 | POST | /auth/register | 201 | Created ✅ |
| 7 | POST | /auth/login | 200 | OK ✅ |
| 8 | GET | /auth/me | 200 | OK ✅ |
| 9 | POST | /auth/logout | 200 | OK ✅ |

---

## Response Body Examples

### Successful Signup (201)
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

### Successful Login (200) - SAVE TOKEN!
```json
{
  "success": true,
  "message": "User logged in successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "token": "eyJhbGciOiJIUzI1NiIs..." ← COPY THIS
  }
}
```

### Error: Validation (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken"]
  }
}
```

### Error: Unauthorized (401)
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

## Testing Timeline

```
⏱️  5 min:  Import collection + setup
⏱️  2 min:  Run Test 1 (Student Signup)
⏱️  3 min:  Run Tests 2-5 (Student CRUD)
⏱️  1 min:  Run Test 6 (Auth Register)
⏱️  2 min:  Run Test 7 (Auth Login) + save token
⏱️  2 min:  Run Tests 8-9 (Protected routes)
─────────────────
⏱️  15 min: Total time

2x Email Notifications ✉️ (visible in Terminal 1)
9 Total API tests
```

---

## Troubleshooting Quick Links

### Connection Issues
→ See **POSTMAN_TESTING_GUIDE.md** (Troubleshooting section)

### Payload Issues
→ See **POSTMAN_PAYLOADS.md** (Error section)

### Step-by-Step Issues
→ See **POSTMAN_STEP_BY_STEP.md** (Troubleshooting)

### Token Issues
→ Make sure token is saved in environment variable `api_token`

---

## Postman Features Used

✅ **Collections** - Organize requests
✅ **Environments** - Store variables
✅ **Pre-request Scripts** - Auto setup
✅ **Response Viewing** - See results
✅ **Request History** - Track all calls
✅ **Body Builders** - JSON editor

---

## Tips & Tricks

### 1. Use Environment Variables
```
Instead of: Authorization: Bearer abc123...
Use: Authorization: Bearer {{api_token}}

Instead of: http://localhost:8000/api/...
Use: {{base_url}}/api/...
```

### 2. Save Responses
```
Response → Save as Example
Use for reference during testing
```

### 3. Check Request Headers
```
Click "Headers" to verify auth is included
Should show: Authorization: Bearer [token]
```

### 4. Pretty Print JSON
```
Response → Click pretty print button
Makes JSON easier to read
```

---

## Success Indicators

✅ All 9 tests return correct status codes  
✅ Signup response contains student data  
✅ Login response contains API token  
✅ Terminal 1 shows 2 email notifications  
✅ Protected routes (test 8) work with token  

---

## What's Happening Behind Scenes

```
Test 1: Signup
  ↓
StudentController receives request
  ↓
Validates student data
  ↓
Creates student in database
  ↓
Publishes StudentSignedUp event to Kafka
  ↓
Notification service receives event
  ↓
Sends welcome email (shown in Terminal 1)
  ↓
Returns 201 Created to Postman

Test 7: Login
  ↓
AuthController receives login request
  ↓
Validates credentials
  ↓
Generates API token (Sanctum)
  ↓
Publishes UserLoggedIn event to Kafka
  ↓
Notification service receives event
  ↓
Sends login confirmation email (shown in Terminal 1)
  ↓
Returns 200 OK with token to Postman
```

---

## Next Steps After Testing

1. ✅ Complete all 9 tests
2. ✅ Verify email notifications in Terminal 1
3. 🔄 Integrate real email service (SendGrid/Mailgun)
4. 🔄 Add email templates (HTML emails)
5. 🔄 Store notifications in database
6. 🔄 Deploy to staging/production

---

## Documentation Map

```
📍 START HERE
    ↓
├─ POSTMAN_STEP_BY_STEP.md      ← Visual guide
│
├─ POSTMAN_PAYLOADS.md          ← Copy-paste payloads
│
├─ POSTMAN_TESTING_GUIDE.md     ← Complete setup
│
└─ Postman-Collection.json      ← Import this
```

---

## Quick Links to Payloads

- [Student Signup Payload](POSTMAN_PAYLOADS.md#1️⃣-student-signup)
- [Auth Login Payload](POSTMAN_PAYLOADS.md#7️⃣-auth-login-triggers-email)
- [Auth Register Payload](POSTMAN_PAYLOADS.md#6️⃣-auth-register)
- [Update Student Payload](POSTMAN_PAYLOADS.md#4️⃣-update-student)
- [Error Payloads](POSTMAN_PAYLOADS.md#error-payloads-test-error-handling)

---

## System Requirements

✅ Postman (any version, free tier OK)
✅ Docker Desktop running
✅ All containers up (`docker compose ps`)
✅ Listener running (Terminal 1)
✅ Internet connection (for Postman app)

---

## Support

Questions about:
- **Setup?** → POSTMAN_TESTING_GUIDE.md
- **Payloads?** → POSTMAN_PAYLOADS.md
- **Steps?** → POSTMAN_STEP_BY_STEP.md
- **APIs?** → API_DOCUMENTATION.md

---

## Summary

| Document | Purpose | Read Time |
|----------|---------|-----------|
| THIS FILE | Overview | 5 min |
| STEP_BY_STEP | Hands-on guide | 10 min |
| PAYLOADS | Copy-paste reference | 2 min |
| TESTING_GUIDE | Complete setup | 15 min |

**Choose your starting point and begin testing!** 🚀

---

**Ready? Open Postman and import `Postman-Collection.json`!**
