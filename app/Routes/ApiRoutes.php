<?php
namespace App\Routes;

use CodeIgniter\Router\RouteCollection;

class ApiRoutes
{
    private static $publicApiNamespace = "App\Controllers\Api\PublicApi";
    private static $adminApiNamespace = "App\Controllers\Api\AdminApi";



    /*
     *------------------------------------------------------------------------------------
     * PUBLIC API
     *------------------------------------------------------------------------------------
     */
    private static function publicApi(RouteCollection &$routes)
    {
        $routes->post('get-user-name', 'Index::getUserNameFromUserId', ['as' => 'api.public.getUserNameFromUserId']);
    }


    /*
     *------------------------------------------------------------------------------------
     * ADMIN API
     *------------------------------------------------------------------------------------
     */

    private static function adminApi(RouteCollection &$routes)
    {
        $routes->post('get-user', 'Index::getUserDetails', ['as' => 'api.admin.getUserDetails']);
    }




    public static function setupRoutes(RouteCollection &$routes, string &$userRouteGroup, string &$adminRouteGroup)
    {
        /*
         *------------------------------------------------------------------------------------
         *  Public API ROUTES
         *------------------------------------------------------------------------------------
         */
        $routes->group(
            '/api',
            ['namespace' => self::$publicApiNamespace],
            static function (&$routes) {
                self::publicApi($routes);
            }
        );


        /*
         *------------------------------------------------------------------------------------
         *  Admin API ROUTES
         *------------------------------------------------------------------------------------
         */
        $routes->group(
            "api/$adminRouteGroup",
            ['namespace' => self::$adminApiNamespace],
            static function (&$routes) {
                self::adminApi($routes);
            }
        );
    }
}