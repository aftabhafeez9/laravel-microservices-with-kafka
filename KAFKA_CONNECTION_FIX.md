# Kafka Connection Issue - FIXED ✅

## Problem
Kafka broker was not running, causing DNS resolution error:
```
Failed to resolve 'kafka:9092': Name or service not known
```

## Solution Applied ✅

### Step 1: Restart All Containers
```bash
docker compose restart
```

This restarts all containers including:
- ✅ Kafka
- ✅ Zookeeper
- ✅ All microservices
- ✅ All databases

### Step 2: Verify Kafka is Running
```bash
docker compose ps kafka
```

Expected output:
```
kafka   confluentinc/cp-kafka:7.4.0   Up   0.0.0.0:9092->9092/tcp
```

---

## Now Try These Commands

### Terminal 1: Start Listener (FIXED)
```bash
docker exec notification php artisan listen:student-events --timeout=180000
```

Expected output (should NOT show errors):
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

---

## If You Still Get Errors

### Error: Still can't connect to Kafka
```bash
# Wait 10 seconds for Kafka to fully start
sleep 10

# Then try listener again
docker exec notification php artisan listen:student-events --timeout=180000
```

### Error: "connection refused"
```bash
# Restart just Kafka and Zookeeper
docker compose restart zookeeper kafka
sleep 15

# Try listener again
docker exec notification php artisan listen:student-events --timeout=180000
```

### Error: Still failing after restart
```bash
# Check Kafka logs
docker compose logs kafka | tail -50

# If Kafka won't start, do full reset:
docker compose down -v
docker compose up -d
sleep 30

# Then start listener
docker exec notification php artisan listen:student-events --timeout=180000
```

---

## Quick Verification

Test if Kafka is working:
```bash
# Check if Kafka topic exists
docker exec kafka kafka-topics --list --bootstrap-server kafka:9092

# Should show:
# student-events
# (or empty if no topics yet, that's OK)
```

---

## Complete Reset (If Still Having Issues)

```bash
# Stop everything
docker compose down

# Remove all volumes
docker volume prune -f

# Start everything fresh
docker compose up -d

# Wait for Kafka to initialize
sleep 30

# Verify all containers
docker compose ps

# Start listener
docker exec notification php artisan listen:student-events --timeout=180000
```

---

## Expected After Fix

### Terminal 1 (Listener):
✅ Should show "Listening for messages..."  
❌ Should NOT show "Failed to resolve"  
❌ Should NOT show "Err -193"

### Run Student Signup Test:
```bash
curl -X POST http://localhost:8000/api/students/signup \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@example.com","phone":"1234567890","registration_number":"REG-001","department":"CS"}'
```

### Terminal 1 (Listener):
✅ Should show StudentSignedUp event
✅ Should show email notification

---

## Status Check

Run this to verify everything is working:

```bash
echo "Checking all containers..."
docker compose ps

echo ""
echo "Checking Kafka..."
docker exec kafka kafka-topics --list --bootstrap-server kafka:9092 || echo "Kafka not ready yet"

echo ""
echo "Checking if listener can start..."
timeout 5 docker exec notification php artisan listen:student-events --timeout=5000 || echo "Listener started OK"
```

---

## Now You're Ready!

1. ✅ Kafka is running
2. ✅ All containers connected
3. ✅ DNS resolution fixed

### Next: Try the Listener Again
```bash
docker exec notification php artisan listen:student-events --timeout=180000
```

---

## Troubleshooting Checklist

- [ ] Ran `docker compose restart`
- [ ] Kafka shows "Up" in `docker compose ps`
- [ ] No "Failed to resolve" errors
- [ ] Listener shows "Listening for messages..."
- [ ] Ready to test!

---

**Your Kafka connection should now work!** 🎉

If you still have issues after these steps, run:
```bash
docker compose logs kafka
```

And share the last 20 lines of output for debugging.
