# NextGen Computing - Second-Hand Parts Market

A Laravel web app for buying and selling second-hand computer parts (CPUs, GPUs, etc.). Supports Admin, Customer, and Seller roles, with features like orders management, profile photo uploads, and a quotation system.

## Prerequisites

- **Docker Desktop**: [Download](https://www.docker.com/products/docker-desktop/)
- **Git**: [Download](https://git-scm.com/downloads)
- 8 GB RAM minimum (16 GB recommended)

## Setup and Environment Configuration

### Clone the Repository
```bash
git clone <repository-url> nextgen-computing
cd nextgen-computing

### Configure the Environment
### Copy the example environment file:

cp .env.example .env

### Edit .env to include the following database settings:

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=NEXTGEN
DB_USERNAME=root
DB_PASSWORD=root

### Run the Setup Script
### Execute the setup script to initialize the application:

chmod +x setup.sh
./setup.sh

### Seed the database

docker-compose exec app php artisan db:seed

Access the Application
Visit the app at:

URL: http://localhost:8000