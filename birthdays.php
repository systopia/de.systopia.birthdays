<?php
/*-------------------------------------------------------+
| SYSTOPIA Birthdays                                     |
| Copyright (C) 2022 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_Birthdays_ExtensionUtil as E;

require_once 'birthdays.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config
 */
function birthdays_civicrm_config(&$config) {
  _birthdays_civix_civicrm_config($config);
  \Civi::dispatcher()->addSubscriber(new CRM_Birthdays_Tokens('birthdays'));
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function birthdays_civicrm_install() {
  _birthdays_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function birthdays_civicrm_enable() {
  _birthdays_civix_civicrm_enable();
}

function birthdays_civicrm_navigationMenu(&$menu) {
    _birthdays_civix_insert_navigation_menu($menu, 'Administer/Communications', [
        'label' => E::ts('Birthday Settings'),
        'url' => 'civicrm/admin/birthdays',
        'permission' => 'administer CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
        'icon' => 'crm-i fa-birthday-cake',
        'active' => 1
    ]);
}
