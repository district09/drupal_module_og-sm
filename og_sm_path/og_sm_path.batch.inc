<?php

/**
 * @file
 * Batch functionality.
 */

use Drupal\Core\Cache\Cache;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\og\OgGroupAudienceHelperInterface;

/**
 * Create a batch for a single Site to update ALL aliases (admin & content).
 *
 * @param \Drupal\node\NodeInterface $site
 *   The Site to create the batch for.
 */
function og_sm_path_site_alias_update_batch(NodeInterface $site) {
  $file = \Drupal::service('extension.list.module')->getPath('og_sm_path') . '/og_sm_path.batch.inc';

  $batch = [
    'title' => t('Update Site content aliases'),
    'operations' => [
      ['og_sm_path_site_alias_update_content_process', [$site]],
      ['og_sm_path_site_alias_update_menu_process', []],
    ],
    'file' => $file,
  ];

  batch_set($batch);
}

/**
 * Batch processing callback; Process to update the Site content pages.
 *
 * This will create/update aliases for all content pages of a single Site.
 *
 * @param \Drupal\node\NodeInterface $site
 *   The Site Node to run the process for.
 * @param array $context
 *   The process context.
 *
 * @see og_sm_path_site_alias_update_batch
 */
function og_sm_path_site_alias_update_content_process(NodeInterface $site, array &$context) {
  _og_sm_path_site_alias_update_content_init($site, $context);

  // Get the site content.
  $site_content = _og_sm_path_site_alias_update_content_get_site_content($site, $context);
  if (!$site_content) {
    $context['finished'] = 1;
    return;
  }

  // Rebuild the path alias for the node.
  $tags = [];
  foreach ($site_content as $node) {
    \Drupal::service('pathauto.generator')->updateEntityAlias($node, 'update');
    $context['sandbox']['progress']++;
    $tags[] = 'node:' . $node->id();
    $context['sandbox']['last_id'] = $node->id();
  }
  // @todo This can probably be removed once
  // https://www.drupal.org/node/2480077 is fixed.
  Cache::invalidateTags($tags);

  // Show some feedback.
  $context['message'] = t(
    'Updated %count of %total content aliases for %site.',
    [
      '%count' => $context['sandbox']['progress'],
      '%total' => $context['sandbox']['total'],
      '%site' => $site->label(),
    ]
  );

  // Progress.
  $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['total'];
}

/**
 * Batch processing callback; Process to update the menu's.
 *
 * This will trigger a menu cache clear.
 *
 * @param array $context
 *   The process context.
 *
 * @see og_sm_path_site_alias_update_batch
 */
function og_sm_path_site_alias_update_menu_process(array &$context) {
  \Drupal::service('router.builder')->rebuild();
  $context['message'] = t('Menu cache cleared.');
  $context['finished'] = 1;
}

/**
 * Initiate the batch.
 *
 * @param \Drupal\node\NodeInterface $site
 *   The Site node id.
 * @param array $context
 *   Batch API context.
 *
 * @see og_sm_path_site_alias_update_content_process
 */
function _og_sm_path_site_alias_update_content_init(NodeInterface $site, array &$context) {
  if (!empty($context['sandbox'])) {
    return;
  }

  /** @var \Drupal\field\Entity\FieldStorageConfig|NULL $config */
  $config = FieldStorageConfig::loadByName(
    'node',
    OgGroupAudienceHelperInterface::DEFAULT_FIELD
  );

  if (!$config) {
    return;
  }

  // Count relevant nodes.
  $query = \Drupal::entityQuery('node');
  $total = $query->accessCheck()
    ->condition(OgGroupAudienceHelperInterface::DEFAULT_FIELD, $site->id())
    ->count()->execute();
  $context['sandbox']['progress'] = 0;
  $context['sandbox']['last_id'] = 0;
  $context['sandbox']['total'] = $total;
}

/**
 * Get the next set of site content to process.
 *
 * @param \Drupal\node\NodeInterface $site
 *   The Site node id.
 * @param array $context
 *   Batch API context.
 *
 * @return array
 *   Array of node objects.
 *
 * @see og_sm_path_site_alias_update_content_process
 */
function _og_sm_path_site_alias_update_content_get_site_content(NodeInterface $site, array &$context) {
  $limit = 50;

  // Retrieve the next batch.
  $query = \Drupal::entityQuery('node');
  $result = $query
    ->accessCheck()
    ->condition(OgGroupAudienceHelperInterface::DEFAULT_FIELD, $site->id())
    ->condition('nid', $context['sandbox']['last_id'], '>')
    ->sort('nid', 'ASC')
    ->range(0, $limit)
    ->execute();

  // Check if we found any site content.
  if (!$result) {
    return [];
  }

  return Node::loadMultiple($result);
}
