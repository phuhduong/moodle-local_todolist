<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../local/todolist/classes/TaskManager.php');
require_once(__DIR__ . '/../../local/todolist/classes/Task.php');

require_login();

use local_todolist\classes\TaskManager;

$PAGE->set_url(new moodle_url('/local/todolist/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_todolist'));
$PAGE->set_heading(get_string('pluginname', 'local_todolist'));

echo $OUTPUT->header();

$taskmanager = new TaskManager($USER->id);
$tasks = $taskmanager->loadTasks();

// Add Task Form
echo '<form method="post" action="' . new moodle_url('/local/todolist/actions/add.php') . '">';
echo '<input type="text" name="name" placeholder="New task name" required>';
echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
echo '<button type="submit">' . get_string('addtask', 'local_todolist') . '</button>';
echo '</form>';

echo '<hr>';

if (empty($tasks)) {
    echo html_writer::div(get_string('notasks', 'local_todolist'));
} else {
    echo html_writer::start_tag('ul');

    foreach ($tasks as $task) {
        echo html_writer::start_tag('li');

        $taskname = $task->isCompleted() ? '<del>' . s($task->getName()) . '</del>' : s($task->getName());
        echo $taskname . ' ';

        // Toggle
        echo '<form method="post" action="' . new moodle_url('/local/todolist/actions/toggle.php') . '" style="display:inline">';
        echo '<input type="hidden" name="id" value="' . $task->getId() . '">';
        echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
        echo '<button type="submit">' . get_string('completed', 'local_todolist') . '</button>';
        echo '</form> ';

        // Rename
        echo '<form method="post" action="' . new moodle_url('/local/todolist/actions/rename.php') . '" style="display:inline">';
        echo '<input type="hidden" name="id" value="' . $task->getId() . '">';
        echo '<input type="text" name="name" value="' . s($task->getName()) . '" required>';
        echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
        echo '<button type="submit">' . get_string('edit', 'local_todolist') . '</button>';
        echo '</form> ';

        // Delete
        echo '<form method="post" action="' . new moodle_url('/local/todolist/actions/delete.php') . '" style="display:inline">';
        echo '<input type="hidden" name="id" value="' . $task->getId() . '">';
        echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
        echo '<button type="submit">' . get_string('delete', 'local_todolist') . '</button>';
        echo '</form>';

        echo html_writer::end_tag('li');
    }

    echo html_writer::end_tag('ul');
}

echo $OUTPUT->footer();
