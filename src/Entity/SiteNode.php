<?php

declare(strict_types=1);

namespace Drupal\og_sm\Entity;

use Drupal\node\Entity\Node;

/**
 * Bundle class site path.
 */
final class SiteNode extends Node implements SiteNodeInterface {

  /**
   * The site path.
   *
   * @var null|string
   */
  private ?string $sitePath;

  /**
   * {@inheritDoc}
   */
  public function getSitePath(): ?string {
    return $this->sitePath ?? NULL;
  }

  /**
   * {@inheritDoc}
   */
  public function setSitePath(string $sitePath): void {
    $this->sitePath = $sitePath;
  }

}
