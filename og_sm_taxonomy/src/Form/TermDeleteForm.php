<?php

namespace Drupal\og_sm_taxonomy\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Url;
use Drupal\og_sm\SiteManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extends TermDeleteForm to override the cancel url based on site context.
 *
 * Overrides TermDeleteForm to change the CancelUrl.
 *
 * @todo: Move functionality to form_alter.
 */
class TermDeleteForm extends ContentEntityDeleteForm {

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
      // The cancel URL is the vocabulary collection, terms have no global
      // list page.
      return new Url('entity.taxonomy_vocabulary.collection');
    }

    return new Url('og_sm_taxonomy.vocabulary.term_overview', [
      'node' => $site->id(),
      'taxonomy_vocabulary' => $this->getBundleEntity()->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  protected function getRedirectUrl() {
    return $this->getCancelUrl();
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Deleting a term will delete all its children if there are any. This action cannot be undone.');
  }

  /**
   * {@inheritdoc}
   */
  protected function getDeletionMessage() {
    return $this->t('Deleted term %name.', ['%name' => $this->entity->label()]);
  }

}
