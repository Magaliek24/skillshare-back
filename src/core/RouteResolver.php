<?php

declare(strict_types=1);

namespace App\core;

use App\core\attributes\Route;
use ReflectionClass;

class RouteResolver
{
    public static function getRoutes(): array
    {
        $routes = [];
        $controllers_path = __DIR__ . '/../controller';
        $controller_files = glob($controllers_path . '/*Controller.php');
        foreach ($controller_files as $controller_file) {
            $class_name = 'App\\Controller\\' . basename($controller_file, '.php');
            $reflection = new ReflectionClass($class_name);

            foreach ($reflection->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $routes[$route->method][$route->path] = [
                        $class_name,
                        $method->getName()
                    ];
                }
            }
        }
        return $routes;
    }
}
