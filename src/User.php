<?php

class User {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }
     // Obtener todos los usuarios activos
     
    public function getActiveUsers() {
        $sql = "SELECT id, name, email FROM users WHERE is_active = 1 ORDER BY name ASC";
        $result = $this->db->query($sql);
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
    // Obtener usuario por ID

    public function getUserById($id) {
        $sql = "SELECT id, name, email, is_active FROM users WHERE id = " . intval($id);
        $result = $this->db->query($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
     // Validar que el usuario está activo
     
    public function isActive($id) {
        $user = $this->getUserById($id);
        return $user && $user['is_active'] == 1;
    }
     // Obtener todos los usuarios
  
    public function getAllUsers() {
        $sql = "SELECT id, name, email, is_active, created_at FROM users ORDER BY created_at DESC";
        $result = $this->db->query($sql);
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
}
