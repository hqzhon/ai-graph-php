<?php

namespace App\Routing;

use App\Http\Request;
use App\Http\Response;

class Router
{
    private $routes = [];
    
    public function addRoute($method, $path, $callback)
    {
        $this->routes[strtoupper($method)][$path] = $callback;
    }
    
    public function resolve(Request $request)
    {
        $method = $request->getMethod();
        $path = $request->getUri();
        
        // 处理带参数的路由
        if (preg_match('/^\/article\/(\d+)$/', $path, $matches)) {
            $id = $matches[1];
            // 返回一个闭包，将ID传递给控制器方法
            return function() use ($id) {
                $controller = new \App\Controller\ArticleController();
                return $controller->view($id);
            };
        }
        
        // 处理带参数的转换路由
        if (preg_match('/^\/article\/(\d+)\/transition\/([a-z_]+)$/', $path, $matches)) {
            $id = $matches[1];
            $transition = $matches[2];
            // 返回一个闭包，将ID和转换名称传递给控制器方法
            return function() use ($id, $transition) {
                $controller = new \App\Controller\ArticleController();
                return $controller->transition($id, $transition);
            };
        }
        
        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }
        
        // Check for 404 handler
        if (isset($this->routes['404'])) {
            return $this->routes['404'];
        }
        
        return null;
    }
    
    public function handle(Request $request)
    {
        $callback = $this->resolve($request);
        
        if ($callback && is_callable($callback)) {
            $response = call_user_func($callback);
            
            if ($response instanceof Response) {
                return $response;
            } else {
                return new Response((string)$response);
            }
        } else {
            // Default 404 response
            return new Response('Not Found', 404);
        }
    }
}