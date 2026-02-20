# ✅ KAFKA IS NOW FIXED - COMPLETE TESTING GUIDE

## ✅ What Was Fixed

**Problem:** Kafka container wasn't running
**Solution:** `docker compose restart`  
**Status:** ✅ NOW WORKING

---

## 🎯 Start Testing Now

### Step 1: Start Listener (Terminal 1)
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

Listening for messages...
```

✅ NO MORE ERRORS!

---

## Step 2: Test Student Signup (Terminal 2)

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

**Response:**
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

---

## Step 3: Check Terminal 1 for Email Notification ✉️

**Terminal 1 will show:**
```
✓ Message received:
  Event: StudentSignedUp
  Student ID: 1
  Name: Alice Johnson
  Email: alice@example.com
  Timestamp: 2026-02-19 XX:XX:XX

[NOTIFICATION SERVICE] StudentSignedUp Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: Student Registration Signup
Student ID: 1
Name: Alice Johnson
Email: alice@example.com
Registration Number: REG-2026-001
Department: Computer Science

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

✅ **SUCCESS! Email notification triggered!**

---

## Step 4: Test User Login (Terminal 2)

First, register a user:
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

Then login:
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Response:**
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

---

## Step 5: Check Terminal 1 for Login Email ✉️

**Terminal 1 will show:**
```
✓ Message received:
  Event: UserLoggedIn
  User ID: 1
  Name: John Doe
  Email: john@example.com
  IP Address: 172.19.0.1
  Timestamp: 2026-02-19 XX:XX:XX

[NOTIFICATION SERVICE] UserLoggedIn Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: User Login Notification
User ID: 1
Name: John Doe
Email: john@example.com
Login Time: 2026-02-19 XX:XX:XX
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
     Dear John Doe, your account was accessed at 2026-02-19 XX:XX:XX from IP: 172.19.0.1
  📝 Logged notification: user_login for ID 1
```

✅ **SUCCESS! Login email notification triggered!**

---

## 🎉 Complete Test Suite

### Use Postman for Full Testing

1. **Import Collection:** `Postman-Collection.json`
2. **Setup Environment:** `base_url = http://localhost:8000`
3. **Select Environment**
4. **Run all 9 tests**
5. **Monitor Terminal 1 for emails**

---

## ✅ Verification Checklist

After running tests:

- [ ] Listener shows "Listening for messages..." (no errors)
- [ ] Student signup returns 201 Created
- [ ] Terminal 1 shows StudentSignedUp email ✉️
- [ ] Auth login returns 200 OK + token
- [ ] Terminal 1 shows UserLoggedIn email ✉️
- [ ] All tests pass

**All checked? PERFECT! System is working!** 🎉

---

## Quick Reference

| What | Command |
|------|---------|
| Start Listener | `docker exec notification php artisan listen:student-events --timeout=180000` |
| Test Signup | `curl -X POST http://localhost:8000/api/students/signup ...` |
| Test Login | `curl -X POST http://localhost:8000/api/auth/login ...` |
| Check Kafka | `docker exec kafka kafka-topics --list --bootstrap-server kafka:9092` |
| View Logs | `docker compose logs kafka` |

---

## Troubleshooting

### Still getting "Failed to resolve kafka:9092"?
```bash
docker compose restart
sleep 10
docker exec notification php artisan listen:student-events --timeout=180000
```

### Listener won't start?
```bash
docker compose logs notification | tail -50
```

### Kafka not responding?
```bash
docker compose restart zookeeper kafka
sleep 15
```

---

## Now Go Test!

1. **Terminal 1:** Run listener command
2. **Terminal 2:** Run test commands
3. **Terminal 1:** Watch emails appear! ✉️
4. **Terminal 2:** Use curl or Postman to test

---

**Everything is ready! Start testing now!** 🚀

See [POSTMAN_STEP_BY_STEP.md](POSTMAN_STEP_BY_STEP.md) for detailed Postman guide.
