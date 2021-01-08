<?php

namespace Drupal\og_sm\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection as DefaultSelectionBase;

/**
 * Selection plugin for entity reference fields in site context.
 *
 * @EntityReferenceSelection(
 *   id = "og_sm",
 *   label = @Translation("Site manager"),
 *   group = "og_sm",
 *   weight = 5,
 *   deriver = "Drupal\Core\Entity\Plugin\Derivative\DefaultSelectionDeriver"
 * )
 */
class DefaultSelection extends DefaultSelectionBase {

  use SiteManagerTrait;

}
