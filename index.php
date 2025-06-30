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

echo $OUTPUT->header();

$taskmanager = new TaskManager($USER->id);
$tasks = $taskmanager->loadTasks();

?>
    <div>
        <input type="text" id="newtaskname" placeholder="New task name">
        <button id="addtask">Add</button>
    </div>

    <hr>

    <ul id="tasklist">
        <?php foreach ($tasks as $task): ?>
            <li data-id="<?= $task->getId() ?>">
            <span class="taskname">
                <?= $task->isCompleted() ? '<del>' . s($task->getName()) . '</del>' : s($task->getName()) ?>
            </span>
                <button class="toggle">Toggle</button>
                <input type="text" class="renameinput" value="<?= s($task->getName()) ?>">
                <button class="rename">Rename</button>
                <button class="delete">Delete</button>
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
                .then(data => location.reload())
                .catch(err => alert("Failed to add task"));
        });

        document.querySelectorAll('.toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.closest('li').dataset.id;
                fetch('ajax.php?action=toggle', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id })
                }).then(res => res.json())
                    .then(data => location.reload());
            });
        });

        document.querySelectorAll('.rename').forEach(btn => {
            btn.addEventListener('click', () => {
                const li = btn.closest('li');
                const id = li.dataset.id;
                const name = li.querySelector('.renameinput').value.trim();
                fetch('ajax.php?action=rename', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id, name })
                }).then(res => res.json())
                    .then(data => location.reload());
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
                    .then(data => location.reload());
            });
        });
    </script>

<?php
echo $OUTPUT->footer();
