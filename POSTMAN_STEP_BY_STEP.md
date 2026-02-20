# Postman Testing - Step-by-Step Visual Guide

## Initial Setup (5 Minutes)

### Step 1: Download Postman
- Visit [postman.com](https://www.postman.com/downloads/)
- Download for your OS (Windows, Mac, Linux)
- Install and launch

### Step 2: Import Collection
1. Open Postman
2. Click **Import** button (top-left)
   ```
   ┌─────────────────────────────────┐
   │ File  Edit  View                │
   │ [Import] [New]  [Save] [Export] │
   └─────────────────────────────────┘
   ```
3. Select **Upload Files**
4. Browse to `Postman-Collection.json` in project root
5. Click **Import**

### Step 3: Create Environment
1. Click **Environments** in left sidebar
   ```
   Left Sidebar:
   ├─ Collections
   ├─ APIs
   ├─ Environments ← Click here
   └─ History
   ```

2. Click **Create** to create new environment
3. Name it: `Laravel Microservices Local`
4. Add variables:
   ```
   Variable Name  | Type   | Value
   ─────────────────────────────────────
   base_url       | string | http://localhost:8000
   api_token      | string | (leave empty)
   ```
5. Click **Save**

### Step 4: Select Environment
At top-right of Postman, select from dropdown:
```
Environment: [Laravel Microservices Local] ✓
```

---

## Pre-Test Checklist

Before running tests, verify:

✅ **Docker containers running:**
```bash
docker compose ps
# All should show "Up"
```

✅ **Gateway accessible:**
```bash
curl http://localhost:8000/api/students
# Should get response (not connection error)
```

✅ **Listener running (Terminal 1):**
```bash
docker exec notification php artisan listen:student-events --timeout=180000
# Should show "Listening for events..."
```

✅ **Postman open** with environment selected

---

## Test Execution Guide

### TEST 1: Student Signup ⭐

```
Step 1: Open Postman
├─ Left sidebar: Collections
├─ Click: Student Service
└─ Click: 01. Student Signup

Step 2: Body Setup
├─ Select the "Body" tab
├─ Select "raw"
├─ Select "JSON" from dropdown
└─ Paste this:

{
  "name": "Alice Johnson",
  "email": "alice@example.com",
  "phone": "1234567890",
  "registration_number": "REG-2026-001",
  "department": "Computer Science"
}

Step 3: Send Request
├─ Click [Send] button
├─ Look for "201 Created" (green)
└─ Response body should show student data

Step 4: Check Terminal 1 (Listener)
├─ You should see:
│  [NOTIFICATION SERVICE] StudentSignedUp Event Handler
│  ...
│  📧 Sending welcome email to alice@example.com
│
└─ SUCCESS! Email notification triggered ✉️
```

### TEST 2: Get All Students

```
Step 1: Click: 02. Get All Students
├─ Method: GET (automatic)
├─ URL: {{base_url}}/api/students
└─ No body needed

Step 2: Send Request
├─ Click [Send]
├─ Look for "200 OK" (green)
└─ Response should show list of students
```

### TEST 3: Get Student by ID

```
Step 1: Click: 03. Get Student by ID
├─ Method: GET
├─ URL: {{base_url}}/api/students/1
└─ No body needed

Step 2: Send Request
├─ Click [Send]
├─ Look for "200 OK"
└─ Response shows Alice's details
```

### TEST 4: Update Student

```
Step 1: Click: 04. Update Student
├─ Select Body tab
├─ Select raw JSON
└─ Paste:

{
  "name": "Alice Johnson Updated",
  "phone": "9876543210",
  "department": "Data Science"
}

Step 2: Send Request
├─ Click [Send]
├─ Look for "200 OK"
└─ Response shows updated data
```

### TEST 5: Auth Register

```
Step 1: Click: Auth Service → 06. Auth Register
├─ Select Body tab
├─ Select raw JSON
└─ Paste:

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Step 2: Send Request
├─ Click [Send]
├─ Look for "201 Created"
└─ Response shows user created
```

### TEST 6: Auth Login ⭐⭐ (KEY TEST)

```
Step 1: Click: Auth Service → 07. Auth Login
├─ Select Body tab
├─ Select raw JSON
└─ Paste:

{
  "email": "john@example.com",
  "password": "password123"
}

Step 2: Send Request
├─ Click [Send]
├─ Look for "200 OK"
└─ Response contains TOKEN

Step 3: COPY THE TOKEN!
┌─────────────────────────────────────────────┐
│ {                                           │
│   "success": true,                          │
│   "data": {                                 │
│     "token": "eyJhbGciOi..." ← COPY THIS   │
│   }                                         │
│ }                                           │
└─────────────────────────────────────────────┘

Step 4: Save Token to Environment
├─ Click Environments (left sidebar)
├─ Click "Laravel Microservices Local"
├─ Find "api_token" variable
├─ Paste token value
├─ Click [Save]
└─ Close environment tab

Step 5: Check Terminal 1 (Listener)
├─ You should see:
│  [NOTIFICATION SERVICE] UserLoggedIn Event Handler
│  ...
│  📧 Sending login confirmation email to john@example.com
│  IP Address: [user_ip]
│
└─ SUCCESS! Login email triggered ✉️
```

### TEST 7: Get Current User (Protected)

```
Step 1: Click: Auth Service → 08. Get Current User
├─ Make sure environment is still selected
├─ Headers should auto-include: Authorization: Bearer {{api_token}}
└─ No body needed

Step 2: Send Request
├─ Click [Send]
├─ Should see "200 OK"
├─ If 401, check:
│  ├─ Token is saved in environment
│  ├─ Environment is selected
│  └─ Token from Test 6 is correct
└─ Response shows user data
```

### TEST 8: Auth Logout

```
Step 1: Click: Auth Service → 09. Auth Logout
├─ Method: POST
├─ Headers include token automatically
└─ No body needed

Step 2: Send Request
├─ Click [Send]
├─ Should see "200 OK"
└─ Response: "User logged out successfully"
```

### TEST 9: Delete Student

```
Step 1: Click: Student Service → 05. Delete Student
├─ Method: DELETE
├─ URL: {{base_url}}/api/students/1
└─ No body needed

Step 2: Send Request
├─ Click [Send]
├─ Should see "200 OK"
└─ Response: "Student deleted successfully"
```

---

## Expected Results Summary

| Test # | Endpoint | Status | Email | Notes |
|--------|----------|--------|-------|-------|
| 1 | POST signup | 201 ✅ | ✉️ YES | See Terminal 1 |
| 2 | GET students | 200 ✅ | ❌ | Lists all |
| 3 | GET {id} | 200 ✅ | ❌ | Single record |
| 4 | PUT {id} | 200 ✅ | ❌ | Updated data |
| 5 | POST register | 201 ✅ | ❌ | User created |
| 6 | POST login | 200 ✅ | ✉️ YES | **Save token!** See Terminal 1 |
| 7 | GET me | 200 ✅ | ❌ | Need token |
| 8 | POST logout | 200 ✅ | ❌ | Need token |
| 9 | DELETE {id} | 200 ✅ | ❌ | Record removed |

---

## Terminal 1 Output (What You'll See)

### After Test 1 (Signup):
```
┌─────────────────────────────────────────────────────────┐
│ [NOTIFICATION SERVICE] StudentSignedUp Event Handler   │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
│ Processing: Student Registration Signup               │
│ Student ID: 1                                         │
│ Name: Alice Johnson                                   │
│ Email: alice@example.com                              │
│ Registration Number: REG-2026-001                     │
│                                                       │
│ Actions:                                              │
│   ✓ Sending welcome email to new student              │
│   ✓ Adding student to mailing list                    │
│   ✓ Creating notification preferences                 │
│   ✓ Scheduling orientation emails                     │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
│   📧 Sending welcome email                            │
│      To: alice@example.com                            │
│      Subject: Welcome to Our Student Portal!          │
│   📝 Logged notification: student_signup for ID 1    │
└─────────────────────────────────────────────────────────┘
```

### After Test 6 (Login):
```
┌─────────────────────────────────────────────────────────┐
│ [NOTIFICATION SERVICE] UserLoggedIn Event Handler     │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
│ Processing: User Login Notification                  │
│ User ID: 1                                           │
│ Name: John Doe                                       │
│ Email: john@example.com                              │
│ Login Time: 2026-02-19 12:05:00                       │
│ IP Address: 172.19.0.1                               │
│                                                      │
│ Actions:                                             │
│   ✓ Sending login confirmation email                 │
│   ✓ Checking for suspicious activity                 │
│   ✓ Logging login event                              │
│   ✓ Updating user activity status                    │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
│   📧 Sending login confirmation email                │
│      To: john@example.com                            │
│      Subject: Login Confirmation                     │
│      Dear John Doe, your account was accessed at     │
│      2026-02-19 12:05:00 from IP: 172.19.0.1        │
│   📝 Logged notification: user_login for ID 1       │
└─────────────────────────────────────────────────────────┘
```

---

## Troubleshooting Guide

### ❌ "Connection refused" or "Cannot GET"
```
Problem: Cannot connect to server
Solution:
1. Check containers: docker compose ps
2. Restart gateway: docker compose restart gateway
3. Wait 10 seconds
4. Try again
```

### ❌ "404 Not Found"
```
Problem: Endpoint not found
Solution:
1. Check URL spelling
2. Verify {{base_url}} is correct: http://localhost:8000
3. Restart gateway
```

### ❌ "Validation failed" errors
```
Problem: Required field missing
Solution:
1. Check all required fields in payload
2. Verify no typos
3. Ensure JSON is valid (paste into jsonlint.com)
```

### ❌ "401 Unauthorized" on protected endpoints
```
Problem: Token missing or invalid
Solution:
1. Run Test 6 (Login) first
2. Copy token from response
3. Paste into environment variable "api_token"
4. Make sure environment is selected
5. Try again
```

### ❌ No email showing in Terminal 1
```
Problem: Email notification not appearing
Solution:
1. Check Terminal 1 is running listener
2. Verify Kafka is running: docker compose ps kafka
3. Restart listener: docker exec notification php artisan listen:student-events
4. Run test again
```

---

## Postman Tips & Tricks

### Auto-fill URLs with Variables
```
Instead of: http://localhost:8000
Use: {{base_url}}

Instead of: Authorization: Bearer token123
Use: Authorization: Bearer {{api_token}}
```

### Save Responses
```
1. Send request
2. Click "Save as Example" under response
3. Use for reference later
```

### Test History
```
Click History (left sidebar) to see all previous requests
Useful for re-running tests
```

### Collections
```
Collections let you organize related requests
All endpoints grouped by service (Student, Auth)
```

---

## Complete Test Timeline

```
Total Time: ~10 minutes

0:00  - Start listener (Terminal 1)
1:00  - Test 1: Student Signup
       └─ Check Terminal 1 for email ✉️
2:00  - Test 2: Get All Students
3:00  - Test 3: Get Student by ID
4:00  - Test 4: Update Student
5:00  - Test 5: Auth Register
6:00  - Test 6: Auth Login
       └─ SAVE TOKEN
       └─ Check Terminal 1 for email ✉️
7:00  - Test 7: Get Current User
8:00  - Test 8: Auth Logout
9:00  - Test 9: Delete Student
10:00 - All tests complete! ✅
```

---

## Success Checklist

After running all tests:

- [ ] Test 1: Student signup returns 201
- [ ] Terminal 1: Shows StudentSignedUp email
- [ ] Test 2: Get students returns 200 with data
- [ ] Test 3: Get single student returns 200
- [ ] Test 4: Update student returns 200
- [ ] Test 5: Register user returns 201
- [ ] Test 6: Login returns 200 with token
- [ ] Terminal 1: Shows UserLoggedIn email
- [ ] Test 7: Get me returns 200 (with token)
- [ ] Test 8: Logout returns 200
- [ ] Test 9: Delete returns 200

**All ✅? PERFECT! System working! 🎉**

---

## Next Steps

After successful testing:

1. ✅ All tests pass
2. 🔄 Integrate real email service (SendGrid/Mailgun)
3. 🔄 Add email templates
4. 🔄 Deploy to staging
5. 🔄 Production deployment

---

**You're ready! Start with Postman now!** 🚀
