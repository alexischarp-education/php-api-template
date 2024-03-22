<?php

require './vendor/autoload.php';
require './controllers/BookingsController.php';

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\ConfigureRoutes $r) {
  $r->get('/bookings', 'BookingsController#get_all_bookings');
  $r->get('/bookings/{id:\d+}', 'BookingsController#get_booking');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
  $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
  case FastRoute\Dispatcher::NOT_FOUND:
    // ... 404 Not Found
    break;
  case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
    $allowedMethods = $routeInfo[1];
    // ... 405 Method Not Allowed
    break;
  case FastRoute\Dispatcher::FOUND:
    $handler = $routeInfo[1];
    $vars = $routeInfo[2];

    $controller = explode('#', $handler)[0];
    $method = explode('#', $handler)[1];

    if (class_exists($controller)) {
      $instance = new $controller();
      $instance->$method($vars);
    } else {
      echo 'Controller not found';
    }
    break;
}
