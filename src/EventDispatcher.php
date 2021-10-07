<?php

declare(strict_types=1);

namespace Drupal\og_sm;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as CoreEventDispatcher;

/**
 * Wrapper around the event dispatcher to avoid deprecations.
 *
 * @todo The event dispatcher has changed since Drupal 9.1:
 *   - We should have separate events instead of reusing them with an event
 *     name. Symfony dispatcher uses the event class name as the event name.
 *   - Only the event should be passed to the event dispatcher.
 */
final class EventDispatcher implements EventDispatcherInterface {

  /**
   * The event dispatcher service.
   *
   * @var \Symfony\Contracts\EventDispatcher\EventDispatcherInterface
   */
  private $eventDispatcher;

  /**
   * Create new dispatcher.
   *
   * @param \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   The core event dispatcher.
   */
  public function __construct(CoreEventDispatcher $eventDispatcher) {
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * {@inheritDoc}
   */
  public function dispatch(Event $event, ?string $eventType) {
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, $eventType);
  }

}
