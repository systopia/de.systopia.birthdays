<?php

use CRM_Birthdays_ExtensionUtil as E;

class CRM_Birthdays_ContactInfo
{
    private ?string $contact_iso_birthdate;

    /**
     * @throws Exception
     */
    public function __construct($contact_id)
    {
        $this->contact_iso_birthdate = self::getBirthDateOfContactId($contact_id);
    }


    /**
     * @param $contact_id
     * @throws Exception
     */
    private function getBirthDateOfContactId($contact_id): string
    {
        try {
            $contact = \Civi\Api4\Contact::get()
                ->addSelect('birth_date')
                ->addWhere('id', '=', $contact_id)
                ->execute()->single();
            return $contact['birth_date'];
        } catch (Exception $exception) {
            Civi::log()->debug(E::LONG_NAME . " " . "Failed to fetch birth_date: $exception");
            throw new Exception("Failed to fetch birth_date: $exception");
        }
    }

    /**
     * Calculates age of given ISO date string
     * @param string $iso_birth_date ISO birthdate
     * @throws Exception
     */
    private function calculateBirthday(string $iso_birth_date): int
    {
        return date_diff(date_create($iso_birth_date), date_create('now'))->y;
    }

    /**
     * @throws Exception
     */
    public function age(): string
    {
        $age = $this->calculateBirthday($this->contact_iso_birthdate);

        if (empty($age)) {
            throw new Exception("Age cannot be 0");
        }
        return $age;
    }
}
