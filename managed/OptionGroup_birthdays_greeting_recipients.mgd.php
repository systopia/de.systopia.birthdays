<?php
return [
    [
        'name' => 'OptionGroup_birthday_greeting_recipients',
        'entity' => 'OptionGroup',
        'cleanup' => 'always',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'birthday_greeting_recipients',
                'title' => ts('Birthday greeting recipients'),
                'description' => ts('Option group for managing birthday greeting recipients.'),
                'is_reserved' => TRUE,
                'is_active' => TRUE,
                'is_locked' => TRUE,
                'option_value_fields' => ['name', 'label', 'description', 'icon'],
            ],
        ],
        'match' => ['name'],
    ]
];