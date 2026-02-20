# Notification Service Event Commands - Complete Guide

## Overview

The notification service now listens to and processes 4 different student events from Kafka:
1. **StudentCreated** - Send welcome emails
2. **StudentUpdated** - Send profile update confirmations
3. **StudentDeleted** - Send account deletion confirmations
4. **StudentEnrolled** - Send course enrollment confirmations

---

## Event Processing Flow

```
Student Service (Publisher)         Kafka Topic              Notification Service (Consumer)
─────────────────────────────       ─────────────            ────────────────────────────
 • PublishStudentEvent              student-events           • ListenToStudentEvents
 • PublishStudentUpdatedEvent       (student-events)         • NotificationEventHandler
 • PublishStudentDeletedEvent                                  ├─ handleStudentCreated()
 • PublishStudentEnrolledEvent                                 ├─ handleStudentUpdated()
                                                                ├─ handleStudentDeleted()
                                                                └─ handleStudentEnrolled()
```

---

## Quick Start

### Terminal 1 - Start Notification Listener

```bash
docker exec notification php artisan listen:student-events --timeout=180000
```

**Output:**
```
╔════════════════════════════════════════════════════════════════╗
║       NOTIFICATION SERVICE - Student Events Listener           ║
╚════════════════════════════════════════════════════════════════╝

Configuration:
  • Kafka Broker: kafka:9092
  • Topic: student-events
  • Consumer Group: notification-service
  • Timeout: 180000ms (180s)

Listening for events...
```

### Terminal 2 - Publish Events

**Event 1: Student Created**
```bash
docker exec student php artisan student:publish-event "Alice Johnson" "alice@example.com"
```

**Event 2: Student Updated**
```bash
docker exec student php artisan student:publish-updated 1001 "Alice Johnson" "alice.johnson@example.com" "email,phone"
```

**Event 3: Student Enrolled**
```bash
docker exec student php artisan student:publish-enrolled 1001 "Alice Johnson" "alice@example.com" "C101" "Advanced Laravel"
```

**Event 4: Student Deleted**
```bash
docker exec student php artisan student:publish-deleted 1001 "Alice Johnson" "alice@example.com"
```

---

## Command Reference

### Listener Command

```bash
# Start listener (default 120 seconds)
docker exec notification php artisan listen:student-events

# Start listener with 180 second timeout
docker exec notification php artisan listen:student-events --timeout=180000

# Start listener with 5 minute timeout
docker exec notification php artisan listen:student-events --timeout=300000

# Start listener with 30 second timeout
docker exec notification php artisan listen:student-events --timeout=30000
```

---

## Publisher Commands

### 1. StudentCreated Event

```bash
# Default values
docker exec student php artisan student:publish-event

# Custom name and email
docker exec student php artisan student:publish-event "Jane Smith" "jane.smith@example.com"

# Examples
docker exec student php artisan student:publish-event "Bob Wilson" "bob@example.com"
docker exec student php artisan student:publish-event "Carol Davis" "carol@example.com"
```

**Output:**
```
Publishing StudentCreated event...
Student ID: 5234
Name: Jane Smith
Email: jane.smith@example.com
✓ Event published successfully to Kafka topic: student-events
```

---

### 2. StudentUpdated Event

```bash
# Syntax: student:publish-updated {id} {name} {email} {fields}

# Default values
docker exec student php artisan student:publish-updated

# Custom student update
docker exec student php artisan student:publish-updated 1001 "John Doe" "john.doe@example.com" "email,phone,address"

# Just email update
docker exec student php artisan student:publish-updated 1002 "Jane Smith" "jane.smith@new.com" "email"

# Multiple fields update
docker exec student php artisan student:publish-updated 1003 "Bob Wilson" "bob@example.com" "phone,address,department"
```

**Output:**
```
╔════════════════════════════════════════════════════════════════╗
║           Publishing StudentUpdated Event                     ║
╚════════════════════════════════════════════════════════════════╝

Event Details:
  • Student ID: 1001
  • Name: John Doe
  • Email: john.doe@example.com
  • Updated Fields: email, phone, address

✓ Event published successfully!
  Topic: student-events
  Message Key: student-updated-1001
```

---

### 3. StudentDeleted Event

```bash
# Syntax: student:publish-deleted {id} {name} {email}

# Default values
docker exec student php artisan student:publish-deleted

# Delete specific student
docker exec student php artisan student:publish-deleted 1001 "John Doe" "john@example.com"

# Delete another student
docker exec student php artisan student:publish-deleted 1005 "Emma Wilson" "emma@example.com"
```

**Output:**
```
╔════════════════════════════════════════════════════════════════╗
║           Publishing StudentDeleted Event                     ║
╚════════════════════════════════════════════════════════════════╝

Event Details:
  • Student ID: 1001
  • Name: John Doe
  • Email: john@example.com

⚠️  Account Deletion - This action is irreversible

✓ Event published successfully!
  Topic: student-events
  Message Key: student-deleted-1001
```

---

### 4. StudentEnrolled Event

```bash
# Syntax: student:publish-enrolled {id} {name} {email} {courseId} {courseName}

# Default values
docker exec student php artisan student:publish-enrolled

# Enroll in Laravel Basics
docker exec student php artisan student:publish-enrolled 1001 "John Doe" "john@example.com" "C101" "Laravel Basics"

# Enroll in Advanced PHP
docker exec student php artisan student:publish-enrolled 1002 "Jane Smith" "jane@example.com" "C205" "Advanced PHP"

# Enroll in Docker Mastery
docker exec student php artisan student:publish-enrolled 1003 "Bob Wilson" "bob@example.com" "C310" "Docker Mastery"
```

