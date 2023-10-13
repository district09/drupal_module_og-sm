<?php

namespace Drupal\og_sm_routing\EventSubscriber;

use Drupal\Core\Routing\RouteBuildEvent;
use Drupal\Core\Routing\RoutingEvents;
use Drupal\node\NodeInterface;
use Drupal\og_sm\EventManagerInterface;
use Drupal\og_sm\SiteManagerInterface;
use Drupal\og_sm_routing\Event\SiteRoutingEvent;
use Drupal\og_sm_routing\Event\SiteRoutingEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * Subscriber for site routes.
 */
class SiteRoutes implements EventSubscriberInterface {

  /**
   * The event dispatcher service.
   *
   * @var \Drupal\og_sm\EventManagerInterface
   */
  protected $eventDispatcher;

  /**
   * The site manager.
   *
   * @var \Drupal\og_sm\SiteManagerInterface
   */
  protected $siteManager;

  /**
   * Constructs a PathProcessorAlias object.
   *
   * @param \Drupal\og_sm\EventManagerInterface $event_dispatcher
   *   The event dispatcher service.
   * @param \Drupal\og_sm\SiteManagerInterface $site_manager
   *   The site path manager.
   */
  public function __construct(
    EventManagerInterface $event_dispatcher,
    SiteManagerInterface $site_manager
  ) {
    $this->eventDispatcher = $event_dispatcher;
    $this->siteManager = $site_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents():array {
    $events = [];
    $events[RoutingEvents::DYNAMIC] = 'onDynamicRouteEvent';
    return $events;
  }

  /**
   * Alters existing routes for a specific collection.
   *
   * @param \Drupal\Core\Routing\RouteBuildEvent $event
   *   The route build event.
   */
  public function onDynamicRouteEvent(RouteBuildEvent $event) {
    foreach ($this->siteManager->getAllSites() as $site) {
      $site_routes = $this->getRoutesForSite($site);
      $event->getRouteCollection()->addCollection($site_routes);
    }
  }

  /**
   * Provides all routes for a given site node.
   *
   * @param \Drupal\node\NodeInterface $site
   *   The site node.
   *
   * @return \Symfony\Component\Routing\RouteCollection
   *   The route collection.
   */
  protected function getRoutesForSite(NodeInterface $site) {
    $collection = new RouteCollection();
    $event = new SiteRoutingEvent($site, $collection);
    // Collect all the routes for this site.
    $this->eventDispatcher->dispatch($event, SiteRoutingEvents::COLLECT);

    // Allow altering the routes.
    $this->eventDispatcher->dispatch($event, SiteRoutingEvents::ALTER);

    // Prefix all the routes within the collection to avoid collision with other
    // site routes.
    foreach ($collection->all() as $route_name => $route) {
      $collection->remove($route_name);
      $route->addDefaults([
        'og_sm_routing:site' => $site,
      ]);
      $collection->add('og_sm_site:' . $site->id() . ':' . $route_name, $route);
    }

    return $collection;
  }

}
