# LangGraph Web Demo

This is a web demonstration of the LangGraph PHP SDK using:
- Laravel backend for API endpoints
- React + Vite + Tailwind CSS frontend for the user interface

## Relationship to CLI Scripts

This web demo complements the existing CLI scripts in the `examples/bin/` directory by providing:
- A graphical user interface for easier interaction
- Visual representation of results
- Modern web-based demonstration

The CLI scripts in `examples/bin/` remain available for:
- Direct command-line access
- Automated testing and scripting
- Developer debugging
- Headless environments

Both approaches use the same underlying LangGraph PHP SDK.

## Project Structure

```
webapp/
├── backend/          # Laravel backend application
├── frontend/         # React frontend application
└── start.sh          # Development server startup script
```

## Getting Started

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and npm
- Laravel CLI (optional)

### Backend Setup

1. Navigate to the backend directory:
   ```bash
   cd backend
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Generate application key:
   ```bash
   php artisan key:generate
   ```

4. Start the Laravel development server:
   ```bash
   php artisan serve
   ```
   
   The backend will be available at `http://localhost:8000`

### Frontend Setup

1. Navigate to the frontend directory:
   ```bash
   cd frontend
   ```

2. Install JavaScript dependencies:
   ```bash
   npm install
   ```

3. Start the Vite development server:
   ```bash
   npm run dev
   ```
   
   The frontend will be available at `http://localhost:5173`

### Running Both Servers

You can start both servers simultaneously using the provided script:
```bash
./start.sh
```

## API Endpoints

The Laravel backend provides the following API endpoints:

- `POST /api/langgraph/simple-workflow` - Execute a simple LangGraph workflow
- `POST /api/langgraph/advanced-workflow` - Execute an advanced LangGraph workflow with channels

## Features Demonstrated

1. **Simple Workflow Execution** - Basic LangGraph workflow with three nodes
2. **Advanced Workflow Execution** - LangGraph workflow with channel-based state management
3. **Frontend-Backend Integration** - React frontend communicating with Laravel backend
4. **Tailwind CSS Styling** - Modern UI with Tailwind CSS

## How It Works

1. The React frontend sends requests to the Laravel backend
2. The Laravel backend executes LangGraph workflows using the PHP SDK
3. Results are returned to the frontend and displayed in a user-friendly interface

## Development

### Backend Development

The backend code is located in `backend/` and uses standard Laravel conventions:
- Controllers: `app/Http/Controllers/`
- Routes: `routes/api.php`
- Configuration: `config/`

### Frontend Development

The frontend code is located in `frontend/` and uses:
- React components in `src/`
- Tailwind CSS for styling
- Vite for development and building

## Contributing

This demo is part of the LangGraph PHP SDK project. Contributions should focus on:
1. Demonstrating SDK features
2. Improving the user interface
3. Adding new workflow examples

The webapp is designed to showcase the SDK capabilities without modifying the core SDK code.