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

        $email_id = Civi::settings()->get(CRM_Birthdays_Form_Settings::BIRTHDAYS_SENDER_EMAIL_ADDRESS);;
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
                $this->create_activity($contact_id, ts('Successful birthday greeting mail'), 'Success!');
            } catch (Exception $exception) {
                $this->create_activity($contact_id, ts('FAILED birthday greeting mail'), 'fixme add params');
                ++$error_count;
                // todo write $exception to logs?
            }
        }
        return $error_count;
    }

    /**
     * @throws CRM_Core_Exception
     * @throws Exception
     */
    private function send_mail($contact_id, $from_email_adress, $to_email_address, $template_id) {
        try {
            civicrm_api3('MessageTemplate', 'send', [
                'check_permissions' => 0,
                'id'                => $template_id,
                'to_name'           => civicrm_api3('Contact', 'getvalue', ['id' => $contact_id, 'return' => 'display_name']),
                'from'              => trim($from_email_adress),
                'contact_id'        => $contact_id,
                'to_email'          => trim($to_email_address),
            ]);
        } catch (Exception $exception) {
            throw new Exception('MessageTemplate exception');
        }
    }




    /**
     * @return void
     */
    private function create_activity($target_id, $title, $description): void
    {
        $target_id = 4;

        $results = \Civi\Api4\Activity::create()
            ->addValue('debug', TRUE)
            ->addValue('activity_type_id', 19)
            ->addValue('subject', 'test subject')
            ->addValue('status_id', 2)
            ->addValue('activity_date_time', '2022-12-18 13:45:00') // fixme use date("YmdHis")
            ->addValue('target_contact_id', [$target_id])
            ->addValue('activity_type_id:icon', '')
            ->addValue('activity_type_id:description', 'Bulk Email Sent.')
            ->addValue('activity_type_id:label', 'Email')
            ->addValue('is_test', TRUE) // fixme on release
            ->addValue('is_auto', TRUE)
            ->addValue('is_deleted', FALSE)
            ->addValue('is_star', FALSE)
            ->addValue('source_contact_id', 4) // ->addValue('source_contact_id', 'user_contact_id')
            ->execute();



        /*civicrm_api3('Activity', 'create', [
            'activity_type_id' => $this->activity_type_id,
            'subject' => E::ts("Document (CiviOffice)"),
            'status_id' => 'Completed',
            'activity_date_time' => date("YmdHis"),
            'target_id' => [$contact_id],
            'details' => '<p>' . E::ts(
                    'Created from document: %1',
                    [1 => '<code>' . CRM_Civioffice_Configuration::getConfig()->getDocument($this->document_uri)->getName() . '</code>']
                ) . '</p>'
                . '<p>' . E::ts('Live Snippets used:') . '</p>'
                . (!empty($live_snippet_values) ? '<table><tr>' . implode(
                        '</tr><tr>',
                        array_map(function ($name, $value) use ($live_snippets) {
                            return '<th>' . $live_snippets[$name]['label'] . '</th>'
                                . '<td>' . $value . '</td>';
                        }, array_keys($live_snippet_values), $live_snippet_values)
                    ) . '</tr></table>' : ''),
        ]);*/
    }

    /**
     * @return mixed
     */
    private function get_sender_email_address_from_id(int $id)
    {
        // resolve/beautify sender (use name instead of value of the option_value)
        $from_addresses = CRM_Core_OptionGroup::values('from_email_address');
        if (isset($from_addresses)) {
            return $from_addresses[$id];
        } else {
            reset($from_addresses);
            return $from_addresses;
        }
    }
}