<?php
/**
 * Página para crear una nueva tarea
 * Archivo: public/create.php
 */

require_once '../src/config.php';
require_once '../src/models/User.php';
require_once '../src/models/Task.php';

$database = new Database();
if (!$database->connect()) {
    die('Error: ' . $database->getError());
}

$userModel = new User($database);
$users = $userModel->getActiveUsers();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskModel = new Task($database);
    $result = $taskModel->create($_POST);
    
    if ($result['success']) {
        header('Location: index.php?msg=success');
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
    <title>Nueva Tarea - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo APP_NAME; ?></h1>
            <nav>
                <a href="index.php">Tareas</a>
                <a href="create.php" class="active btn-primary">+ Nueva Tarea</a>
            </nav>
        </header>

        <main>
            <div class="form-container">
                <h2>Crear Nueva Tarea</h2>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" id="taskForm" class="form">
                    <div class="form-group">
                        <label for="title">Título *</label>
                        <input type="text" id="title" name="title" required 
                               placeholder="Ingrese el título de la tarea"
                               value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                        <span class="error-message" id="titleError"></span>
                    </div>

                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea id="description" name="description" rows="4" 
                                  placeholder="Ingrese una descripción opcional"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="user_id">Responsable *</label>
                            <select id="user_id" name="user_id" required>
                                <option value="">Seleccione un responsable</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['id']; ?>" 
                                            <?php echo isset($_POST['user_id']) && $_POST['user_id'] == $user['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($user['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="error-message" id="userError"></span>
                        </div>

                        <div class="form-group">
                            <label for="priority">Prioridad</label>
                            <select id="priority" name="priority">
                                <option value="baja" <?php echo isset($_POST['priority']) && $_POST['priority'] === 'baja' ? 'selected' : ''; ?>>Baja</option>
                                <option value="media" <?php echo !isset($_POST['priority']) || $_POST['priority'] === 'media' ? 'selected' : ''; ?>>Media</option>
                                <option value="alta" <?php echo isset($_POST['priority']) && $_POST['priority'] === 'alta' ? 'selected' : ''; ?>>Alta</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select id="status" name="status">
                                <option value="pendiente" <?php echo !isset($_POST['status']) || $_POST['status'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="en_progreso" <?php echo isset($_POST['status']) && $_POST['status'] === 'en_progreso' ? 'selected' : ''; ?>>En Progreso</option>
                                <option value="completada" <?php echo isset($_POST['status']) && $_POST['status'] === 'completada' ? 'selected' : ''; ?>>Completada</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="due_date">Fecha Límite</label>
                            <input type="date" id="due_date" name="due_date"
                                   value="<?php echo isset($_POST['due_date']) ? htmlspecialchars($_POST['due_date']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Crear Tarea</button>
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