**Output:**
```
╔════════════════════════════════════════════════════════════════╗
║           Publishing StudentEnrolled Event                    ║
╚════════════════════════════════════════════════════════════════╝

Event Details:
  • Student ID: 1001
  • Name: John Doe
  • Email: john@example.com
  • Course ID: C101
  • Course Name: Laravel Basics

✓ Event published successfully!
  Topic: student-events
  Message Key: student-enrolled-1001-C101
```

---

## Complete Testing Scenario

### Step 1: Start the listener (Terminal 1)

```bash
docker exec notification php artisan listen:student-events --timeout=180000
```

### Step 2: Publish multiple events (Terminal 2)

```bash
# Event 1: Student registration
docker exec student php artisan student:publish-event "Alice Johnson" "alice@example.com"

# Wait 2 seconds, Event 2: Profile update
sleep 2
docker exec student php artisan student:publish-updated 1001 "Alice Johnson" "alice.j@example.com" "email,phone"

# Wait 2 seconds, Event 3: Course enrollment
sleep 2
docker exec student php artisan student:publish-enrolled 1001 "Alice Johnson" "alice@example.com" "C101" "Laravel Masterclass"

# Wait 2 seconds, Event 4: Another course enrollment
sleep 2
docker exec student php artisan student:publish-enrolled 1001 "Alice Johnson" "alice@example.com" "C205" "Docker & Kubernetes"
```

### Expected Listener Output

Terminal 1 will show:

```
[NOTIFICATION SERVICE] StudentCreated Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: Student Registration
Student ID: 1001
Name: Alice Johnson
Email: alice@example.com
Time: 2026-02-19 11:15:00

Actions:
  ✓ Sending welcome email
  ✓ Creating notification record
  ✓ Adding to notification queue
  ✓ Scheduling onboarding emails
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  📧 Email sent to: alice@example.com
     Subject: Welcome to Our Platform
  📝 Logged notification: student_created for student 1001

[NOTIFICATION SERVICE] StudentUpdated Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: Student Profile Update
Student ID: 1001
Name: Alice Johnson
Email: alice.j@example.com
Updated Fields: email, phone
Time: 2026-02-19 11:15:02

Actions:
  ✓ Sending profile update confirmation email
  ✓ Updating notification preferences
  ✓ Logging update event
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  📧 Email sent to: alice.j@example.com
     Subject: Profile Updated
  📝 Logged notification: student_updated for student 1001

[NOTIFICATION SERVICE] StudentEnrolled Event Handler
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Processing: Course Enrollment
Student ID: 1001
Name: Alice Johnson
Email: alice@example.com
Course: Laravel Masterclass (ID: C101)
Time: 2026-02-19 11:15:04

Actions:
  ✓ Sending course enrollment confirmation
  ✓ Scheduling course materials email
  ✓ Adding to course notification group
  ✓ Setting up course reminders
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  📧 Email sent to: alice@example.com
     Subject: Welcome to Laravel Masterclass
  📝 Logged notification: student_enrolled for student 1001
```

---

## Event Payloads

### StudentCreated Payload
```json
{
  "event": "StudentCreated",
  "student_id": 1001,
  "student_name": "Alice Johnson",
  "student_email": "alice@example.com",
  "timestamp": "2026-02-19 11:15:00"
}
```

### StudentUpdated Payload
```json
{
  "event": "StudentUpdated",
  "student_id": 1001,
  "student_name": "Alice Johnson",
  "student_email": "alice.j@example.com",
  "updated_fields": ["email", "phone"],
  "timestamp": "2026-02-19 11:15:02"
}
```

### StudentDeleted Payload
```json
{
  "event": "StudentDeleted",
  "student_id": 1001,
  "student_name": "Alice Johnson",
  "student_email": "alice@example.com",
  "timestamp": "2026-02-19 11:15:03"
}
```

### StudentEnrolled Payload
```json
{
  "event": "StudentEnrolled",
  "student_id": 1001,
  "student_name": "Alice Johnson",
  "student_email": "alice@example.com",
  "course_id": "C101",
  "course_name": "Laravel Masterclass",
  "timestamp": "2026-02-19 11:15:04"
}
```

---

## File Structure

```
student/
  app/
    Events/
      ├─ StudentCreated.php         ✓ Created
      ├─ StudentUpdated.php         ✓ Created
      ├─ StudentDeleted.php         ✓ Created
      └─ StudentEnrolled.php        ✓ Created
    Services/
      └─ KafkaProducerService.php   ✓ Created
    Console/Commands/
      ├─ PublishStudentEvent.php              ✓ Created
      ├─ PublishStudentUpdatedEvent.php       ✓ Created
      ├─ PublishStudentDeletedEvent.php       ✓ Created
      └─ PublishStudentEnrolledEvent.php      ✓ Created

notification/
  app/
    Events/
      ├─ StudentUpdated.php         (reference only)
      ├─ StudentDeleted.php         (reference only)
      └─ StudentEnrolled.php        (reference only)
    Services/
      ├─ KafkaConsumerService.php   ✓ Created
      └─ NotificationEventHandler.php ✓ Created
    Console/Commands/
      └─ ListenToStudentEvents.php   ✓ Updated
```

---

## Troubleshooting

### No events received?

1. Make sure listener is running first
2. Check Kafka is running: `docker compose ps`
3. Verify topic: `docker exec kafka kafka-topics --list --bootstrap-server kafka:9092`
4. Check container logs: `docker compose logs notification`

### "Connection refused"?

```bash
docker compose restart kafka
docker compose restart notification
```

### Unknown event type?

The listener logs unknown events. Check the event name in the payload.

---

## Next Steps

1. ✅ Test with the scenario above
2. Add database persistence
3. Implement real email sending
4. Add error handling & retries
5. Create dashboard/admin service listeners
6. Add monitoring & alerting

Happy testing!
