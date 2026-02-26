<?php
/*-------------------------------------------------------+
| SYSTOPIA Birthdays                                     |
| Copyright (C) 2022 SYSTOPIA                            |
| Author: J. Franz (franz@systopia.de)                   |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+-------------------------------------------------------*/

declare(strict_types = 1);

use CRM_Birthdays_ExtensionUtil as E;
use Civi\Token\AbstractTokenSubscriber;
use Civi\Token\TokenRow;
use Civi\Token\Event\TokenValueEvent;

class CRM_Birthdays_Tokens extends AbstractTokenSubscriber {

  /**
   * @param string $entity
   * @param array<string, string> $tokenNames
   */
  public function __construct($entity, $tokenNames = []) {
    $tokenNames += self::getTokens();
    parent::__construct($entity, $tokenNames);
  }

  /**
   * @return array<string, string>
   */
  public static function getTokens(): array {
    return ['contact.age' => E::ts('Contact Age (Birthdays Extension)')];
  }

  /**
   * @throws Exception
   * @return array<string, array<string, int>>
   */
  public function prefetch(TokenValueEvent $e): array {
    $contact_id = $e->getTokenProcessor()->rowContexts[0]['contact']['contact_id'];
    $contact_info = new CRM_Birthdays_ContactInfo($contact_id);

    return [
      'contact' => [
        'age' => $contact_info->age(),
      ],
    ];
  }

  public function evaluateToken(TokenRow $row, $entity, $field, $prefetch = NULL): void {
    [$token_type, $token_name] = explode('.', $field);
    /** @phpstan-ignore-next-line */
    $row->tokens($entity, $field, $prefetch[$token_type][$token_name] ?? 'test');
  }

}
