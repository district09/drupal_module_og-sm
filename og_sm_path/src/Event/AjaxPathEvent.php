<?php

namespace Drupal\og_sm_path\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Defines the ajax event.
 *
 * @see \Drupal\og_sm\Event\AjaxPathEvents
 */
class AjaxPathEvent extends Event {

  /**
   * An array of ajax paths.
   *
   * @var string[]
   */
  protected $ajaxPaths = [];

  /**
   * Adds an ajax path.
   *
   * @param string $ajax_path
   *   An ajax path.
   */
  public function addPath($ajax_path) {
    $this->ajaxPaths[] = $ajax_path;
  }

  /**
   * Returns a collection of ajax paths.
   *
   * @return string[]
   *   The collection of ajax paths.
   */
  public function getAjaxPaths() {
    return $this->ajaxPaths;
  }

}
