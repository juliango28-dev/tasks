<?php


class Task {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }
    
     // Crear una nueva tarea
   
    public function create($data) {
        // Validaciones básicas
        if (empty($data['title'])) {
            return ['success' => false, 'error' => 'El título es obligatorio'];
        }

        if (empty($data['user_id'])) {
            return ['success' => false, 'error' => 'Debe seleccionar un responsable'];
        }

        // Validar que el usuario está activo
        $userModel = new User($this->db);
        if (!$userModel->isActive($data['user_id'])) {
            return ['success' => false, 'error' => 'El responsable seleccionado no está activo'];
        }

        $title = $this->db->escape($data['title']);
        $description = isset($data['description']) ? $this->db->escape($data['description']) : '';
        $user_id = intval($data['user_id']);
        $status = isset($data['status']) ? $this->db->escape($data['status']) : 'pendiente';
        $priority = isset($data['priority']) ? $this->db->escape($data['priority']) : 'media';
        $due_date = isset($data['due_date']) && !empty($data['due_date']) ? $data['due_date'] : NULL;

        $sql = "INSERT INTO tasks (title, description, user_id, status, priority, due_date) 
                VALUES ('$title', '$description', $user_id, '$status', '$priority'";
        
        if ($due_date) {
            $due_date = $this->db->escape($due_date);
            $sql .= ", '$due_date')";
        } else {
            $sql .= ", NULL)";
        }

        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => 'Tarea creada exitosamente'];
        } else {
            return ['success' => false, 'error' => 'Error al crear la tarea'];
        }
    }

    public function getAll() {
        $sql = "SELECT t.*, u.name as user_name, u.email as user_email 
                FROM tasks t 
                LEFT JOIN users u ON t.user_id = u.id 
                ORDER BY t.created_at DESC";
        
        $result = $this->db->query($sql);
        
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            // Calcular días transcurridos
            $created = new DateTime($row['created_at']);
            $now = new DateTime();
            $interval = $now->diff($created);
            $row['days_elapsed'] = $interval->days;
            
            $tasks[] = $row;
        }
        return $tasks;
    }

     //funcion para obterner una tarea
    public function getById($id) {
        $sql = "SELECT t.*, u.name as user_name 
                FROM tasks t 
                LEFT JOIN users u ON t.user_id = u.id 
                WHERE t.id = " . intval($id);
        
        $result = $this->db->query($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    //Funcion para actualizar una tarea
    public function update($id, $data) {
        $id = intval($id);

        if (empty($data['title'])) {
            return ['success' => false, 'error' => 'El título es obligatorio'];
        }

        if (empty($data['user_id'])) {
            return ['success' => false, 'error' => 'Debe seleccionar un responsable'];
        }

        $userModel = new User($this->db);
        if (!$userModel->isActive($data['user_id'])) {
            return ['success' => false, 'error' => 'El responsable seleccionado no está activo'];
        }

        $title = $this->db->escape($data['title']);
        $description = isset($data['description']) ? $this->db->escape($data['description']) : '';
        $user_id = intval($data['user_id']);
        $status = isset($data['status']) ? $this->db->escape($data['status']) : 'pendiente';
        $priority = isset($data['priority']) ? $this->db->escape($data['priority']) : 'media';
        $due_date = isset($data['due_date']) && !empty($data['due_date']) ? $this->db->escape($data['due_date']) : 'NULL';

        $assigned_at = 'NOW()';
        
        $sql = "UPDATE tasks SET 
                title = '$title', 
                description = '$description', 
                user_id = $user_id, 
                status = '$status', 
                priority = '$priority', 
                due_date = " . ($due_date === 'NULL' ? 'NULL' : "'$due_date'") . ", 
                assigned_at = $assigned_at 
                WHERE id = $id";

        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => 'Tarea actualizada exitosamente'];
        } else {
            return ['success' => false, 'error' => 'Error al actualizar la tarea'];
        }
    }
    
  // Funcion para Eliminar una tarea
    public function delete($id) {
        $id = intval($id);

        $sql = "DELETE FROM tasks WHERE id = $id";

        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => 'Tarea eliminada exitosamente'];
        } else {
            return ['success' => false, 'error' => 'Error al eliminar la tarea'];
        }
    }
}
