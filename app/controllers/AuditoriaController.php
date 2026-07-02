<?php
class AuditoriaController extends Controller
{
    public function index()
    {
        $this->requireAuth();

        $auditoriaModel = $this->model('Auditoria');
        $usuarioModel   = $this->model('Usuario');

        $filtros = [
            'modulo'     => $_GET['modulo'] ?? '',
            'usuario_id' => $_GET['usuario_id'] ?? '',
            'desde'      => $_GET['desde'] ?? '',
            'hasta'      => $_GET['hasta'] ?? '',
        ];

        $this->view('auditoria/index', [
            'registros' => $auditoriaModel->all($filtros),
            'modulos'   => $auditoriaModel->modulosDisponibles(),
            'usuarios'  => $usuarioModel->all(),
            'filtros'   => $filtros,
        ]);
    }
}
