<?php
/*
 * Copyright (C) 2022 SYSTOPIA GmbH
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation in version 3.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class CRM_Birthdays_BirthdayContacts
{
    private int $group_id;

    /**
     * @throws API_Exception
     */
    public function __construct()
    {
        $this->group_id = $this->get_group_id_from_api();
    }

    /**
     * @throws Exception
     */
    public function get_birthday_contacts_of_today(): array
    {
        try {
            /*
             * Important:
             * Please sync documentation text in localhost/alle/civicrm/admin/birthdays/settings
             * whenever this query changes
             */
            $sql = "SELECT civicrm_contact.id AS contact_id, civicrm_contact.birth_date AS birth_date, civicrm_email.email AS email FROM civicrm_contact
                        INNER JOIN civicrm_group_contact group_contact ON civicrm_contact.id = group_contact.contact_id
                        INNER JOIN civicrm_email ON civicrm_contact.id = civicrm_email.contact_id
                            WHERE DAY(birth_date) = DAY(CURDATE())
                              AND MONTH(birth_date) = MONTH(CURDATE())
                              AND civicrm_contact.contact_type = 'Individual'
                              AND civicrm_contact.is_opt_out = 0
                              AND civicrm_contact. do_not_email = 0
                              AND group_contact.group_id = {$this->group_id}
                              AND civicrm_email.is_primary = 1";
            $query = CRM_Core_DAO::executeQuery($sql);
            $query_result = [];
            while ($query->fetch()) {
                $query_result[$query->contact_id] =
                    [
                        'birth_date' => $query->birth_date,
                        'email' => $query->email
                    ];
            }
            return $query_result;
        } catch (Exception  $exception) {
            throw new Exception("SQL query failed: $exception");
        }
    }

    /**
     * @throws API_Exception
     */
    private function get_group_id_from_api()
    {
        try {
            $groups = \Civi\Api4\Group::get()
                ->addSelect('id')
                ->addWhere('name', '=', 'birthday_greeting_recipients_group')
                ->setLimit(2)
                ->execute();
            return $groups[0]['id'];
        } catch (Exception $exception) {
            \Civi::log()->debug("Birthdays: Default group called birthday_greeting_recipients_group not found: $exception");
            throw new \API_Exception("Birthdays: Default group called birthday_greeting_recipients_group not found: $exception");
        }
    }

}