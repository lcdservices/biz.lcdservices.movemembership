<?php

require_once 'movemembership.civix.php';

use CRM_LCD_MoveMembership_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function movemembership_civicrm_config(&$config) {
  _movemembership_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function movemembership_civicrm_install() {
  _movemembership_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function movemembership_civicrm_enable() {
  _movemembership_civix_civicrm_enable();
}

function movemembership_civicrm_links($op, $objectName, $objectId, &$links, &$mask, &$values) {
  if ($op === 'membership.tab.row' && $objectName === 'Membership' && CRM_Core_Permission::check('allow Move Membership')) {
    $links[] = array(
      'name' => E::ts('Move'),
      'url' => 'civicrm/movemembership/task?reset=1&task_item=move',
      'qs' => 'id=%%id%%',
      'title' => E::ts('Move'),
      'key' => 'move',
      'weight' => 130,
    );
  }
}

function movemembership_civicrm_searchTasks($objectType, &$tasks) {
  if ($objectType === 'membership' && CRM_Core_Permission::check('allow Move Membership')) {
    $tasks[] = [
      'title' => E::ts('Move Membership'),
      'class' => 'CRM_LCD_MoveMembership_Form_Task',
      'result' => TRUE,
      'is_single_mode' => TRUE,
      'title_single_mode' => E::ts('Move'),
      'name' => E::ts('Move'),
      'url' => 'civicrm/membership/task?reset=1&task_item=move',
      'key' => 'move',
      'weight' => 130,
    ];
  }
}

/**
 *Implementation of hook_civicrm_permission
 * @param array $permissions
 */
function movemembership_civicrm_permission(&$permissions) {
  $permissions['allow Move Membership'] = [
    'label' => E::ts('CiviCRM: Allow Move Memberships'),
    'description' => E::ts('Allow Move Membership')
   ];

}