<?php

namespace App\Database;

class Database
{
    private static $instance = null;
    private $connection;
    
    private function __construct()
    {
        $config = [
            'host' => 'localhost',
            'dbname' => 'mvp_app',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4'
        ];
        
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $this->connection = new \PDO($dsn, $config['username'], $config['password'], $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
}