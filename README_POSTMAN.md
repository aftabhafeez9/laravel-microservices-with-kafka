# 🚀 Postman Testing - START HERE

## What You Have

✅ **Postman Collection** - Ready to import (`Postman-Collection.json`)
✅ **9 API Endpoints** - All configured and ready
✅ **Complete Documentation** - Step-by-step guides
✅ **Email Notifications** - Real-time monitoring
✅ **Error Testing** - Validation and auth examples

---

## 30-Second Quick Start

1. **Download Postman**
   - Go to postman.com/downloads
   - Install it

2. **Import Collection**
   - Open Postman
   - Click Import
   - Upload `Postman-Collection.json`

3. **Start Testing**
   - Run any of the 9 endpoints
   - See responses instantly
   - Check Terminal 1 for email notifications

---

## Documentation (Pick One)

### 🟢 Complete Beginner? 
→ **[POSTMAN_STEP_BY_STEP.md](POSTMAN_STEP_BY_STEP.md)**
- Visual step-by-step guide
- Screenshots and diagrams  
- Perfect for first-time users
- Time: 10 minutes

### 🟡 Want Quick Reference?
→ **[POSTMAN_PAYLOADS.md](POSTMAN_PAYLOADS.md)**
- All payloads ready to copy-paste
- All expected responses
- Status codes and errors
- Time: 2 minutes

### 🔴 Need Complete Setup?
→ **[POSTMAN_TESTING_GUIDE.md](POSTMAN_TESTING_GUIDE.md)**
- Full setup instructions
- Environment configuration
- Complete workflow
- Time: 15 minutes

### 🔵 Want Overview?
→ **[POSTMAN_COMPLETE.md](POSTMAN_COMPLETE.md)**
- Complete reference guide
- File navigation
- Links to everything
- Time: 5 minutes

---

## 9 API Endpoints Included

### Student Service (5 endpoints)
```
1. POST   /api/students/signup          ✉️ Triggers email
2. GET    /api/students                  
3. GET    /api/students/{id}             
4. PUT    /api/students/{id}             
5. DELETE /api/students/{id}             
```

### Auth Service (4 endpoints)
```
6. POST   /api/auth/register
7. POST   /api/auth/login               ✉️ Triggers email
8. GET    /api/auth/me                  🔐 Protected
9. POST   /api/auth/logout              🔐 Protected
```

---

## What to Expect

### Test 1: Student Signup
```
Send request → 201 Created
Check Terminal 1 → See welcome email ✉️
```

### Tests 2-5: Student CRUD
```
Get all → 200 OK
Get one → 200 OK
Update → 200 OK
Delete → 200 OK
```

### Test 6: Auth Register
```
Send request → 201 Created
```

### Test 7: Auth Login ⭐
```
Send request → 200 OK + TOKEN
Check Terminal 1 → See login email ✉️
SAVE TOKEN → Use for protected endpoints
```

### Tests 8-9: Protected Routes
```
Get me → 200 OK (with token)
Logout → 200 OK (with token)
```

---

## File Quick Reference

| File | What | When |
|------|------|------|
| **Postman-Collection.json** | Import this | RIGHT NOW |
| **POSTMAN_STEP_BY_STEP.md** | Read this | First time |
| **POSTMAN_PAYLOADS.md** | Copy from this | While testing |
| **POSTMAN_TESTING_GUIDE.md** | Reference this | Full setup |
| **POSTMAN_COMPLETE.md** | Overview | Navigation |

---

## Pre-Test Checklist

Before you start:

- [ ] Postman installed
- [ ] `docker compose ps` shows all containers "Up"
- [ ] Terminal 1 running: `docker exec notification php artisan listen:student-events --timeout=180000`
- [ ] Collection imported into Postman
- [ ] Environment created with `base_url = http://localhost:8000`

---

## The Test (5 Steps)

