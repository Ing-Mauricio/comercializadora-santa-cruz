<?php
class LoginController extends Controller
{
    public function index()
    {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirect('dashboard');
        }
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);
        $this->view('login/index', ['error' => $error], false);
    }

    public function autenticar()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = 'Debes ingresar correo y contraseña.';
            $this->redirect('login');
        }

        $usuarioModel = $this->model('Usuario');
        $usuario = $usuarioModel->findByEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            $_SESSION['login_error'] = 'Credenciales incorrectas. Verifica tu correo y contraseña.';
            $this->redirect('login');
        }

        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol']    = $usuario['rol'];

        $usuarioModel->actualizarUltimoAcceso($usuario['id']);
        $this->auditar('Autenticación', 'Login', "El usuario {$usuario['nombre']} inició sesión");

        $this->redirect('dashboard');
    }

    public function logout()
    {
        if (isset($_SESSION['usuario_nombre'])) {
            $this->auditar('Autenticación', 'Logout', "El usuario {$_SESSION['usuario_nombre']} cerró sesión");
        }
        session_unset();
        session_destroy();
        session_start();
        $this->redirect('login');
    }
}
