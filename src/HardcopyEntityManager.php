<?php

/**
 * @file
 * Contains \Drupal\hardcopy\HardcopyEntityManager
 */

namespace Drupal\hardcopy;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Helper class for the hardcopy module.
 */
class HardcopyEntityManager implements HardcopyEntityManagerInterface {

  /**
   * The entity manager service.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The entity definitions of entities that have hardcopy versions available.
   *
   * @var array
   */
  protected $compatibleEntities = array();

  /**
   * Constructs a new HardcopyEntityManager object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *  The entity manager service.
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *  The configuration factory service.
   */
  public function __construct(EntityManagerInterface $entity_manager, ConfigFactory $config_factory) {
    $this->entityManager = $entity_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getHardcopyEntities() {
    $compatible_entities = $this->getCompatibleEntities();
    $entities = array();
    foreach($this->configFactory->get('hardcopy.settings')->get('hardcopy_entities') as $entity_type) {
      if (isset($compatible_entities[$entity_type])) {
        $entities[$entity_type] = $compatible_entities[$entity_type];
      }
    }
    return $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function isHardcopyEntity(EntityInterface $entity) {
    return array_key_exists($entity->entityType(), $this->getHardcopyEntities());
  }

  /**
   * {@inheritdoc}
   */
  public function getCompatibleEntities() {
    // If the entities are yet to be populated, get the entity definitions from
    // the entity manager.
    if (empty($this->compatibleEntities)) {
      foreach($this->entityManager->getDefinitions() as $entity_type => $entity_definition) {
        // If this entity has a render controller, it has a hardcopy version.
        if ($entity_definition->hasControllerClass('view_builder')) {
          $this->compatibleEntities[$entity_type] = $entity_definition;
        }
      }
    }
    return $this->compatibleEntities;
  }
}
