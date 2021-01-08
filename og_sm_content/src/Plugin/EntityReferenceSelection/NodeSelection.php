<?php

namespace Drupal\og_sm_content\Plugin\EntityReferenceSelection;

use Drupal\node\Plugin\EntityReferenceSelection\NodeSelection as NodeSelectionBase;
use Drupal\og_sm\Plugin\EntityReferenceSelection\SiteManagerTrait;

/**
 * Selection plugin for node reference fields in site context.
 *
 * @EntityReferenceSelection(
 *   id = "og_sm:node",
 *   label = @Translation("Site manager: Node selection"),
 *   entity_types = {"node"},
 *   group = "og_sm",
 *   weight = 5
 * )
 */
class NodeSelection extends NodeSelectionBase {

  use SiteManagerTrait;

}
