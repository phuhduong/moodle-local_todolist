<?php
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../../../local/todolist/classes/TaskManager.php');

require_login();

use local_todolist\classes\TaskManager;

global $USER;

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$name = required_param('name', PARAM_TEXT);

$manager = new TaskManager($USER->id);
$task = $manager->addTask($name);

redirect(new moodle_url('/local/todolist/index.php'));
