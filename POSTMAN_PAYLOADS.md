# Postman Payloads - Ready to Copy & Paste

## Quick Copy-Paste Guide

Just copy each payload below and paste into Postman body (set to `raw` JSON).

---

## 1️⃣ Student Signup

**URL:**
```
POST http://localhost:8000/api/students/signup
```

**Headers:**
```
Content-Type: application/json
```

**Payload:**
```json
{
  "name": "Alice Johnson",
  "email": "alice@example.com",
  "phone": "1234567890",
  "registration_number": "REG-2026-001",
  "department": "Computer Science"
}
```

**Expected Response (201):**
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

---

## 2️⃣ Get All Students

**URL:**
```
GET http://localhost:8000/api/students
```

**Headers:**
```
Content-Type: application/json
```

**No Payload Required**

**Expected Response (200):**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Alice Johnson",
        "email": "alice@example.com",
        "phone": "1234567890",
        "registration_number": "REG-2026-001",
        "department": "Computer Science",
        "status": "active",
        "email_verified_at": null,
        "created_at": "2026-02-19 12:00:00",
        "updated_at": "2026-02-19 12:00:00"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "total": 1
  }
}
```

---

## 3️⃣ Get Student by ID

**URL:**
```
GET http://localhost:8000/api/students/1
```

**Headers:**
```
Content-Type: application/json
```

**No Payload Required**

**Expected Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "phone": "1234567890",
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

## 4️⃣ Update Student

**URL:**
```
PUT http://localhost:8000/api/students/1
```

**Headers:**
```
Content-Type: application/json
```

**Payload:**
```json
{
  "name": "Alice Johnson Updated",
  "phone": "9876543210",
  "department": "Data Science"
}
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Student updated successfully",
  "data": {
    "id": 1,
    "name": "Alice Johnson Updated",
    "phone": "9876543210",
    "registration_number": "REG-2026-001",
    "department": "Data Science",
    "status": "active",
    "email_verified_at": null,
    "created_at": "2026-02-19 12:00:00",
    "updated_at": "2026-02-19 12:05:00"
  }
}
```

---

## 5️⃣ Delete Student

**URL:**
```
DELETE http://localhost:8000/api/students/1
```

**Headers:**
```
Content-Type: application/json
```

**No Payload Required**

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Student deleted successfully"
}
```

---

## 6️⃣ Auth Register

**URL:**
```
POST http://localhost:8000/api/auth/register
```

**Headers:**
```
Content-Type: application/json
```

**Payload:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Expected Response (201):**
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

## 7️⃣ Auth Login ⭐ (Triggers Email)

**URL:**
```
POST http://localhost:8000/api/auth/login
```

**Headers:**
```
Content-Type: application/json
```

**Payload:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "User logged in successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiaWF0IjoxNjQ2MTU5MzAwfQ.signature"
  }
}
```

**⚠️ IMPORTANT: Copy the token value and save it!**

---

## 8️⃣ Get Current User (Protected)

**URL:**
```
GET http://localhost:8000/api/auth/me
```

**Headers:**
```
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

**Replace `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` with the token from Test 7**

**No Payload Required**

**Expected Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2026-02-19 12:05:00",
    "updated_at": "2026-02-19 12:05:00"
  }
}
```

---

## 9️⃣ Auth Logout

**URL:**
```
POST http://localhost:8000/api/auth/logout
```

**Headers:**
```
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

**Replace with your actual token**

**No Payload Required**

**Expected Response (200):**
```json
{
  "success": true,
  "message": "User logged out successfully"
}
```

---

## Test Data Variations

### Alternative Student Signup Payloads

**Tech Student:**
```json
{
  "name": "Bob Smith",
  "email": "bob@example.com",
  "phone": "5555555555",
  "registration_number": "REG-2026-002",
  "department": "Information Technology"
}
```

**Business Student:**
```json
{
  "name": "Carol White",
  "email": "carol@example.com",
  "phone": "4444444444",
  "registration_number": "REG-2026-003",
  "department": "Business Administration"
}
```

**Engineering Student:**
```json
{
  "name": "David Brown",
  "email": "david@example.com",
  "phone": "3333333333",
  "registration_number": "REG-2026-004",
  "department": "Mechanical Engineering"
}
```

### Alternative Auth Payloads

**User 2:**
```json
{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "password": "securepass456",
  "password_confirmation": "securepass456"
}
```

**User 3:**
```json
{
  "name": "Mike Johnson",
  "email": "mike@example.com",
  "password": "mypassword789",
  "password_confirmation": "mypassword789"
}
```

---

## Error Payloads (Test Error Handling)

### Missing Required Field
**URL:**
```
POST http://localhost:8000/api/students/signup
```

**Payload:**
```json
{
  "name": "Test User",
  "email": "test@example.com"
}
```

**Expected Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "phone": ["The phone field is required"],
    "registration_number": ["The registration_number field is required"],
    "department": ["The department field is required"]
  }
}
```

---

### Duplicate Email
**URL:**
```
POST http://localhost:8000/api/students/signup
```

**Payload:**
```json
{
  "name": "Alice Johnson",
  "email": "alice@example.com",
  "phone": "1111111111",
  "registration_number": "REG-2026-DUP",
  "department": "CS"
}
```

**Expected Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken"]
  }
}
```

---

### Invalid Credentials
**URL:**
```
POST http://localhost:8000/api/auth/login
```

**Payload:**
```json
{
  "email": "john@example.com",
  "password": "wrongpassword"
}
```

**Expected Response (401):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

### Invalid Token
**URL:**
```
GET http://localhost:8000/api/auth/me
```

**Headers:**
```
Authorization: Bearer invalid-token-here
```

**Expected Response (401):**
```json
{
  "message": "Unauthenticated"
}
```

---

## Postman Collection Structure

```
📦 Postman Collection
│
├─ 📁 Student Service
│  ├─ 01. Student Signup
│  ├─ 02. Get All Students
│  ├─ 03. Get Student by ID
│  ├─ 04. Update Student
│  └─ 05. Delete Student
│
├─ 📁 Auth Service
│  ├─ 06. Auth Register
│  ├─ 07. Auth Login
│  ├─ 08. Get Current User
│  └─ 09. Auth Logout
│
└─ 📁 Kafka Events
   └─ 10. Publish Student Event (terminal only)
```

---

## Status Codes Reference

| Code | Meaning | Example |
|------|---------|---------|
| 200 | OK | GET, PUT, POST logout |
| 201 | Created | POST signup, register |
| 401 | Unauthorized | Invalid token |
| 404 | Not Found | Non-existent ID |
| 422 | Validation Error | Missing required field |
| 500 | Server Error | Database connection failed |

---

## Quick Reference Card

| # | Endpoint | Method | Auth | Email |
|---|----------|--------|------|-------|
| 1 | /students/signup | POST | ❌ | ✅ |
| 2 | /students | GET | ❌ | ❌ |
| 3 | /students/{id} | GET | ❌ | ❌ |
| 4 | /students/{id} | PUT | ❌ | ❌ |
| 5 | /students/{id} | DELETE | ❌ | ❌ |
| 6 | /auth/register | POST | ❌ | ❌ |
| 7 | /auth/login | POST | ❌ | ✅ |
| 8 | /auth/me | GET | ✅ | ❌ |
| 9 | /auth/logout | POST | ✅ | ❌ |

---

**Copy any payload above and test immediately in Postman!** 🚀
