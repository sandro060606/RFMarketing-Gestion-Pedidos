<?php

namespace App\Controllers\Administrador;

use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index(): string
    {
        return view('administrador/panel/index', [
    'titulo'       => 'Dashboard',
    'tituloPagina' => 'DASHBOARD',
    'paginaActual' => 'dashboard',
    'empresas'     => [], 
]);
    }
}