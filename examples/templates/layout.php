<?php
/** @var string $title */
/** @var string $content */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'LangGraph PHP') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 2rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .feature-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .footer {
            margin-top: 3rem;
            padding: 2rem 0;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 400px;
            overflow-y: auto;
        }
        .stream-output {
            max-height: 500px;
            overflow-y: auto;
        }
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .status-running {
            background-color: #ffc107;
        }
        .status-completed {
            background-color: #28a745;
        }
        .status-error {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-diagram-3 me-2"></i>LangGraph PHP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/workflow-lab">
                            <i class="bi bi-robot me-1"></i>多代理工作流
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/model-test">
                            <i class="bi bi-cpu me-1"></i>AI模型测试
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/article-demo">
                            <i class="bi bi-file-text me-1"></i>文章工作流
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="https://github.com/langchain-ai/langgraph" target="_blank">
                            <i class="bi bi-github me-1"></i>GitHub
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        <?= $content ?? '' ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>LangGraph PHP</h5>
                    <p class="text-muted">一个基于PHP的工作流引擎实现，具有LangGraph风格的功能</p>
                </div>
                <div class="col-md-6">
                    <h5>资源</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://github.com/langchain-ai/langgraph" target="_blank">LangGraph文档</a></li>
                        <li><a href="/model-test">AI模型测试</a></li>
                        <li><a href="/workflow-lab">多代理工作流</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center text-muted">
                <small>© <?= date('Y') ?> LangGraph PHP. 基于PHP 7.4+构建.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 全局JavaScript函数
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
        
        // 显示加载状态的函数
        function showLoading(button) {
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 处理中...';
            return originalText;
        }
        
        // 恢复按钮状态的函数
        function restoreButton(button, originalText) {
            button.disabled = false;
            button.innerHTML = originalText;
        }
        
        // 平滑滚动到元素
        function smoothScrollTo(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    </script>
</body>
</html>