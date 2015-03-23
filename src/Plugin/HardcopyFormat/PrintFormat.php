<?php

/**
 * @file
 * Contains \Drupal\hardcopy\Plugin\HardcopyFormat\PrintFormat
 */

namespace Drupal\hardcopy\Plugin\HardcopyFormat;

use Drupal\hardcopy\Plugin\HardcopyFormatBase;
use Drupal\hardcopy\Annotation\HardcopyFormat;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a plugin to display a printable version of a page.
 *
 * @HardcopyFormat(
 *   id = "print",
 *   module = "hardcopy",
 *   title = @Translation("Print"),
 *   description = @Translation("Print description.")
 * )
 */
class PrintFormat extends HardcopyFormatBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'show_print_dialogue' => TRUE,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array &$form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    $form['show_print_dialogue'] = array(
      '#type' => 'checkbox',
      '#title' => 'Show print dialogue',
      '#default_value' => $config['show_print_dialogue'],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface &$form_state) {
    $this->setConfiguration(array(
      'show_print_dialogue' => $form_state->getValue('show_print_dialogue'),
    ));
  }

  /**
   * {@inheritdoc}
   */
  protected function buildContent() {
    $build = parent::buildContent();
    $config = $this->getConfiguration();
    if ($config['show_print_dialogue']) {
      $build['#attached']['js'][] = array(
        'type' => 'inline',
        'data' => '(function ($) { $(window).load(function() { window.print(); })})(jQuery);',
      );
    }
    return $build;
  }
}
