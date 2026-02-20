# Kafka Event Testing Guide

## Overview

This guide shows how to test event-driven communication between your microservices using Kafka.

**Architecture:**
- **Student Service**: Publishes `StudentCreated` events
- **Admin Service**: Listens and processes student events (updates admin dashboard)
- **Notification Service**: Listens and sends notifications (emails)

---

## Setup

All services are already configured with:
- ✅ Kafka Producer (Student service)
- ✅ Kafka Consumers (Admin & Notification services)
- ✅ Console commands for testing

---

## Testing Steps

### Step 1: Start the Notification Service Listener

Open a new terminal and run:

```bash
docker exec notification php artisan listen:student-events --timeout=120000
```

**Output will show:**
```
============================================
NOTIFICATION SERVICE - Student Events Listener
============================================

Connecting to Kafka broker: kafka:9092
Topic: student-events
Consumer Group: notification-service
Timeout: 120000ms

[Listening for messages...]
```

---

### Step 2: Start the Admin Service Listener

Open another new terminal and run:

```bash
docker exec admin php artisan listen:student-events --timeout=120000
```

**Output will show:**
```
========================================
ADMIN SERVICE - Student Events Listener
========================================

Connecting to Kafka broker: kafka:9092
Topic: student-events
Consumer Group: admin-service
Timeout: 120000ms

[Listening for messages...]
```

---

### Step 3: Publish a Student Event from Student Service

Open a third terminal and run:

```bash
docker exec student php artisan student:publish-event
```

Or with custom parameters:

```bash
docker exec student php artisan student:publish-event "Jane Smith" "jane@example.com"
```

**Output will show:**
```
Publishing StudentCreated event...
Student ID: 5234
Name: Jane Smith
Email: jane@example.com
✓ Event published successfully to Kafka topic: student-events
```

---

## What Happens Next

1. The event is published to the Kafka topic `student-events`
2. Both listeners receive the event (separate consumer groups)
3. Admin service processes it (simulated - updates dashboard)
4. Notification service processes it (simulated - sends email)

**Both listener terminals will show:**
```
✓ Message received:
  Event: StudentCreated
  Student ID: 5234
  Name: Jane Smith
  Email: jane@example.com
  Timestamp: 2026-02-19 11:15:00
```

Then each shows its own processing message:
- Admin: `[ADMIN] Processing student event: Storing student record...`
- Notification: `[NOTIFICATION] Processing student event: Sending welcome email...`

---

## Command Reference

### Publish Events

```bash
# Publish with default values
docker exec student php artisan student:publish-event

# Publish with custom name and email
docker exec student php artisan student:publish-event "John Doe" "john@example.com"
```

### Listen to Events

```bash
# Admin service listener (default timeout: 120 seconds)
docker exec admin php artisan listen:student-events

# Admin service listener with custom timeout (60 seconds)
docker exec admin php artisan listen:student-events --timeout=60000

# Notification service listener
docker exec notification php artisan listen:student-events

# Notification service listener with 30 second timeout
docker exec notification php artisan listen:student-events --timeout=30000
```

---

## Testing Scenarios

### Scenario 1: Single Event to Multiple Listeners

1. Start admin listener in terminal 1
2. Start notification listener in terminal 2
3. Publish event from terminal 3

**Result:** Both listeners receive and process the same event

```bash
# Terminal 1
docker exec admin php artisan listen:student-events

# Terminal 2
docker exec notification php artisan listen:student-events

# Terminal 3
docker exec student php artisan student:publish-event "Alice" "alice@example.com"
```

---

### Scenario 2: Multiple Events

```bash
# Terminal 1 - Listener
docker exec admin php artisan listen:student-events --timeout=180000

# Terminal 2 - Publisher (run multiple times)
docker exec student php artisan student:publish-event "Bob" "bob@example.com"
docker exec student php artisan student:publish-event "Charlie" "charlie@example.com"
docker exec student php artisan student:publish-event "Diana" "diana@example.com"
```

Both events will be received and processed in order.

---

### Scenario 3: Check Kafka Connectivity

Verify Kafka is running:

```bash
docker exec kafka kafka-topics --list --bootstrap-server kafka:9092
```

Check existing topics:

```bash
docker exec kafka kafka-topics --list --bootstrap-server kafka:9092
```

Create test topic manually (optional):

```bash
docker exec kafka kafka-topics --create --topic student-events --bootstrap-server kafka:9092 --partitions 1 --replication-factor 1
```

---

## Troubleshooting

### Listeners not receiving messages?

1. **Check Kafka is running:**
   ```bash
   docker compose ps
   ```
   All services should show "Up"

2. **Verify topic exists:**
   ```bash
   docker exec kafka kafka-topics --list --bootstrap-server kafka:9092
   ```

3. **Check Kafka logs:**
   ```bash
   docker compose logs kafka
   ```

4. **Verify rdkafka is enabled:**
   ```bash
   docker exec student php -m | grep rdkafka
   ```

### Timeout without messages?

- Make sure you publish a message AFTER starting the listener
- The listeners wait for new messages (don't replay old ones)
- Increase timeout: `--timeout=300000` (5 minutes)

### Connection refused?

- Make sure all containers are running: `docker compose ps`
- Restart Kafka: `docker compose restart kafka`
- Restart all services: `docker compose restart`

---

## File Structure

**Created files:**
```
student/
  app/
    Events/
      StudentCreated.php          # Event definition
    Services/
      KafkaProducerService.php    # Kafka producer
    Console/Commands/
      PublishStudentEvent.php     # Publish command

admin/
  app/
    Services/
      KafkaConsumerService.php    # Kafka consumer
    Console/Commands/
      ListenToStudentEvents.php   # Listener command

notification/
  app/
    Services/
      KafkaConsumerService.php    # Kafka consumer
    Console/Commands/
      ListenToStudentEvents.php   # Listener command
```

---

## Next Steps

Once testing works, you can:

1. **Add more events**: StudentUpdated, StudentDeleted, etc.
2. **Database logging**: Save events to database
3. **Error handling**: Retry logic, dead letter queues
4. **Production**: Use Laravel queue workers with Kafka
5. **Monitoring**: Add metrics and alerting

Happy testing!
