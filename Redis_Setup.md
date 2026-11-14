Ensure Docker Desktop or Docker Engine is running.

Execute:
docker run -d \
  --name todo-redis \
  -p 127.0.0.1:6379:6379 \
  redis:7-alpine


  Then:
  docker run -d --name todo-redis -p 127.0.0.1:6379:6379 redis:7-alpine

  Check that Redis is running:
  docker ps

  Test Redis:
  docker exec todo-redis redis-cli ping

  Expected output:
  PONG


Install a Redis client in Laravel
Laravel needs a PHP client to communicate with Redis.
Laravel 13 supports both PhpRedis (a PHP extension) and Predis (a Composer package). For this tutorial, we will use Predis, because it does not require installing the PhpRedis extension Laravel.

Run:
composer require predis/predis

# Main database — remains MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_api
DB_USERNAME=root
DB_PASSWORD=

# Redis configuration
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Redis is now our cache store
CACHE_STORE=redis

# Keep other components independent of Redis
SESSION_DRIVER=file
QUEUE_CONNECTION=sync