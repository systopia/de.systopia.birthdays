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

namespace Civi\Api4\Action\Birthdays;

use Civi\Api4\Generic\Result;
use CRM_Birthdays_ExtensionUtil as E;

final class sendGreetings extends \Civi\Api4\Generic\AbstractAction
{

    /**
     * Birthdays::sendmessage() API v4
     *
     * These parameters allow you to dry run your birthday mailing setup
     *
     * @see \Civi\Api4\Generic\AbstractAction
     *
     * @package Civi\Api4\Action\Birthdays
     */


    /**
     * Debug email can be set for testing.
     *
     * All emails will be redirected to this email set using debug_email. Example: all_mails_to@thisdomain.com
     *
     * WARNING: Chances are you want to disable activities using this option
     *
     * @var string
     */
    protected string $debug_email = '';

    /**
     * Acitivites can be enabled or disabled here.
     *
     * - true:  "successful" or "failed" activities will be suppressed
     * - false: "successful" or "failed" activities will be added to contacts
     *
     * @var bool
     */
    protected bool $disable_activities = false;

    public function _run(Result $result): void
    {
        $error_count = 1;
        try {
            $birthday_contacts = new \CRM_Birthdays_BirthdayContacts();
            $contacts = $birthday_contacts->getBirthdayContactsOfToday($this->debug_email);

            if (!$birthday_contacts->groupHasBirthDateContacts()) {
                $result[] = [
                    'error' => E::ts(
                        "There are no contacts in the birthday group or 
                        there are contacts where no birth date is set."
                    )
                ];
            }
        } catch (\Exception $exception) {
            $contacts = [];
            $result[] = [
                'error' => E::ts('There is a problem collecting birthday contacts: %1', [1 => $exception])
            ];
        }
        $mailer = new \CRM_Birthdays_Mailer();
        if (!empty($contacts)) {
            $error_count = $mailer->sendMailsAndWriteActivity($contacts, !$this->disable_activities);
        } else {
            $error_count = 0;
        }

        $contacts_count = count($contacts);
        $send_count = $contacts_count - $error_count;

        $result[] = [
            'status' => E::ts(
                'Executed: %1 out of %2 mails/activities processed',
                [1 => $send_count, 2 => $contacts_count]
            )
        ];
    }
}
