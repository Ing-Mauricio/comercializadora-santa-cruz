<?php
class ReportesController extends Controller
{
    public function index()
    {
        $this->requireAuth();

        $ventaModel = $this->model('Venta');
        $productoModel = $this->model('Producto');

        $desde = $_GET['desde'] ?? date('Y-m-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-d');

        $data = [
            'desde'            => $desde,
            'hasta'            => $hasta,
            'ventasRango'      => $ventaModel->ventasPorRango($desde, $hasta),
            'ventasPorVendedor'=> $ventaModel->ventasPorVendedor($desde, $hasta),
            'masVendidos'      => $ventaModel->productosMasVendidos(10),
            'stockBajo'        => $productoModel->stockBajo(),
            'valorInventario'  => $productoModel->valorInventario(),
        ];

        $data['totalPeriodo'] = array_sum(array_column($data['ventasRango'], 'total'));

        $this->view('reportes/index', $data);
    }
}
