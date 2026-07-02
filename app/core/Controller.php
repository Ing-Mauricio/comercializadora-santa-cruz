<?php
/**
 * Clase base Controller
 * Provee carga de modelos y renderizado de vistas con layout
 */
class Controller
{
    protected function model(string $model)
    {
        require_once APP_PATH . '/models/' . $model . '.php';
        return new $model();
    }

    protected function view(string $view, array $data = [], bool $useLayout = true)
    {
        extract($data);
        $viewFile = APP_PATH . '/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die('La vista "' . $view . '" no existe.');
        }

        if ($useLayout) {
            require APP_PATH . '/views/layouts/header.php';
            require APP_PATH . '/views/layouts/sidebar.php';
            echo '<main class="main-content">';
            require $viewFile;
            echo '</main>';
            require APP_PATH . '/views/layouts/footer.php';
        } else {
            require $viewFile;
        }
    }

    protected function redirect(string $url)
    {
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }

    /** Verifica que exista sesión activa, si no, redirige al login */
    protected function requireAuth()
    {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('login');
        }
    }

    /** Verifica que el usuario logueado sea administrador */
    protected function requireAdmin()
    {
        $this->requireAuth();
        if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
            http_response_code(403);
            die('<h2 style="font-family:sans-serif;text-align:center;margin-top:80px;">403 - Acceso restringido a administradores</h2>');
        }
    }

    /** Registra una acción en la tabla de auditoría */
    protected function auditar(string $modulo, string $accion, string $descripcion)
    {
        $auditoria = $this->model('Auditoria');
        $auditoria->registrar(
            $_SESSION['usuario_id'] ?? null,
            $modulo,
            $accion,
            $descripcion,
            $_SERVER['REMOTE_ADDR'] ?? null
        );
    }
}
