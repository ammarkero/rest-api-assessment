<?php

namespace Core;
use Predis;

class Router
{
    protected $routes = [];
    protected $redis;

    public function __construct()
    {
        $this->redis = new Predis\Client();
        $this->redis->connect();
    }

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function get($uri, $controller)
    {
        $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        $this->add('PUT', $uri, $controller);
    }

    public function route($uri, $method)
    {

        $route = $this->findMatchingRoute($uri, $method);

        if ($route) {
            $this->applyRateLimitMiddleware();
            return require base_path($route['controller']);
        }

        $this->abort();
    }

    protected function findMatchingRoute($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                return $route;
            }
        }

        return null;
    }

    protected function applyRateLimitMiddleware()
    {
        $requestKey = $_SERVER['REMOTE_ADDR'];
        
        $limit = 100; // number of requests
        $timeFrame = 60 * 60; // 1 hour in seconds
        
        $requestCount = $this->redis->get($requestKey);

        $this->redis->incr($requestKey);
        // set the TTL to 1 hour only if it's the first request
        if ($requestCount == 1) $this->redis->expire($requestKey, $timeFrame);
        if ($requestCount >= $limit) { // 100 requests
            sendResponse(429, "You have exceeded your rate limit. Please try again in {$this->redis->ttl($requestKey)} seconds.");
        }

    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        echo "<h1>{$code} - Not Found</h1>";

        die();
    }
}