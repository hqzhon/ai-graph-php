<?php
/**
 * @var string $title
 * @var string|null $error
 * @var string|null $response
 * @var array $defaults
 */

$defaults = $defaults ?? [];
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0">
                    <i class="bi bi-cpu me-2"></i><?= htmlspecialchars($title) ?>
                </h1>
            </div>
            <div class="card-body">
                <p class="card-text">选择模型提供商，输入您的API密钥和提示词来测试模型的响应。</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form id="modelTestForm" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="model_type" class="form-label">模型</label>
                            <select id="model_type" name="model_type" class="form-select">
                                <option value="deepseek" <?= ($defaults['model_type'] ?? '') === 'deepseek' ? 'selected' : '' ?>>DeepSeek</option>
                                <option value="qwen" <?= ($defaults['model_type'] ?? '') === 'qwen' ? 'selected' : '' ?>>Qwen</option>
                            </select>
                            <div class="form-text">选择要测试的AI模型</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="model_name" class="form-label">模型名称 (可选)</label>
                            <input type="text" id="model_name" name="model_name" class="form-control" placeholder="例如: deepseek-chat" value="<?= htmlspecialchars($defaults['model_name'] ?? '') ?>">
                            <div class="form-text">指定特定的模型版本</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="deepseek_key" class="form-label">DeepSeek API Key</label>
                            <input type="password" id="deepseek_key" name="deepseek_key" class="form-control" value="<?= htmlspecialchars($defaults['deepseek_key'] ?? '') ?>">
                            <div class="form-text">DeepSeek API密钥，用于访问DeepSeek模型</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="qwen_key" class="form-label">Qwen API Key</label>
                            <input type="password" id="qwen_key" name="qwen_key" class="form-control" value="<?= htmlspecialchars($defaults['qwen_key'] ?? '') ?>">
                            <div class="form-text">Qwen API密钥，用于访问Qwen模型</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="prompt" class="form-label">提示词</label>
                        <textarea id="prompt" name="prompt" class="form-control" rows="4" placeholder="请输入您的提示词"><?= htmlspecialchars($defaults['prompt'] ?? '什么是法国的首都在哪里？它的历史是怎样的？') ?></textarea>
                        <div class="form-text">输入您想测试的提示词</div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="streaming" name="streaming" checked>
                        <label class="form-check-label" for="streaming">
                            启用逐字流输出 (Streaming)
                        </label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">
                            <i class="bi bi-play-circle me-2"></i>测试模型
                        </button>
                    </div>
                </form>

                <div class="mt-4" id="responseSection" style="display: none;">
                    <h3>
                        <i class="bi bi-chat-square-text me-2"></i>模型响应:
                    </h3>
                    <div class="card bg-light">
                        <div class="card-body">
                            <pre id="responseText" class="mb-0" style="white-space: pre-wrap; word-wrap: break-word; min-height: 50px;"></pre>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard()">
                            <i class="bi bi-clipboard me-1"></i>复制响应
                        </button>
                    </div>
                </div>
                
                <div class="mt-3" id="streamingProgress" style="display: none;">
                    <div class="progress">
                        <div id="streamProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div class="mt-2">
                        <span id="characterCount">0</span> characters streamed
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>使用说明
                </h5>
            </div>
            <div class="card-body">
                <ul>
                    <li>API密钥仅在当前请求中使用，不会存储在服务器上</li>
                    <li>支持DeepSeek和Qwen模型</li>
                    <li>可以通过模型名称指定特定的模型版本</li>
                    <li>如果未提供API密钥，系统将尝试从环境变量中获取</li>
                    <li><strong>启用逐字流输出</strong>: 实时显示AI响应，字符逐个出现</li>
                </ul>
                
                <h6>配置方法:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <strong>环境变量:</strong>
                        <pre class="bg-dark text-white p-2 rounded">export DEEPSEEK_API_KEY=your_key
export QWEN_API_KEY=your_key</pre>
                    </div>
                    <div class="col-md-6">
                        <strong>配置文件:</strong>
                        <pre class="bg-dark text-white p-2 rounded">&lt;?php
return [
    'deepseek_api_key' => 'your_key',
    'qwen_api_key' => 'your_key',
];
</pre>
                    </div>
                </div>
                
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>注意:</strong> 请妥善保管您的API密钥，避免泄露给他人。
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('modelTestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = showLoading(submitBtn);
    
    const streamingEnabled = document.getElementById('streaming').checked;
    const responseSection = document.getElementById('responseSection');
    const responseText = document.getElementById('responseText');
    const streamingProgress = document.getElementById('streamingProgress');
    const streamProgressBar = document.getElementById('streamProgressBar');
    const characterCount = document.getElementById('characterCount');
    
    // Reset UI
    responseText.textContent = '';
    responseSection.style.display = 'none';
    streamingProgress.style.display = 'none';
    streamProgressBar.style.width = '0%';
    characterCount.textContent = '0';
    
    // Validate form inputs
    const formData = new FormData(this);
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        params.append(key, value);
    }
    
    // Check if at least one API key is provided
    const deepseekKey = formData.get('deepseek_key') || '';
    const qwenKey = formData.get('qwen_key') || '';
    if (!deepseekKey && !qwenKey) {
        // Check if environment variables might be set
        console.warn('No API keys provided in form - relying on environment variables');
    }
    
    if (streamingEnabled) {
        // Use streaming endpoint
        streamModelResponse(params, submitBtn, originalText);
    } else {
        // Use regular endpoint
        regularModelResponse(params, submitBtn, originalText);
    }
});

