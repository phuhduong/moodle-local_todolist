<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/todolist:manage' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'user' => CAP_ALLOW,
        ],
    ],
];