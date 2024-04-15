<?php

namespace App\router;

/**
 * Class Router - Responsible for routing requests to the appropriate handler
 */
class Router
{
    /**
     * @var array An array to store routes based on HTTP methods
     */
    public static array $routes = [];

    /**
     * Add a new route to the routing table
     *
     * @param string $method The HTTP method of the route (e.g., GET, POST)
     * @param string $pattern The URL pattern to match
     * @param callable $callback The callback function to execute when the route is matched
     */
    public static function addRoute(string $method, string $pattern, callable $callback): void
    {
        self::$routes[$method][$pattern] = $callback;
    }

    /**
     * Insert routes from an external file into the routing table
     *
     * @param mixed $conn The database connection (not used in the current implementation)
     */
    public static function insertRoutes(mixed $conn): void
    {
        require_once __DIR__ . "/routes.php";
    }

    /**
     * Handle an incoming request and route it to the appropriate callback function
     *
     * @param string $method The HTTP method of the request
     * @param string $url The URL of the request
     * @return mixed The result of the routed callback function or false if no route matches
     */
    public static function handleRequest(string $method, string $url): mixed
    {
        foreach (self::$routes[$method] as $pattern => $callback) {
            // Replace route parameters in the pattern with named capture groups
            $pattern = preg_replace('/{([^\/]+)}/', '(?P<\1>[^\/]+)', $pattern);
            if (preg_match("#^$pattern$#", $url, $matches)) {
                return call_user_func_array($callback, [$matches]);
            }
        }
        return false; // No matching route found
    }
}
