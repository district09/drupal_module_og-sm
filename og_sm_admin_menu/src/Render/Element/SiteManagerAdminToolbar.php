<?php

declare(strict_types=1);

namespace Drupal\og_sm_admin_menu\Render\Element;

use Drupal\admin_toolbar\Render\Element\AdminToolbar;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\og_sm\OgSm;
use Drupal\og_sm_admin_menu\Controller\ToolbarController;

/**
 * Pre render callbacks for the AdminToolbar.
 */
class SiteManagerAdminToolbar implements TrustedCallbackInterface {

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return ['preRenderTray'];
  }

  /**
   * Pre render the admin tray.
   *
   * @param array $build
   *   The build to alter.
   *
   * @return array
   *   The altered render array.
   */
  public static function preRenderTray(array $build): array {
    $siteManager = OgSm::siteManager();

    $adminToolbarExists = \Drupal::moduleHandler()->moduleExists('admin_toolbar');

    if (!$siteManager->currentSite()) {
      // If there's no site context, render the toolbar as usual.
      return $adminToolbarExists
        ? AdminToolbar::preRenderTray($build)
        : ToolbarController::preRenderAdministrationTray($build);
    }

    // @todo This can be simplified once https://www.drupal.org/node/1869638 has
    // been implemented in core and the "admin_toolbar" module.
    /** @var \Drupal\Core\Menu\MenuLinkTreeInterface $menuTree */
    $menuTree = \Drupal::service('toolbar.menu_tree');
    $parameters = new MenuTreeParameters();
    // Depending on whether the 'admin_toolbar' module exists we should change
    // the menu depth shown in the toolbar.
    $max_depth = $adminToolbarExists ? 4 : 2;
    $parameters->setMinDepth(2)->setMaxDepth($max_depth)->onlyEnabledLinks();
    $tree = $menuTree->load('og_sm_admin_menu', $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
      ['callable' => $adminToolbarExists ? 'toolbar_tools_menu_navigation_links' : 'toolbar_menu_navigation_links'],
    ];
    $tree = $menuTree->transform($tree, $manipulators);
    $build['administration_menu'] = $menuTree->build($tree);

    return $build;
  }

}
