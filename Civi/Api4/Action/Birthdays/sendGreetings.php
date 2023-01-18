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
use CRM_Defaulteventmessages_ExtensionUtil as E;

final class sendGreetings extends \Civi\Api4\Generic\AbstractAction {


    /**
   * @inheritDoc
   *
   */
  public function _run(Result $result): void {

      /*
       * A debug email can be set here which leads to:
       * - Adding "successful" or "failed" activities will be suppressed
       * - All emails will be redirected to this email set here ( $is_debug_email = 'all_mails_to@thisdomain.com'; )
       * - A filter is de-activated which selects the first 10 contacts/mails where a birthdate is set
       */
      $is_debug_email = '';

      try {
          $birthday_contacts = new \CRM_Birthdays_BirthdayContacts();
          $contacts = $birthday_contacts->get_birthday_contacts_of_today($is_debug_email); // set output email to enable debug mode
      } catch (\Exception $exception) {
          $contacts = [];
          $result[] = [
              'error' => E::ts("There is a problem collecting birthday contacts: $exception")
          ];
      }
      if (!empty($contacts)) {
          $mailer = new \CRM_Birthdays_Mailer();
          $error_count = $mailer->send_mails_and_write_activity($contacts, empty($is_debug_email));
      } else {
          $error_count = 0;
      }

      $contacts_count = count($contacts);
      $send_count = $contacts_count - $error_count;

      $result[] = [
          'status' => E::ts("Executed: $send_count out of $contacts_count mails/activities processed")
      ];
  }
}
