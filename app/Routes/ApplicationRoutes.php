<?php
namespace App\Routes;

use CodeIgniter\Router\RouteCollection;

class ApplicationRoutes
{
    const CONTROLLER = "\App\Controllers\ApplicationController";

    public static function setupRoutes(RouteCollection &$routes)
    {
        $controller = self::CONTROLLER;

        $routes->group('application', function ($routes) use (&$controller) {

            // $routes->get('session/(:segment)', "$controller::sessions/$1");

        });
    }
}