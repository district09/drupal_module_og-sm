<?php

declare(strict_types=1);

namespace Drupal\og_sm_admin_menu\Render\Element;

use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\og_sm\OgSm;
use Drupal\og_sm_admin_menu\Controller\ToolbarController;

/**
 * Pre render callbacks for the AdminToolbar.
 */
class AdminToolbar implements TrustedCallbackInterface {

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
   *   Tha altered render array.
   */
  public static function preRenderTray(array $build): array {
    $site_manager = OgSm::siteManager();

    $admin_toolbar_exists = \Drupal::moduleHandler()->moduleExists('admin_toolbar');

    if (!$site_manager->currentSite()) {
      // If there's no site context, render the toolbar as usual.
      return $admin_toolbar_exists
        ? AdminToolbar::preRenderTray($build)
        : ToolbarController::preRenderAdministrationTray($build);
    }

    // @todo This can be simplified once https://www.drupal.org/node/1869638 has
    // been implemented in core and the "admin_toolbar" module.
    /** @var \Drupal\Core\Menu\MenuLinkTreeInterface $menu_tree */
    $menu_tree = \Drupal::service('toolbar.menu_tree');
    $parameters = new MenuTreeParameters();
    // Depending on whether the 'admin_toolbar' module exists we should change
    // the menu depth shown in the toolbar.
    $max_depth = $admin_toolbar_exists ? 4 : 2;
    $parameters->setMinDepth(2)->setMaxDepth($max_depth)->onlyEnabledLinks();
    $tree = $menu_tree->load('og_sm_admin_menu', $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
      ['callable' => $admin_toolbar_exists ? 'toolbar_tools_menu_navigation_links' : 'toolbar_menu_navigation_links'],
    ];
    $tree = $menu_tree->transform($tree, $manipulators);
    $build['administration_menu'] = $menu_tree->build($tree);

    return $build;
  }

}
