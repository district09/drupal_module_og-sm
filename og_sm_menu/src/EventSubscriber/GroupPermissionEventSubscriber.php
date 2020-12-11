<?php

namespace Drupal\og_sm_menu\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\og\Event\PermissionEventInterface;
use Drupal\og\GroupPermission;
use Drupal\og\OgRoleInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Group permission event subscriber for og_sm_menu.
 */
class GroupPermissionEventSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      PermissionEventInterface::EVENT_NAME => [['provideDefaultOgPermissions']],
    ];
  }

  /**
   * Provides default OG permissions.
   *
   * @param \Drupal\og\Event\PermissionEventInterface $event
   *   The OG permission event.
   */
  public function provideDefaultOgPermissions(PermissionEventInterface $event) {
    $menus = $this->entityTypeManager
      ->getStorage('ogmenu_instance')
      ->loadMultiple();

    foreach ($menus as $menu) {
      /** @var \Drupal\og_menu\OgMenuInstanceInterface $menu */
      $permission = new GroupPermission([
        'name' => "administer {$menu->getType()} menu items",
        'title' => $this->t('Administer %menu_name menu items'),
        'default roles' => [OgRoleInterface::ADMINISTRATOR],
      ]);

      $event->setPermission($permission);
    }
  }

}
