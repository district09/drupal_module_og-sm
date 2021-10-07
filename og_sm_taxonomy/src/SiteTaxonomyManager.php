<?php

namespace Drupal\og_sm_taxonomy;

use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Query\ConditionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\og\GroupTypeManagerInterface;
use Drupal\taxonomy\VocabularyInterface;

/**
 * A manager to keep track of which taxonomy terms are og_sm Site enabled.
 */
class SiteTaxonomyManager implements SiteTaxonomyManagerInterface {

  /**
   * The group type manager.
   *
   * @var \Drupal\og\GroupTypeManagerInterface
   */
  protected $groupTypeManager;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a SiteTaxonomyManager object.
   *
   * @param \Drupal\og\GroupTypeManagerInterface $group_type_manager
   *   The group type manager.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(GroupTypeManagerInterface $group_type_manager, EntityTypeManagerInterface $entity_type_manager, Connection $database) {
    $this->groupTypeManager = $group_type_manager;
    $this->entityTypeManager = $entity_type_manager;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public function getSiteVocabularyNames($entity_type_id, $bundle) {
    $bundles = $this->groupTypeManager->getGroupContentBundleIdsByGroupBundle($entity_type_id, $bundle);

    if (isset($bundles['taxonomy_term'])) {
      return $bundles['taxonomy_term'];
    }

    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getSiteVocabularies($entity_type_id, $bundle) {
    $vids = $this->getSiteVocabularyNames($entity_type_id, $bundle);
    if (!$vids) {
      return [];
    }

    /** @var \Drupal\taxonomy\VocabularyInterface[] $vocabularies */
    $vocabularies = $this->entityTypeManager
      ->getStorage('taxonomy_vocabulary')
      ->loadMultiple($vids);

    return $vocabularies;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  public function getSiteVocabulariesFromConditions(array $table_aliases, array $conditions, array $vocabularies = []) {
    foreach ($conditions as $condition) {
      if (empty($condition['field'])) {
        continue;
      }

      // If the condition field implements the QueryConditionInterface it means
      // this condition contains several child conditions. In that case we need
      // loop through those to find the referenced vocabularies.
      if ($condition['field'] instanceof ConditionInterface) {
        /** @var \Drupal\taxonomy\VocabularyInterface[] $vocabularies */
        $vocabularies = $this->getSiteVocabulariesFromConditions($table_aliases, $condition['field']->conditions(), $vocabularies);
        continue;
      }

      if (!is_string($condition['field']) || empty($condition['value'])) {
        continue;
      }
      // Check if the condition field is either a vid or a machine name. If so
      // load the vocabularies and add them to the vocabularies array.
      $field_parts = explode('.', $condition['field']);
      if (count($field_parts) !== 2) {
        continue;
      }

      if (!in_array($field_parts[0], $table_aliases)) {
        continue;
      }

      switch ($field_parts[1]) {
        case 'vid':
          /** @var \Drupal\taxonomy\VocabularyInterface[] $entities */
          $entities = $this->entityTypeManager
            ->getStorage('taxonomy_vocabulary')
            ->loadMultiple((array) $condition['value']);
          $vocabularies += $entities;
          break;

        case 'machine_name':
          /** @var \Drupal\taxonomy\VocabularyInterface[] $entities */
          $entities = $this->entityTypeManager
            ->getStorage('taxonomy_vocabulary')
            ->loadByProperties([
              'machine_name' => (array) $condition['value'],
            ]);
          $vocabularies += $entities;
          break;
      }
    }

    return $vocabularies;
  }

  /**
   * {@inheritdoc}
   */
  public function isSiteVocabulary($name) {
    $names = $this->groupTypeManager->getAllGroupContentBundlesByEntityType('taxonomy_term');
    return array_key_exists($name, $names);
  }

  /**
   * {@inheritdoc}
   */
  public function resetTermWeights(NodeInterface $site, VocabularyInterface $vocabulary) {
    // Custom query to order only the terms of the current Site.
    // \Drupal\Core\Database\Query\Update does not support joins :(.
    $query = <<<EOT
    UPDATE
      {taxonomy_term_field_data} td
      JOIN {taxonomy_term__og_audience} audience ON (td.tid = audience.entity_id)
    SET
      td.`weight` = 0
    WHERE
      td.vid = :vid
      AND audience.og_audience_target_id = :site_id
EOT;

    $this->database->query($query, [
      ':vid' => $vocabulary->id(),
      ':site_id' => $site->id(),
    ]);
  }

}