### Step 1: Import Collection
1. Open Postman
2. Click Import
3. Upload `Postman-Collection.json`
4. ✅ Done

### Step 2: Create Environment
1. Click Environments (left)
2. Click Create
3. Add: `base_url = http://localhost:8000`
4. ✅ Done

### Step 3: Select Environment
1. Top-right dropdown
2. Select `Laravel Microservices Local`
3. ✅ Done

### Step 4: Run Tests 1-6
1. Student Service → Test 1-5
2. Auth Service → Test 6
3. ✅ Check responses

### Step 5: Save Token & Run Tests 7-9
1. Copy token from Test 7
2. Paste into Environment `api_token`
3. Run Tests 8-9
4. ✅ All working!

---

## Email Notifications

### After Student Signup (Test 1):
You'll see in Terminal 1:
```
[NOTIFICATION SERVICE] StudentSignedUp Event Handler
...
📧 Sending welcome email to alice@example.com
```

### After User Login (Test 7):
You'll see in Terminal 1:
```
[NOTIFICATION SERVICE] UserLoggedIn Event Handler
...
📧 Sending login confirmation email to john@example.com
IP Address: [user_ip]
```

---

## Status Codes

| Code | Meaning | Tests |
|------|---------|-------|
| **201** | Created | Tests 1, 6 |
| **200** | OK | Tests 2, 3, 4, 5, 7, 8, 9 |
| **422** | Validation Error | Missing required fields |
| **401** | Unauthorized | Bad credentials |
| **404** | Not Found | Wrong endpoint |

---

## Common Issues & Fixes

| Issue | Fix |
|-------|-----|
| Can't connect to localhost | Check: `docker compose ps` |
| 404 Not Found | Restart gateway: `docker compose restart gateway` |
| No email in Terminal 1 | Restart listener |
| Token not working | Save token to environment variable |
| Validation errors | Check payload spelling |

---

## Timeline

```
⏱️  2 min: Download & install Postman
⏱️  1 min: Import collection
⏱️  2 min: Setup environment
⏱️ 10 min: Run all 9 tests
⏱️  2 min: Verify email notifications
─────────────
⏱️ 17 min: Total
```

---

## What You're Testing

✅ **Student Registration** → Works → Email sent  
✅ **User Authentication** → Login works → Token generated  
✅ **Protected Routes** → Token auth works  
✅ **CRUD Operations** → All work  
✅ **Email Notifications** → Real-time delivery  
✅ **Error Handling** → Validation works  

---

## After Testing

1. ✅ All 9 tests pass
2. ✅ Email notifications work
3. 🔄 Now integrate real email service
4. 🔄 Add email templates
5. 🔄 Deploy to production

---

## Need Help?

- **I'm completely new to Postman?**
  → Read [POSTMAN_STEP_BY_STEP.md](POSTMAN_STEP_BY_STEP.md)

- **I want to copy payloads?**
  → Go to [POSTMAN_PAYLOADS.md](POSTMAN_PAYLOADS.md)

- **I need complete setup guide?**
  → Read [POSTMAN_TESTING_GUIDE.md](POSTMAN_TESTING_GUIDE.md)

- **I want to understand system?**
  → Check [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

---

## Your Next Action

1. **RIGHT NOW:** Download Postman from postman.com
2. **THEN:** Open `Postman-Collection.json` in this folder
3. **CLICK:** Import
4. **SUCCESS!** All 9 endpoints ready to test

---

## Key Takeaways

✅ **9 endpoints** ready to test
✅ **2 email triggers** (signup & login)
✅ **Complete payloads** provided
✅ **Full documentation** included
✅ **5-minute setup** time
✅ **Real results** immediately

---

**You're all set! Open Postman and import the collection now!** 🎉

Need guidance? Pick a document above and start reading.
Want to jump in? Import the collection and run Test 1!

---

*Last updated: February 19, 2026*  
*Status: ✅ Ready for testing*
