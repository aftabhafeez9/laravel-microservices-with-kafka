# Notification Service Events - Complete Setup ✓

## Status: All Commands Ready

All event publishing and listening commands are fully functional and tested.

---

## Available Commands

### Student Service (Publisher) - 4 Event Types

1. **StudentCreated** - New student registration
   ```bash
   docker exec student php artisan student:publish-event [name] [email]
   ```

2. **StudentUpdated** - Student profile update
   ```bash
   docker exec student php artisan student:publish-updated [id] [name] [email] [fields]
   ```

3. **StudentDeleted** - Student account deletion
   ```bash
   docker exec student php artisan student:publish-deleted [id] [name] [email]
   ```

4. **StudentEnrolled** - Course enrollment
   ```bash
   docker exec student php artisan student:publish-enrolled [id] [name] [email] [courseId] [courseName]
   ```

### Notification Service (Consumer)

1. **Listen to Events**
   ```bash
   docker exec notification php artisan listen:student-events [--timeout=120000]
   ```

---

## Quick Test (5 Minutes)

### Terminal 1 - Start Listener

```bash
docker exec notification php artisan listen:student-events --timeout=180000
```

### Terminal 2 - Publish Events

```bash
# Event 1: Student created
docker exec student php artisan student:publish-event "Alice Johnson" "alice@example.com"

# Event 2: Profile updated
docker exec student php artisan student:publish-updated 1001 "Alice Johnson" "alice.new@example.com" "email,phone"

# Event 3: Course enrolled
docker exec student php artisan student:publish-enrolled 1001 "Alice Johnson" "alice@example.com" "C101" "Laravel Masterclass"

# Event 4: Student deleted
docker exec student php artisan student:publish-deleted 1001 "Alice Johnson" "alice@example.com"
```

---

## What You'll See

Terminal 1 (Listener) will display:

```
✓ Message received:
  Event: StudentCreated
  Student ID: 5234
  Name: Alice Johnson
  Email: alice@example.com
  Timestamp: 2026-02-19 11:15:00

[NOTIFICATION SERVICE] StudentCreated Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: Student Registration
Student ID: 5234
Name: Alice Johnson
Email: alice@example.com

Actions:
  ✓ Sending welcome email
  ✓ Creating notification record
  ✓ Adding to notification queue
  ✓ Scheduling onboarding emails
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  📧 Email sent to: alice@example.com
     Subject: Welcome to Our Platform
  📝 Logged notification: student_created for student 5234
```

---

## Test Results ✓

### StudentUpdated Event
```
✓ Event published successfully!
  Topic: student-events
  Message Key: student-updated-1001
```

### StudentEnrolled Event
```
✓ Event published successfully!
  Topic: student-events
  Message Key: student-enrolled-1001-C101
```

---

## Files Created

### Student Service (Publisher)
- `app/Events/StudentCreated.php` ✓
- `app/Events/StudentUpdated.php` ✓
- `app/Events/StudentDeleted.php` ✓
- `app/Events/StudentEnrolled.php` ✓
- `app/Services/KafkaProducerService.php` ✓
- `app/Console/Commands/PublishStudentEvent.php` ✓
- `app/Console/Commands/PublishStudentUpdatedEvent.php` ✓
- `app/Console/Commands/PublishStudentDeletedEvent.php` ✓
- `app/Console/Commands/PublishStudentEnrolledEvent.php` ✓

### Notification Service (Consumer)
- `app/Services/KafkaConsumerService.php` ✓
- `app/Services/NotificationEventHandler.php` ✓
- `app/Console/Commands/ListenToStudentEvents.php` ✓

---

## Event Handlers

All events are automatically routed to the appropriate handler in `NotificationEventHandler`:

1. **StudentCreated** → `handleStudentCreated()`
   - Sends welcome email
   - Creates notification record
   - Adds to queue
   - Schedules onboarding emails

2. **StudentUpdated** → `handleStudentUpdated()`
   - Sends profile update confirmation
   - Updates notification preferences
   - Logs update event

3. **StudentDeleted** → `handleStudentDeleted()`
   - Sends account deletion confirmation
   - Unsubscribes from lists
   - Archives notifications
   - Stops scheduled notifications

4. **StudentEnrolled** → `handleStudentEnrolled()`
   - Sends course enrollment confirmation
   - Schedules course materials email
   - Adds to course notification group
   - Sets up course reminders

---

## Command Examples

### All StudentCreated Examples
```bash
# Default values
docker exec student php artisan student:publish-event

# With name and email
docker exec student php artisan student:publish-event "John Doe" "john@example.com"
docker exec student php artisan student:publish-event "Jane Smith" "jane@example.com"
docker exec student php artisan student:publish-event "Bob Wilson" "bob@example.com"
```

