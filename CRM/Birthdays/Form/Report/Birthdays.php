<?php
/*-------------------------------------------------------+
| SYSTOPIA Birthdays Integration                         |
| Copyright (C) 2016 SYSTOPIA                            |
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

/**
 * Report on upcoming birthdays
 *
 * loosely based on https://stackoverflow.com/questions/18747853/mysql-select-upcoming-birthdays#18748008
 */
class CRM_Birthdays_Form_Report_Birthdays extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupExtends = ['Contact', 'Individual'];

  protected $_customGroupGroupBy = FALSE;


  function __construct() {
    $this->_columns = [
      'civicrm_contact' => [
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => [
          'id' => [
            'no_display' => TRUE,
            'required' => TRUE,
          ],
          'sort_name' => [
            'title' => E::ts('Contact Name'),
            'required' => TRUE,
            'default' => TRUE,
          ],
          'contact_type' => [
            'title' => E::ts('Contact Type'),
          ],
          'contact_sub_type' => [
            'title' => E::ts('Contact Subtype'),
          ],
          'first_name' => [
            'title' => E::ts('First Name'),
          ],
          'last_name' => [
            'title' => E::ts('Last Name'),
          ],
          'birth_date' => [
            'title' => E::ts('Birth Date'),
            'type' => CRM_Utils_Type::T_DATE,
          ],
          'birthday' => [
            'title' => E::ts('Birthday'),
            'type' => CRM_Utils_Type::T_DATE,
            'required' => TRUE,
            'default' => TRUE,
          ],
          'age' => [
            'title' => E::ts('Age'),
          ],
        ],
        'filters' => [
          'sort_name' => [
            'title' => E::ts('Contact Name'),
            'operator' => 'like',
          ],
          'id' => [
            'no_display' => TRUE,
          ],
          'birth_date' => [
            'title' => E::ts('Birth Date'),
            'type' => CRM_Utils_Type::T_DATE,
          ],
          'birthday' => [
            'title' => E::ts('Birthday'),
            'type' => CRM_Utils_Type::T_DATE,
          ],
          'age' => [
            'title' => E::ts('Age'),
            'type' => CRM_Utils_Type::T_INT,
          ],
          'contact_type' => [
            'title' => E::ts('Contact Type'),
          ],
          'contact_sub_type' => [
            'title' => E::ts('Contact Subtype'),
          ],
        ],
        'grouping' => 'contact-fields',
      ],
      'civicrm_email' => [
        'dao' => 'CRM_Core_DAO_Email',
        'fields' => ['email' => NULL],
        'grouping' => 'contact-fields',
      ],
      'civicrm_phone' => [
        'dao' => 'CRM_Core_DAO_Phone',
        'fields' => [
          'phone' => NULL,
        ],
        'grouping' => 'contact-fields',
      ],
        ] + $this->getAddressColumns(['group_by' => FALSE]);
    $this->_groupFilter = TRUE;
    $this->_tagFilter = TRUE;
    parent::__construct();
  }

  function preProcess() {
    $this->assign('reportTitle', E::ts('Birthday Report'));
    parent::preProcess();
  }

  function select() {
    $select = $this->_columnHeaders = [];

    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('fields', $table)) {
        foreach ($table['fields'] as $fieldName => $field) {
          if (CRM_Utils_Array::value('required', $field) || CRM_Utils_Array::value($fieldName, $this->_params['fields'])) {

            if ($fieldName == 'birthday') {
              $select[] = "DATE_ADD({$this->_aliases['civicrm_contact']}.birth_date, INTERVAL YEAR(CURDATE() - INTERVAL 2 DAY) - YEAR({$this->_aliases['civicrm_contact']}.birth_date) + IF(DAYOFYEAR(CURDATE() - INTERVAL 2 DAY) >= DAYOFYEAR({$this->_aliases['civicrm_contact']}.birth_date),1,0) YEAR) AS birthday";
              $this->_columnHeaders["birthday"]['title'] = $field['title'];
              $this->_columnHeaders["birthday"]['type'] = CRM_Utils_Array::value('type', $field);

            } elseif ($fieldName == 'age') {
              $select[] = "(YEAR(DATE_ADD({$this->_aliases['civicrm_contact']}.birth_date, INTERVAL YEAR(CURDATE() - INTERVAL 2 DAY) - YEAR({$this->_aliases['civicrm_contact']}.birth_date) + IF(DAYOFYEAR(CURDATE() - INTERVAL 2 DAY) >= DAYOFYEAR({$this->_aliases['civicrm_contact']}.birth_date),1,0) YEAR)) - YEAR({$this->_aliases['civicrm_contact']}.birth_date)) AS age";
              $this->_columnHeaders["age"]['title'] = $field['title'];
              $this->_columnHeaders["age"]['type'] = CRM_Utils_Array::value('type', $field);

            } else {
              if ($tableName == 'civicrm_email') {
                $this->_emailField = TRUE;
              }

              $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
              $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
              $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
            }
          }
        }
      }
    }

    $this->_select = "SELECT " . implode(', ', $select) . " ";
  }

  function from() {
    $this->_from = NULL;

    $this->_from = "FROM  civicrm_contact {$this->_aliases['civicrm_contact']} {$this->_aclFrom}";

    //used when email field is selected
    if ($this->_emailField) {
      $this->_from .= "
              LEFT JOIN civicrm_email {$this->_aliases['civicrm_email']}
                        ON {$this->_aliases['civicrm_contact']}.id =
                           {$this->_aliases['civicrm_email']}.contact_id AND
                           {$this->_aliases['civicrm_email']}.is_primary = 1\n";
    }
    if ($this->isTableSelected('civicrm_address')) {
      $this->_from .= "
            LEFT JOIN civicrm_address {$this->_aliases['civicrm_address']}
                   ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_address']}.contact_id AND
                      {$this->_aliases['civicrm_address']}.is_primary = 1 ) ";
    }

    $this->joinPhoneFromContact();
  }

  function where() {
    $clauses = [];
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('filters', $table)) {
        foreach ($table['filters'] as $fieldName => $field) {
          $clause = NULL;
          if ($fieldName == 'birthday') {
            $field['name'] = "CAST(DATE_ADD({$this->_aliases['civicrm_contact']}.birth_date, INTERVAL YEAR(CURDATE() - INTERVAL 2 DAY) - YEAR({$this->_aliases['civicrm_contact']}.birth_date) + IF(DAYOFYEAR(CURDATE() - INTERVAL 2 DAY) >= DAYOFYEAR({$this->_aliases['civicrm_contact']}.birth_date),1,0) YEAR) AS DATETIME)";
          }
          elseif ($fieldName == 'age') {
            $field['dbAlias'] = "(YEAR(DATE_ADD({$this->_aliases['civicrm_contact']}.birth_date, INTERVAL YEAR(CURDATE() - INTERVAL 2 DAY) - YEAR({$this->_aliases['civicrm_contact']}.birth_date) + IF(DAYOFYEAR(CURDATE() - INTERVAL 2 DAY) >= DAYOFYEAR({$this->_aliases['civicrm_contact']}.birth_date),1,0) YEAR)) - YEAR({$this->_aliases['civicrm_contact']}.birth_date)) ";
          }

          if (CRM_Utils_Array::value('operatorType', $field) & CRM_Utils_Type::T_DATE) {
            $relative = CRM_Utils_Array::value("{$fieldName}_relative", $this->_params);
            $from     = CRM_Utils_Array::value("{$fieldName}_from", $this->_params);
            $to       = CRM_Utils_Array::value("{$fieldName}_to", $this->_params);

            $clause = $this->dateClause($field['name'], $relative, $from, $to, $field['type']);
          }
          else {
            $op = CRM_Utils_Array::value("{$fieldName}_op", $this->_params);
            if ($op) {
              $clause = $this->whereClause($field,
                $op,
                CRM_Utils_Array::value("{$fieldName}_value", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_min", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_max", $this->_params)
              );
            }
          }

          if (!empty($clause)) {
            $clauses[] = $clause;
          }
        }
      }
    }

    // only contacts with birthdays
    $clauses[] = "({$this->_aliases['civicrm_contact']}.birth_date IS NOT NULL)";

    // no deleted contacts (see https://github.com/systopia/de.systopia.birthdays/issues/10)
    $clauses[] = "(({$this->_aliases['civicrm_contact']}.is_deleted IS NULL) OR ({$this->_aliases['civicrm_contact']}.is_deleted = 0))";

    $this->_where = "WHERE " . implode(' AND ', $clauses);

    if ($this->_aclWhere) {
      $this->_where .= " AND {$this->_aclWhere} ";
    }
  }

  function orderBy() {
    $this->_orderBy = " ORDER BY birthday ASC ";
  }

  function postProcess() {

    $this->beginPostProcess();

    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);
    $sql = $this->buildQuery(TRUE);

    $rows = [];
    $this->buildRows($sql, $rows);

    $this->formatDisplay($rows);
    $this->doTemplateAssignment($rows);
    $this->endPostProcess($rows);
  }

  function alterDisplay(&$rows) {
    // custom code to alter rows
    $entryFound = FALSE;
    foreach ($rows as $rowNum => $row) {
      if (array_key_exists('civicrm_contact_sort_name', $row) &&
        $row['civicrm_contact_sort_name'] &&
        array_key_exists('civicrm_contact_id', $row)
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view",
          'reset=1&cid=' . $row['civicrm_contact_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_contact_sort_name_link'] = $url;
        $rows[$rowNum]['civicrm_contact_sort_name_hover'] = E::ts("View Contact Summary for this Contact.");
        $entryFound = TRUE;
      }

      $entryFound = $this->alterDisplayAddressFields($row, $rows, $rowNum, NULL, NULL) ? TRUE : $entryFound;

      if (!$entryFound) {
        break;
      }
    }
  }

  /**
   * Override getOperationPair to add 'in', 'not in' for 'age' field
   */
  public function getOperationPair($type = 'string', $fieldName = NULL): array
  {
    $operations = parent::getOperationPair($type, $fieldName);
    if ($fieldName == 'age') {
      $operations += parent::getOperationPair(CRM_Report_Form::OP_MULTISELECT, $fieldName);
    }
    return $operations;
  }
}
