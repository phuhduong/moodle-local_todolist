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

    <script>
        document.getElementById('addtask').addEventListener('click', () => {
            const name = document.getElementById('newtaskname').value.trim();
            if (!name) return alert("Enter task name");

            fetch('ajax.php?action=add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ name })
            }).then(res => res.json())
                .then(() => location.reload())
                .catch(() => alert("Failed to add task"));
        });

        document.querySelectorAll('.toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.closest('li').dataset.id;
                fetch('ajax.php?action=toggle', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id })
                }).then(res => res.json())
                    .then(() => location.reload());
            });
        });

        document.querySelectorAll('.rename').forEach(btn => {
            btn.addEventListener('click', () => {
                const li = btn.closest('li');
                const taskname = li.querySelector('.taskname');
                const input = li.querySelector('.renameinput');

                if (input.style.display === 'none') {
                    input.style.display = 'inline';
                    taskname.style.display = 'none';
                    input.focus();
                } else {
                    const id = li.dataset.id;
                    const name = input.value.trim();
                    if (!name) return;

                    fetch('ajax.php?action=rename', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ id, name })
                    }).then(res => res.json())
                        .then(() => location.reload());
                }
            });
        });

        document.querySelectorAll('.delete').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.closest('li').dataset.id;
                fetch('ajax.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id })
                }).then(res => res.json())
                    .then(() => location.reload());
            });
        });
    </script>

<?php echo $OUTPUT->footer();
