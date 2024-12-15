<?php

namespace DynamicMailer;

class Router
{
    private array $routes = [];

    public function post(string $path, callable $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function get(string $path, callable $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            $response = $callback($this->getRequestData());
            $this->sendResponse($response);
        } else {
            $this->sendResponse([
                'error' => 'Route not found'
            ], 404);
        }
    }

    private function getRequestData(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return json_decode(file_get_contents('php://input'), true) ?? [];
        }
        return $_GET;
    }

    private function sendResponse($data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }
}
