<?php

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'task_manager');

//define('APP_NAME', 'Gestor de Tareas');
//define('BASE_URL', 'http://localhost/task-manager/public/');

class Database {
    private $conn;
    private $error;

    public function connect() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            $this->error = 'Error de conexión: ' . $this->conn->connect_error;
            return false;
        }

        // Configurar charset
        $this->conn->set_charset('utf8mb4');
        return true;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function getError() {
        return $this->error;
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    public function escape($string) {
        return $this->conn->real_escape_string($string);
    }

    public function close() {
        $this->conn->close();
    }
}
