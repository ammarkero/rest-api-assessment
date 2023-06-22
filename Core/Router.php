<?php

namespace Core;
use Predis;

class Router
{
    protected $routes = [];

    protected $ttl;
    protected $ip;

    public function __construct()
    {
        // Example Rate limit: 60 requests per 10 seconds
        // $this->ip = 'localhost';
        // // $this->ip = $_SERVER['REMOTE_ADDR'];
        // $redisClient = new Predis\Client();
        // $redisClient->incr($this->ip);
        // if ($redisClient->get($this->ip) == 1) $redisClient->expire($this->ip, 10); // 10 seconds
        // if ($redisClient->get($this->ip) > 60) { // 60 requests
        //     $this->ttl = $redisClient->ttl($this->ip);
        //     sendResponse(429, "You have exceeded your rate limit. Please try again in {$this->ttl} seconds.");
        // }
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
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                return require base_path($route['controller']);
            }
        }

        $this->abort();
    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        echo "<h1>{$code} - Not Found</h1>";

        die();
    }
}