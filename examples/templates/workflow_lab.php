<?php
/**
 * @var string $title
 */
?>

<div class="card">
    <div class="card-header">
        <h1 class="mb-0">
            <i class="bi bi-robot me-2"></i><?= htmlspecialchars($title) ?>
        </h1>
    </div>
    <div class="card-body">
        <p class="card-text">运行复杂的多代理工作流，并实时查看执行流。选择预构建的工作流并提供任务描述。</p>
        
        <form id="workflowForm" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="deepseek_key" class="form-label">DeepSeek API Key</label>
                    <input type="password" id="deepseek_key" name="deepseek_key" class="form-control">
                    <div class="form-text">DeepSeek API密钥，用于访问DeepSeek模型（可选，可以从环境变量获取）</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="qwen_key" class="form-label">Qwen API Key</label>
                    <input type="password" id="qwen_key" name="qwen_key" class="form-control">
                    <div class="form-text">Qwen API密钥，用于访问Qwen模型（可选，可以从环境变量获取）</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="workflow_type" class="form-label">工作流类型</label>
                <select id="workflow_type" name="workflow_type" class="form-select">
                    <option value="simple">简单多代理工作流 (规划器 -> 执行器)</option>
                    <option value="advanced">高级协作工作流 (群体智能)</option>
                </select>
                <div class="form-text">选择要运行的工作流类型</div>
            </div>

            <div class="mb-3">
                <label for="task" class="form-label">任务描述</label>
                <textarea id="task" name="task" class="form-control" rows="3" placeholder="请输入任务描述">研究提高数据中心能源效率的方法，重点关注冷却和电源管理。</textarea>
                <div class="form-text">描述您希望代理系统完成的任务</div>
            </div>

            <div class="d-grid">
                <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">
                    <i class="bi bi-play-circle me-2"></i>运行工作流
                </button>
            </div>
        </form>

        <div id="status" class="mt-4 d-none"></div>
        <div id="output" class="mt-3 stream-output d-none"></div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-info-circle me-2"></i>工作流说明
        </h5>
    </div>
    <div class="card-body">
        <h6>简单多代理工作流:</h6>
        <ul>
            <li><strong>研究代理</strong>: 收集信息和分析数据</li>
            <li><strong>规划代理</strong>: 创建详细计划和策略</li>
            <li><strong>执行代理</strong>: 执行计划任务</li>
            <li><strong>审查代理</strong>: 审查和验证结果</li>
        </ul>
        
        <h6>高级协作工作流:</h6>
        <ul>
            <li><strong>智能任务分配</strong>: 根据代理能力和负载分配任务</li>
            <li><strong>动态协调</strong>: 实时协调代理活动</li>
            <li><strong>群体智能优化</strong>: 利用群体智能优化解决方案</li>
            <li><strong>分布式决策机制</strong>: 分布式和集体决策</li>
        </ul>
        
        <div class="alert alert-info">
            <i class="bi bi-lightbulb me-2"></i>
            <strong>提示:</strong> 高级协作工作流需要更多的API调用，但能提供更智能的解决方案。
        </div>
        
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>注意:</strong> API密钥可以从环境变量中获取，无需在表单中填写。如果已设置环境变量，请确保DEEPSEEK_API_KEY或QWEN_API_KEY环境变量已正确配置。
        </div>
    </div>
</div>

