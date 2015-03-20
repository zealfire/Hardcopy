<?php

/**
 * @file
 * Contains \Drupal\hardcopy\LinkExtractor\LinkExtractorInterface
 */

namespace Drupal\hardcopy\LinkExtractor;

/**
 * Defines an interface for extracting links from a string of HTMl.
 */
interface LinkExtractorInterface {

  /**
   * Extract hrefs from links in the given HTML string.
   *
   * @param string $string
   *  The HTML string to extract links from.
   * @return string
   *  The HTML string, with links extracted.
   */
  public function extract(string $string);
}
