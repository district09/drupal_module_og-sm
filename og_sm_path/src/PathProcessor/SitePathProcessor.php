<?php

namespace Drupal\og_sm_path\PathProcessor;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Drupal\Core\PathProcessor\OutboundPathProcessorInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Url;
use Drupal\og_sm\EventManagerInterface;
use Drupal\og_sm\SiteManagerInterface;
use Drupal\og_sm_path\Event\AjaxPathEvent;
use Drupal\og_sm_path\Event\AjaxPathEvents;
use Drupal\og_sm_path\SitePathManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Path processor manager.
 */
class SitePathProcessor implements InboundPathProcessorInterface, OutboundPathProcessorInterface {

  /**
   * The site path manager.
   *
   * @var \Drupal\og_sm_path\SitePathManagerInterface
   */
  protected $sitePathManager;

  /**
   * The site manager.
   *
   * @var \Drupal\og_sm\SiteManagerInterface
   */
  protected $siteManager;

  /**
   * The event manager.
   *
   * @var \Drupal\og_sm\EventManagerInterface
   */
  protected $eventManager;

  /**
   * An array of ajax paths.
   *
   * @var string[]
   */
  protected $ajaxPaths;

  /**
   * Constructs a SitePathProcessor object.
   *
   * @param \Drupal\og_sm_path\SitePathManagerInterface $sitePathManager
   *   The site path manager.
   * @param \Drupal\og_sm\SiteManagerInterface $siteManager
   *   The site manager.
   * @param \Drupal\og_sm\EventManagerInterface $eventManager
   *   The event dispatcher.
   */
  public function __construct(
    SitePathManagerInterface $sitePathManager,
    SiteManagerInterface $siteManager,
    EventManagerInterface $eventManager
  ) {
    $this->sitePathManager = $sitePathManager;
    $this->siteManager = $siteManager;
    $this->eventManager = $eventManager;
  }

  /**
   * Returns an array of paths that should be rewritten to have site context.
   *
   * @return array
   *   An array of ajax paths.
   */
  protected function ajaxPaths() {
    if ($this->ajaxPaths !== NULL) {
      return $this->ajaxPaths;
    }

    $event = new AjaxPathEvent();
    $this->eventManager->dispatch($event, AjaxPathEvents::COLLECT);
    $this->ajaxPaths = $event->getAjaxPaths();
    return $this->ajaxPaths;
  }

  /**
   * {@inheritdoc}
   */
  public function processInbound($path, Request $request) {
    // Translate an admin path without alias back to its original path.
    $parts = [];

    if (preg_match('#^([\w/_-]+)/(admin.*)#', $path, $parts)) {
      $site = $this->sitePathManager->getSiteFromPath($parts[1]);
      if ($site) {
        $path = sprintf('/group/node/%d/%s', $site->id(), $parts[2]);
      }
    }
    // Translate a system path back to normal path.
    elseif (preg_match('#^([\w/_-]+)(' . implode('|', $this->ajaxPaths()) . ')$#', $path, $parts)) {
      $site = $this->sitePathManager->getSiteFromPath($parts[1]);
      if ($site) {
        $path = $parts[2];

        $request->query->set('og_sm_context_site_id', $site->id());
      }
    }
    return $path;
  }

  /**
   * {@inheritdoc}
   *
   * This will check & replace any destination (in the options > query) by its
   * path alias. Note: this will affect links outside a Site as well. We can
   * have links outside a Site context with a destination that is in a Site.
   */
  public function processOutbound($path, &$options = [], Request $request = NULL, BubbleableMetadata $bubbleable_metadata = NULL) {
    $path = $this->getOutboundSitePath($path);

    if (!isset($options['query']['destination'])) {
      return $path;
    }

    $base_path = $request ? $request->getBasePath() . '/' : '/';
    $destination = $options['query']['destination'];
    if (strpos($destination, $base_path) === 0) {
      $destination = substr($destination, strlen($base_path));
    }

    $destination = ltrim($destination, '/');
    $parts = parse_url($destination);
    if (!isset($parts['path'])) {
      return $path;
    }

    $alias = Url::fromUserInput('/' . $parts['path']);
    if (!empty($parts['query'])) {
      $alias->setOption('query', $parts['query']);
    }
    $options['query']['destination'] = $alias->toString();

    // Return proper path, destination is altered in $options array.
    return $path;
  }

  /**
   * Rewrite all outgoing site admin paths for paths that do not have an alias.
   *
   * @param string $path
   *   The outbound path.
   *
   * @return string
   *   The correct site path.
   */
  private function getOutboundSitePath(string $path): string {
    // Rewrite all outgoing site admin paths for paths that do not have an
    // alias.
    if (preg_match('#^/group/node/([0-9]+)/(admin.*)#', $path, $parts)) {
      $site = $this->siteManager->load($parts[1]);
      if ($site) {
        return $this->sitePathManager->getPathFromSite($site) . '/' . $parts[2];
      }
    }

    // Only check specific paths in a Site context.
    $site = $this->siteManager->currentSite();
    if ($site && preg_match('#^(' . implode('|', $this->ajaxPaths()) . ')$#', $path, $parts)) {
      return $this->sitePathManager->getPathFromSite($site) . $parts[1];
    }

    // Fallback to original path.
    return $path;
  }

}
