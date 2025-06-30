<?php
global $USER;

define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../local/todolist/classes/TaskManager.php');
require_once(__DIR__ . '/../../local/todolist/classes/Task.php');

require_login();

use local_todolist\classes\TaskManager;

$action = required_param('action', PARAM_ALPHANUMEXT);

$taskmanager = new TaskManager($USER->id);

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'add':
            $name = required_param('name', PARAM_TEXT);
            $task = $taskmanager->addTask($name);
            echo json_encode(['success' => true, 'task' => [
                'id' => $task->getId(),
                'name' => $task->getName(),
                'completed' => $task->isCompleted()
            ]]);
            break;

        case 'toggle':
            $id = required_param('id', PARAM_INT);
            $task = $taskmanager->getTask($id);
            if ($task->isCompleted()) {
                $task->markPending();
            } else {
                $task->markCompleted();
            }
            $taskmanager->updateTask($task);
            echo json_encode(['success' => true]);
            break;

        case 'rename':
            $id = required_param('id', PARAM_INT);
            $name = required_param('name', PARAM_TEXT);
            $task = $taskmanager->getTask($id);
            $task->rename($name);
            $taskmanager->updateTask($task);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = required_param('id', PARAM_INT);
            $taskmanager->removeTask($id);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new \moodle_exception('Invalid action');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}