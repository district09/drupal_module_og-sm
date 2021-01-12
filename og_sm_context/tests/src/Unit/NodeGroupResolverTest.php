<?php

namespace Drupal\Tests\og_sm_context\Unit;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\og_sm_context\Plugin\OgGroupResolver\NodeGroupResolver;

/**
 * Tests the NodeGroupResolver plugin.
 *
 * @group og_sm
 * @coversDefaultClass \Drupal\og_sm_context\Plugin\OgGroupResolver\NodeGroupResolver
 */
class NodeGroupResolverTest extends OgSmGroupResolverTestBase {

  use RouteTrait;

  /**
   * {@inheritdoc}
   */
  protected $className = NodeGroupResolver::class;

  /**
   * {@inheritdoc}
   */
  protected $pluginId = 'og_sm_context_node';

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->routeMatch = $this->prophesize(RouteMatchInterface::class);
    $this->entityTypeManager = $this->prophesize(EntityTypeManagerInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function getInjectedDependencies() {
    return [
      $this->routeMatch->reveal(),
      $this->siteManager->reveal(),
      $this->entityTypeManager->reveal(),
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
        '/node/{node}',
        'group',
        NULL,
      ],
      [
        '/node/{node}',
        'site',
        'site',
      ],
      [
        '/node/{node}/edit',
        'site',
        'site',
      ],
      [
        '/node/{node}',
        'site_content',
        'site',
      ],
      [
        '/node/{node}/edit',
        'site_content',
        'site',
      ],
      [
        '/node/{node}',
        'non_group',
        NULL,
      ],
    ];
  }

}
