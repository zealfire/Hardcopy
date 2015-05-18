<?php

/**
 * @file
 * Contains \Drupal\hardcopy\HardcopyCssIncludeInterface
 */

namespace Drupal\hardcopy;

/**
 * Helper interface for the hardcopy module.
 */
interface HardcopyCssIncludeInterface {

  /**
   * Get the configured CSS include path for hardcopy pages.
   *
   * @return string
   *  The include path, relative to the root of the Drupal install.
   */
  public function getCssIncludePath();

}
