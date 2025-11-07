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
 * Birthdays::sendmessage() API v3
 *
 * This API is calling an API v4
 *
 * These parameters allow you to dry run your birthday mailing setup
 *
 **/
function _civicrm_api3_birthdays_sendgreetings_spec(&$params) {
    $params['debug_email'] = [
        'name'         => 'debug_email',
        'api.required' => 0,
        'type'         => CRM_Utils_Type::T_STRING,
        'title'        => E::ts('Set a debug email address (default: empty)'),
        'description'  => E::ts('A debug email is used to redirect all mails to this address')
    ];
    $params['disable_activities'] = [
        'name'         => 'disable_activities',
        'api.required' => 0,
        'type'         => CRM_Utils_Type::T_BOOLEAN,
        'title'        => E::ts('Option to disable activities (default: no)'),
        'description'  => E::ts('Should activities be disabled?')
    ];
    $params['custom_sender_email'] = [
        'name'         => 'custom_sender_email',
        'api.required' => 0,
        'type'         => CRM_Utils_Type::T_STRING,
        'title'        => E::ts('Custom sender email address'),
        'description'  => E::ts('Override the default sender email address from settings')
    ];
    $params['target_group_id'] = [
        'name'         => 'target_group_id',
        'api.required' => 0,
        'type'         => CRM_Utils_Type::T_INT,
        'title'        => E::ts('Target group ID'),
        'description'  => E::ts('Override the default birthday group with a custom group ID')
    ];
}

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
        $debug_email = $params['debug_email'] ?? '';
        $disable_activities = (bool) ($params['disable_activities'] ?? NULL);
        $custom_sender_email = $params['custom_sender_email'] ?? '';
        $target_group_id = (int) ($params['target_group_id'] ?? 0);

        $api_status_info = \Civi\Api4\Birthdays::sendGreetings()
            ->setDisable_activities($disable_activities)
            ->setDebug_email($debug_email)
            ->setCustom_sender_email($custom_sender_email)
            ->setTarget_group_id($target_group_id)
            ->execute()
            ->first();

        if ((bool) ($api_status_info['error'] ?? NULL)) {
            return civicrm_api3_create_error(
                E::LONG_NAME . ' ' . E::ts(
                    "Rethrow error from APIv4: %1",
                    [1 => $api_status_info['error']]
                ));
        }

        return civicrm_api3_create_success($api_status_info, $params, 'birthdays', 'sendgreetings');
    } catch (Exception $exception) {
        $short_ex = $exception->getMessage();
        return civicrm_api3_create_error(
            E::LONG_NAME . ' ' . E::ts("Error found in APIv3 wrapper calling APIv4: %1", [1 => $short_ex])
        );
    }
}
