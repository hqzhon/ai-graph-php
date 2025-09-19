<?php
// router.php - 用于PHP内置服务器的路由文件

// 如果请求的是文件且存在，则直接返回该文件
if (is_file($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
    return false;
}

// 否则将请求转发给index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require $_SERVER['DOCUMENT_ROOT'] . '/index.php';