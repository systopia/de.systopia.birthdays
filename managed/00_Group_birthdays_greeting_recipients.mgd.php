<?php
use CRM_Birthdays_ExtensionUtil as E;
return [
    [
        'name' => 'Group_birthday_greeting_recipients',
        'entity' => 'Group',
        'cleanup' => 'never',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'birthday_greeting_recipients_group',
                'title' => E::ts('Birthday greeting recipients group'),
                'description' => E::ts('Every contact in this group is elected to get birthday greetings via e-mail if birthday is set correctly'),
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