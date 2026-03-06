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

declare(strict_types = 1);

use Civi\Api4\Activity;
use CRM_Birthdays_ExtensionUtil as E;

class CRM_Birthdays_Mailer {
  private int $template_id;
  private string $email_address_from;

  /**
   * @throws Exception
   */
  public function __construct() {
    /** @phpstan-ignore assign.propertyType */
    $this->template_id = Civi::settings()->get(CRM_Birthdays_Form_Settings::BIRTHDAYS_MESSAGE_TEMPLATE);
    /** @phpstan-ignore voku.Identical,identical.alwaysFalse */
    if ($this->template_id === NULL || $this->template_id === 0) {
      throw new RuntimeException(
        E::LONG_NAME . ' ' . 'Message template not found. Please set a template in birthday settings'
      );
    }

    try {
      /** @var string $email_name_mix */
      $email_name_mix = Civi::settings()->get(CRM_Birthdays_Form_Settings::BIRTHDAYS_SENDER_EMAIL_ADDRESS);
      /*
       * Examples:
       * - Input: "to database" <database@domain.com>
       * - Output: database@domain.com
       */
      $this->email_address_from = CRM_Utils_Mail::pluckEmailFromHeader($email_name_mix);
    }
    /** @phpstan-ignore catch.neverThrown */
    catch (TypeError $typeError) {
      throw new RuntimeException(
      // phpcs:ignore Generic.Files.LineLength.TooLong
        E::LONG_NAME . ' ' . 'Pre selected outgoing email not found. Please set an outgoing email address in birthday settings ' . $typeError->getMessage(),
        0,
        $typeError
        );
    }

    /** @phpstan-ignore voku.Identical */
    if (NULL === $this->email_address_from || $this->email_address_from === '') {
      throw new RuntimeException(
      // phpcs:ignore Generic.Files.LineLength.TooLong
        E::LONG_NAME . ' ' . 'Pre selected outgoing email is empty. Please set an outgoing email address in birthday settings'
      );
    }
  }

  /**
   * @param array<int|string, array<string, string>> $contacts
   * @param bool $write_activity
   *
   * @return int error count
   */
  public function sendMailsAndWriteActivity(array $contacts, bool $write_activity): int {
    $error_count = 0;
    foreach ($contacts as $contact_id => $contact_info) {
      try {
        $this->sendMail($contact_id, $this->email_address_from, $contact_info['email'], $this->template_id);
        if ($write_activity) {
          $this->createActivity(
          $contact_id,
          E::ts('Successful birthday greeting mail'),
          E::ts(
            'Successful birthday greeting mail! Template ID %1 has been used.',
            [1 => $this->template_id]
          )
          );
        }
      }
      catch (Exception $exception) {
        // @ignoreException
        if ($write_activity) {
          $this->createActivity(
          $contact_id,
          E::ts('FAILED birthday greeting mail'),
          E::ts(
            'Failed sending an birthday greeting mail with template ID nr %1. Error: %2',
            [1 => $this->template_id, 2 => $exception]
          )
          );
        }
        ++$error_count;
      }
    }
    return $error_count;
  }

  /**
   * @param int|string $contact_id
   * @param string $from_email_address
   * @param string $to_email_address
   * @param int $template_id
   *
   * @throws CRM_Core_Exception
   * @throws Exception
   */
  private function sendMail(
    int|string $contact_id,
    string $from_email_address,
    string $to_email_address,
    int $template_id
  ): void {
    try {
      $contact = \Civi\Api4\Contact::get()
        ->addSelect('display_name')
        ->addWhere('id', '=', $contact_id)
        ->execute()
        ->single();
      civicrm_api3('MessageTemplate', 'send', [
        'check_permissions' => 0,
        'id' => $template_id,
        'to_name' => $contact['display_name'],
        'from' => trim($from_email_address),
        'contact_id' => $contact_id,
        'to_email' => trim($to_email_address),
      ]);
    }
    catch (Exception $exception) {
      throw new RuntimeException(E::LONG_NAME . ' ' . "MessageTemplate exception: $exception");
    }
  }

  /**
   * @param int|string $target_id
   * @param string $title
   * @param string $description
   */
  private function createActivity($target_id, $title, $description): void {
    try {
      Activity::create(FALSE)
      // = email
        ->addValue('activity_type_id', 3)
        ->addValue('subject', $title)
        ->addValue('details', $description)
        ->addValue('source_contact_id', 'user_contact_id')
        ->addValue('target_contact_id', $target_id)
        ->execute();
    }
    catch (Exception $exception) {
      // @ignoreException
      Civi::log()->debug(E::LONG_NAME . ' ' . "Unable to write activity: $exception");
    }
  }

}
