<?php

namespace local_todolist\external;

require_once(__DIR__ . '/../TaskManager.php');
require_once(__DIR__ . '/../Task.php');

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;
use local_todolist\classes\TaskManager;

class todolist_service extends external_api {

    public static function add_task_parameters() {
        return new external_function_parameters([
            'name' => new external_value(PARAM_TEXT, 'Task name')
        ]);
    }

    public static function add_task($name) {
        global $USER;

        $params = self::validate_parameters(self::add_task_parameters(), [
            'name' => $name
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('local/todolist:manage', $context);

        $taskmanager = new TaskManager($USER->id);
        $task = $taskmanager->addTask($params['name']);

        return [
            'success' => true,
            'task' => [
                'id' => $task->getId(),
                'name' => $task->getName(),
                'completed' => $task->isCompleted()
            ]
        ];
    }

    public static function add_task_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Success status'),
            'task' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Task ID'),
                'name' => new external_value(PARAM_TEXT, 'Task name'),
                'completed' => new external_value(PARAM_BOOL, 'Task completion status')
            ])
        ]);
    }

    public static function toggle_task_parameters() {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'Task ID')
        ]);
    }

    public static function toggle_task($id) {
        global $USER;

        $params = self::validate_parameters(self::toggle_task_parameters(), [
            'id' => $id
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('local/todolist:manage', $context);

        $taskmanager = new TaskManager($USER->id);
        $task = $taskmanager->getTask($params['id']);

        if ($task->isCompleted()) {
            $task->markPending();
        } else {
            $task->markCompleted();
        }

        $taskmanager->updateTask($task);

        return ['success' => true];
    }

    public static function toggle_task_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Success status')
        ]);
    }

    public static function rename_task_parameters() {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'Task ID'),
            'name' => new external_value(PARAM_TEXT, 'New task name')
        ]);
    }

    public static function rename_task($id, $name) {
        global $USER;

        $params = self::validate_parameters(self::rename_task_parameters(), [
            'id' => $id,
            'name' => $name
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('local/todolist:manage', $context);

        $taskmanager = new TaskManager($USER->id);
        $task = $taskmanager->getTask($params['id']);
        $task->rename($params['name']);
        $taskmanager->updateTask($task);

        return ['success' => true];
    }

    public static function rename_task_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Success status')
        ]);
    }

    public static function delete_task_parameters() {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'Task ID')
        ]);
    }

    public static function delete_task($id) {
        global $USER;

        $params = self::validate_parameters(self::delete_task_parameters(), [
            'id' => $id
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('local/todolist:manage', $context);

        $taskmanager = new TaskManager($USER->id);
        $taskmanager->removeTask($params['id']);

        return ['success' => true];
    }

    public static function delete_task_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Success status')
        ]);
    }
}