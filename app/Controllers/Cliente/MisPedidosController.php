<?php

namespace App\Controllers\Cliente;

use CodeIgniter\Controller;
use App\Models\PedidoModel; //Llamar al Modelo Pedido (Info)

class MisPedidosController extends Controller
{
    /**
     * Muestra la lista principal de todos los pedidos de la empresa del cliente
     */
    public function index()
    {
        // Cargamos el helper antes de retornar la vista
        helper('pedido');
        // Obtener el id del usuario desde la sesión
        $idUsuario = session()->get('id');
        // Seguridad: si no hay sesión activa, retornamos al login
        if (!$idUsuario) {
           return redirect()->to('/login');
        }
        // Instanciar el Objeto
        $modelo = new PedidoModel();
        // Solo pedimos la data básica al modelo
        $pedidos = $modelo->listarPorCliente($idUsuario);
        //Perpara los Datos para la Vista (Mis Pedidos)
        return view('cliente/lista', [
            'titulo' => 'Mis Pedidos',
            'pedidos' => $pedidos,
        ]);
    }
}