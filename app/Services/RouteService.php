<?php

namespace App\Services;



final class RouteService
{
    const CACHE_TTL = 2678400; // 1 month
    const CACHE_NAME = 'routes_cache';
    private static ?RouteService $instance = null; // Static property to hold the instance
    private array $routeCache = [];
    private $storedRoutes = [];
    public function __construct()
    {
        if ($rtCache = cache(self::CACHE_NAME))
            $this->routeCache = $rtCache;
    }

    // Method to get the singleton instance
    public static function getInstance(): RouteService
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }



    private function saveToCache(string $routeName, string $url)
    {
        $this->routeCache[$routeName] = $url;

        cache()->save(self::CACHE_NAME, $this->routeCache, self::CACHE_TTL);
    }

    private function getFromCache(string $routeName): ?string
    {
        if (isset($this->routeCache[$routeName])) {
            return $this->routeCache[$routeName];
        }
        return null;
    }




    private function createDummyUrl(string $routeName, ...$params): string
    {
        $paramCount = count($params);

        if ($paramCount > 0) {

            foreach (range(1, $paramCount) as $i) {
                $nullParams[] = "__$i";
                $i++;
            }

            $url = url_to($routeName, ...$nullParams);

        } else {

            $url = url_to($routeName);
        }



        return $url;
    }

    private function getUrlFromDummyUrl(string $dummyUrl, ...$params): string
    {
        $paramCount = count($params);

        if ($paramCount > 0) {

            $i = 1;
            foreach ($params as &$param) {
                $dummyUrl = str_replace("__$i", $param, $dummyUrl);
                $i++;
            }
        }

        return $dummyUrl;
    }


    public function route(string $routeName, ...$params): string
    {
        if (isset($this->storedRoutes[$routeName])) {
            $url = $this->storedRoutes[$routeName];
        } else {
            // getting url from cache
            $url = $this->getFromCache($routeName);
        }


        // if didnt get from cache, then generating it, and caching it
        if (!$url) {
            $url = $this->createDummyUrl($routeName, ...$params);
            $this->saveToCache($routeName, $url);
            $this->storedRoutes[$routeName] = $url;
        }


        $orgUrl = $this->getUrlFromDummyUrl($url, ...$params);
        return $orgUrl;
    }


    public function removeCache(): bool
    {
        return cache()->delete(self::CACHE_NAME);
    }

}