<script>
    // Global state tracking to prevent duplicate processing
    const processedSteps = new Set();
    let currentWorkflowId = null;

    document.getElementById('workflowForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const outputContainer = document.getElementById('output');
        const statusContainer = document.getElementById('status');
        let lastResponseLength = 0;

        // Generate unique workflow ID for this session
        currentWorkflowId = 'workflow_' + Date.now();
        
        // Clear processed steps for new workflow
        processedSteps.clear();

        const originalText = showLoading(submitBtn);
        outputContainer.innerHTML = '';
        outputContainer.classList.remove('d-none');
        statusContainer.classList.remove('alert-success', 'alert-danger');
        statusContainer.classList.add('alert', 'alert-info');
        statusContainer.innerHTML = '<span class="status-indicator status-running"></span>正在启动工作流...';
        statusContainer.classList.remove('d-none');

        const formData = new FormData(this);
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value.trim() !== '') {
                params.append(key, value);
            }
        }

        // Check if at least one API key is provided
        const deepseekKey = formData.get('deepseek_key') || '';
        const qwenKey = formData.get('qwen_key') || '';
        if (!deepseekKey && !qwenKey) {
            // Check if environment variables might be set
            console.warn('No API keys provided in form - relying on environment variables');
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/multi-agent/stream', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onprogress = function() {
            const newText = xhr.responseText.substring(lastResponseLength);
            lastResponseLength = xhr.responseText.length;

            if (newText) {
                const lines = newText.trim().split('\n');
                for (const line of lines) {
                    if (line.startsWith('data: ')) {
                        try {
                            const jsonData = JSON.parse(line.substring(6));
                            handleStreamData(jsonData);
                        } catch (e) {
                            // Log parsing errors for debugging
                            console.error('Error parsing JSON:', e, 'Line:', line);
                        }
                    }
                }
            }
        };

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) { // DONE
                restoreButton(submitBtn, originalText);
                if (xhr.status !== 200) {
                    statusContainer.classList.remove('alert-info');
                    statusContainer.classList.add('alert-danger');
                    statusContainer.innerHTML = '<span class="status-indicator status-error"></span>错误: HTTP ' + xhr.status + ' - ' + xhr.statusText;
                }
            }
        };
        
        xhr.send(params.toString());
    });

    function handleStreamData(data) {
        const statusContainer = document.getElementById('status');
        const outputContainer = document.getElementById('output');

        // Log incoming data for debugging
        console.debug('Received stream data:', data);

        switch (data.status) {
            case 'started':
                statusContainer.innerHTML = '<span class="status-indicator status-running"></span>工作流已启动。等待第一步...';
                break;
                
            case 'streaming':
                const state = data.step_state;
                const nodeName = state._currentNode || '未知步骤';
                
                // Create unique identifier for this step
                const stepId = `${currentWorkflowId}_${nodeName}_${Date.now()}`;
                
                // Check if we've already processed a similar step recently
                const stepKey = `${currentWorkflowId}_${nodeName}`;
                if (processedSteps.has(stepKey)) {
                    console.debug('Skipping duplicate step for node:', nodeName);
                    return;
                }
                
                // Mark this step as processed
                processedSteps.add(stepKey);
                
                let content = '';
                if(state.agent_response) {
                    content = state.agent_response;
                } else if (state.messages && state.messages.length > 0) {
                    const lastMessage = state.messages[state.messages.length - 1];
                    content = lastMessage.content;
                }

                // Check if a card for this node already exists
                const existingCard = outputContainer.querySelector(`.node-card[data-node="${nodeName}"]`);
                if (existingCard) {
                    // Update existing card content
                    const preElement = existingCard.querySelector('pre');
                    if (preElement) {
                        const currentContent = preElement.textContent;
                        // Only append if content is different
                        if (!currentContent.includes(content)) {
                            preElement.textContent = currentContent ? currentContent + '\n' + content : content;
                        }
                    }
                    // Update badge to show it's still processing
                    const badge = existingCard.querySelector('.badge');
                    if (badge) {
                        badge.className = 'badge bg-warning';
                        badge.textContent = '执行中';
                    }
                } else {
                    // Create new card
                    const card = `
                        <div class="card mb-3 node-card" data-node="${nodeName}" data-step-id="${stepId}">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <span>节点: <strong>${escapeHtml(nodeName)}</strong></span>
                                <span class="badge bg-warning text-dark">执行中</span>
                            </div>
                            <div class="card-body"> 
                                <pre class="mb-0">${escapeHtml(content)}</pre>
                            </div>
                        </div>`;
                    outputContainer.insertAdjacentHTML('beforeend', card);
                }
                outputContainer.scrollTop = outputContainer.scrollHeight;
                break;
                
            case 'completed':
                statusContainer.classList.remove('alert-info');
                statusContainer.classList.add('alert-success');
                statusContainer.innerHTML = '<span class="status-indicator status-completed"></span>工作流成功完成！';
                
                // Update all node cards status
                const nodeCards = outputContainer.querySelectorAll('.node-card');
                nodeCards.forEach(card => {
                    const badge = card.querySelector('.badge');
                    if (badge) {
                        badge.className = 'badge bg-success';
                        badge.textContent = '已完成';
                    }
                });
                
                const finalCard = `
                    <div class="card border-success mt-4">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <span>=== 最终状态 ===</span>
                            <span class="badge bg-light text-dark">结果</span>
                        </div>
                        <div class="card-body"> 
                            <pre class="mb-0">${escapeHtml(JSON.stringify(data.final_state, null, 2))}</pre>
                        </div>
                    </div>`;
                outputContainer.insertAdjacentHTML('beforeend', finalCard);
                outputContainer.scrollTop = outputContainer.scrollHeight;
                break;
                
            case 'error':
                statusContainer.classList.remove('alert-info');
                statusContainer.classList.add('alert-danger');
                statusContainer.innerHTML = '<span class="status-indicator status-error"></span>错误: ' + escapeHtml(data.message);
                break;
                
            case 'finished':
                // Workflow finished, clear processed steps
                processedSteps.clear();
                break;
        }
    }
    
    // Workflow type change handler
    document.getElementById('workflow_type').addEventListener('change', function() {
        const selectedValue = this.value;
        const alertDiv = document.querySelector('.alert-info');
        if (selectedValue === 'advanced') {
            alertDiv.innerHTML = '<i class="bi bi-lightbulb me-2"></i><strong>提示:</strong> 高级协作工作流使用群体智能算法，能提供更优化的解决方案，但需要更多的API调用和计算时间。';
        } else {
            alertDiv.innerHTML = '<i class="bi bi-lightbulb me-2"></i><strong>提示:</strong> 简单多代理工作流按顺序执行任务，适合快速获得结果。';
        }
    });
    
    // Utility function to escape HTML
    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') {
            unsafe = String(unsafe);
        }
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    
    // Utility functions for loading states
    function showLoading(button) {
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>运行中...';
        return originalText;
    }
    
    function restoreButton(button, originalText) {
        button.disabled = false;
        button.innerHTML = originalText;
    }
</script>