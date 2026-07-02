<?php
/**
 * Clase App
 * Router simple: interpreta la URL en controlador/método/parámetros
 * Ejemplo: index.php?url=productos/edit/5  ->  ProductoController->edit(5)
 */
class App
{
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Controlador
        if (isset($url[0]) && $url[0] !== '') {
            $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
            if (file_exists(APP_PATH . '/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                $this->controller = 'ErrorController';
            }
        }

        require_once APP_PATH . '/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller();

        // Método
        if (isset($url[1]) && $url[1] !== '') {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Parámetros
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = preg_replace('/[^a-zA-Z0-9\/_\-]/', '', $url);
            return $url === '' ? [] : explode('/', $url);
        }
        return [];
    }
}