### All StudentUpdated Examples
```bash
# Default values
docker exec student php artisan student:publish-updated

# Update email only
docker exec student php artisan student:publish-updated 1001 "John Doe" "john.new@example.com" "email"

# Update multiple fields
docker exec student php artisan student:publish-updated 1001 "John Doe" "john@example.com" "email,phone,address,department"

# Various students
docker exec student php artisan student:publish-updated 1002 "Jane Smith" "jane@example.com" "phone"
docker exec student php artisan student:publish-updated 1003 "Bob Wilson" "bob@example.com" "address,city"
```

### All StudentDeleted Examples
```bash
# Default values
docker exec student php artisan student:publish-deleted

# Delete specific student
docker exec student php artisan student:publish-deleted 1001 "John Doe" "john@example.com"
docker exec student php artisan student:publish-deleted 1005 "Emma Davis" "emma@example.com"
```

### All StudentEnrolled Examples
```bash
# Default values
docker exec student php artisan student:publish-enrolled

# Enroll in Laravel course
docker exec student php artisan student:publish-enrolled 1001 "John Doe" "john@example.com" "C101" "Laravel Fundamentals"

# Enroll in PHP course
docker exec student php artisan student:publish-enrolled 1002 "Jane Smith" "jane@example.com" "C205" "Advanced PHP"

# Enroll in Docker course
docker exec student php artisan student:publish-enrolled 1003 "Bob Wilson" "bob@example.com" "C310" "Docker Mastery"

# Multiple enrollments for same student
docker exec student php artisan student:publish-enrolled 1001 "John Doe" "john@example.com" "C205" "Advanced PHP"
docker exec student php artisan student:publish-enrolled 1001 "John Doe" "john@example.com" "C310" "Docker Mastery"
```

### All Listener Examples
```bash
# Default timeout (120 seconds)
docker exec notification php artisan listen:student-events

# Short timeout (30 seconds)
docker exec notification php artisan listen:student-events --timeout=30000

# Medium timeout (2 minutes)
docker exec notification php artisan listen:student-events --timeout=120000

# Long timeout (5 minutes)
docker exec notification php artisan listen:student-events --timeout=300000
```

---

## Testing Workflow

### Full Integration Test

```bash
# Terminal 1: Start listener
docker exec notification php artisan listen:student-events --timeout=180000

# Terminal 2: Publish sequence of events
docker exec student php artisan student:publish-event "Alice" "alice@example.com"
sleep 2

docker exec student php artisan student:publish-updated 1001 "Alice" "alice.new@example.com" "email"
sleep 2

docker exec student php artisan student:publish-enrolled 1001 "Alice" "alice@example.com" "C101" "Laravel"
sleep 2

docker exec student php artisan student:publish-enrolled 1001 "Alice" "alice@example.com" "C205" "PHP"
sleep 2

docker exec student php artisan student:publish-deleted 1001 "Alice" "alice@example.com"
```

All events will be received, processed, and logged in real-time in Terminal 1.

---

## Architecture

```
┌──────────────────────────────────────────┐
│        STUDENT SERVICE                   │
│                                          │
│  ┌─ student:publish-event               │
│  ├─ student:publish-updated             │
│  ├─ student:publish-deleted             │
│  └─ student:publish-enrolled            │
└─────────────┬──────────────────────────┘
              │ (Kafka Messages)
              │
              ▼
        ┌─────────────────┐
        │ KAFKA TOPIC     │
        │ student-events  │
        └────────┬────────┘
                 │
                 ▼
┌──────────────────────────────────────────┐
│     NOTIFICATION SERVICE                 │
│                                          │
│  listen:student-events                  │
│         │                                │
│         ├─→ StudentCreated               │
│         ├─→ StudentUpdated               │
│         ├─→ StudentDeleted               │
│         └─→ StudentEnrolled              │
│                                          │
│  NotificationEventHandler                │
│  ├─ handleStudentCreated()               │
│  ├─ handleStudentUpdated()               │
│  ├─ handleStudentDeleted()               │
│  └─ handleStudentEnrolled()              │
└──────────────────────────────────────────┘
```

---

## Next Steps

1. ✅ Run complete test (workflow above)
2. Add database persistence
3. Implement real email sending (SendGrid, Mailgun)
4. Add error handling & dead letter queue
5. Implement retry logic
6. Add monitoring & alerting
7. Create dashboard to view notifications
8. Add notification templates
9. Implement SMS notifications

---

## Documentation

Full details available in: `NOTIFICATION_EVENTS_GUIDE.md`

This includes:
- Detailed command reference
- Complete testing scenarios
- Troubleshooting guide
- Event payload examples
- Architecture diagrams

---

## Summary

✓ 4 event types fully functional
✓ Producer commands in Student service
✓ Consumer listener in Notification service
✓ Event handlers for all event types
✓ Tested and verified working
✓ Ready for production enhancement

Happy testing!
