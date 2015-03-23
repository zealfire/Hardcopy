<?php

/**
 * @file
 * Contains \Drupal\hardcopy\Form\HardcopyConfigurationForm
 */

namespace Drupal\hardcopy\Form;

use Drupal\hardcopy\HardcopyEntityManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides shared configuration form for all hardcopy formats.
 */
class HardcopyConfigurationForm extends FormBase {

  /**
   * The hardcopy entity manager.
   *
   * @var \Drupal\hardcopy\HardcopyEntityManagerInterface
   */
  protected $hardcopyEntityManager;

  /**
   * Constructs a new form object.
   *
   * @param \Drupal\hardcopy\HardcopyEntityManagerInterface $hardcopy_entity_manager
   *   The hardcopy entity manager.
   */
  public function __construct(HardcopyEntityManagerInterface $hardcopy_entity_manager) {
    $this->hardcopyEntityManager = $hardcopy_entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('hardcopy.entity_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'hardcopy_configuration';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $hardcopy_format = NULL) {

    // Allow users to choose what entities hardcopy is enabled for.
    $form['hardcopy_entities'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('Hardcopy Enabled Entities'),
      '#description' => $this->t('Select the entities that hardcopy support should be enabled for.'),
      '#options' => array(),
      '#default_value' => array(),
    );
    // Build the options array.
    foreach($this->hardcopyEntityManager->getCompatibleEntities() as $entity_type => $entity_definition) {
      $form['hardcopy_entities']['#options'][$entity_type] = $entity_definition->getLabel();
    }
    // Build the default values array.
    foreach($this->hardcopyEntityManager->getHardcopyEntities() as $entity_type => $entity_definition) {
      $form['hardcopy_entities']['#default_value'][] = $entity_type;
    }

    // Provide option to open hardcopy page in a new tab/window.
    $form['open_target_blank'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Open in New Tab'),
      '#description' => $this->t('Open the hardcopy version in a new tab/window.'),
      '#default_value' => $this->config('hardcopy.settings')->get('open_target_blank'),
    );

    // Allow users to include CSS from the current theme.
    $form['css_include'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('CSS Include'),
      '#description' => $this->t('Specify an additional CSS file to include. Relative to the root of the Drupal install. The token <em>[theme:theme_machine_name]</em> is available.'),
      '#default_value' => $this->config('hardcopy.settings')->get('css_include'),
    );

    // Provide option to turn off link extraction.
    $form['extract_links'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Extract Links'),
      '#description' => $this->t('Extract any links in the content, e.g. "Some Link (http://drupal.org)'),
      '#default_value' => $this->config('hardcopy.settings')->get('extract_links'),
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Submit',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::configFactory()->getEditable('hardcopy.settings')->set('hardcopy_entities', $form_state->getValue('hardcopy_entities'))->save();
    \Drupal::configFactory()->getEditable('hardcopy.settings')->set('open_target_blank', $form_state->getValue('open_target_blank'))->save();
    \Drupal::configFactory()->getEditable('hardcopy.settings')->set('css_include', $form_state->getValue('css_include'))->save();
    \Drupal::configFactory()->getEditable('hardcopy.settings')->set('extract_links', $form_state->getValue('extract_links'))->save();
  }
}
