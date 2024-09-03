<?php

use CRM_LCD_MoveMembership_ExtensionUtil as E;

/**
 * This class provides the functionality to delete a group of memberships.
 *
 * This class provides functionality for the actual deletion.
 */
class CRM_LCD_MoveMembership_Form_Task extends CRM_Member_Form_Task {

  /**
   * Build all the data structures needed to build the form.
   */
  public function preProcess() {
    //check for delete
    if (!CRM_Core_Permission::checkActionPermission('CiviMember', CRM_Core_Action::UPDATE)) {
      CRM_Core_Error::statusBounce(ts('You do not have permission to access this page.'));
    }
    parent::preProcess();
  }

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    $this->addEntityRef('change_contact_id', ts('Select Contact'));

    if (class_exists('CRM_LCD_MoveContrib_BAO_MoveContrib')) {
      $this->add('checkbox', 'change_contributions', E::ts('Move all associated contributions'));
    } else {
      $this->add('hidden', 'change_contributions', '', ['id' => 'change_contributions']);
    }

    $count = count($this->_memberIds);
    $this->assign('count', $count);

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

  /**
   * Process the form after the input has been submitted and validated.
   */
  public function postProcess() {
    $moved = $failed = 0;
    $values = $this->exportValues();
    //Civi::log()->debug('postProcess', array('values' => $values));

    foreach ($this->_memberIds as $membershipId) {
      try {
        $currentContactId = civicrm_api3('membership', 'getvalue', array(
          'id' => $membershipId,
          'return' => 'contact_id',
        ));
      }
      catch (CiviCRM_API3_Exception $e) {}

      $params = array(
        'change_contact_id' => $values['change_contact_id'],
        'contact_id' => $values['change_contact_id'],
        'membership_id' => $membershipId,
        'current_contact_id' => $currentContactId,
        'change_contributions' => $values['change_contributions'],
      );

      if (CRM_LCD_MoveMembership_BAO_MoveMembership::moveMembership($params)) {
        $moved++;
      }
      else {
        $failed++;
      }
    }

    if ($moved) {
      CRM_Core_Session::setStatus(E::ts('%count membership moved.', array(
        'plural' => '%count memberships moved.',
        'count' => $moved
      )), ts('Moved'), 'success');
    }

    if ($failed) {
      CRM_Core_Session::setStatus(E::ts('1 could not be moved.', array(
        'plural' => '%count could not be moved.',
        'count' => $failed
      )), ts('Error'), 'error');
    }

    parent::postProcess();

    $session = CRM_Core_Session::singleton();
    CRM_Utils_System::redirect($session->readUserContext());
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
