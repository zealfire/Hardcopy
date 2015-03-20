<?php

/**
 * @file
 * Contains \Drupal\hardcopy\HardcopyLinkBuilderInterface
 */

namespace Drupal\hardcopy;

use Drupal\Core\Entity\EntityInterface;

/**
 * Interface for building the hardcopy links.
 */
interface HardcopyLinkBuilderInterface {

  /**
   * Build a render array of the hardcopy links for a given entity.
   *
   * @param EntityInterface $entity
   *  The entity to build the hardcopy links for.
   *
   * @return array
   *  The render array of hardcopy links for the passed in entity.
   */
  public function buildLinks(EntityInterface $entity);

}
