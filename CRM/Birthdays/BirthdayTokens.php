<?php

use CRM_Birthdays_ExtensionUtil as E;

class CRM_Birthdays_BirthdayTokens
{
    /**
     * Handles civicrm_tokens hook
     * @see https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_tokens
     */
    public static function addTokens(&$tokens): void
    {
        $tokens["contact"] = [
            "contact.ageold" => E::ts("Age")
        ];
    }

    /**
     * Handles civicrm_tokenValues hook
     * @param $values - array of values, keyed by contact id
     * @param $contact_ids - array of contactIDs that the system needs values for.
     * @param null $job - the job_id
     * @param array $tokens - tokens used in the mailing - use this to check whether a token is being used and avoid fetching data for unneeded tokens
     * @param null $context - the class name
     *
     * @see https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_tokenValues
     */
    public static function tokenValues(&$values, $contact_ids, $job = null, $tokens = [], $context = null): void
    {
        try {
            $contacts = \Civi\Api4\Contact::get()
                ->addSelect('id', 'birth_date')
                ->addWhere('id', 'IN', $contact_ids)
                ->addWhere('birth_date', 'IS NOT NULL')
                ->execute();
            foreach ($contacts  as $key => $contact_info ) {
                $values[$contact_info['id']]['contact.ageold'] =
                    self::calculateBirthday($contact_info['birth_date']);
            }
        } catch (Exception $exception) {
            Civi::log()->debug(E::LONG_NAME . " " . "Failed to fetch and calculate age: $exception");
        }
    }

    /**
     * Calculates age of given ISO date string
     * @param string $iso_birth_date ISO birthdate
     * @return int age
     */
    private static function calculateBirthday(string $iso_birth_date): int
    {
        return date_diff(date_create($iso_birth_date), date_create('now'))->y;
    }
}