<?php

/**
 * @file
 * Contains \Drupal\hardcopy\Routing\RouteSubscriber
 */

namespace Drupal\hardcopy\Routing;

use Drupal\Core\Routing\RoutingEvents;
use Drupal\Core\Routing\RouteBuildEvent;
use Drupal\hardcopy\HardcopyEntityManagerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


/**
 * Defines a route subscriber to generate a print route for all content entities.
 */
class RouteSubscriber implements EventSubscriberInterface {

  /**
   * The hardcopy entity manager service.
   *
   * @var \Drupal\hardcopy\HardcopyEntityManagerInterface
   */
  protected $hardcopyEntityManager;

  /**
   * Constructs a hardcopy RouteSubscriber object.
   *
   * @param \Drupal\hardcopy\HardcopyEntityManagerInterface $hardcopy_entity_manager
   *  The hardcopy entity manager service
   */
  public function __construct(HardcopyEntityManagerInterface $hardcopy_entity_manager) {
    $this->hardcopyEntityManager = $hardcopy_entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[RoutingEvents::ALTER] = 'routes';
    return $events;
  }

  /**
   * Adds a print route for each content entity.
   *
   * @param \Drupal\Core\Routing\RouteBuildEvent $event
   *   The route build event.
   */
  public function routes(RouteBuildEvent $event) {
    $collection = $event->getRouteCollection();
    //echo "hola";
    //print_r($this->hardcopyEntityManager->getHardcopyEntities());
    foreach($this->hardcopyEntityManager->getHardcopyEntities() as $entity_type => $entity_definition) {
      $route = new Route(
        "/$entity_type/{entity}/hardcopy/{hardcopy_format}",
        array(
          '_controller' => 'Drupal\hardcopy\Controller\HardcopyController::showFormat',
          '_title' => 'Hardcopy',
        ),
        array(
          //'_entity_access' => 'entity.view',
          '_permission' => 'view printer friendly versions',
        ),
        array(
          'parameters' => array(
            'entity' => array('type' => 'entity:' . $entity_type),
          ),
        )
      );
      $collection->add('hardcopy.show_format.' . $entity_type, $route);
    }
    /*$route=new Route(
       "/killer",
       array(
        '_controller' => 'Drupal\hardcopy\Controller\HardcopyController::demo',
          '_title' => 'Hardcopy',
        ),
       array(
        '_permission' => 'access content',
        )
      );*/
    //$collection->add('hardcopy.show_format', $route);
  }
}
