<?php
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../../../local/todolist/classes/TaskManager.php');

require_login();

use local_todolist\classes\TaskManager;

global $USER;

$id = required_param('id', PARAM_INT);

$manager = new TaskManager($USER->id);
$manager->removeTask($id);

redirect(new moodle_url('/local/todolist/index.php'));
