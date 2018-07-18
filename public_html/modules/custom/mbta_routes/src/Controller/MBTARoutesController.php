<?php
namespace Drupal\mbta_routes\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class MBTARoutesController extends ControllerBase {

  public function schedule($type) {
    // Set the route_type array
    $route_type = array();

    // Get the route data
    $route = $this->restGet('https://api-v3.mbta.com/routes/' . $type);

    $markup = print_r($route, true);
    return [
      '#markup' => $markup,
    ];
  }

  /**
   * Returns a page with all routes
   *
   * @return array
   *   Returns the route page html data
   */
  public function routes() {

    // Set the route_type array
    $route_type = array();

    // Get the route data
    $routes = $this->restGet('https://api-v3.mbta.com/routes');

    // Loop through the available routes passed from the mbta api
    for ($i = 1; $i <= count($routes['data']); $i++) {

      // Store the route data in an array for easier processing
      $route = $routes['data'][$i];

      // Make sure the route type isn't empty?
      if ($route['attributes']['description'] !== '' && $route['attributes']['long_name'] !== '') {

        // Store the route type in a cleaned variable for validation
        $type = trim($route['attributes']['description']);

        $prepped_route = array(
          'color' => $route['attributes']['color'],
          'name' => $route['attributes']['long_name'],
          'url' => $route['id'],
        );

        // If the route type is already in the array...
        if (!array_key_exists($type, $route_type)) {
          $route_type[$type] = array($prepped_route);
        }

        // ... route type is already in the array, append to existing content
        else {
          array_push($route_type[$type], $prepped_route);
        }
      }
    }

    // dsm($route_type);

    return [
      // '#markup' => 'testing',
      '#theme' => 'route_template',
      '#routes' => $route_type,
    ];
  }

  private function restGet($url, $header = array()) {

    // Exit early if the header is not an array
    if (!is_array($header)) {
      echo 'Error: header is not an array';
      return false;
    }

    // Init the curl command and set the params.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Process the rest command
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    // Return the token
    return $result;
  }

}
