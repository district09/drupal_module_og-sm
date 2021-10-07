<?php

namespace Drupal\og_sm_taxonomy\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Drupal\og\OgAccessInterface;
use Drupal\og_sm_taxonomy\SiteTaxonomyManagerInterface;

/**
 * Determines access to for taxonomy operations within site context.
 */
class SiteVocabularyAccessCheck implements AccessInterface {

  /**
   * The site taxonomy manager.
   *
   * @var \Drupal\og_sm_taxonomy\SiteTaxonomyManagerInterface
   */
  protected $siteTaxonomyManager;

  /**
   * The OG access service.
   *
   * @var \Drupal\og\OgAccessInterface
   */
  protected $ogAccess;

  /**
   * Constructs a new SiteVocabularyAccessCheck.
   *
   * @param \Drupal\og_sm_taxonomy\SiteTaxonomyManagerInterface $siteTaxonomyManager
   *   The site taxonomy manager.
   * @param \Drupal\og\OgAccessInterface $ogAccess
   *   The OG access service.
   */
  public function __construct(SiteTaxonomyManagerInterface $siteTaxonomyManager, OgAccessInterface $ogAccess) {
    $this->siteTaxonomyManager = $siteTaxonomyManager;
    $this->ogAccess = $ogAccess;
  }

  /**
   * Checks access for a site taxonomy vocabulary.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged in account.
   * @param \Drupal\node\NodeInterface $node
   *   The site node.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, NodeInterface $node) {
    if ($account->hasPermission('administer taxonomy')) {
      return AccessResult::allowed();
    }

    if ($this->ogAccess->userAccess($node, 'access taxonomy overview', $account)->isAllowed()) {
      return AccessResult::allowed();
    }

    if ($this->ogAccess->userAccess($node, 'administer taxonomy', $account)->isAllowed()) {
      return AccessResult::allowed();
    }

    foreach ($this->siteTaxonomyManager->getSiteVocabularyNames($node->getEntityTypeId(), $node->bundle()) as $vocabulary_name) {
      $access = $this->ogAccess->userAccess($node, "create $vocabulary_name taxonomy_term", $account);
      $access->orIf($this->ogAccess->userAccess($node, "edit any $vocabulary_name taxonomy_term", $account));
      $access->orIf($this->ogAccess->userAccess($node, "edit own $vocabulary_name taxonomy_term", $account));
      $access->orIf($this->ogAccess->userAccess($node, "delete any $vocabulary_name taxonomy_term", $account));
      $access->orIf($this->ogAccess->userAccess($node, "delete own $vocabulary_name taxonomy_term", $account));

      if ($access->isAllowed()) {
        return AccessResult::allowed();
      }
    }

    return AccessResult::neutral();
  }

}
