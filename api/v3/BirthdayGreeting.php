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

require_once 'birthdays.civix.php';


/**
 * birthdays.send
 *
 * @param array $params
 *   API call parameters
 *
 * @return array
 *   API3 response
 * @throws CiviCRM_API3_Exception
 */
function civicrm_api3_birthday_greeting_send($params)
{
    // todo add apiv4 call
    sleep(3);

    $return = ['done'];

    return civicrm_api3_create_success($return, $params, 'birthdays', 'send');
}