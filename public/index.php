<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Kernel;
use App\Config\Config;
use App\Routing\Router;
use App\Controller\DefaultController;
use App\Controller\ArticleController;
use App\Http\Request;

// 加载配置
Config::load(__DIR__ . '/../config/app.php');

// 初始化控制器和路由
$defaultController = new DefaultController();
$articleController = new ArticleController();
$router = new Router();

// 添加路由
$router->addRoute('GET', '/', [$defaultController, 'index']);
$router->addRoute('GET', '/article/create', [$articleController, 'create']);
// 其他文章路由在 Router::resolve() 方法中动态处理

// 处理请求
$request = new Request();
$response = $router->handle($request);
$response->send();