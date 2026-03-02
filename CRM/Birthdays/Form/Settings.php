<?php

declare(strict_types = 1);

use CRM_Birthdays_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Birthdays_Form_Settings extends CRM_Core_Form {
  public const BIRTHDAYS_MESSAGE_TEMPLATE = 'Birthdays_Message_Template_ID';
  public const BIRTHDAYS_SENDER_EMAIL_ADDRESS = 'Birthdays_Sender_Email_Address';

  public function buildQuickForm(): void {
    $this->setDefaults(
        ['birthday_sender_email_address' => Civi::settings()->get(self::BIRTHDAYS_SENDER_EMAIL_ADDRESS)]
    );
    $this->add(
        'select',
        'birthday_sender_email_address',
        E::ts('Send From'),
        ['' => E::ts('- Default -')] + CRM_Core_BAO_Email::domainEmails(),
        TRUE,
        ['class' => 'huge crm-select2', 'placeholder' => E::ts('-select-')]
    );

    $this->setDefaults(['message_template_id' => Civi::settings()->get(self::BIRTHDAYS_MESSAGE_TEMPLATE)]);
    $this->add(
        'select',
        'message_template_id',
        E::ts('Select template'),
        $this->getMessageTemplates(),
        TRUE,
        ['class' => 'huge crm-select2', 'placeholder' => E::ts('-select-')]
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

  public function postProcess(): void {
    $values = $this->exportValues();

    Civi::settings()->set(self::BIRTHDAYS_SENDER_EMAIL_ADDRESS, $values['birthday_sender_email_address']);

    Civi::settings()->set(self::BIRTHDAYS_MESSAGE_TEMPLATE, intval($values['message_template_id']));

    parent::postProcess();
  }

  /**
   * @return array<int, string>
   */
  public function getMessageTemplates(): array {
    $list = [];
    try {
      $messageTemplates = \Civi\Api4\MessageTemplate::get()
        ->addSelect('msg_subject')
        ->addWhere('is_active', '=', TRUE)
        ->addWhere('workflow_id', 'IS EMPTY')
        ->execute();
      $messageTemplates->indexBy('id');
      $messageTemplates->column('msg_subject');
      /**
       * @var int $tpl_key
       * @var array<string, string> $tpl_value
       */
      foreach ($messageTemplates as $tpl_key => $tpl_value) {
        $list[$tpl_key] = $tpl_value['msg_subject'];
      }
    }
    catch (Exception $exception) {
      // @ignoreException
      Civi::log()->debug(E::LONG_NAME . ' ' . "getMessageTemplates method call failed: $exception");
    }

    return $list;
  }

}
