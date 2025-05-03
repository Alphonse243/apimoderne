<?php

namespace App\Core;

class Router
{
    protected array $routes = [];
    
    public function get(string $path, string $controller, string $method)
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/karma-master/public', '', $uri);
        $uri = trim($uri, '/');
        
        $method = $_SERVER['REQUEST_METHOD'];
        
        if (isset($this->routes[$method][$uri])) {
            $controllerName = $this->routes[$method][$uri]['controller'];
            $methodName = $this->routes[$method][$uri]['method'];
            
            require_once __DIR__ . "/../Controllers/{$controllerName}.php";
            $controller = new $controllerName();
            $controller->$methodName();
            return;
        }
        
        http_response_code(404);
        include __DIR__ . '/../Views/errors/404.php';
    }
}
