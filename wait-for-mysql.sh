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
MAX_DNS_RETRIES=60
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
MAX_MYSQL_RETRIES=60
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

# Reset the database to avoid migration conflicts
echo "Resetting database to ensure a fresh state..."
mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "DROP DATABASE IF EXISTS $DB_DATABASE; CREATE DATABASE $DB_DATABASE;"
if [ $? -ne 0 ]; then
  echo "Error: Failed to reset the database."
  exit 1
fi

# Ensure Composer dependencies are installed
echo "Installing Composer dependencies (if not already installed)..."
composer install --optimize-autoloader --no-dev || true

# Generate application key
echo "Generating application key..."
php artisan key:generate

# Clear config cache
echo "Clearing config cache..."
php artisan config:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force
if [ $? -ne 0 ]; then
  echo "Error: Migrations failed."
  exit 1
fi

# Set up storage for file uploads
echo "Creating storage link for file uploads..."
php artisan storage:link

echo "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm
