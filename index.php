<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'autoload.php';

class Router
{
    private $routes = [];
    private $prefix;

    public function __construct($prefix = '')
    {
        $this->prefix = trim($prefix, '/');
    }

    public function addRoute($uri, $controllerMethod)
    {
        $this->routes[trim($uri, '/')] = $controllerMethod;
    }

    public function route($url)
    {
        if ($this->prefix && strpos($url, $this->prefix) === 0) {
            $url = substr($url, strlen($this->prefix) + 1);
        }

        $url = trim($url, '/');

        foreach ($this->routes as $route => $controllerMethod) {
            $routeParts = explode('/', $route);
            $urlParts = explode('/', $url);

            if (count($routeParts) === count($urlParts)) {
                $params = [];
                $isMatch = true;
                foreach ($routeParts as $index => $part) {
                    if (preg_match('/^{\w+}$/', $part)) {
                        $params[] = $urlParts[$index];
                    } elseif ($part !== $urlParts[$index]) {
                        $isMatch = false;
                        break;
                    }
                }

                if ($isMatch) {
                    list($controllerName, $methodName) = explode('@', $controllerMethod);
                    $controller = new $controllerName();
                    call_user_func_array([$controller, $methodName], $params);
                    return;
                }
            }
        }

        require_once 'views/404.php';
    }
}

function loadEnv($file)
{
    if (file_exists($file)) {
        $lines = file($file);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            putenv(trim($line));
        }
    }
}

loadEnv('.env');

$prefix = getenv('PREFIX') ?: '';

$router = new Router($prefix);

$router->addRoute('', 'HomeController@index');
$router->addRoute('tasks', 'TaskController@index');
$router->addRoute('tasks/{id}', 'TaskController@show');
$router->addRoute('about', 'AboutController@index');

$router->route(trim($_SERVER['REQUEST_URI'], '/'));

