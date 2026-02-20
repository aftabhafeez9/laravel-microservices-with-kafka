# Kafka Event Testing - Setup Complete ✓

## Files Created

### Student Service (Producer)
- **Event**: `student/app/Events/StudentCreated.php` - Event definition with payload
- **Service**: `student/app/Services/KafkaProducerService.php` - Kafka producer using rdkafka
- **Command**: `student/app/Console/Commands/PublishStudentEvent.php` - CLI to publish events

### Admin Service (Consumer)
- **Service**: `admin/app/Services/KafkaConsumerService.php` - Kafka consumer
- **Command**: `admin/app/Console/Commands/ListenToStudentEvents.php` - Listener command

### Notification Service (Consumer)
- **Service**: `notification/app/Services/KafkaConsumerService.php` - Kafka consumer
- **Command**: `notification/app/Console/Commands/ListenToStudentEvents.php` - Listener command

---

## Quick Start Commands

### Terminal 1 - Start Notification Listener
```bash
docker exec notification php artisan listen:student-events --timeout=120000
```

### Terminal 2 - Start Admin Listener
```bash
docker exec admin php artisan listen:student-events --timeout=120000
```

### Terminal 3 - Publish Event
```bash
docker exec student php artisan student:publish-event
```

Or with custom data:
```bash
docker exec student php artisan student:publish-event "John Doe" "john@example.com"
```

---

## What Happens

1. **Terminal 3** publishes a StudentCreated event with random ID and custom name/email
2. **Terminals 1 & 2** both receive the message from Kafka
3. Each service processes it independently (simulated logic)
4. Messages show in colored output with full event details

---

## Event Payload

```json
{
  "event": "StudentCreated",
  "student_id": 5234,
  "student_name": "John Doe",
  "student_email": "john@example.com",
  "timestamp": "2026-02-19 11:15:30"
}
```

---

## Configuration

**Kafka Settings:**
- Broker: `kafka:9092`
- Topic: `student-events`
- Consumer Groups:
  - `admin-service` (Admin service)
  - `notification-service` (Notification service)

**Timeouts:**
- Default: 120 seconds (120000ms)
- Customizable with `--timeout` flag
- Each timeout value in milliseconds

---

## Testing Scenarios

### Scenario 1: Basic Test
1. Start both listeners
2. Publish 1 event
3. See both services receive it

### Scenario 2: Multiple Events
1. Start listeners
2. Publish 3-4 events
3. See all queued messages processed

### Scenario 3: Service Recovery
1. Stop a listener mid-test
2. Publish new events
3. Restart the listener
4. It should still receive new events (depends on consumer group offset)

---

## Troubleshooting

**Issue: "Connection refused"**
```bash
docker compose ps
# Make sure all containers show "Up"
docker compose restart kafka
```

**Issue: "Listener timeout (no messages)"**
- Ensure you publish AFTER the listener starts
- Increase timeout: `--timeout=300000`

**Issue: "rdkafka not enabled"**
```bash
docker exec student php -m | grep rdkafka
# Should show "rdkafka"
```

**Issue: "Serialization error"**
- Check that Kafka broker is running
- Verify topic exists: `docker exec kafka kafka-topics --list --bootstrap-server kafka:9092`

---

## Automated Test Script

Run the batch file to auto-start all three terminals:

```bash
# Windows
test-kafka.bat

# Or manually:
docker exec notification php artisan listen:student-events --timeout=120000
docker exec admin php artisan listen:student-events --timeout=120000
docker exec student php artisan student:publish-event
```

---

## Next Steps

1. ✅ **Test basic flow** (as above)
2. **Add database logging** - Save consumed events to database
3. **Implement business logic** - Real email sending, dashboard updates
4. **Add error handling** - Retry logic, dead letter queue
5. **Production deployment** - Use Laravel queue workers
6. **Create more events** - StudentUpdated, StudentDeleted, etc.
7. **Add monitoring** - Event count metrics, latency tracking

---

## File Locations

See `KAFKA_TESTING_GUIDE.md` for detailed documentation of:
- All created files
- Complete command reference
- Detailed testing scenarios
- Troubleshooting guide

Happy testing!
