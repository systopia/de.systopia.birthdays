<?php
return [
    [
        'name' => 'Group_birthday_greeting_recipients',
        'entity' => 'Group',
        'cleanup' => 'always',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'birthday_greeting_recipients_group',
                'title' => ts('Birthday greeting recipients group'),
                'description' => ts('Every contact in this group is elected to get a birthday email if birthday is set correctly'),
                'data_type' => NULL,
                'is_reserved' => TRUE,
                'is_active' => TRUE,
                'is_locked' => TRUE,
                'option_value_fields' => ['name', 'label', 'description', 'icon'],
            ],
        ],
        'match' => ['name'],
    ]
];