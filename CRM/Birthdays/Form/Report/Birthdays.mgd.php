<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders

use CRM_Birthdays_ExtensionUtil as E;

return [
  0 => 
  [
    'name' => E::ts('Birthday Report'),
    'entity' => 'ReportTemplate',
    'params' => 
    [
      'version' => 3,
      'label' => E::ts('Birthdays'),
      'description' => E::ts('Report on upcoming birthdays'),
      'class_name' => 'CRM_Birthdays_Form_Report_Birthdays',
      'report_url' => 'contact/birthdays',
       'cleanup'=> 'always',
      'component' => '',
    ],
  ],
];