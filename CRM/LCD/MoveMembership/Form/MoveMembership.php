<?php

use CRM_LCD_MoveMembership_ExtensionUtil as E;

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_LCD_MoveMembership_Form_MoveMembership extends CRM_Core_Form {

  /**
   * check permissions
   */
  public function preProcess() {
    //check for edit permission
    if (!CRM_Core_Permission::checkActionPermission('CiviMember', CRM_Core_Action::UPDATE)) {
      CRM_Core_Error::statusBounce(ts('You do not have permission to access this page.'));
    }
    parent::preProcess();
  }

  public function buildQuickForm() {
    $membershipID = CRM_Utils_Request::retrieve('id', 'Positive', $this);

    $contactID = civicrm_api3('membership', 'getvalue', [
      'id' => $membershipID,
      'return' => 'contact_id',
    ]);


    //get current contact name.
    $this->assign('currentContactName', CRM_Contact_BAO_Contact::displayName($contactID));

    $this->addEntityRef('change_contact_id', ts('Select Contact'));

    if (class_exists('CRM_LCD_MoveContrib_BAO_MoveContrib')) {
      $this->add('checkbox', 'change_contributions', E::ts('Move all associated contributions'));
    } else {
      $this->add('hidden', 'change_contributions', '', ['id' => 'change_contributions']);
    }

    $this->add('hidden', 'contact_id', '', ['id' => 'contact_id']);
    $this->add('hidden', 'membership_id', $membershipID, ['id' => 'membership_id']);
    $this->add('hidden', 'current_contact_id', $contactID, ['id' => 'current_contact_id']);
    $this->assign('contactId', $contactID);

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    //Civi::log()->debug('postProcess', array('values' => $values));

    $params = array(
      'change_contact_id' => $values['change_contact_id'],
      'contact_id' => $values['change_contact_id'],
      'membership_id' => $values['membership_id'],
      'current_contact_id' => $values['current_contact_id'],
      'change_contributions' => $values['change_contributions'],
    );

    $result = CRM_LCD_MoveMembership_BAO_MoveMembership::moveMembership($params);

    if ($result) {
      CRM_Core_Session::setStatus(E::ts('Membership moved successfully.'), E::ts('Moved'), 'success');
    }
    else {
      CRM_Core_Session::setStatus(E::ts('Unable to move membership.'), ts('Error'), 'error');
    }

    parent::postProcess();
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
    $elementNames = array();
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
