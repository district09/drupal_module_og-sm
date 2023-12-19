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
   * @param $site_path
   *   The path of the site.
   *
   * @return SiteNodeInterface
   *    The site node.
   */
  public function setSitePath($site_path): SiteNodeInterface;
}
