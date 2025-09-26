# LangGraph PHP SDK FAQ - Installation and Configuration

## How to install LangGraph PHP SDK?
You can install the package via Composer:

```bash
composer require langgraph/langgraph-php
```

## How to configure AI model API keys?
1. Copy `config/model.example.php` to `config/model.php`
2. Fill in your API keys in `config/model.php`:

```php
<?php
return [
    'deepseek_api_key' => 'your_deepseek_api_key_here',
    'qwen_api_key' => 'your_qwen_api_key_here',
];
```

Or set via environment variables:
- `DEEPSEEK_API_KEY`
- `QWEN_API_KEY`

## What are the system requirements?
- PHP 7.4 or higher
- Composer dependency manager