<?php

namespace Drupal\og_sm_taxonomy\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\og_sm\SiteManagerInterface;
use Drupal\og_sm_taxonomy\SiteTaxonomyManagerInterface;
use Drupal\taxonomy\TermStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides confirmation form for resetting a vocabulary to alphabetical order.
 */
class VocabularyResetForm extends EntityConfirmFormBase {

  /**
   * The term storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * The site manager.
   *
   * @var \Drupal\og_sm\SiteManagerInterface
   */
  protected $siteManager;

  /**
   * The site taxonomy manager.
   *
   * @var \Drupal\og_sm_taxonomy\SiteTaxonomyManagerInterface
   */
  protected $siteTaxonomyManager;

  /**
   * Class constructor.
   *
   * @param \Drupal\taxonomy\TermStorageInterface $term_storage
   *   The taxonomy term storage.
   * @param \Drupal\og_sm\SiteManagerInterface $siteManager
   *   The site manager.
   * @param \Drupal\og_sm_taxonomy\SiteTaxonomyManagerInterface $siteTaxonomyManager
   *   The site taxonomy manager.
   */
  public function __construct(
    TermStorageInterface $term_storage,
    SiteManagerInterface $siteManager,
    SiteTaxonomyManagerInterface $siteTaxonomyManager
  ) {
    $this->termStorage = $term_storage;
    $this->siteManager = $siteManager;
    $this->siteTaxonomyManager = $siteTaxonomyManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('taxonomy_term'),
      $container->get('og_sm.site_manager'),
      $container->get('og_sm_taxonomy.site_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'taxonomy_vocabulary_confirm_reset_alphabetical';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $site = $this->siteManager->currentSite();
    $form_state->set('site', $site);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\node\NodeInterface|NULL $site */
    $site = $form_state->get('site');
    if (!$site) {
      parent::submitForm($form, $form_state);
      return;
    }

    $form_state->cleanValues();
    $this->entity = $this->buildEntity($form, $form_state);

    $this->siteTaxonomyManager->resetTermWeights($site, $this->entity);

    $this->messenger()->addStatus($this->t('Reset vocabulary %name to alphabetical order.', ['%name' => $this->entity->label()]));
    $this->logger('taxonomy')->notice('Reset vocabulary %name to alphabetical order.', ['%name' => $this->entity->label()]);
    $form_state->setRedirectUrl($this->getCancelUrl($site));
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to reset the vocabulary %title to alphabetical order?', ['%title' => $this->entity->label()]);
  }

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\node\NodeInterface $site
   *   The site node.
   */
  public function getCancelUrl(NodeInterface $site = NULL) {
    if (!$site) {
      $site = $this->siteManager->currentSite();
    }

    if (!$site) {
      return $this->entity->toUrl('overview-form');
    }
    return new Url('og_sm_taxonomy.vocabulary.term_overview', [
      'node' => $site->id(),
      'taxonomy_vocabulary' => $this->getEntity()->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Resetting a vocabulary will discard all custom ordering and sort items alphabetically.');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Reset to alphabetical');
  }

}
