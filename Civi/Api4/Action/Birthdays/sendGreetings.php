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

final class sendGreetings extends \Civi\Api4\Generic\AbstractAction {


    /**
   * @inheritDoc
   *
   */
  public function _run(Result $result): void {

      try {
          $birthday_contacts = new \CRM_Birthdays_BirthdayContacts();
          $contacts = $birthday_contacts->get_birthday_contacts_of_today(); // can be called with debug option true
      } catch (\Exception $exception) {
          $contacts = 0;
          $result[] = [
              'error' => ts("There is a problem collecting birthday contacts: $exception")
          ];
      }
      if (!empty($contacts)) {
          $mailer = new \CRM_Birthdays_Mailer();
          $error_count = $mailer->send_mails_and_write_activity($contacts);
      } else {
          $error_count = 0;
      }

      $contacts_count = count($contacts);
      $send_count = $contacts_count - $error_count;

      $result[] = [
          'status' => ts("Executed: $send_count out of $contacts_count mails/activities processed")
      ];
  }
}
