<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
return [
  0 => 
  [
    'name' => ts('Birthday Report', ['domain' => 'de.systopia.birthdays']),
    'entity' => 'ReportTemplate',
    'params' => 
    [
      'version' => 3,
      'label' => ts('Birthdays', ['domain' => 'de.systopia.birthdays']),
      'description' => ts('Report on upcoming birthdays', ['domain' => 'de.systopia.birthdays']),
      'class_name' => 'CRM_Birthdays_Form_Report_Birthdays',
      'report_url' => 'contact/birthdays',
      'component' => '',
    ],
  ],
];