<?php
/**
 * @var string $title
 * @var App\Model\Article $article
 * @var array $availableTransitions
 * @var int $id
 */
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0">
                    <i class="bi bi-file-text me-2"></i><?= htmlspecialchars($title) ?>
                </h1>
            </div>
            <div class="card-body">
                <p class="card-text">此页面演示了Symfony工作流组件用于状态管理。下面的文章可以在不同状态之间转换（草稿、审核、已发布、已拒绝）。</p>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($article->getTitle()) ?></h5>
                        <p class="card-text">这是一个演示状态转换的示例文章。</p>
                        <p class="card-text">
                            当前状态: 
                            <?php
                            $status = $article->getStatus();
                            $statusClasses = [
                                'draft' => 'bg-secondary',
                                'review' => 'bg-warning',
                                'published' => 'bg-success',
                                'rejected' => 'bg-danger'
                            ];
                            $statusText = [
                                'draft' => '草稿',
                                'review' => '审核中',
                                'published' => '已发布',
                                'rejected' => '已拒绝'
                            ];
                            ?>
                            <span class="badge <?= $statusClasses[$status] ?? 'bg-secondary' ?> fs-6">
                                <?= $statusText[$status] ?? ucfirst($status) ?>
                            </span>
                        </p>
                    </div>
                </div>

                <h5>可用操作:</h5>
                <p>点击下面的按钮触发状态转换。</p>
                
                <?php if (empty($availableTransitions)): ?>
                    <div class="alert alert-light">当前状态下没有可用操作。</div>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <?php foreach ($availableTransitions as $transition): ?>
                            <?php
                            $transitionText = [
                                'request_review' => '请求审核',
                                'publish' => '发布',
                                'reject' => '拒绝',
                                'rework' => '重新编辑'
                            ];
                            ?>
                            <a href="/article-demo/transition/<?= $id ?>/<?= $transition ?>" class="btn btn-success">
                                <i class="bi bi-arrow-right-circle me-1"></i>
                                <?= $transitionText[$transition] ?? ucwords(str_replace('_', ' ', $transition)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <hr class="my-4">

                <h5>尝试其他状态:</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="/article-demo/1" class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark-text me-1"></i>加载草稿文章
                    </a>
                    <a href="/article-demo/2" class="btn btn-outline-secondary">
                        <i class="bi bi-eye me-1"></i>加载审核中文章
                    </a>
                    <a href="/article-demo/3" class="btn btn-outline-secondary">
                        <i class="bi bi-check-circle me-1"></i>加载已发布文章
                    </a>
                    <a href="/article-demo/4" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>加载已拒绝文章
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>工作流说明
                </h5>
            </div>
            <div class="card-body">
                <h6>文章状态转换:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul>
                            <li><strong>草稿</strong> → <strong>审核中</strong>: 请求审核</li>
                            <li><strong>审核中</strong> → <strong>已发布</strong>: 发布</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li><strong>审核中</strong> → <strong>已拒绝</strong>: 拒绝</li>
                            <li><strong>已拒绝</strong> → <strong>草稿</strong>: 重新编辑</li>
                        </ul>
                    </div>
                </div>
                
                <h6>状态说明:</h6>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <span class="badge bg-secondary">草稿</span> - 文章初始状态
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="badge bg-warning">审核中</span> - 文章等待审核
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="badge bg-success">已发布</span> - 文章已发布
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="badge bg-danger">已拒绝</span> - 文章被拒绝
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>提示:</strong> 点击"尝试其他状态"按钮可以快速切换到不同状态的文章进行演示。
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// 添加状态变化时的视觉反馈
document.addEventListener('DOMContentLoaded', function() {
    // 如果URL中有transition参数，说明刚进行了状态转换
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('transition')) {
        // 添加一个临时的提示信息
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
        alertDiv.innerHTML = `
            <i class="bi bi-check-circle me-2"></i>
            状态转换成功完成！
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.card-body').firstChild);
        
        // 3秒后自动关闭提示
        setTimeout(() => {
            if (alertDiv.classList.contains('show')) {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 150);
            }
        }, 3000);
    }
});
</script>