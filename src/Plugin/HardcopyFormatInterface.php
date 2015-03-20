<?php

/**
 * @file
 * Contains \Drupal\hardcopy\Plugin\HardcopyFormatInterface
 */

namespace Drupal\hardcopy\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Defines an interface for hardcopy format plugins.
 */
interface HardcopyFormatInterface extends ConfigurablePluginInterface, PluginFormInterface {

  /**
   * Returns the administrative label for this format plugin.
   *
   * @return string
   */
  public function getLabel();

  /**
   * Returns the administrative description for this format plugin.
   *
   * @return string
   */
  public function getDescription();

  /**
   * Set the content for the hardcopy response.
   *
   * @param array $content
   *  A render array of the content to be output by the hardcopy format.
   */
  public function setContent(array $content);

  /**
   * Returns the response object for this format plugin.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *  The response object.
   */
  public function getResponse();
}
