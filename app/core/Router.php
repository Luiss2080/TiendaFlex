<?php

class Router
{
    private $routes = [];
    private $request;
    private $response;
    private $middlewares = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $handler, $middlewares = [])
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post($path, $handler, $middlewares = [])
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    public function put($path, $handler, $middlewares = [])
    {
        $this->addRoute('PUT', $path, $handler, $middlewares);
    }

    public function delete($path, $handler, $middlewares = [])
    {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
    }

    private function addRoute($method, $path, $handler, $middlewares = [])
    {
        $pattern = $this->createPattern($path);
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch()
    {
        $uri = $this->request->getUri();
        $method = $this->request->getMethod();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                // Extraer parámetros
                $params = $this->extractParams($route['path'], $matches);
                $this->request->setParams($params);

                // Ejecutar middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $this->executeMiddleware($middleware);
                }

                // Ejecutar controlador
                $this->executeHandler($route['handler']);
                return;
            }
        }

        // Ruta no encontrada
        $this->response->notFound();
    }

    private function createPattern($path)
    {
        // Convertir parámetros {param} a regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function extractParams($path, $matches)
    {
        $params = [];
        
        // Obtener nombres de parámetros del path
        preg_match_all('/\{([^}]+)\}/', $path, $paramNames);
        
        // Emparejar con los valores capturados
        for ($i = 0; $i < count($paramNames[1]); $i++) {
            if (isset($matches[$i + 1])) {
                $params[$paramNames[1][$i]] = $matches[$i + 1];
            }
        }
        
        return $params;
    }

    private function executeMiddleware($middleware)
    {
        if (is_string($middleware)) {
            $className = $middleware . 'Middleware';
            $middlewareInstance = new $className();
            $middlewareInstance->handle($this->request, $this->response);
        } elseif (is_callable($middleware)) {
            $middleware($this->request, $this->response);
        }
    }

    private function executeHandler($handler)
    {
        if (is_string($handler)) {
            // Formato: 'Controller@method'
            list($controllerName, $method) = explode('@', $handler);
            $controllerClass = $controllerName; // Ya incluye 'Controller' en el nombre
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();

                if (method_exists($controller, $method)) {
                    // Pasar los parámetros extraídos de la ruta (p. ej. {id})
                    // como argumentos posicionales del método del controlador.
                    $routeParams = array_values($this->request->getParams());
                    $result = call_user_func_array([$controller, $method], $routeParams);

                    if ($result !== null) {
                        $this->response->setContent($result);
                        $this->response->send();
                    }
                } else {
                    throw new Exception("Método $method no encontrado en $controllerClass");
                }
            } else {
                throw new Exception("Controlador $controllerClass no encontrado");
            }
        } elseif (is_callable($handler)) {
            $result = $handler($this->request, $this->response);
            
            if ($result !== null) {
                $this->response->setContent($result);
                $this->response->send();
            }
        }
    }
}