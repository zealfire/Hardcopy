<?php

/**
 * @file
 * Contains \Drupal\hardcopy_pdf\Plugin\HardcopyFormat\PdfFormat
 */

namespace Drupal\hardcopy_pdf\Plugin\HardcopyFormat;

use Drupal\hardcopy\Plugin\HardcopyFormatBase;
use Drupal\hardcopy\Annotation\HardcopyFormat;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\pdf_api\PdfGeneratorPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a plugin to display a PDF version of a page.
 *
 * @HardcopyFormat(
 *   id = "pdf",
 *   module = "hardcopy_pdf",
 *   title = @Translation("PDF"),
 *   description = @Translation("PDF description.")
 * )
 */
class PdfFormat extends HardcopyFormatBase {

  /**
   * The PDF generator plugin manager service.
   *
   * @var \Drupal\pdf_api\PdfGeneratorPluginManager
   */
  protected $pdfGeneratorManager;

  /**
   * The PDF generator plugin instance.
   *
   * @var \Drupal\pdf_api\Plugin\PdfGeneratorInterface
   */
  protected $pdfGenerator;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *  The config factory service.
   * @param \Drupal\pdf_api\PdfGeneratorPluginManager $pdf_generator_manager
   *  The PDF generator plugin manager service.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, ConfigFactory $config_factory, PdfGeneratorPluginManager $pdf_generator_manager) {
    parent::__construct($configuration,$plugin_id, $plugin_definition, $config_factory);
    $config = $this->getConfiguration();
    $this->pdfGeneratorManager = $pdf_generator_manager;
    $this->pdfGenerator = $this->pdfGeneratorManager->createInstance($config['pdf_generator']);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('config.factory'),
      $container->get('plugin.manager.pdf_generator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'pdf_generator' => 'wkhtmltopdf',
    );
  }
  public function calculateDependencies(){}

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    $options = array();
    foreach($this->pdfGeneratorManager->getDefinitions() as $definition) {
      $options[$definition['id']] = $definition['title'];
    }
    $form['pdf_generator'] = array(
      '#type' => 'radios',
      '#title' => 'PDF Generator',
      '#default_value' => $config['pdf_generator'],
      '#options' => $options,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state){}


  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->setConfiguration(array(
      'pdf_generator' => $form_state['values']['pdf_generator'],
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function build(array &$content) {
    parent::build($content);
    $this->pdfGenerator->addPage(render($content));
  }

  /**
   * {@inheritdoc}
   */
  public function getResponse(/*array $content*/) {
    $this->build($content);
    $this->pdfGenerator->stream();
  }

}
