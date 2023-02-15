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

    public static function getTokens() {
        $var['contact.age'] = E::ts('Contact Age (Birthdays Extension)');
        return $var;
    }

    public function prefetch(TokenValueEvent $e)
    {
        $token_values = [
            'contact.age' => $e->getTokenProcessor()->rowContexts[0]['contact.age']
        ];

        return $token_values;
    }

    public function evaluateToken(TokenRow $row, $entity, $field, $prefetch = null)
    {
        [$token_type, $token_name] = explode('.', $field);
        $row->tokens($entity, $field, $prefetch[$token_type][$token_name] ?? 'test');
    }
}