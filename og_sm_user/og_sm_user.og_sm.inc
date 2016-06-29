<?php
/**
 * @file
 * Site Manager (og_sm) related hooks.
 */

/**
 * Implements hook_og_sm_user_profile_info().
 */
function og_sm_user_og_sm_user_profile_info() {
  return array(
    'basic_info' => array(
      'render callback' => 'og_sm_user_section_basic_info',
      'weight' => -10,
    ),
  );
}

/**
 * Implements hook_og_sm_feature_info().
 */
function og_sm_user_og_sm_feature_info() {
  $items = array();

  $items[OG_SM_USER_FEATURE_PROFILE] = array(
    'name' => t('User profile'),
    'description' => t('User profile within a site.'),
  );

  return $items;
}