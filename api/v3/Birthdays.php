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

use CRM_Birthdays_ExtensionUtil as E;

/**
 * birthdays.sendgreetings
 *
 * @param array $params
 *   API call parameters
 *
 * @return array
 *   API3 response
 *
 */
function civicrm_api3_birthdays_sendgreetings(array $params): array
{
    try {
        $results = \Civi\Api4\Birthdays::sendGreetings()
            ->execute();
        $return_info = [];

        foreach ($results as $results_sub) {
            foreach ($results_sub as $result_key => $result_value) {
                $return_info[$result_key] = $result_value;
            }
        }
        if ($return_info['error']) return civicrm_api3_create_error(E::LONG_NAME . ' ' . E::ts("Rethrow error from APIv4: %1", [1 => $return_info['error']]));

        return civicrm_api3_create_success($return_info, $params, 'birthdays', 'sendgreetings');
    } catch (Exception $exception) {
        $short_ex = $exception->getMessage();
        return civicrm_api3_create_error(E::LONG_NAME . ' ' . E::ts("Error found in APIv3 wrapper calling APIv4: %1", [1 => $short_ex]));
    }
}