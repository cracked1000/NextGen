NextGen Computing 

Docker Desktop: Download
Git: Download
8 GB RAM minimum (16 GB recommended)

Setup

Clone the Repo:
git clone <repository-url> nextgen-computing
cd nextgen-computing


Set Up Environment:
cp .env.example .env

Ensure:
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=NEXTGEN
DB_USERNAME=root
DB_PASSWORD=root


Run the Setup Script:
chmod +x setup.sh
./setup.sh

This script:

Cleans up containers/volumes
Fixes migration order (users before second_hand_parts)
Starts containers (mysql, app, web)
Runs migrations
Sets up storage

Seeding the Database
Populate the database with test data:

Run Default Seeder:
docker-compose exec app php artisan db:seed


Access the App

URL: http://localhost:8000

