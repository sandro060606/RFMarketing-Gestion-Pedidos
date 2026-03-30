<?php

namespace App\Controllers\Administrador;

use CodeIgniter\Controller;
use App\Models\EmpresaModel;
use App\Models\PedidoModel;
use App\Models\AreaModel;

class DashboardController extends Controller
{
    public function index(): string
    {
        $pedidoModel = new PedidoModel();
        $empresaModel = new EmpresaModel();
        $areaModel = new AreaModel();
        $porAprobar  = $pedidoModel->contarPorEstado('por_aprobar');
        $activos     = $pedidoModel->contarPorEstado('en_proceso');
        $completados = $pedidoModel->contarPorEstado('completado');
        $total       = max(1, $porAprobar + $activos + $completados);

        return view('administrador/panel/index', [
            'titulo' => 'Dashboard',
            'tituloPagina' => 'DASHBOARD',
            'paginaActual' => 'dashboard',

            'porAprobar'     => $porAprobar,
            'activos'        => $activos,
            'enRevision'     => $pedidoModel->contarPorEstado('en_revision'),
            'completados'    => $completados,
            'empresas'       => $empresaModel->obtenerConStats(),
            'areas'          => $areaModel->obtenerActivas(),
            'totalPedidos'   => $total,
            'pctActivos'     => round($activos     / $total * 100),
            'pctPorAprobar'  => round($porAprobar  / $total * 100),
            'pctCompletados' => round($completados / $total * 100),
        ]);
    }
}