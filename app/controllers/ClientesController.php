<?php
class ClientesController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $clienteModel = $this->model('Cliente');
        $busqueda = trim($_GET['q'] ?? '');
        $clientes = $clienteModel->all($busqueda);
        $this->view('clientes/index', ['clientes' => $clientes, 'busqueda' => $busqueda]);
    }

    public function create()
    {
        $this->requireAuth();
        $this->view('clientes/create');
    }

    public function guardar()
    {
        $this->requireAuth();
        $clienteModel = $this->model('Cliente');

        $data = $this->sanitize($_POST);
        if ($data['nombre'] === '' || $data['apellido'] === '') {
            $_SESSION['flash_error'] = 'El nombre y apellido son obligatorios.';
            $this->redirect('clientes/create');
        }

        $clienteModel->create($data);
        $this->auditar('Clientes', 'Creación', "Registró al cliente {$data['nombre']} {$data['apellido']}");
        $_SESSION['flash_ok'] = 'Cliente registrado correctamente.';
        $this->redirect('clientes');
    }

    public function edit($id)
    {
        $this->requireAuth();
        $clienteModel = $this->model('Cliente');
        $cliente = $clienteModel->find((int)$id);
        if (!$cliente) { $this->redirect('clientes'); }
        $this->view('clientes/edit', ['cliente' => $cliente]);
    }

    public function actualizar($id)
    {
        $this->requireAuth();
        $clienteModel = $this->model('Cliente');
        $data = $this->sanitize($_POST);
        $clienteModel->update((int)$id, $data);
        $this->auditar('Clientes', 'Edición', "Actualizó los datos del cliente ID {$id}");
        $_SESSION['flash_ok'] = 'Cliente actualizado correctamente.';
        $this->redirect('clientes');
    }

    public function eliminar($id)
    {
        $this->requireAuth();
        $clienteModel = $this->model('Cliente');
        $cliente = $clienteModel->find((int)$id);
        $clienteModel->delete((int)$id);
        $this->auditar('Clientes', 'Eliminación', "Eliminó al cliente " . ($cliente['nombre'] ?? '') . " " . ($cliente['apellido'] ?? ''));
        $_SESSION['flash_ok'] = 'Cliente eliminado.';
        $this->redirect('clientes');
    }

    private function sanitize(array $post): array
    {
        return [
            'nombre'    => trim($post['nombre'] ?? ''),
            'apellido'  => trim($post['apellido'] ?? ''),
            'nit_ci'    => trim($post['nit_ci'] ?? ''),
            'email'     => trim($post['email'] ?? ''),
            'telefono'  => trim($post['telefono'] ?? ''),
            'direccion' => trim($post['direccion'] ?? ''),
        ];
    }
}
