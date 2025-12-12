<?php
/**
 * Acción para eliminar una tarea
 * Archivo: public/delete.php
 */

require_once '../../src/config.php';
require_once '../../src/models/Task.php';

$database = new Database();
if (!$database->connect()) {
    header('Location: ../index.php?error=connection');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ../index.php');
    exit();
}

$taskModel = new Task($database);
$result = $taskModel->delete($_GET['id']);

if ($result['success']) {
    header('Location: ../index.php?msg=deleted');
} else {
    header('Location: ../index.php?error=delete_failed');
}
exit();