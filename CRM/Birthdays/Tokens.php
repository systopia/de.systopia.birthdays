<?php
/*-------------------------------------------------------+
| SYSTOPIA Birthdays Integration                         |
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

use CRM_Birthdays_ExtensionUtil as E;
use Civi\Token\AbstractTokenSubscriber;
use Civi\Token\TokenRow;
use Civi\Token\Event\TokenValueEvent;

class CRM_Birthdays_Tokens extends AbstractTokenSubscriber
{
    public function __construct($entity, $tokenNames = [])
    {
        $tokenNames += self::getTokens();
        parent::__construct($entity, $tokenNames);
    }

    public static function getTokens(): array
    {
        return ['contact.age' => E::ts('Contact Age (Birthdays Extension)')];
    }

    /**
     * @throws Exception
     */
    public function prefetch(TokenValueEvent $e): array
    {
        $contact_id = $e->getTokenProcessor()->rowContexts[0]['contact']['contact_id'];
        $contact_info = new CRM_Birthdays_ContactInfo($contact_id);

        return [
            'contact' => [
                'age' => $contact_info->age()
            ]
        ];
    }

    public function evaluateToken(TokenRow $row, $entity, $field, $prefetch = null)
    {
        [$token_type, $token_name] = explode('.', $field);
        $row->tokens($entity, $field, $prefetch[$token_type][$token_name] ?? 'test');
    }
}