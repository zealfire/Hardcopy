<?php

/**
 * @file
 * Contains \Drupal\hardcopy\Plugin\Derivative\HardcopyLinksBlock.
 */

namespace Drupal\hardcopy\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DerivativeBase;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeInterface;
use Drupal\hardcopy\HardcopyEntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Local tasks plugin derivative to provide a tab for each hardcopy format.
 */
class HardcopyLinksBlock extends DerivativeBase implements ContainerDerivativeInterface {

  /**
   * The hardcopy entity manager.
   *
   * @var \Drupal\hardcopy\HardcopyEntityManagerInterface.
   */
  protected $hardcopyEntityManager;

  /**
   * Construct a new hardcopy format links block.
   *
   * @param \Drupal\hardcopy\HardcopyEntityManagerInterface $hardcopy_entity_manager
   *  The hardcopy entity manager.
   */
  public function __construct(HardcopyEntityManagerInterface $hardcopy_entity_manager) {
    $this->hardcopyEntityManager = $hardcopy_entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('hardcopy.entity_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions(array $base_plugin_definition) {
    foreach ($this->hardcopyEntityManager->getHardcopyEntities() as $entity_type => $entity_definition) {
      $this->derivatives[$entity_type] = $base_plugin_definition;
      $this->derivatives[$entity_type]['admin_label'] .= ' (' . $entity_definition->getLabel() . ')';
    }
    return $this->derivatives;
  }
}
