<?php

/**
 * @file
 * Contains \Drupal\hardcopy\HardcopyLinkBuilder
 */

namespace Drupal\hardcopy;

use Drupal\hardcopy\HardcopyFormatPluginManager;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;

/**
 * Helper class for the hardcopy module.
 */
class HardcopyLinkBuilder implements HardcopyLinkBuilderInterface {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The URL generator service.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * The hardcopy format plugin manager.
   *
   * @var \Drupal\hardcopy\HardcopyFormatPluginManager
   */
  protected $hardcopyFormatManager;

  /**
   * Constructs a new HardcopyLinkBuilder object.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *  The configuration factory service.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *  The URL generator service.
   * @param \Drupal\hardcopy\HardcopyFormatPluginManager $hardcopy_format_manager
   *  The hardcopy format plugin manager.
   */
  public function __construct(ConfigFactory $config_factory, UrlGeneratorInterface $url_generator, HardcopyFormatPluginManager $hardcopy_format_manager) {
    $this->configFactory = $config_factory;
    $this->urlGenerator = $url_generator;
    $this->hardcopyFormatManager = $hardcopy_format_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function buildLinks(EntityInterface $entity) {
    // Build the array of links to be added to the entity.
    $links = array();
    foreach($this->hardcopyFormatManager->getDefinitions() as $key => $definition) {
      $links[$key] = array(
        'title' => $definition['title'],
        'href' => $this->urlGenerator->generateFromRoute('hardcopy.show_format.' . $entity->entityType(), array('hardcopy_format' => $key, 'entity' => $entity->id())),
      );
      // Add target "blank" if the configuration option is set.
      if ($this->configFactory->get('hardcopy.settings')->get('open_target_blank')) {
        $links[$key]['attributes']['target'] = '_blank';
      }
    }
    return $links;
  }
}
