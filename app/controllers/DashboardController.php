<?php
class DashboardController extends Controller
{
    public function index()
    {
        $this->requireAuth();

        $productoModel = $this->model('Producto');
        $clienteModel  = $this->model('Cliente');
        $ventaModel    = $this->model('Venta');

        $data = [
            'totalProductos'   => $productoModel->total(),
            'totalClientes'    => $clienteModel->total(),
            'valorInventario'  => $productoModel->valorInventario(),
            'ventasHoy'        => $ventaModel->totalVentasHoy(),
            'ventasMes'        => $ventaModel->totalVentasMes(),
            'numVentasHoy'     => $ventaModel->countHoy(),
            'stockBajo'        => $productoModel->stockBajo(),
            'masVendidos'      => $ventaModel->productosMasVendidos(5),
            'ventasSemana'     => $ventaModel->ventasUltimos7Dias(),
        ];

        $this->view('dashboard/index', $data);
    }
}
