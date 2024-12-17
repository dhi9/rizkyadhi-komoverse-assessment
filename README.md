# Back End Assessment - PT. Komodo Legends Interaktif

This project is a backend REST API system developed using **Laravel 9+** with **PHP 8+**. It fulfills the requirements for the assessment task, implementing a score submission and leaderboard system for a game, along with API integration with external systems.

---

## Table of Contents
- [Technical Requirements](#technical-requirements)
- [Features](#features)
- [Database Schema](#database-schema)
- [API Endpoints](#api-endpoints)
  - [Submit Score API](#submit-score-api)
  - [Leaderboard API](#leaderboard-api)
  - [External API Integration](#external-api-integration)
- [Setup Instructions](#setup-instructions)
- [Testing](#testing)
- [Caching](#caching)
- [Notes](#notes)
- [Submission Details](#submission-details)

---

## Technical Requirements

The project is built to meet the following technical requirements:
- **Framework**: Laravel 11+ with PHP 8.4+.
- **Validation**: Implemented using Form Request Validation.
- **Controller Logic**: Minimal logic in Controllers.
- **Database**: MySQL with Eloquent ORM or Query Builder. Optimized using indexes.
- **Documentation**: REST API documented in Postman (included in the repository).
- **Caching**: Implemented for the Leaderboard API using Redis/File Cache.

---

## Features

### Task 1: Game Leaderboard System
- Create a list of 10,000 users in the database.
- Users can **submit scores** for specific game levels.
- Only the **highest score** per level is considered in the leaderboard.
- Leaderboard API supports:
  - Pagination for efficient data retrieval.
  - Filtering by username to get user-specific data and ranking.
- The leaderboard displays:
  - `ranking`, `username`, `last_level`, and `total_score`.

### Task 2: External API Interaction
- Integrates with an external API:
  - **Endpoint**: `https://unisync.alphagames.my.id/api/assessment`
  - **Request Headers**:
    - `X-Nonce`: Randomly generated string.
    - `X-API-Signature`: SHA256 encoded signature.
  - **Request Body**: Includes `timestamp` as a 13-digit epoch value.

---

## Setup Instructions

### 1. Clone the repository:

```
git clone git@github.com:dhi9/rizkyadhi-komoverse-assessment.git
```

### 2. Navigate to the project directory:
```
cd rizkyadhi-komoverse-assessment
```

### 3. Set up the .env file:
```
cp .env.example .env
```
## Docker Run
### Requirements
- Docker
- Docker Compose

## Build and Start Containers
Run the following command to build and start the Docker containers:
```
docker-compose up --build
```

### Access the Services
Once the containers are up and running, you can access the services:
```
http://localhost:8000 
```

### Check Logs
To check logs for any issues or information about the services:
```
Laravel logs: docker-compose logs komoverse_assessment_laravel
Redis logs: docker-compose logs komoverse_assessment_redis
Mysql logs: docker-compose logs komoverse_assessment_laravel
```

## Manual Run
### (Optional) Run database migrations and seed for 10.000 players/users:
```
php artisan migrate --seed
php -d memory_limit=2G artisan db:seed
```

Start the development server:
```
php artisan serve
```

## Caching
### Cache can be cleared using:
```
php artisan cache:clear
```

### Access the Services
Once running, you can access the services:
```
http://localhost:8000 
```