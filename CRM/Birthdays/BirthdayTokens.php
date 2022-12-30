<?php

class CRM_Birthdays_BirthdayTokens
{
    /**
     * Handles civicrm_tokens hook
     * @see https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_tokens
     */
    public static function addTokens(&$tokens): void
    {
        $tokens["contact"] = [
            "contact.age" => ts("Age")
        ];
    }

    /**
     * Handles civicrm_tokenValues hook
     * @param $values - array of values, keyed by contact id
     * @param $contact_ids - array of contactIDs that the system needs values for.
     * @param $job - the job_id
     * @param $tokens - tokens used in the mailing - use this to check whether a token is being used and avoid fetching data for unneeded tokens
     * @param $context - the class name
     *
     * @see https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_tokenValues
     */
    public static function tokenValues(&$values, $contact_ids, $job = null, $tokens = [], $context = null): void
    {
        foreach ($contact_ids as $contact_id) {
            $values[$contact_id]['contact.age'] = 'EXAMPLE-fixme!!';
        }
    }
}