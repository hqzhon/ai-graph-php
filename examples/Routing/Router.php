<?php

namespace App\Examples\Routing;

use App\Examples\Http\Request;
use App\Examples\Http\Response;
use Psr\Container\ContainerInterface;

class Router
{
    private $routes = [];
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addRoute($method, $path, $callback)
    {
        $this->routes[] = ['method' => strtoupper($method), 'path' => $path, 'callback' => $callback];
    }

    public function resolve(Request $request)
    {
        $requestMethod = $request->getMethod();
        $requestPath = $request->getUri();

        foreach ($this->routes as $route) {
            // Convert route path with placeholders to a regex
            $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '([a-zA-Z0-9_]+)', $route['path']);
            $pattern = "#^" . $pattern . "$#";

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestPath, $matches)) {
                array_shift($matches); // Remove the full match
                return ['callback' => $route['callback'], 'params' => $matches];
            }
        }
        return null;
    }

    public function handle(Request $request): Response
    {
        $route = $this->resolve($request);

        if ($route === null) {
            // 处理404错误，使用错误模板
            try {
                $template = $this->container->get(\App\View\Template::class);
                $content = $template->render('error', [
                    'title' => '页面未找到',
                    'heading' => '404 - 页面未找到',
                    'content' => '抱歉，您访问的页面不存在。'
                ]);
                return new Response($content, 404);
            } catch (\Exception $e) {
                // 如果模板渲染失败，返回简单的404响应
                return new Response('Not Found', 404);
            }
        }

        $callback = $route['callback'];
        $params = $route['params'];

        if (is_array($callback)) {
            [$controllerClass, $method] = $callback;
            if ($this->container->has($controllerClass)) {
                $controller = $this->container->get($controllerClass);
                $response = $controller->{$method}(...$params);
            } else {
                return new Response("Controller {$controllerClass} not found in container", 500);
            }
        } elseif (is_callable($callback)) {
            $response = call_user_func_array($callback, $params);
        } else {
            return new Response('Invalid callback for route', 500);
        }

        if ($response instanceof Response) {
            return $response;
        }

        return new Response((string)$response);
    }
}