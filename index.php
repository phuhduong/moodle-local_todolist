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
?>

    <div class="taskinput">
        <label for="newtaskname" class="accesshide">New task name</label>
        <input type="text" id="newtaskname" name="newtaskname" placeholder="New task name">
        <button type="button" id="addtask">Add</button>
    </div>

    <hr>

    <ul id="tasklist">
        <?php foreach ($tasks as $task): ?>
            <?php
            $id = $task->getId();
            $name = s($task->getName());
            $completed = $task->isCompleted();
            $inputid = "renameinput_$id";
            ?>
            <li data-id="<?= $id ?>" class="<?= $task->isCompleted() ? 'completed' : '' ?>">
                <span class="taskname"><?= $name ?>

                <?php if ($completed): ?>
                    <span class="accesshide">Completed task <?= $name ?></span>
                <?php endif; ?>

                <label for="<?= $inputid ?>" class="accesshide">Rename task <?= $name ?></label>
                <input type="text"
                       id="<?= $inputid ?>"
                       name="<?= $inputid ?>"
                       class="renameinput"
                       value="<?= $name ?>"
                       style="display: none;">

                <button class="toggle" name="Toggle task <?= $name ?>">Toggle</button>
                <button class="rename" name="Rename task <?= $name ?>">Rename</button>
                <button class="delete" name="Delete task <?= $name ?>">Delete</button>
            </li>
        <?php endforeach; ?>
    </ul>

<?php echo $OUTPUT->footer();
