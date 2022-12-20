<?php

use CRM_Birthdays_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Birthdays_Form_Settings extends CRM_Core_Form
{
    public function buildQuickForm()
    {
        $this->setDefaults(['message_template_id' => Civi::settings()->get('Birthdays_Message_Template')]);
        $this->add(
            'select', // field type
            'message_template_id', // field name
            ts('Select template'), // field label
            $this->get_message_templates(), // list of options
            TRUE // is required
        );

        $this->addButtons([
            [
                'type' => 'submit',
                'name' => E::ts('Submit'),
                'isDefault' => TRUE,
            ],
        ]);

        // export form elements
        $this->assign('elementNames', $this->getRenderableElementNames());
        parent::buildQuickForm();
    }

    public function postProcess()
    {
        $values = $this->exportValues();

        Civi::settings()->set('Birthdays_Message_Template', intval($values['message_template_id']));

        parent::postProcess();
    }

    public function get_message_templates(): array
    {
        $list = [];
        try {
            $messageTemplates = \Civi\Api4\MessageTemplate::get()
                ->addSelect('id', 'msg_title')
                ->addWhere('is_active', '=', TRUE)
                ->addWhere('workflow_id', 'IS EMPTY')
                ->setLimit(25)
                ->execute();
            foreach ($messageTemplates as $messageTemplate) {
                $list[$messageTemplate['id']] = $messageTemplate['msg_title'];
            }
        } catch (Exception $exception) {
            Civi::log()->debug("Birthdays: getTemplates API call failed!");
        }

        return $list;
    }

    /**
     * Get the fields/elements defined in this form.
     *
     * @return array (string)
     */
  public function getRenderableElementNames() {
        // The _elements list includes some items which should not be
        // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
        // items don't have labels.  We'll identify renderable by filtering on
        // the 'label'.
        $elementNames = [];
        foreach ($this->_elements as $element) {
            /** @var HTML_QuickForm_Element $element */
            $label = $element->getLabel();
            if (!empty($label)) {
                $elementNames[] = $element->getName();
            }
        }
        return $elementNames;
    }
}
