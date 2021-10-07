<?php

namespace Drupal\og_sm_taxonomy\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Url;
use Drupal\og_sm\SiteManagerInterface;
use Drupal\taxonomy\Form\TermDeleteForm as TermDeleteFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extends TermDeleteForm to override the cancel url based on site context.
 */
class TermDeleteForm extends TermDeleteFormBase {

  /**
   * The site manager.
   *
   * @var \Drupal\og_sm\SiteManagerInterface
   */
  protected $siteManager;

  /**
   * Constructs a TermDeleteForm object.
   *
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\og_sm\SiteManagerInterface $site_manager
   *   The site manager.
   */
  public function __construct(
    EntityRepositoryInterface $entity_repository,
    EntityTypeBundleInfoInterface $entity_bundle_info = NULL,
    TimeInterface $time = NULL,
    SiteManagerInterface $site_manager = NULL
  ) {
    parent::__construct($entity_repository, $entity_bundle_info, $time);
    $this->siteManager = $site_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.repository'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time'),
      $container->get('og_sm.site_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    $site = $this->siteManager->currentSite();

    if (!$site) {
      return parent::getCancelUrl();
    }

    return new Url('og_sm_taxonomy.vocabulary.term_overview', [
      'node' => $site->id(),
      'taxonomy_vocabulary' => $this->getBundleEntity()->id(),
    ]);
  }

}
