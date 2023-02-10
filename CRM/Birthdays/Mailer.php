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

use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\Activity;
use CRM_Birthdays_ExtensionUtil as E;

class CRM_Birthdays_Mailer
{
    private ?int $template_id;
    private ?string $email_address_from;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->template_id = Civi::settings()->get(CRM_Birthdays_Form_Settings::BIRTHDAYS_MESSAGE_TEMPLATE);
        if (empty($this->template_id)) {
            throw new Exception(E::LONG_NAME . ": " . "Message template not found. Please set a template in birthday settings");
        }

        try {
            $email_id = Civi::settings()->get(CRM_Birthdays_Form_Settings::BIRTHDAYS_SENDER_EMAIL_ADDRESS_ID);
            $this->email_address_from = $this->getSenderEmailAddressFromId($email_id);
        } catch (TypeError $typeError) {
            throw new Exception(E::LONG_NAME . ": " . "Pre selected outgoing email not found. Please set an outgoing email address in birthday settings");
        }

        if (empty($this->email_address_from)) {
            throw new Exception(E::LONG_NAME . ": " . "Pre selected outgoing email not found. Please set an outgoing email address in birthday settings");
        }
    }

    /**
     * @param $contacts
     * @param $write_activity
     * @return int error count
     * @throws API_Exception
     * @throws CRM_Core_Exception
     * @throws UnauthorizedException
     */
    public function sendMailsAndWriteActivity($contacts, $write_activity): int
    {
        $error_count = 0;
        foreach ($contacts as $contact_id => $contact_info) {
            try {
                $this->sendMail($contact_id, $this->email_address_from, $contact_info['email'], $this->template_id);
                if ($write_activity) $this->createActivity($contact_id, E::ts('Successful birthday greeting mail'), E::ts('Successful birthday greeting mail! Template ID %1 has been used.', [$this->template_id]));
            } catch (Exception $exception) {
                if ($write_activity) $this->createActivity($contact_id, E::ts('FAILED birthday greeting mail'), E::ts("Failed sending an birthday greeting mail with template ID nr %1. Error: %2", [$this->template_id, $exception]));
                ++$error_count;
            }
        }
        return $error_count;
    }

    /**
     * @throws CRM_Core_Exception
     * @throws Exception
     */
    private function sendMail($contact_id, $from_email_address, $to_email_address, $template_id): void
    {
        try {
            civicrm_api3('MessageTemplate', 'send', [
                'check_permissions' => 0,
                'id'                => $template_id,
                'to_name'           => civicrm_api3('Contact', 'getvalue', ['id' => $contact_id, 'return' => 'display_name']),
                'from'              => trim($from_email_address),
                'contact_id'        => $contact_id,
                'to_email'          => trim($to_email_address),
            ]);
        } catch (Exception $exception) {
            throw new Exception(E::LONG_NAME . ": " . "MessageTemplate exception: $exception");
        }
    }


    /**
     * @param $target_id
     * @param $title
     * @param $description
     * @return void
     * @throws UnauthorizedException|CRM_Core_Exception
     */
    private function createActivity($target_id, $title, $description): void
    {
        Activity::create()
            ->addValue('activity_type_id', 3) // = email
            ->addValue('subject', E::ts($title))
            ->addValue('details', $description)
            ->addValue('source_contact_id', $target_id)
            ->addValue('target_contact_id', $target_id)
            ->execute();
    }

    /**
     * @param int $id
     * @return string|null
     */
    private function getSenderEmailAddressFromId(int $id): ?string
    {
        // this is something like: "to database" <database@domain.com>
        $email_name_combined_with_email_address_string =
            CRM_Core_OptionGroup::values('from_email_address', NULL, NULL, NULL, ' AND value = ' . $id);

        return CRM_Utils_Mail::pluckEmailFromHeader($email_name_combined_with_email_address_string[$id]);
    }
}