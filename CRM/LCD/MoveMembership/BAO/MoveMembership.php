<?php

/**
 * Class CRM_LCD_MoveMembership_Form_MoveMembership
 */
class CRM_LCD_MoveMembership_BAO_MoveMembership {

  static function moveMembership($params) {
    try {
      $membership = civicrm_api3('membership', 'create', array(
        'id' => $params['membership_id'],
        'contact_id' => $params['contact_id'],
      ));
    }
    catch (CiviCRM_API3_Exception $e) {}

    // record activity for moving membership
    if (empty($membership['is_error'])) {
      $subject = "Membership #{$params['membership_id']} Moved";
      $details = "Membership #{$params['membership_id']} was moved from contact #{$params['current_contact_id']} to contact #{$params['change_contact_id']}.";

      $activityTypeID = \CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'membership_reassignment', 'name');

      $activityParams = array(
        'source_contact_id' => $params['current_contact_id'],
        'activity_type_id' => $activityTypeID,
        'activity_date_time' => date('YmdHis'),
        'subject' => $subject,
        'details' => $details,
        'status_id' => 2,
      );

      $session = CRM_Core_Session::singleton();
      $id = $session->get('userID');

      if ($id) {
        $activityParams['source_contact_id'] = $id;
        $activityParams['target_contact_id'][] = $params['current_contact_id'];
        $activityParams['target_contact_id'][] = $params['change_contact_id'];
      }

      try {
        CRM_Activity_BAO_Activity::create($activityParams);
      }
      catch (CiviCRM_API3_Exception $e) {}

      return TRUE;
    }

    return FALSE;
  }
}
