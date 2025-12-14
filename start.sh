#!/bin/bash

echo "Starting Docker containers..."
docker compose up --build -d

echo "Running commands inside PHP container..."
docker exec -it turismo-app bash -c "composer install && php artisan migrate:fresh --seed && php artisan key:generate"

echo "Starting local dev server..."
npm run dev
