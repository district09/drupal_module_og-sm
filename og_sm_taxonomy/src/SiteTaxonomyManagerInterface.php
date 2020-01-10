<?php

namespace Drupal\og_sm_taxonomy;

use Drupal\node\NodeInterface;
use Drupal\taxonomy\VocabularyInterface;

/**
 * Interface for site taxonomy manager classes.
 */
interface SiteTaxonomyManagerInterface {

  /**
   * Get a list of vocabulary names that may reference an OG group of the
   * specified type and bundle.
   *
   * @param string $entity_type_id
   *   The group entity ID.
   * @param string $bundle
   *   The group bundle.
   *
   * @return string[]
   *   Vocabulary names (labels) keyed by their machine name.
   */
  public function getSiteVocabularyNames($entity_type_id, $bundle);

  /**
   * Get all vocabulary objects that may reference an OG group of the
   * specified type and bundle.
   *
   * @param string $entity_type_id
   *   The group entity ID.
   * @param string $bundle
   *   The group bundle.
   *
   * @return \Drupal\taxonomy\VocabularyInterface[]
   *   Vocabulary objects keyed by their machine name.
   */
  public function getSiteVocabularies($entity_type_id, $bundle);

  /**
   * Fetches an array vocabularies referenced in an array query conditions.
   *
   * This helper function will loop recursively through the conditions and return
   * an array of referenced vocabularies.
   *
   * @param string[] $table_aliases
   *   An array of taxonomy table aliases.
   * @param array $conditions
   *   An array of query conditions.
   * @param \Drupal\taxonomy\VocabularyInterface[] $vocabularies
   *   (optional) An array of vocabulary objects.
   *
   * @return \Drupal\taxonomy\VocabularyInterface[]
   *   An array of vocabulary objects.
   */
  public function getSiteVocabulariesFromConditions(array $table_aliases, array $conditions, array $vocabularies = []);

  /**
   * Check if a given taxonomy vocabulary has the OG group audience field.
   *
   * @param string $name
   *   The vocabulary name.
   *
   * @return bool
   *   Whether or not this is a site vocabulary.
   */
  public function isSiteVocabulary($name);

  /**
   * Resets the weight for all site terms for a given vocabulary.
   *
   * @param \Drupal\node\NodeInterface $site
   *   The site for which the terms should be reset.
   * @param \Drupal\taxonomy\VocabularyInterface $vocabulary
   *   The vocabulary for which the terms should be reset.
   */
  public function resetTermWeights(NodeInterface $site, VocabularyInterface $vocabulary);

}
