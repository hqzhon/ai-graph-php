# LangGraph PHP MVP - Web 应用程序

LangGraph PHP SDK 的综合 Web 演示，展示具有现代 Web 界面的高级 AI 工作流功能。

## 🌟 功能特性

### 核心工作流
- **LangGraph 简单工作流** - 带有状态管理的基本图执行
- **LangGraph 高级工作流** - 具有基于通道的状态管理的复杂工作流
- **多智能体系统** - 具有动态任务分配的协作 AI 智能体
- **工作流验证** - 具有自定义规则的综合工作流验证
- **AI 聊天界面** - 带上下文管理的交互式聊天
- **文章工作流** - 带状态转换的内容管理

### 技术栈
- **后端**: Laravel PHP 框架
- **前端**: React + Vite + Tailwind CSS
- **架构**: 统一图实现，使用 LangGraph PHP SDK
- **API**: 具有流支持的 RESTful API，用于实时更新

## 📋 项目结构

```
webapp/
├── backend/              # Laravel 后端应用程序
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/    # API 控制器
│   │   └── ...
│   ├── routes/
│   │   └── api.php           # API 路由
│   ├── storage/
│   │   └── framework/        # 运行时文件 (被 git 忽略)
│   └── ...
├── frontend/             # React 前端应用程序
│   ├── src/
│   │   ├── components/      # React 组件
│   │   ├── services/        # API 服务调用
│   │   └── ...
│   └── ...
├── start.sh              # 开发服务器启动脚本
└── test_apis.py          # API 验证脚本
```

## 🚀 快速开始

### 系统要求

- PHP 8.1 或更高版本
- Composer
- Node.js (v16 或更高版本) 和 npm
- Redis 服务器 (用于会话管理)
- Git

### 安装

1. **克隆仓库**:
   ```bash
   git clone https://github.com/hqzhon/langgraph-php
   cd examples/webapp
   ```

2. **后端设置**:
   ```bash
   # 导航到后端目录
   cd backend

   # 安装 PHP 依赖
   composer install

   # 生成应用密钥
   php artisan key:generate

   # 创建存储符号链接 (如需要)
   php artisan storage:link

   # 设置环境变量
   cp .env.example .env
   # 编辑 .env 文件以配置数据库和 API 密钥
   ```

3. **前端设置**:
   ```bash
   # 导航到前端目录
   cd ../frontend

   # 安装 JavaScript 依赖
   npm install
   ```

### 运行应用程序

#### 选项 1: 使用启动脚本 (推荐用于开发)
```bash
cd /path/to/examples/webapp
./start.sh
```

#### 选项 2: 手动启动
1. **启动后端服务器**:
   ```bash
   cd backend
   php artisan serve --port=8000
   ```

2. **启动前端服务器**:
   ```bash
   cd frontend
   npm run dev
   ```

## 🔧 配置

### 环境变量

在 `backend/.env` 中，您可以配置:

```env
APP_NAME="LangGraph PHP MVP"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
# 或其他数据库配置

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# API 密钥 (测试可选)
QWEN_API_KEY=your_qwen_api_key
DEEPSEEK_API_KEY=your_deepseek_api_key
```

## 🧪 测试

### API 验证
运行综合 API 测试套件:
```bash
python3 test_apis.py
```

### 核心功能测试
运行核心功能的综合测试套件:
```bash
python3 test_core_features.py
```

## 🌐 架构

### LangGraph PHP SDK 集成
应用程序使用 LangGraph PHP SDK 实现基于图的 AI 工作流。核心实现包括:

1. **统一图实现** - 结合多种工作流方法
2. **状态管理** - 基于通道的状态及变更跟踪
3. **节点执行** - 支持可调用函数和节点对象
4. **条件边** - 基于条件的动态工作流路由

### 前端组件
- **LangGraph 演示** - 工作流执行的可视化演示
- **多智能体界面** - 智能体协作的实时流
- **聊天界面** - 带上下文的交互式 AI 对话
- **工作流验证 UI** - 工作流验证的可视化表示
- **文章工作流** - 带状态可视化的内 容管理

## 🤝 贡献

1. Fork 仓库
2. 创建功能分支 (`git checkout -b feature/amazing-feature`)
3. 提交您的更改 (`git commit -m 'Add some amazing feature'`)
4. 推送到分支 (`git push origin feature/amazing-feature`)
5. 开启 Pull Request

### 开发指南
- 遵循 PHP 的 PSR-12 编码标准
- 使用 React 最佳实践编写前端组件
- 为新功能编写测试
- 在 README 中记录 API 端点
