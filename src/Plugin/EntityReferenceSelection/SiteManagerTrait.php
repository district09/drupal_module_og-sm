<?php

namespace Drupal\og_sm\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\og\OgGroupAudienceHelperInterface;
use Drupal\og_sm\SiteManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait to inject the site manager in an entity reference selection handler.
 */
trait SiteManagerTrait {

  /**
   * The site manager.
   *
   * @var \Drupal\og_sm\SiteManagerInterface
   */
  protected $siteManager;

  /**
   * Class constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface|null $entity_bundle_info
   *   The entity type bundle info service.
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository.
   * @param \Drupal\og_sm\SiteManagerInterface $site_manager
   *   The site manager.
   *
   * @SuppressWarnings(PHPMD.ExcessiveParameterList)
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    ModuleHandlerInterface $module_handler,
    AccountInterface $current_user,
    EntityFieldManagerInterface $entity_field_manager,
    EntityTypeBundleInfoInterface $entity_bundle_info = NULL,
    EntityRepositoryInterface $entity_repository,
    SiteManagerInterface $site_manager
  ) {
    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $entity_type_manager,
      $module_handler,
      $current_user,
      $entity_field_manager,
      $entity_bundle_info,
      $entity_repository
    );

    $this->siteManager = $site_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('module_handler'),
      $container->get('current_user'),
      $container->get('entity_field.manager'),
      $container->get('entity_type.bundle.info'),
      $container->get('entity.repository'),
      $container->get('og_sm.site_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function buildEntityQuery($match = NULL, $match_operator = 'CONTAINS') {
    $query = parent::buildEntityQuery($match, $match_operator);

    $site = $this->siteManager->currentSite();
    if ($site) {
      $query->condition(OgGroupAudienceHelperInterface::DEFAULT_FIELD, $site->id());
    }

    return $query;
  }

}
