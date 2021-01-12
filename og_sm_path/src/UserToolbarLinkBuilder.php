<?php

namespace Drupal\og_sm_path;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\og_sm\SiteManager;
use Drupal\user\ToolbarLinkBuilder;

/**
 * Link builder for the toolbar.
 */
class UserToolbarLinkBuilder extends ToolbarLinkBuilder {

  /**
   * Site manager.
   *
   * @var \Drupal\og_sm\SiteManager
   *   Site manager.
   */
  protected $siteManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(AccountProxyInterface $account, SiteManager $site_manager) {
    parent::__construct($account);
    $this->siteManager = $site_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function renderToolbarLinks() {
    $build = parent::renderToolbarLinks();

    $site = $this->siteManager->currentSite();
    if (!$site) {
      return $build;
    }

    // Make sure that the destination of the logout link stays within the
    // current site.
    $logout_url = &$build['#links']['logout']['url'];
    $logout_url->setOption('query', [
      'destination' => '/node/' . $site->id(),
    ]);

    return $build;
  }

}
