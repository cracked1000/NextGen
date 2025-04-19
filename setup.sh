#!/bin/bash

# Robust setup script for NextGen-main

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

log() {
  echo -e "${GREEN}[$(date '+%Y-%m-%d %H:%M:%S')] $1${NC}"
}

error() {
  echo -e "${RED}[$(date '+%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}"
  exit 1
}

warn() {
  echo -e "${YELLOW}[$(date '+%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}"
}

# Step 1: Clean up existing environment
log "Cleaning up existing environment..."
docker-compose down -v || error "Failed to stop containers and remove volumes"
docker volume rm -f nextgen_mysql_data 2>/dev/null || true
log "Environment cleaned."

# Step 2: Fix migration order for users table
log "Fixing migration order for users table..."
if [ -f "database/migrations/2025_04_04_161212_create_users_table.php" ]; then
  mv database/migrations/2025_04_04_161212_create_users_table.php database/migrations/2025_03_16_000000_create_users_table.php || error "Failed to rename users migration"
fi
log "Migration order fixed."

# Step 3: Start containers
log "Building and starting containers..."
docker-compose up -d --build || error "Failed to start containers"
log "Containers started."

# Step 4: Wait for MySQL to be ready
log "Waiting for MySQL to be ready..."
MAX_RETRIES=60
RETRY_INTERVAL=2
RETRIES=0
until docker-compose exec -T mysql mysqladmin ping -uroot -proot --silent; do
  RETRIES=$((RETRIES + 1))
  if [ $RETRIES -ge $MAX_RETRIES ]; then
    error "MySQL is not ready after $MAX_RETRIES attempts"
  fi
  warn "MySQL not ready yet... (Attempt $RETRIES/$MAX_RETRIES)"
  sleep $RETRY_INTERVAL
done
log "MySQL is ready."

# Step 5: Reset the database
log "Resetting database..."
docker-compose exec -T mysql mysql -uroot -proot -e "DROP DATABASE IF EXISTS NEXTGEN; CREATE DATABASE NEXTGEN;" || error "Failed to reset database"
log "Dropping unexpected tables..."
docker-compose exec -T mysql mysql -uroot -proot NEXTGEN -e "DROP TABLE IF EXISTS users, failed_jobs, cache_locks, job_batches;" || warn "Failed to drop unexpected tables"
TABLE_COUNT=$(docker-compose exec -T mysql mysql -uroot -proot NEXTGEN -e "SHOW TABLES;" | wc -l)
if [ "$TABLE_COUNT" -gt 1 ]; then  # 1 line for header
  error "Database reset failed - tables still exist after cleanup"
fi
log "Database reset successful."

# Step 6: Wait for app container to be ready
log "Waiting for app container to be ready..."
RETRIES=0
until docker-compose ps | grep nextgen_app | grep -q "Up"; do
  RETRIES=$((RETRIES + 1))
  if [ $RETRIES -ge $MAX_RETRIES ]; then
    error "App container is not running after $MAX_RETRIES attempts"
  fi
  warn "App container not ready yet... (Attempt $RETRIES/$MAX_RETRIES)"
  sleep $RETRY_INTERVAL
done
log "App container is ready."

# Step 7: Check for duplicate migrations
log "Checking for duplicate migrations..."
USER_MIGRATIONS=$(docker-compose exec -T app ls -1 database/migrations | grep -c "create_users_table")
if [ "$USER_MIGRATIONS" -gt 1 ]; then
  error "Multiple migrations for 'users' table found. Please ensure only one 'create_users_table' migration exists."
fi
log "No duplicate migrations found."

# Step 8: Run migrations
log "Running migrations..."
docker-compose exec -T app php artisan migrate --force || error "Migrations failed"
log "Migrations completed successfully."

# Step 9: Set up storage
log "Setting up storage..."
docker-compose exec -T app php artisan storage:link || warn "Failed to create storage link"
docker-compose exec -T app chmod -R 775 storage bootstrap/cache || warn "Failed to set storage permissions"
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache || warn "Failed to set storage ownership"
log "Storage setup completed."

# Step 10: Verify services
log "Verifying services..."
docker-compose ps || error "Failed to verify services"
log "Services are running."

log "Setup completed successfully! Access the app at http://localhost:8000"