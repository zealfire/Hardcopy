<?php

/**
 * @file
 * Contains \Drupal\hardcopy\LinkExtractor\InlineLinkExtractor;
 */

namespace Drupal\hardcopy\LinkExtractor;

use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\hardcopy\LinkExtractor\LinkExtractorInterface;
use wa72\htmlpagedom\HtmlPageCrawler;

/**
 * Link extractor
 */
class InlineLinkExtractor implements LinkExtractorInterface {

  /**
   * The DomCrawler object.
   *
   * @var \Wa72\HtmlPageDom\HtmlPageCrawler
   */
  protected $crawler;

  /**
   * The URL generator service.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * Constructs a new InlineLinkExtractor object.
   */
  public function __construct(HtmlPageCrawler $crawler, UrlGeneratorInterface $url_generator) {
    $this->crawler = $crawler;
    $this->urlGenerator = $url_generator;
  }

  /**
   * {@inheritdoc}
   */
  public function extract( $string) {
    $this->crawler->addContent($string);

    $this->crawler->filter('a')->each(function(HtmlPageCrawler $anchor, $uri) {
      $href = $anchor->attr('href');
      // This method is deprecated, however it is the correct method to use here
      // as we only have the path
      $href = $this->urlGenerator->generateFromPath($href, array('absolute' => TRUE));
      $anchor->append(' (' . $href . ')');
    });

    return (string) $this->crawler;
  }

  public function removeAttribute( $content, $string) {
    //echo ($content);
    //echo "hola starts";
    $this->crawler->addContent($content);
    $this->crawler->filter('a')->each(function(HtmlPageCrawler $anchor, $uri) {
      $anchor->removeAttribute('href');
    });
   return (string) $this->crawler;// $this->crawler->removeAttribute($string);
    //return (string) $this->crawler;
  }
}
