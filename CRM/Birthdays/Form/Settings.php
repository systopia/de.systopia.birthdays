<?php

use CRM_Birthdays_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Birthdays_Form_Settings extends CRM_Core_Form
{
    const BIRTHDAYS_MESSAGE_TEMPLATE = 'Birthdays_Message_Template_ID';
    const BIRTHDAYS_SENDER_EMAIL_ADDRESS_ID = 'Birthdays_Sender_Email_Address';

    public function buildQuickForm()
    {
        $this->setDefaults(['birthday_sender_email_address_id' => Civi::settings()->get(self::BIRTHDAYS_SENDER_EMAIL_ADDRESS_ID)]);
        $this->add(
            'select',
            "birthday_sender_email_address_id",
            E::ts("Send From"),
            $this->getSenderEmailAddresses(),
            true,
            ['class' => 'huge crm-select2', 'placeholder' => E::ts("-select-")]
        );

        $this->setDefaults(['message_template_id' => Civi::settings()->get(self::BIRTHDAYS_MESSAGE_TEMPLATE)]);
        $this->add(
            'select', // field type
            'message_template_id', // field name
            E::ts('Select template'), // field label
            $this->getMessageTemplates(), // list of options
            TRUE, // is required
            ['class' => 'huge crm-select2', 'placeholder' => E::ts("-select-")]
        );

        $this->addButtons([
            [
                'type' => 'submit',
                'name' => E::ts('Submit'),
                'isDefault' => TRUE,
            ],
        ]);
        parent::buildQuickForm();
    }

    public function postProcess()
    {
        $values = $this->exportValues();

        Civi::settings()->set(self::BIRTHDAYS_SENDER_EMAIL_ADDRESS_ID, $values['birthday_sender_email_address_id']);

        Civi::settings()->set(self::BIRTHDAYS_MESSAGE_TEMPLATE, intval($values['message_template_id']));

        parent::postProcess();
    }

    public function getMessageTemplates(): array
    {
        $list = [];
        try {
            $messageTemplates = \Civi\Api4\MessageTemplate::get()
                ->addSelect('msg_subject')
                ->addWhere('is_active', '=', TRUE)
                ->addWhere('workflow_id', 'IS EMPTY')
                ->setLimit(25)
                ->execute();
            $messageTemplates->indexBy('id');
            $messageTemplates->column('msg_subject');
            foreach ($messageTemplates as $tpl_key => $tpl_value) {
                $list[$tpl_key] = $tpl_value['msg_subject'];
            }
        } catch (Exception $exception) {
            Civi::log()->debug(E::LONG_NAME . " " . "getMessageTemplates method call failed: $exception");
        }

        return $list;
    }

    /**
     * Get a list of the available/allowed sender email addresses
     */
    protected function getSenderEmailAddresses(): array
    {
        $dropdown_list = [];
        $from_email_addresses = CRM_Core_OptionGroup::values('from_email_address');
        foreach ($from_email_addresses as $key => $from_email_address) {
            $dropdown_list[$key] = htmlentities($from_email_address);
        }
        return $dropdown_list;
    }
}
