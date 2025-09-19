<?php
/** @var string $title */
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold mb-3">LangGraph PHP演示</h1>
            <p class="lead mb-4">一个基于PHP的工作流引擎实现，具有LangGraph风格的功能</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/workflow-lab" class="btn btn-primary btn-lg">
                    <i class="bi bi-robot me-2"></i>多代理工作流
                </a>
                <a href="/model-test" class="btn btn-success btn-lg">
                    <i class="bi bi-cpu me-2"></i>AI模型测试
                </a>
                <a href="/article-demo" class="btn btn-info btn-lg">
                    <i class="bi bi-file-text me-2"></i>文章工作流
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-primary hover-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-robot fs-1 text-primary"></i>
                        </div>
                        <h5 class="card-title">多代理系统</h5>
                        <p class="card-text">体验高级协作AI系统，支持智能任务分配、动态协调和群体智能。</p>
                        <a href="/workflow-lab" class="btn btn-outline-primary">探索</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-success hover-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-cpu fs-1 text-success"></i>
                        </div>
                        <h5 class="card-title">AI模型集成</h5>
                        <p class="card-text">支持DeepSeek和Qwen模型，通过统一的工厂模式进行管理。</p>
                        <a href="/model-test" class="btn btn-outline-success">测试</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-info hover-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-diagram-3 fs-1 text-info"></i>
                        </div>
                        <h5 class="card-title">工作流引擎</h5>
                        <p class="card-text">三种工作流引擎：Symfony Workflow、自定义引擎和LangGraph风格实现。</p>
                        <a href="/article-demo" class="btn btn-outline-info">演示</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-charge me-2"></i>快速开始
                </h5>
            </div>
            <div class="card-body">
                <h6>通过Web界面：</h6>
                <ol>
                    <li>点击上方的"多代理工作流"、"AI模型测试"或"文章工作流"按钮</li>
                    <li>输入您的API密钥（仅在当前请求中使用，不会存储）</li>
                    <li>运行演示并查看结果</li>
                </ol>
                
                <h6>通过命令行：</h6>
                <div class="row">
                    <div class="col-md-6">
                        <pre class="bg-dark text-white p-3 rounded">php bin/console hello
php bin/console workflow:run
php bin/console model:test
php bin/test-workflow.php
php bin/test-graph.php</pre>
                    </div>
                    <div class="col-md-6">
                        <pre class="bg-dark text-white p-3 rounded">php bin/test-models.php
php bin/test-multiagent.php
php bin/test-advanced-collaboration.php</pre>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>项目特性
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>核心功能</h6>
                        <ul>
                            <li>基本MVC架构与路由系统</li>
                            <li>模板引擎用于渲染视图</li>
                            <li>集成Symfony工作流组件进行状态管理</li>
                            <li>自定义LangGraph风格工作流引擎实现</li>
                            <li>服务容器用于依赖管理</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>AI集成</h6>
                        <ul>
                            <li>DeepSeek和Qwen模型客户端工厂</li>
                            <li>多代理系统与通信协调</li>
                            <li>高级协作AI系统与群体智能</li>
                            <li>文章管理与状态转换</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>