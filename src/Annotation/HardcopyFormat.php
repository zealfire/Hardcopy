<?php

/**
 * @file
 * Contains \Drupal\hardcopy\Annotation\HardcopyFormat
 */

namespace Drupal\hardcopy\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an hardcopy format annotation object.
 *
 * @Annotation
 */
class HardcopyFormat extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The name of the module providing the type.
   *
   * @var string
   */
  public $module;

  /**
   * The human-readable name of the format.
   *
   * This is used as an administrative summary of what the format does.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $title;

  /**
   * Additional administrative information about the format's behavior.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation (optional)
   */
  public $description = '';

}
