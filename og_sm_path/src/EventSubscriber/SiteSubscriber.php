<?php

namespace Drupal\og_sm_path\EventSubscriber;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\og_sm\Event\SiteEvent;
use Drupal\og_sm\Event\SiteEvents;
use Drupal\og_sm_path\Event\SitePathEvent;
use Drupal\og_sm_path\Event\SitePathEvents;
use Drupal\og_sm_path\SitePathManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Listens to the site events.
 */
class SiteSubscriber implements EventSubscriberInterface {

  /**
   * The site path manager.
   *
   * @var \Drupal\og_sm_path\SitePathManagerInterface
   */
  protected $sitePathManager;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a SiteSubscriber object.
   *
   * @param \Drupal\og_sm_path\SitePathManagerInterface $site_path_manager
   *   The site path manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(SitePathManagerInterface $site_path_manager, ModuleHandlerInterface $module_handler) {
    $this->sitePathManager = $site_path_manager;
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents():array {
    $events = [];
    $events[SiteEvents::INSERT][] = 'onSiteInsert';
    $events[SiteEvents::UPDATE][] = 'onSiteUpdate';
    $events[SiteEvents::DELETE][] = 'onSiteDelete';
    $events[SitePathEvents::CHANGE][] = 'onSitePathChange';

    return $events;
  }

  /**
   * Event listener triggered when a site is inserted.
   *
   * @param \Drupal\og_sm\Event\SiteEvent $event
   *   The site event.
   */
  public function onSiteInsert(SiteEvent $event) {
    $site = $event->getSite();
    if (isset($site->site_path)) {
      $this->sitePathManager->setPath($site, $site->site_path, FALSE);
    }
  }

  /**
   * Event listener triggered when a site is updated.
   *
   * @param \Drupal\og_sm\Event\SiteEvent $event
   *   The site event.
   */
  public function onSiteUpdate(SiteEvent $event) {
    $site = $event->getSite();
    if (isset($site->site_path)) {
      $this->sitePathManager->setPath($site, $site->site_path);
    }
  }

  /**
   * Event listener triggered when a site is deleted.
   *
   * @param \Drupal\og_sm\Event\SiteEvent $event
   *   The site event.
   */
  public function onSiteDelete(SiteEvent $event) {
    // Delete all aliases for a site when it is deleted.
    $this->sitePathManager->deleteSiteAliases($event->getSite());
  }

  /**
   * Event listener triggered when a site path is changed.
   *
   * @param \Drupal\og_sm_path\Event\SitePathEvent $event
   *   The site path event.
   */
  public function onSitePathChange(SitePathEvent $event) {
    // Update all aliases for the Site when its alias changes.
    $this->moduleHandler->loadInclude('og_sm_path', 'inc', 'og_sm_path.batch');
    og_sm_path_site_alias_update_batch($event->getSite());
  }

}
