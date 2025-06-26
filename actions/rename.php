<?php
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../../../local/todolist/classes/TaskManager.php');

require_login();

use local_todolist\classes\TaskManager;

global $USER;

$id = required_param('id', PARAM_INT);
$name = required_param('name', PARAM_TEXT);

$manager = new TaskManager($USER->id);
$tasks = $manager->loadTasks();

if (!array_key_exists($id, $tasks)) {
    throw new moodle_exception('Task not found');
}

$task = $tasks[$id];
$task->rename($name);
$manager->updateTask($task);

redirect(new moodle_url('/local/todolist/index.php'));
