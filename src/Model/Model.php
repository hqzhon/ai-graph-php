<?php

namespace App\Model;

use App\Database\Database;

abstract class Model
{
    protected $db;
    protected $table;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }
    
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}