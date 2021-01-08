<?php

namespace Drupal\Tests\og_sm_context\Unit;

use Symfony\Component\Routing\Route;

/**
 * Route related helpers.
 */
trait RouteTrait {

  /**
   * The route match object.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $routeMatch;

  /**
   * {@inheritdoc}
   *
   * @param string $path
   *   The current path.
   * @param string $route_object_id
   *   The ID of the object passed in the path.
   * @param string $expected_added_group
   *   The group that is expected to be added by the plugin. If left empty it is
   *   explicitly expected that the plugin will not add any group to the
   *   collection.
   *
   * @covers ::resolve
   * @dataProvider resolveProvider
   */
  public function testResolve($path = NULL, $route_object_id = NULL, $expected_added_group = NULL) {
    if ($path) {
      /** @var \Symfony\Component\Routing\Route|\Prophecy\Prophecy\ObjectProphecy $route */
      $route = $this->prophesize(Route::class);
      $route
        ->getPath()
        ->willReturn($path)
        ->shouldBeCalled();
      $this->routeMatch
        ->getRouteObject()
        ->willReturn($route->reveal())
        ->shouldBeCalled();
    }

    if ($route_object_id) {
      $this->routeMatch->getParameter('node')
        ->willReturn($this->testEntities[$route_object_id]);

      $this->siteManager
        ->getSitesFromEntity($this->testEntities[$route_object_id])
        ->willReturn($expected_added_group ? [$this->testEntities[$expected_added_group]] : []);
    }

    $this->mightRetrieveSite($expected_added_group);
  }

}
