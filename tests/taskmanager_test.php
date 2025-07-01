<?php
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../classes/Task.php');
require_once(__DIR__ . '/../classes/TaskManager.php');

use local_todolist\classes\Task;
use local_todolist\classes\TaskManager;

class local_todolist_taskmanager_test extends advanced_testcase {

    protected function setUp(): void {
        $this->resetAfterTest();
    }

    public function test_add_task_creates_task_in_db() {
        global $DB;

        $user = $this->getDataGenerator()->create_user();
        $manager = new TaskManager($user->id);

        $task = $manager->addTask('Test Task');

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->getName());
        $this->assertFalse($task->isCompleted());

        $record = $DB->get_record('local_todolist_items', ['id' => $task->getId()], '*', MUST_EXIST);
        $this->assertEquals($user->id, $record->userid);
    }

    public function test_load_tasks_returns_correct_tasks() {
        $user = $this->getDataGenerator()->create_user();
        $manager = new TaskManager($user->id);
        $manager->addTask('Task A');
        $manager->addTask('Task B');

        $tasks = $manager->loadTasks();
        $this->assertCount(2, $tasks);
        $this->assertEquals('Task A', $tasks[array_key_first($tasks)]->getName());
    }

    public function test_update_task_changes_db_record() {
        global $DB;

        $user = $this->getDataGenerator()->create_user();
        $manager = new TaskManager($user->id);
        $task = $manager->addTask('Initial');

        $task->rename('Updated');
        $task->markCompleted();
        $manager->updateTask($task);

        $record = $DB->get_record('local_todolist_items', ['id' => $task->getId()], '*', MUST_EXIST);
        $this->assertEquals('Updated', $record->name);
        $this->assertEquals(1, $record->completed);
    }

    public function test_get_task_fetches_exact_task() {
        $user = $this->getDataGenerator()->create_user();
        $manager = new TaskManager($user->id);
        $added = $manager->addTask('My Task');

        $fetched = $manager->getTask($added->getId());

        $this->assertEquals($added->getName(), $fetched->getName());
        $this->assertEquals($added->getId(), $fetched->getId());
    }

    public function test_remove_task_deletes_task_from_db() {
        global $DB;

        $user = $this->getDataGenerator()->create_user();
        $manager = new TaskManager($user->id);
        $task = $manager->addTask('Delete Me');

        $manager->removeTask($task->getId());

        $this->assertFalse($DB->record_exists('local_todolist_items', ['id' => $task->getId()]));
    }
}