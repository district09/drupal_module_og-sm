<?php

declare(strict_types=1);

namespace Drupal\og_sm\Entity;

use Drupal\node\NodeInterface;

/**
 * Interface for site path.
 */
interface SiteNodeInterface extends NodeInterface {

  /**
   * Get the site path.
   *
   * @return string|null
   *   The site path.
   */
  public function getSitePath(): ?string;

  /**
   * Set the site path.
   *
   * @param string $sitePath
   *   The path of the site.
   */
  public function setSitePath(string $sitePath): void;
}
