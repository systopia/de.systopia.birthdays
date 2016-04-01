<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => ts('Birthday Report', array('domain' => 'de.systopia.birthdays')),
    'entity' => 'ReportTemplate',
    'params' => 
    array (
      'version' => 3,
      'label' => ts('Birthdays', array('domain' => 'de.systopia.birthdays')),
      'description' => ts('Report on upcoming birthdays', array('domain' => 'de.systopia.birthdays')),
      'class_name' => 'CRM_Birthdays_Form_Report_Birthdays',
      'report_url' => 'contact/birthdays',
      'component' => '',
    ),
  ),
);