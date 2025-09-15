<?php

namespace App\Middleware;

class CorsMiddleware extends Middleware
{
    public function handle($request)
    {
        // 设置CORS头部
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        
        // 如果是OPTIONS请求，直接返回
        if ($request->getMethod() === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        
        return parent::handle($request);
    }
}