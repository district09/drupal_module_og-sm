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
   * @var string
   */
  protected string $site_path;

  /**
   * {@inheritDoc}
   */
  public function getSitePath(): ?string {
    return $this->get('site_path')->getString() ?: NULL;
  }

  /**
   * {@inheritDoc}
   */
  public function setSitePath($site_path): SiteNodeInterface {
    $this->site_path = $site_path;

    return $this;
  }

}
