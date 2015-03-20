<?php

/**
 * @file
 * Contains \Drupal\hardcopy\Plugin\Block\HardcopyLinksBlock.
 */

namespace Drupal\hardcopy\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\hardcopy\HardcopyLinkBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a hardcopy links block for each hardcopy entity.
 *
 * @Block(
 *   id = "hardcopy_links_block",
 *   admin_label = @Translation("Hardcopy Links Block"),
 *   category = @Translation("Hardcopy"),
 *   derivative = "Drupal\hardcopy\Plugin\Derivative\HardcopyLinksBlock"
 * )
 */
class HardcopyLinksBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The request service.
   *
   * @var \Symfony\Component\HttpFoundation\Request;
   */
  protected $request;

  /**
   * The hardcopy link builder.
   *
   * @var \Drupal\hardcopy\HardcopyLinkBuilderInterface
   */
  protected $linkBuilder;

  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *  The request service.
   * @param \Drupal\hardcopy\HardcopyLinkBuilderInterface $link_builder
   *  The hardcopy link builder.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, Request $request, HardcopyLinkBuilderInterface $link_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->request = $request;
    $this->linkBuilder = $link_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, array $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('request'),
      $container->get('hardcopy.link_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // @todo Clean up when http://drupal.org/node/1874498 lands.
    list(, $entity_type) = explode(':', $this->getPluginId());

    if ($this->request->attributes->has($entity_type)) {
      return array(
        '#theme' => 'links__entity__hardcopy',
        '#links' => $this->linkBuilder->buildLinks($this->request->attributes->get($entity_type)),
      );
    }
  }

}
