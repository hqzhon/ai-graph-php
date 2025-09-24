#!/bin/bash

# Start the Laravel backend server
echo "Starting Laravel backend server..."
cd backend && php artisan serve &

# Start the React frontend development server
echo "Starting React frontend development server..."
cd ../frontend && npm run dev