function streamModelResponse(params, submitBtn, originalText) {
    const responseSection = document.getElementById('responseSection');
    const responseText = document.getElementById('responseText');
    const streamingProgress = document.getElementById('streamingProgress');
    const streamProgressBar = document.getElementById('streamProgressBar');
    const characterCount = document.getElementById('characterCount');
    
    // Initialize scoped variables
    let accumulatedText = '';
    let totalCharacters = 0;
    
    // Show streaming UI
    streamingProgress.style.display = 'block';
    responseSection.style.display = 'block';
    
    // Use fetch with POST method for streaming
    fetch('/streaming/model-test/stream', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params.toString()
    })
    .then(response => {
        // Check if response is successful
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        
        function readStream() {
            reader.read().then(({done, value}) => {
                if (done) {
                    // Stream completed
                    streamProgressBar.style.width = '100%';
                    streamProgressBar.classList.remove('progress-bar-animated');
                    restoreButton(submitBtn, originalText);
                    return;
                }
                
                const text = decoder.decode(value, {stream: true});
                const lines = text.split('\n\n');
                
                for (const line of lines) {
                    if (line.startsWith('data: ')) {
                        try {
                            const data = JSON.parse(line.substring(6));
                            
                            switch (data.status) {
                                case 'started':
                                    console.log('Streaming started');
                                    break;
                                    
                                case 'streaming':
                                    // Add character to response
                                    accumulatedText += data.character;
                                    responseText.textContent = accumulatedText;
                                    totalCharacters++;
                                    characterCount.textContent = totalCharacters;
                                    
                                    // Update progress bar (just for visual effect)
                                    const progress = Math.min((totalCharacters % 100) + 1, 100);
                                    streamProgressBar.style.width = progress + '%';
                                    
                                    // Scroll to bottom
                                    responseText.parentElement.scrollTop = responseText.parentElement.scrollHeight;
                                    break;
                                    
                                case 'completed':
                                    console.log('Streaming completed with ' + data.total_characters + ' characters');
                                    streamProgressBar.style.width = '100%';
                                    streamProgressBar.classList.remove('progress-bar-animated');
                                    break;
                                    
                                case 'error':
                                    console.error('Streaming error:', data.message);
                                    responseText.textContent = 'Error: ' + data.message;
                                    // Stop streaming on error
                                    reader.cancel();
                                    restoreButton(submitBtn, originalText);
                                    break;
                            }
                        } catch (e) {
                            console.error('Error parsing streaming data:', e);
                        }
                    }
                }
                
                // Continue reading the stream
                readStream();
            }).catch(error => {
                console.error('Stream reading error:', error);
                responseText.textContent = 'Connection error occurred: ' + error.message;
                restoreButton(submitBtn, originalText);
            });
        }
        
        readStream();
    })
    .catch(error => {
        console.error('Fetch error:', error);
        responseText.textContent = 'Connection error occurred: ' + error.message;
        restoreButton(submitBtn, originalText);
    });
}

function regularModelResponse(params, submitBtn, originalText) {
    const responseSection = document.getElementById('responseSection');
    const responseText = document.getElementById('responseText');
    
    fetch('/model-test/process', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params.toString()
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(html => {
        // Directly replace the entire page content
        document.open();
        document.write(html);
        document.close();
        restoreButton(submitBtn, originalText);
    })
    .catch(error => {
        console.error('Error:', error);
        restoreButton(submitBtn, originalText);
        alert('发生错误，请查看控制台了解详情: ' + error.message);
    });
}

function copyToClipboard() {
    const responseElement = document.querySelector('#responseText');
    if (responseElement) {
        const text = responseElement.textContent;
        navigator.clipboard.writeText(text).then(() => {
            // Show success message
            const copyBtn = event.target.closest('button') || event.target;
            const originalHTML = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="bi bi-check me-1"></i>已复制';
            setTimeout(() => {
                copyBtn.innerHTML = originalHTML;
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('复制失败，请手动复制');
        });
    }
}

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

// Model type change handler
document.getElementById('model_type').addEventListener('change', function() {
    const selectedValue = this.value;
    const modelNameInput = document.getElementById('model_name');
    if (selectedValue === 'deepseek') {
        modelNameInput.placeholder = '例如: deepseek-chat';
    } else {
        modelNameInput.placeholder = '例如: qwen-plus';
    }
});

// Utility functions for loading states
function showLoading(button) {
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>测试中...';
    return originalText;
}

function restoreButton(button, originalText) {
    button.disabled = false;
    button.innerHTML = originalText;
}
</script>