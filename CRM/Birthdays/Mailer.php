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

class CRM_Birthdays_Mailer
{
    private int $template_id;
    private string $email_address_from;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->template_id = Civi::settings()->get(CRM_Birthdays_Form_Settings::BIRTHDAYS_MESSAGE_TEMPLATE); // todo if null?
        if (empty($this->template_id)) {
            throw new Exception('Message template not found. Please set a template in birthday settings');
        }

        $email_id = Civi::settings()->get(CRM_Birthdays_Form_Settings::BIRTHDAYS_SENDER_EMAIL_ADDRESS_ID);
        $this->email_address_from = $this->get_sender_email_address_from_id($email_id);
        if (empty($this->email_address_from)) {
            throw new Exception('Pre selected outgoing email not found. Please set am outgoing email address in birthday settings');
        }
    }

    /**
     * @param $contacts
     * @return int error count
     */
    public function send_mails_and_write_activity($contacts): int
    {
        $error_count = 0;
        foreach ($contacts as $contact_id => $contact_info) {
            try {
                $this->send_mail($contact_id, $this->email_address_from, $contact_info['email'], $this->template_id);
                $this->create_activity($contact_id, ts('Successfull birthday greeting mail'), ts('Successful birthday greeting mail!'));
            } catch (Exception $exception) {
                $this->create_activity($contact_id, ts('FAILED birthday greeting mail'), ts("Failed birthday greeting mail: $exception"));
                ++$error_count;
            }
        }
        return $error_count;
    }

    /**
     * @throws CRM_Core_Exception
     * @throws Exception
     */
    private function send_mail($contact_id, $from_email_address, $to_email_address, $template_id): void
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
            throw new Exception("MessageTemplate exception: $exception");
        }
    }


    /**
     * @param $target_id
     * @param $title
     * @param $description
     * @return void
     * @throws API_Exception
     * @throws UnauthorizedException
     */
    private function create_activity($target_id, $title, $description): void
    {
        Activity::create()
            ->addValue('activity_type_id', 3) // = email
            ->addValue('activity_type_id:label', 'Email')
            ->addValue('subject', ts($title))
            ->addValue('details', $description)
            ->addValue('source_contact_id', $target_id)
            ->addValue('target_contact_id', $target_id)
            ->addValue('is_auto', TRUE)
            ->execute();
    }

    /**
     * @param int $id
     * @return mixed
     */
    private function get_sender_email_address_from_id(int $id): mixed
    {
        // this is something like: "to database" <database@domain.com>
        $email_from_name_combination_string = CRM_Core_OptionGroup::values('from_email_address', NULL, NULL, NULL, ' AND value = ' . $id);

        return CRM_Utils_Mail::pluckEmailFromHeader($email_from_name_combination_string[$id]);
    }
}