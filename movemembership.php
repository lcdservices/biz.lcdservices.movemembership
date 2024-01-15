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
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function movemembership_civicrm_xmlMenu(&$files) {
  _movemembership_civix_civicrm_xmlMenu($files);
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

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function movemembership_civicrm_managed(&$entities) {
  _movemembership_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function movemembership_civicrm_caseTypes(&$caseTypes) {
  _movemembership_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function movemembership_civicrm_angularModules(&$angularModules) {
_movemembership_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function movemembership_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _movemembership_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

function movemembership_civicrm_links($op, $objectName, $objectId, &$links, &$mask, &$values) {
  if ($op === 'membership.tab.row' && $objectName === 'Membership' && CRM_Core_Permission::check('allow Move Membership')) {
    $links[] = array(
      'name' => E::ts('Move membership'),
      'url' => 'civicrm/movemembership/task?reset=1&task_item=move',
      'qs' => 'id=%%id%%',
      'title' => E::ts('Move Membership'),
      'key' => 'move',
      'weight' => 130,
    );
  }
}

function movemembership_civicrm_searchTasks($objectType, &$tasks) {
  if ($objectType === 'membership' && CRM_Core_Permission::check('allow Move Membership')) {
    $tasks[] = [
      'title' => E::ts('Move memberships'),
      'class' => 'CRM_LCD_MoveMembership_Form_Task',
      'result' => TRUE,
      'is_single_mode' => TRUE,
      'title_single_mode' => E::ts('Move Membership'),
      'name' => E::ts('Move Membership'),
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
  $prefix = ts('Move Memberships') . ': '; // name of extension or module
  $permissions['allow Move Membership'] = $prefix . ts('allow Move Membership');
}