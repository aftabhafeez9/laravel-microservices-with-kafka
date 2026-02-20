# Recreate Docker Containers and Images

## Step 1: Stop all running containers
```bash
docker compose down
```

## Step 2: Remove all containers (if they exist)
```bash
docker compose down -v
```

This removes containers, networks, and volumes associated with the compose project.

## Step 3: Remove dangling images (optional but recommended)
```bash
docker image prune -f
```

## Step 4: Remove specific service images (if you want to rebuild from scratch)
```bash
docker rmi laravel-microservices-with-kafka-auth:latest
docker rmi laravel-microservices-with-kafka-admin:latest
docker rmi laravel-microservices-with-kafka-student:latest
docker rmi laravel-microservices-with-kafka-notification:latest
docker rmi laravel-microservices-with-kafka-gateway:latest
```

Or remove all at once:
```bash
docker rmi $(docker images -q --filter "reference=laravel-microservices-with-kafka-*")
```

## Step 5: Rebuild and recreate all containers
```bash
docker compose up -d --build
```

This will:
- Rebuild all images
- Create new containers
- Start all services

## Step 6: Verify everything is running
```bash
docker compose ps
```

## Step 7: Check logs to ensure services started correctly
```bash
docker compose logs -f
```

Or check individual services:
```bash
docker logs auth
docker logs admin
docker logs student
docker logs notification
docker logs gateway
```

## Quick One-Liner to Completely Recreate Everything

```bash
docker compose down -v && docker rmi $(docker images -q --filter "reference=laravel-microservices-with-kafka-*") 2>/dev/null; docker compose up -d --build
```

## After Recreating Containers

You can now run artisan commands correctly:

```bash
# For auth service
docker exec auth php artisan make:command YourCommandName

# For admin service
docker exec admin php artisan make:command YourCommandName

# Run migrations
docker exec auth php artisan migrate
docker exec admin php artisan migrate

# Clear cache
docker exec auth php artisan cache:clear
docker exec admin php artisan cache:clear
```
