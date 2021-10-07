<?php

namespace Drupal\og_sm_config\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\og_sm\SiteManagerInterface;
use Drupal\og_sm_config\Config\SiteConfigFactoryOverrideInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for implementing site configuration forms.
 */
abstract class SiteConfigFormBase extends ConfigFormBase {

  /**
   * The site configuration override service.
   *
   * @var \Drupal\og_sm_config\Config\SiteConfigFactoryOverrideInterface
   */
  protected $configOverride;

  /**
   * The site manager.
   *
   * @var \Drupal\og_sm\SiteManagerInterface
   */
  protected $siteManager;

  /**
   * The current site.
   *
   * @var \Drupal\node\NodeInterface|null
   */
  protected $currentSite;

  /**
   * Constructs a \Drupal\system\SiteConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The factory for configuration objects.
   * @param \Drupal\og_sm_config\Config\SiteConfigFactoryOverrideInterface $configOverride
   *   The site configuration override service.
   * @param \Drupal\og_sm\SiteManagerInterface $siteManager
   *   The site manager.
   */
  public function __construct(
    ConfigFactoryInterface $configFactory,
    SiteConfigFactoryOverrideInterface $configOverride,
    SiteManagerInterface $siteManager
  ) {
    parent::__construct($configFactory);
    $this->siteManager = $siteManager;
    $this->currentSite = $this->siteManager->currentSite();
    $this->configOverride = $configOverride;
  }

  /**
   * Retrieves a global configuration object.
   *
   * @param string $name
   *   The name of the configuration object to retrieve. The name corresponds to
   *   a configuration file. For @code \Drupal::config('book.admin') @endcode,
   *   the config object returned will contain the contents of book.admin
   *   configuration file.
   *
   * @return \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig
   *   An editable configuration object if the given name is listed in the
   *   getEditableConfigNames() method or an immutable configuration object if
   *   not.
   */
  protected function getGlobalConfig($name) {
    return parent::config($name);
  }

  /**
   * Retrieves a configuration object.
   *
   * @param string $name
   *   The name of the configuration object to retrieve.
   *
   * @return \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig|\Drupal\og_sm_config\Config\SiteConfigOverride
   *   An configuration object.
   */
  protected function config($name) {
    if ($this->currentSite) {
      return $this->configOverride->getOverride($this->currentSite, $name);
    }

    return parent::config($name);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('og_sm.config_factory_override'),
      $container->get('og_sm.site_manager')
    );
  }

}
