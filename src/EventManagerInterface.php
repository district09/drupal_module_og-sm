<?php

declare(strict_types=1);

namespace Drupal\og_sm;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Wrapper around the event dispatcher to avoid deprecations.
 */
interface EventManagerInterface {

  /**
   * Dispatch a new event.
   *
   * @param \Symfony\Contracts\EventDispatcher\Event $event
   *   The event to dispatch.
   * @param string|null $eventType
   *   The event type.
   */
  public function dispatch(Event $event, ?string $eventType);

}
