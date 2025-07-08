<?php

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_todolist_add_task' => [
        'classname' => 'local_todolist\external\todolist_service',
        'methodname' => 'add_task',
        'description' => 'Add a new task',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'local_todolist_toggle_task' => [
        'classname' => 'local_todolist\external\todolist_service',
        'methodname' => 'toggle_task',
        'description' => 'Toggle task completion status',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'local_todolist_rename_task' => [
        'classname' => 'local_todolist\external\todolist_service',
        'methodname' => 'rename_task',
        'description' => 'Rename a task',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'local_todolist_delete_task' => [
        'classname' => 'local_todolist\external\todolist_service',
        'methodname' => 'delete_task',
        'description' => 'Delete a task',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
];