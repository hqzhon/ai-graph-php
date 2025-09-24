#!/bin/bash

# Final verification script for the LangGraph Web Demo

echo "=== LangGraph Web Demo Verification ==="
echo

# Check if required directories exist
echo "1. Checking directory structure..."
if [ ! -d "backend" ]; then
    echo "  ERROR: backend directory not found"
    exit 1
fi

if [ ! -d "frontend" ]; then
    echo "  ERROR: frontend directory not found"
    exit 1
fi

echo "  ✓ backend directory exists"
echo "  ✓ frontend directory exists"

# Check if required files exist
echo
echo "2. Checking required files..."

# Backend files
if [ ! -f "backend/app/Http/Controllers/LangGraphController.php" ]; then
    echo "  ERROR: LangGraphController.php not found"
    exit 1
fi

if [ ! -f "backend/routes/api.php" ]; then
    echo "  ERROR: api.php not found"
    exit 1
fi

echo "  ✓ LangGraphController.php exists"
echo "  ✓ api.php exists"

# Frontend files
if [ ! -f "frontend/src/App.jsx" ]; then
    echo "  ERROR: App.jsx not found"
    exit 1
fi

if [ ! -f "frontend/tailwind.config.cjs" ]; then
    echo "  ERROR: tailwind.config.cjs not found"
    exit 1
fi

echo "  ✓ App.jsx exists"
echo "  ✓ tailwind.config.js exists"

# Check if Laravel backend can access the main SDK
echo
echo "3. Testing Laravel backend access to main SDK..."
cd backend
php tests/LangGraphTest.php > /dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "  ERROR: Laravel backend cannot access the main SDK"
    exit 1
fi
echo "  ✓ Laravel backend can access the main SDK"

# Check if frontend dependencies are installed
echo
echo "4. Checking frontend dependencies..."
cd ../frontend
if [ ! -d "node_modules" ]; then
    echo "  WARNING: node_modules directory not found. Please run 'npm install' in the frontend directory"
else
    echo "  ✓ Frontend dependencies are installed"
fi

echo
echo "=== Verification Complete ==="
echo
echo "The LangGraph Web Demo is ready to use!"
echo
echo "To start the demo:"
echo "  1. Start the Laravel backend: cd backend && php artisan serve"
echo "  2. Start the React frontend: cd frontend && npm run dev"
echo "  3. Or use the start script: ./start.sh"
echo