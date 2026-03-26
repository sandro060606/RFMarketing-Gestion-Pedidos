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

        return view('administrador/panel/index', [
            'titulo' => 'Dashboard',
            'tituloPagina' => 'DASHBOARD',
            'paginaActual' => 'dashboard',

            // Tarjetas del resumen
            'porAprobar' => $pedidoModel->contarPorEstado('por_aprobar'),
            'activos' => $pedidoModel->contarPorEstado('en_proceso'),
            'enRevision' => $pedidoModel->contarPorEstado('en_revision'),
            'completados' => $pedidoModel->contarPorEstado('completado'),

            // Para la sección de empresas
            'empresas' => $empresaModel->obtenerConStats(),
            'areas' => $areaModel->obtenerActivas(),
        ]);
    }
}