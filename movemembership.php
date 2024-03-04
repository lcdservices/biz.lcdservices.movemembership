<?php

require_once 'movemembership.civix.php';

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
  /*Civi::log()->debug('movemembership_civicrm_links', array(
    '$op' => $op,
    '$objectName' => $objectName,
    '$objectId' => $objectId,
    '$links' => $links,
    '$mask' => $mask,
    '$values' => $values,
  ));*/

  if ($op == 'membership.tab.row' && $objectName == 'Membership') {
    $links[] = array(
      'name' => 'Move',
      'url' => 'civicrm/movemembership',
      'qs' => 'id=%%id%%',
      'title' => 'Move',
      //'bit' => NULL,
    );
  }
}

function movemembership_civicrm_searchTasks($objectType, &$tasks) {
  /*Civi::log()->debug('movemembership_civicrm_searchTasks', array(
    '$objectType' => $objectType,
    '$tasks' => $tasks,
  ));*/

  if ($objectType == 'membership') {
    $tasks[] = array(
      'title' => 'Move memberships',
      'class' => 'CRM_LCD_MoveMembership_Form_Task',
      'result' => TRUE,
    );
  }
}
