<?php
return [
    [
        'name' => 'group_birthday_greeting_recipients',
        'entity' => 'Group',
        'title' => ts('Birthday greeting recipients group'),
        'description' => ts('Every contact in this group is elected to get a birthday email if birthday is set correctly'),
        'source' => NULL,
        'saved_search_id' => NULL,
        'is_active' => TRUE,
        'visibility' => 'User and User Admin Only',
        'where_clause' => NULL,
        'select_tables' => NULL,
        'where_tables' => NULL,
        'group_type' => [
            '2',
        ],
        'cache_date' => NULL,
        'refresh_date' => NULL,
        'parents' => NULL,
        'children' => NULL,
        'is_hidden' => FALSE,
        'is_reserved' => FALSE,
        'created_id' => NULL,
        'modified_id' => NULL
    ],
];