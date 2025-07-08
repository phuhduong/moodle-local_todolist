<?php
global $OUTPUT, $PAGE, $USER;

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../local/todolist/classes/TaskManager.php');
require_once(__DIR__ . '/../../local/todolist/classes/Task.php');

require_login();

use local_todolist\classes\TaskManager;

$PAGE->set_url(new moodle_url('/local/todolist/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_todolist'));
$PAGE->set_heading(get_string('pluginname', 'local_todolist'));

$PAGE->requires->css(new moodle_url('/local/todolist/styles.css'));
$PAGE->requires->js_call_amd('local_todolist/actions', 'init');

echo $OUTPUT->header();

$taskmanager = new TaskManager($USER->id);
$tasks = $taskmanager->loadTasks();

$templatedata = [
    'tasks' => []
];

foreach ($tasks as $task) {
    $templatedata['tasks'][] = [
        'id' => $task->getId(),
        'name' => s($task->getName()),
        'completed' => $task->isCompleted(),
        'inputid' => 'renameinput_' . $task->getId(),
        'completedclass' => $task->isCompleted() ? 'completed' : ''
    ];
}

echo $OUTPUT->render_from_template('local_todolist/todolist', $templatedata);

echo $OUTPUT->footer();
