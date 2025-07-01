<?php
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../classes/Task.php');

use local_todolist\classes\Task;

class local_todolist_task_test extends advanced_testcase {

    public function test_valid_task_construction() {
        $task = new Task(id: 1, name: 'Buy milk');

        $this->assertEquals(1, $task->getId());
        $this->assertEquals('Buy milk', $task->getName());
        $this->assertFalse($task->isCompleted());
    }

    public function test_trimmed_name_on_creation() {
        $task = new Task(id: 1, name: '   Clean room   ');
        $this->assertEquals('Clean room', $task->getName());
    }

    public function test_constructor_invalid_id() {
        $this->expectException(InvalidArgumentException::class);
        new Task(id: 0, name: 'Some task');
    }

    public function test_constructor_empty_name() {
        $this->expectException(InvalidArgumentException::class);
        new Task(id: 1, name: '   ');
    }

    public function test_rename_with_valid_name() {
        $task = new Task(id: 1, name: 'Initial');
        $task->rename('Updated');
        $this->assertEquals('Updated', $task->getName());
    }

    public function test_rename_with_empty_name() {
        $task = new Task(id: 1, name: 'Initial');
        $this->expectException(InvalidArgumentException::class);
        $task->rename('   ');
    }

    public function test_mark_completed_and_pending() {
        $task = new Task(id: 1, name: 'Do it');
        $this->assertFalse($task->isCompleted());

        $task->markCompleted();
        $this->assertTrue($task->isCompleted());

        $task->markPending();
        $this->assertFalse($task->isCompleted());
    }
}