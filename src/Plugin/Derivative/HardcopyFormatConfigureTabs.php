<?php

/**
 * @file
 * Contains \Drupal\hardcopy\Plugin\Derivative\HardcopyFormatConfigureTabs.
 */

namespace Drupal\hardcopy\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeInterface;
use Drupal\hardcopy\HardcopyFormatPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Local tasks plugin derivative to provide a tab for each hardcopy format.
 */
class HardcopyFormatConfigureTabs extends DeriverBase implements ContainerDerivativeInterface {

  /**
   * The hardcopy format plugin manager.
   *
   * @var \Drupal\hardcopy\HardcopyFormatPluginManager.
   */
  protected $hardcopyFormatManager;

  /**
   * Construct a new hardcopy format configuration tab plugin derivative.
   *
   * @param \Drupal\hardcopy\HardcopyFormatPluginManager $hardcopy_format_manager
   *  The hardcopy format plugin manager.
   */
  public function __construct(HardcopyFormatPluginManager $hardcopy_format_manager) {
    $this->hardcopyFormatManager = $hardcopy_format_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('plugin.manager.hardcopyformat')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions(array $base_plugin_definition) {
    foreach ($this->hardcopyFormatManager->getDefinitions() as $key => $definition) {
      $this->derivatives[$key] = $base_plugin_definition;
      $this->derivatives[$key]['title'] = $definition['title'];
      $this->derivatives[$key]['route_parameters'] = array('hardcopy_format' => $key);
    }
    return $this->derivatives;
  }
}
