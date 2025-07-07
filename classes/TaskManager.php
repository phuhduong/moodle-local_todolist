<?php

namespace local_todolist\classes;

require_once(__DIR__ . '/Task.php');

class TaskManager {
    public function __construct(
        private int $userid
    ){
        if ($this->userid <= 0) {
            throw new \InvalidArgumentException('User id must be a positive integer');
        }
    }

    public function loadTasks(): array {
        global $DB;

        $records = $DB->get_records('local_todolist_items', ['userid' => $this->userid]);

        $tasks = [];
        foreach ($records as $record) {
            $task = new Task((int) $record->id, $record->name, (bool) $record->completed);
            $tasks[$record->id] = $task;
        }

        return $tasks;
    }

    public function addTask(string $name): Task {
        $name = trim($name);
        if ($name === '') {
            throw new \InvalidArgumentException('Task name cannot be empty');
        }

        global $DB;

        $record = new \stdClass();
        $record->userid = $this->userid;
        $record->name = $name;
        $record->completed = 0;

        $id = $DB->insert_record('local_todolist_items', $record);

        return new Task($id, $record->name, false);
    }

    public function updateTask(Task $task): void {
        if ($task === null) {
            throw new \InvalidArgumentException('Task cannot be null');
        }

        global $DB;

        $record = new \stdClass();
        $record->id = $task->getId();
        $record->userid = $this->userid;
        $record->name = $task->getName();
        $record->completed = 0;
        if ($task->isCompleted()) {
            $record->completed = 1;
        }

        $DB->update_record('local_todolist_items', $record);
    }

    public function removeTask(int $id): void {
        if ($id <= 0) {
            throw new \InvalidArgumentException('Task id must be a positive integer');
        }

        global $DB;

        $DB->delete_records('local_todolist_items', ['id' => $id, 'userid' => $this->userid]);
    }

    public function getTask(int $id): Task {
        global $DB;
        $record = $DB->get_record('local_todolist_items', ['id' => $id, 'userid' => $this->userid], '*', MUST_EXIST);
        return new Task((int)$record->id, $record->name, (bool)$record->completed);
    }
}