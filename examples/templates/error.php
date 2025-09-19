<?php
/**
 * @var string $title
 * @var string $heading
 * @var string $content
 */
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($title) ?>
                </h1>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                </div>
                <h2><?= htmlspecialchars($heading ?? '404 - 页面未找到') ?></h2>
                <p class="lead"><?= htmlspecialchars($content ?? '请求的页面不存在。') ?></p>
                <a href="/" class="btn btn-primary btn-lg">
                    <i class="bi bi-house me-2"></i>返回首页
                </a>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-question-circle me-2"></i>需要帮助？
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="bi bi-robot fs-1 text-primary mb-3"></i>
                        <h6>多代理工作流</h6>
                        <a href="/workflow-lab" class="btn btn-outline-primary">探索</a>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-cpu fs-1 text-success mb-3"></i>
                        <h6>AI模型测试</h6>
                        <a href="/model-test" class="btn btn-outline-success">测试</a>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-file-text fs-1 text-info mb-3"></i>
                        <h6>文章工作流</h6>
                        <a href="/article-demo" class="btn btn-outline-info">演示</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>