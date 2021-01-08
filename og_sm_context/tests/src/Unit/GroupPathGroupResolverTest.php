<?php

namespace Drupal\Tests\og_sm_context\Unit;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\og_sm_context\Plugin\OgGroupResolver\GroupPathGroupResolver;

/**
 * Tests the GroupPathGroupResolver plugin.
 *
 * @group og_sm
 * @coversDefaultClass \Drupal\og_sm_context\Plugin\OgGroupResolver\GroupPathGroupResolver
 */
class GroupPathGroupResolverTest extends OgSmGroupResolverTestBase {

  use RouteTrait;

  /**
   * {@inheritdoc}
   */
  protected $className = GroupPathGroupResolver::class;

  /**
   * {@inheritdoc}
   */
  protected $pluginId = 'og_sm_context_group_path';

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->routeMatch = $this->prophesize(RouteMatchInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function getInjectedDependencies() {
    return [
      $this->routeMatch->reveal(),
      $this->siteManager->reveal(),
    ];
  }

  /**
   * Data provider for testResolve().
   *
   * @see ::testResolve()
   */
  public function resolveProvider() {
    return [
      [
        '/user/logout',
        NULL,
        NULL,
      ],
      [
        '/group/node/{node}/admin',
        'group',
        NULL,
      ],
      [
        '/group/node/{node}/admin',
        'site',
        'site',
      ],
      [
        '/group/node/{node}/admin/config',
        'site',
        'site',
      ],
      [
        '/group/node/{node}/admin',
        'site_content',
        NULL,
      ],
      [
        '/group/node/{node}/admin/config',
        'site_content',
        NULL,
      ],
      [
        '/group/node/{node}/admin',
        'non_group',
        NULL,
      ],
    ];
  }

}
