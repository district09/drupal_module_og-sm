<?php

namespace Drupal\og_sm_menu\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\menu_ui\Form\MenuLinkEditForm;
use Drupal\node\NodeInterface;

/**
 * Provides a form to edit site menu links.
 */
class SiteMenuLinkEditForm extends MenuLinkEditForm {

  /**
   * Build the form.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   * @param \Drupal\Core\Menu\MenuLinkInterface|null $menu_link_plugin
   *   The menu link plugin.
   * @param \Drupal\node\NodeInterface|null $node
   *   The site node.
   *
   * @return array
   *   The form array.
   */
  public function buildForm(array $form, FormStateInterface $form_state, MenuLinkInterface $menu_link_plugin = NULL, NodeInterface $node = NULL) {
    $form_state->set('site', $node);

    return parent::buildForm($form, $form_state, $menu_link_plugin);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    /** @var \Drupal\node\NodeInterface|NULL $site */
    $site = $form_state->get('site');

    if ($site) {
      $form_state->setRedirect(
        'og_sm.site_menu',
        ['node' => $site->id()]
      );
    }
  }

}
