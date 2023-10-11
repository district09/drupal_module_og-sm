<?php

namespace Drupal\og_sm_menu\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\node\NodeInterface;

/**
 * Provides a form to edit site menu links.
 */
class SiteMenuLinkEditForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_link_edit';
  }

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
    $form['menu_link_id'] = [
      '#type' => 'value',
      '#value' => $menu_link_plugin->getPluginId(),
    ];
    $class_name = $menu_link_plugin->getFormClass();
    $form['#plugin_form'] = $this->classResolver->getInstanceFromDefinition($class_name);
    $form['#plugin_form']->setMenuLinkInstance($menu_link_plugin);

    $form += $form['#plugin_form']->buildConfigurationForm($form, $form_state);

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    $form_state->set('site', $node);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $link = $form['#plugin_form']->submitConfigurationForm($form, $form_state);

    $this->messenger()->addStatus($this->t('The menu link has been saved.'));
    $form_state->setRedirect(
      'entity.menu.edit_form',
      ['menu' => $link->getMenuName()]
    );

    /** @var \Drupal\node\NodeInterface|NULL $site */
    $site = $form_state->get('site');

    if ($site) {
      $form_state->setRedirect(
        'og_sm.site_menu',
        ['node' => $site->id()]
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $form['#plugin_form']->validateConfigurationForm($form, $form_state);
  }

}
