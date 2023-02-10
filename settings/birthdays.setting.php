<?php

/*-------------------------------------------------------+
| SYSTOPIA Birthdays Integration                         |
| Copyright (C) 2023 SYSTOPIA                            |
| Author: J. Franz (franz@systopia.de)                   |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+-------------------------------------------------------*/

use CRM_Birthdays_ExtensionUtil as E;

return [
    'Birthdays_Message_Template_ID' => [
        'name' => 'Birthdays_Message_Template_ID',
        'type' => 'Integer',
        'serialize' => CRM_Core_DAO::SERIALIZE_JSON,
        'is_domain' => 1,
        'description' => E::ts('Message Template ID'),
        'default' => [],
        'title' => E::ts('Template ID'),
        'help_text' => '',
        'html_type' => 'select',
        'html_attributes' => [
            'class' => 'crm-select2',
            'multiple' => 1,
        ]
    ],
    'Birthdays_Sender_Email_Address' => [
        'name' => 'Birthdays_Sender_Email_Address',
        'type' => 'String',
        'serialize' => CRM_Core_DAO::SERIALIZE_JSON,
        'is_domain' => 1,
        'description' => E::ts('Sending Email address'),
        'default' => [],
        'title' => E::ts('Sending Email address'),
        'help_text' => '',
        'html_type' => 'select',
        'html_attributes' => [
            'class' => 'crm-select2',
            'multiple' => 1,
        ]
    ]
];