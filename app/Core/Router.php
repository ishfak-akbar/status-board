<?php
namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, $callback): void {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, $callback): void {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch(string $uri, string $method): void {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $callback = $this->routes[$method][$path] ?? null;
        
        if (!$callback) {
            http_response_code(404);
            echo "<h1>404 Not Found</h1>";
            return;
        }

        //Handle controller class callbacks [ClassName::class, 'methodName']
        if (is_array($callback) && count($callback) === 2) {
            [$className, $methodName] = $callback;
            
            if (class_exists($className)) {
                //CREATE THE CONTROLLER INSTANCE
                $controller = new $className();
                
                if (method_exists($controller, $methodName)) {
                    //Call the method on the INSTANCE, not statically
                    $controller->$methodName();
                    return;
                }
            }
            
            http_response_code(500);
            echo "<h1>500 Internal Server Error</h1>";
            echo "<p>Controller or method not found: $className::$methodName</p>";
            return;
        }

        //Handle regular callbacks (closures/functions)
        if (is_callable($callback)) {
            echo call_user_func($callback);
        } else {
            http_response_code(500);
            echo "<h1>500 Internal Server Error</h1>";
            echo "<p>Invalid callback provided for route: " . gettype($callback) . "</p>";
        }
    }

    public function redirect(string $path): void {
        header("Location: $path");
        exit;
    }
}
