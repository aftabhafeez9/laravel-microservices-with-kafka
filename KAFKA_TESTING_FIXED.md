# Kafka Testing - FIXED & WORKING ✓

## Status: All Systems Go!

The Kafka producer and consumer services are now fully functional and tested.

---

## Test Results

### ✓ Notification Service Listener
```
✓ Message received:
  Event: StudentCreated
  Student ID: 1228
  Name: John Doe
  Email: john@example.com
  Timestamp: 2026-02-19 06:23:37

[NOTIFICATION] Processing student event:
  - Sending welcome email to: john@example.com
  - Logging notification event
  - Storing notification record
```

### ✓ Admin Service Listener
```
✓ Message received:
  Event: StudentCreated
  Student ID: 1228
  Name: John Doe
  Email: john@example.com
  Timestamp: 2026-02-19 06:23:37

[ADMIN] Processing student event:
  - Storing student record in admin database
  - Updating admin dashboard
  - Syncing with admin services
```

### ✓ Student Service Publisher
```
Publishing StudentCreated event...
Student ID: 6048
Name: Alice Smith
Email: alice@example.com
✓ Event published successfully to Kafka topic: student-events
```

---

## Ready to Test - Full Instructions

### 1. Start Notification Listener (Terminal 1)

```bash
docker exec notification php artisan listen:student-events --timeout=120000
```

Expected output:
```
============================================
NOTIFICATION SERVICE - Student Events Listener
============================================

Connecting to Kafka broker: kafka:9092
Topic: student-events
Consumer Group: notification-service
Timeout: 120000ms

Listening for messages... (timeout: 120s)
```

---

### 2. Start Admin Listener (Terminal 2)

```bash
docker exec admin php artisan listen:student-events --timeout=120000
```

Expected output:
```
========================================
ADMIN SERVICE - Student Events Listener
========================================

Connecting to Kafka broker: kafka:9092
Topic: student-events
Consumer Group: admin-service
Timeout: 120000ms

Listening for messages... (timeout: 120s)
```

---

### 3. Publish Events (Terminal 3)

**Default event:**
```bash
docker exec student php artisan student:publish-event
```

**Custom event:**
```bash
docker exec student php artisan student:publish-event "Jane Doe" "jane@example.com"
```

**Another custom event:**
```bash
docker exec student php artisan student:publish-event "Bob Wilson" "bob@example.com"
```

---

## What You'll See

When you publish an event from Terminal 3:

**Terminal 1 (Notification) will show:**
```
✓ Message received:
  Event: StudentCreated
  Student ID: 5234
  Name: Jane Doe
  Email: jane@example.com
  Timestamp: 2026-02-19 11:15:00

[NOTIFICATION] Processing student event:
  - Sending welcome email to: jane@example.com
  - Logging notification event
  - Storing notification record
```

**Terminal 2 (Admin) will show:**
```
✓ Message received:
  Event: StudentCreated
  Student ID: 5234
  Name: Jane Doe
  Email: jane@example.com
  Timestamp: 2026-02-19 11:15:00

[ADMIN] Processing student event:
  - Storing student record in admin database
  - Updating admin dashboard
  - Syncing with admin services
```

**Terminal 3 (Student) will show:**
```
Publishing StudentCreated event...
Student ID: 5234
Name: Jane Doe
Email: jane@example.com
✓ Event published successfully to Kafka topic: student-events
```

---

## Architecture

```
┌─────────────────────────────────────────────────────┐
│         STUDENT SERVICE (Producer)                  │
│  - Publishes StudentCreated events to Kafka         │
│  - Command: student:publish-event                   │
│  - Topic: student-events                            │
└────────────────────┬────────────────────────────────┘
                     │
                     │ Publishes Event
                     ▼
            ┌────────────────────┐
            │ KAFKA BROKER       │
            │ Topic: student-     │
            │ events             │
            └────┬───────────┬───┘
                 │           │
        ┌────────▼┐    ┌────▼──────────┐
        │ Admin   │    │ Notification  │
        │ Service │    │ Service       │
        │         │    │               │
        │Consumes │    │ Consumes      │
        │Consumer │    │ Consumer      │
        │Group:   │    │ Group:        │
        │admin-   │    │ notification- │
        │service  │    │ service       │
        └─────────┘    └───────────────┘
```

---

## Commands Reference

### Publisher Commands

```bash
# Publish with default values (John Doe, john@example.com)
docker exec student php artisan student:publish-event

# Publish with custom name and email
docker exec student php artisan student:publish-event "Name" "email@example.com"

# Examples
docker exec student php artisan student:publish-event "Alice" "alice@example.com"
docker exec student php artisan student:publish-event "Bob Wilson" "bob@example.com"
docker exec student php artisan student:publish-event "Carol Davis" "carol@example.com"
```

### Listener Commands

```bash
# Admin listener (default timeout: 120 seconds)
docker exec admin php artisan listen:student-events

# Admin listener with custom timeout (60 seconds)
docker exec admin php artisan listen:student-events --timeout=60000

# Notification listener (default timeout: 120 seconds)
docker exec notification php artisan listen:student-events

# Notification listener with custom timeout (30 seconds)
docker exec notification php artisan listen:student-events --timeout=30000
```

---

## What Was Fixed

**Issues Resolved:**
1. ✅ Changed from `RdKafka\Consumer` to `RdKafka\KafkaConsumer` (correct high-level API)
2. ✅ Fixed `subscribe()`, `consume()`, `unsubscribe()` method calls
3. ✅ Added proper topic parameter to producer's `publish()` method
4. ✅ Improved error handling and configuration
5. ✅ All three services now working in concert

---

## Testing Scenarios

### Scenario 1: Single Event to Multiple Listeners
1. Start admin listener (Terminal 1)
2. Start notification listener (Terminal 2)
3. Publish one event (Terminal 3)
4. Both listeners receive and process it

### Scenario 2: Multiple Events
1. Start both listeners
2. Publish 3-5 events
3. See all queued messages processed in order

### Scenario 3: Service Recovery
1. Start listeners
2. Publish events
3. Stop a listener (Ctrl+C)
4. Publish more events
5. Restart listener - it will receive new events

---

## Troubleshooting

**Issue: "Connection refused to kafka:9092"**
```bash
# Check Kafka is running
docker compose ps
# Should show kafka container "Up"

# Restart Kafka if needed
docker compose restart kafka
```

**Issue: "Timeout - no messages received"**
- Make sure to start the listener BEFORE publishing
- Or increase timeout: `--timeout=300000` (5 minutes)

**Issue: "RdKafka not installed"**
```bash
docker exec student php -m | grep rdkafka
# Should show "rdkafka"
```

**Issue: "Check if topic exists"**
```bash
docker exec kafka kafka-topics --list --bootstrap-server kafka:9092
# Should show "student-events"
```

---

## Files Updated

- `student/app/Services/KafkaProducerService.php` ✓ Fixed API
- `student/app/Console/Commands/PublishStudentEvent.php` ✓ Updated
- `admin/app/Services/KafkaConsumerService.php` ✓ Fixed API
- `admin/app/Console/Commands/ListenToStudentEvents.php` ✓ Working
- `notification/app/Services/KafkaConsumerService.php` ✓ Fixed API
- `notification/app/Console/Commands/ListenToStudentEvents.php` ✓ Working

---

## Next Steps

1. ✅ Test the basic flow (instructions above)
2. Implement real business logic (send actual emails, update databases)
3. Add error handling and retry logic
4. Log events to database
5. Create more event types (StudentUpdated, StudentDeleted, etc.)
6. Deploy to production with Laravel queue workers

Happy testing!
