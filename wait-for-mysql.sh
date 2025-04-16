#!/bin/sh

# Function to check DNS resolution
check_dns() {
  # Try using ping first
  if command -v ping >/dev/null 2>&1; then
    ping -c 1 mysql > /dev/null 2>&1
    return $?
  fi
  # Fallback to getent if ping is not available
  if command -v getent >/dev/null 2>&1; then
    getent hosts mysql > /dev/null 2>&1
    return $?
  fi
  # If neither ping nor getent is available, fail
  echo "Error: Neither ping nor getent is available for DNS resolution."
  return 1
}

# Wait for DNS resolution of the MySQL service
MAX_DNS_RETRIES=60  # Increased from 30
DNS_RETRY_INTERVAL=2
DNS_RETRIES=0

until check_dns; do
  DNS_RETRIES=$((DNS_RETRIES + 1))
  if [ $DNS_RETRIES -ge $MAX_DNS_RETRIES ]; then
    echo "Error: DNS resolution for mysql failed after $MAX_DNS_RETRIES attempts."
    exit 1
  fi
  echo "Waiting for DNS resolution of mysql... (Attempt $DNS_RETRIES/$MAX_DNS_RETRIES)"
  sleep $DNS_RETRY_INTERVAL
done

echo "DNS resolution for mysql succeeded!"

# Wait for MySQL to be ready
MAX_MYSQL_RETRIES=60  # Increased from 30
MYSQL_RETRY_INTERVAL=2
MYSQL_RETRIES=0

until mysqladmin ping -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
  MYSQL_RETRIES=$((MYSQL_RETRIES + 1))
  if [ $MYSQL_RETRIES -ge $MAX_MYSQL_RETRIES ]; then
    echo "Error: MySQL is not ready after $MAX_MYSQL_RETRIES attempts."
    exit 1
  fi
  echo "Waiting for MySQL to be ready... (Attempt $MYSQL_RETRIES/$MAX_MYSQL_RETRIES)"
  sleep $MYSQL_RETRY_INTERVAL
done

echo "MySQL is ready!"

# Start PHP-FPM
exec php-fpm