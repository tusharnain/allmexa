<?php

use CodeIgniter\Router\RouteCollection;



/**
 * @var RouteCollection $routes
 */


// die('Currently in maintanance!');


$userRouteGroup = _setting('user_route_group', 'dashboard');
$adminRouteGroup = _setting('admin_route_group', 'admin');




$routes->get('/', function () {
    return redirect()->to(route('login'));
});


App\Routes\AdminRoutes::setupRoutes($routes, $adminRouteGroup);
App\Routes\UserRoutes::setupRoutes($routes, $userRouteGroup);
App\Routes\ApiRoutes::setupRoutes($routes, $userRouteGroup, $adminRouteGroup);



// Email Template Views
// $routes->get('email/(:any)', function ($view) {
//     return view('email/' . $view, ['test' => true]);
// });


$routes->get('test', function() {
    
});




// Tools
$routes->group('tools', ['namespace' => 'App\Controllers\Tools'], function (RouteCollection $routes) {

    $routes->get('generate-qr', 'QrCodeGenerator::index', ['as' => 'tools.qrcode']);

});

