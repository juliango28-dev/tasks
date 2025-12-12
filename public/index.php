<?php
/**
 * Página principal - Lista de tareas
 * Archivo: public/index.php
 */

// Incluir configuración
require_once '../src/config.php';
require_once '../src/models/User.php';
require_once '../src/models/Task.php';

// Conectar a la base de datos
$database = new Database();
if (!$database->connect()) {
    die('Error: ' . $database->getError());
}

$taskModel = new Task($database);
$tasks = $taskModel->getAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo APP_NAME; ?></h1>
            <nav>
                <a href="index.php" class="active">Tareas</a>
                <a href="create.php" class="btn-primary">+ Nueva Tarea</a>
            </nav>
        </header>

        <main>
            <div class="content">
                <?php if (empty($tasks)): ?>
                    <div class="empty-state">
                        <p>No hay tareas registradas</p>
                        <a href="create.php" class="btn-primary">Crear la primera tarea</a>
                    </div>
                <?php else: ?>
                    <table class="tasks-table">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Responsable</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Vence</th>
                                <th>Días Transcurridos</th>
                                <th>Asignado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td class="title"><?php echo htmlspecialchars($task['title']); ?></td>
                                    <td><?php echo $task['user_name'] ? htmlspecialchars($task['user_name']) : 'Sin asignar'; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $task['status']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-priority-<?php echo $task['priority']; ?>">
                                            <?php echo ucfirst($task['priority']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($task['due_date']) {
                                            echo date('d/m/Y', strtotime($task['due_date']));
                                        } else {
                                            echo 'Sin fecha';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $task['days_elapsed']; ?> días</td>
                                    <td><?php echo date('d/m/Y', strtotime($task['assigned_at'])); ?></td>
                                    <td class="actions">
                                        <a href="edit.php?id=<?php echo $task['id']; ?>" class="btn-edit">Editar</a>
                                        <button class="btn-delete" onclick="deleteTask(<?php echo $task['id']; ?>)">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>

        <footer>
            <p>&copy; 2025 <?php echo APP_NAME; ?>. Desarrollado con PHP nativo.</p>
        </footer>
    </div>

    <script src="js/main.js"></script>
    <script>
        function deleteTask(id) {
            if (confirm('¿Está seguro de que desea eliminar esta tarea?')) {
                window.location.href = 'actions/delete.php?id=' + id;
            }
        }
    </script>
</body>
</html>