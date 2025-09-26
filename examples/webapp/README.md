# LangGraph PHP MVP - Web Application

A comprehensive web demonstration of the LangGraph PHP SDK showcasing advanced AI workflow capabilities with a modern web interface.

## 🌟 Features

### Core Workflows
- **LangGraph Simple Workflow** - Basic graph execution with state management
- **LangGraph Advanced Workflow** - Complex workflows with channel-based state management
- **Multi-Agent System** - Collaborative AI agents with dynamic task allocation
- **Workflow Validation** - Comprehensive workflow validation with custom rules
- **AI Chat Interface** - Interactive chat with context management
- **Article Workflow** - Content management with state transitions

### Tech Stack
- **Backend**: Laravel PHP framework
- **Frontend**: React + Vite + Tailwind CSS
- **Architecture**: Unified Graph Implementation with LangGraph PHP SDK
- **API**: RESTful API with streaming support for real-time updates

## 📋 Project Structure

```
webapp/
├── backend/              # Laravel backend application
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/    # API controllers
│   │   └── ...
│   ├── routes/
│   │   └── api.php           # API routes
│   ├── storage/
│   │   └── framework/        # Runtime files (ignored by git)
│   └── ...
├── frontend/             # React frontend application
│   ├── src/
│   │   ├── components/      # React components
│   │   ├── services/        # API service calls
│   │   └── ...
│   └── ...
├── start.sh              # Development server startup script
└── test_apis.py          # API validation script
```

## 🚀 Getting Started

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js (v16 or higher) and npm
- Redis server (for session management)
- Git

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/hqzhon/langgraph-php
   cd examples/webapp
   ```

2. **Backend Setup**:
   ```bash
   # Navigate to backend directory
   cd backend

   # Install PHP dependencies
   composer install

   # Generate application key
   php artisan key:generate

   # Create symbolic link for storage (if needed)
   php artisan storage:link

   # Set up environment variables
   cp .env.example .env
   # Edit .env file to configure your database and API keys
   ```

3. **Frontend Setup**:
   ```bash
   # Navigate to frontend directory
   cd ../frontend

   # Install JavaScript dependencies
   npm install
   ```

### Running the Application

#### Option 1: Using the start script (recommended for development)
```bash
cd /path/to/examples/webapp
./start.sh
```

#### Option 2: Manual startup
1. **Start the backend server**:
   ```bash
   cd backend
   php artisan serve --port=8000
   ```

2. **Start the frontend server**:
   ```bash
   cd frontend
   npm run dev
   ```

### API Endpoints

The application provides several API endpoints for different features:

#### LangGraph Workflows
- `POST /api/langgraph/simple-workflow` - Execute simple LangGraph workflow
- `POST /api/langgraph/advanced-workflow` - Execute advanced LangGraph workflow

#### Multi-Agent System
- `POST /api/multi-agent/stream` - Stream multi-agent workflow results

#### Workflow Validation
- `POST /api/workflow-validation/validate` - Validate workflow structure
- `GET /api/workflow-validation/report/{validationId}` - Get validation report

#### Chat Interface
- `POST /api/chat/process` - Process chat conversation
- `GET /api/chat/history/{conversationId}` - Get chat history

#### Article Workflow
- `GET /api/article/{id?}` - Get article information
- `POST /api/article/{id}/transition/{transition}` - Transition article state

## 🔧 Configuration

### Environment Variables

In `backend/.env`, you can configure:

```env
APP_NAME="LangGraph PHP MVP"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
# Or other database configurations

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# API Keys (optional for testing)
QWEN_API_KEY=your_qwen_api_key
DEEPSEEK_API_KEY=your_deepseek_api_key
```

## 🧪 Testing

### API Validation
Run the comprehensive API test suite:
```bash
cd /path/to/examples/webapp
python3 test_apis.py
```

### Core Features Testing
Run the comprehensive test suite for core features:
```bash
cd /path/to/examples/webapp
python3 test_core_features.py
```

## 🌐 Architecture

### LangGraph PHP SDK Integration
The application uses the LangGraph PHP SDK to implement graph-based AI workflows. The core implementation includes:

1. **Unified Graph Implementation** - Combines multiple workflow approaches
2. **State Management** - Channel-based state with change tracking
3. **Node Execution** - Support for both callable functions and Node objects
4. **Conditional Edges** - Dynamic workflow routing based on conditions

### Frontend Components
- **LangGraph Demo** - Visual demonstration of workflow execution
- **Multi-Agent Interface** - Real-time streaming of agent collaboration
- **Chat Interface** - Interactive AI conversation with context
- **Workflow Validation UI** - Visual representation of workflow validation
- **Article Workflow** - Content management with state visualization

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards for PHP
- Use React best practices for frontend components
- Write tests for new features
- Document API endpoints in the README
