<?php

namespace Drupal\og_sm_path;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\og_sm\SiteManagerInterface;
use Drupal\og_sm_config\Config\SiteConfigFactoryOverrideInterface;
use Drupal\og_sm_path\Event\SitePathEvent;
use Drupal\og_sm_path\Event\SitePathEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * A manager to process site paths.
 */
class SitePathManager implements SitePathManagerInterface {

  /**
   * The path alias storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $pathAliasStorage;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The site manager.
   *
   * @var \Drupal\og_sm\SiteManagerInterface
   */
  protected $siteManager;

  /**
   * The site configuration override service.
   *
   * @var \Drupal\og_sm_config\Config\SiteConfigFactoryOverrideInterface
   */
  protected $configFactoryOverride;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The cache tag invalidator service.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $invalidator;

  /**
   * Key value array where the key is the path and the value the site node.
   *
   * @var array
   */
  protected $sitesByPath = [];

  /**
   * Constructs a SitePathManager object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\og_sm\SiteManagerInterface $site_manager
   *   The site manager.
   * @param \Drupal\og_sm_config\Config\SiteConfigFactoryOverrideInterface $config_factory_override
   *   The site configuration override service.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   * @param \Drupal\Core\Database\Connection $connection
   *   A database connection for reading and writing path aliases.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $invalidator
   *   The cache tag invalidator service.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, LanguageManagerInterface $language_manager, SiteManagerInterface $site_manager, SiteConfigFactoryOverrideInterface $config_factory_override, EventDispatcherInterface $event_dispatcher, Connection $connection, CacheTagsInvalidatorInterface $invalidator) {
    $this->pathAliasStorage = $entity_type_manager->getStorage('path_alias');
    $this->languageManager = $language_manager;
    $this->siteManager = $site_manager;
    $this->configFactoryOverride = $config_factory_override;
    $this->eventDispatcher = $event_dispatcher;
    $this->connection = $connection;
    $this->invalidator = $invalidator;
  }

  /**
   * {@inheritdoc}
   */
  public function getPathFromSite(NodeInterface $site) {
    if (!empty($site->site_path)) {
      /** @var string $path */
      $path = $site->site_path;
      return $path;
    }

    return (string) $this->configFactoryOverride
      ->getOverride($site, 'site_settings')
      ->get('path');
  }

  /**
   * {@inheritdoc}
   */
  public function lookupPathAlias($path) {
    $langcode = $this->languageManager->getCurrentLanguage()->getId();

    /** @var \Drupal\path_alias\PathAliasInterface[] $path_alias */
    $path_alias = $this->pathAliasStorage->loadByProperties([
      'alias' => $path,
      'langcode' => $langcode,
    ]);

    if (!$path_alias) {
      /** @var \Drupal\path_alias\PathAliasInterface[] $path_alias */
      $path_alias = $this->pathAliasStorage->loadByProperties([
        'path' => $path,
        'langcode' => $langcode,
      ]);
    }

    if ($path_alias) {
      $path_alias = reset($path_alias);
      return $path_alias->getAlias();
    }

    return $path;
  }

  /**
   * {@inheritdoc}
   */
  public function getSiteFromPath($path) {
    // If the site for this path is already known, return it.
    if (isset($this->sitesByPath[$path])) {
      return $this->sitesByPath[$path];
    }
    foreach ($this->siteManager->getAllSites() as $site) {
      if ($this->getPathFromSite($site) === $path) {
        $this->sitesByPath[$path] = $site;
        return $site;
      }
    }
    $this->sitesByPath[$path] = FALSE;
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteSiteAliases(NodeInterface $site) {
    if (!$path = $this->getPathFromSite($site)) {
      return;
    }

    $path_alias_ids = $this->pathAliasStorage->getQuery()
      ->condition('alias', $path, 'STARTS_WITH')
      ->execute();

    if (!$path_alias_ids) {
      return;
    }

    /** @var \Drupal\path_alias\Entity\PathAlias[] $path_aliasses */
    $path_aliasses = $this->pathAliasStorage->loadMultiple($path_alias_ids);

    $tags = [];
    foreach ($path_aliasses as $path_alias) {
      // Try to find the route parameters from the path source so we can use
      // them to construct cache tags which should be invalidated.
      // @todo Remove once https://www.drupal.org/node/2480077 is fixed.
      $url = Url::fromUserInput($path_alias->getPath());

      if ($url->isRouted()) {
        foreach ($url->getRouteParameters() as $name => $value) {
          $tag = $name . ':' . $value;
          $tags[$tag] = $tag;
        }
      }

      $path_alias->delete();
    }

    if ($tags) {
      $this->invalidator->invalidateTags($tags);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setPath(NodeInterface $site, $path, $trigger_event = TRUE) {
    $config = $this->configFactoryOverride->getOverride($site, 'site_settings');
    $original_path = $config->get('path');
    if ($original_path === $path) {
      // No change.
      return;
    }

    // Change the path variable.
    $config->set('path', $path)->save();

    // Trigger the path change event.
    if ($trigger_event) {
      $event = new SitePathEvent($site, $original_path, $path);
      $this->eventDispatcher->dispatch(SitePathEvents::CHANGE, $event);
    }
  }

}
