<?php
/**
 * Página para editar una tarea
 * Archivo: public/edit.php
 */

require_once '../src/config.php';
require_once '../src/models/User.php';
require_once '../src/models/Task.php';

$database = new Database();
if (!$database->connect()) {
    die('Error: ' . $database->getError());
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$taskModel = new Task($database);
$userModel = new User($database);

$task = $taskModel->getById($_GET['id']);
if (!$task) {
    header('Location: index.php');
    exit();
}

$users = $userModel->getActiveUsers();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $taskModel->update($_GET['id'], $_POST);
    
    if ($result['success']) {
        header('Location: index.php?msg=updated');
        exit();
    } else {
        $error = $result['error'];
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo APP_NAME; ?></h1>
            <nav>
                <a href="index.php">Tareas</a>
                <a href="create.php">+ Nueva Tarea</a>
            </nav>
        </header>

        <main>
            <div class="form-container">
                <h2>Editar Tarea</h2>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" id="taskForm" class="form">
                    <div class="form-group">
                        <label for="title">Título *</label>
                        <input type="text" id="title" name="title" required 
                               placeholder="Ingrese el título de la tarea"
                               value="<?php echo htmlspecialchars($task['title']); ?>">
                        <span class="error-message" id="titleError"></span>
                    </div>

                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea id="description" name="description" rows="4" 
                                  placeholder="Ingrese una descripción opcional"><?php echo htmlspecialchars($task['description']); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="user_id">Responsable *</label>
                            <select id="user_id" name="user_id" required>
                                <option value="">Seleccione un responsable</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['id']; ?>" 
                                            <?php echo $task['user_id'] == $user['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($user['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="error-message" id="userError"></span>
                        </div>

                        <div class="form-group">
                            <label for="priority">Prioridad</label>
                            <select id="priority" name="priority">
                                <option value="baja" <?php echo $task['priority'] === 'baja' ? 'selected' : ''; ?>>Baja</option>
                                <option value="media" <?php echo $task['priority'] === 'media' ? 'selected' : ''; ?>>Media</option>
                                <option value="alta" <?php echo $task['priority'] === 'alta' ? 'selected' : ''; ?>>Alta</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select id="status" name="status">
                                <option value="pendiente" <?php echo $task['status'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="en_progreso" <?php echo $task['status'] === 'en_progreso' ? 'selected' : ''; ?>>En Progreso</option>
                                <option value="completada" <?php echo $task['status'] === 'completada' ? 'selected' : ''; ?>>Completada</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="due_date">Fecha Límite</label>
                            <input type="date" id="due_date" name="due_date"
                                   value="<?php echo $task['due_date'] ? $task['due_date'] : ''; ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Guardar Cambios</button>
                        <a href="index.php" class="btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </main>

        <footer>
            <p>&copy; 2025 <?php echo APP_NAME; ?>. Desarrollado con PHP nativo.</p>
        </footer>
    </div>

    <script src="js/main.js"></script>
</body>
</html>