# LangGraph PHP SDK FAQ - 安装与配置

## 如何安装LangGraph PHP SDK？
您可以通过Composer安装此包：

```bash
composer require langgraph/langgraph-php
```

## 如何配置AI模型API密钥？
1. 复制`config/model.example.php`为`config/model.php`
2. 在`config/model.php`中填写您的API密钥：

```php
<?php
return [
    'deepseek_api_key' => 'your_deepseek_api_key_here',
    'qwen_api_key' => 'your_qwen_api_key_here',
];
```

或者通过环境变量设置：
- `DEEPSEEK_API_KEY`
- `QWEN_API_KEY`

## 系统要求是什么？
- PHP 7.4或更高版本
- Composer依赖管理器