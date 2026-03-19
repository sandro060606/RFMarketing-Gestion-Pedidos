<?php

namespace App\Controllers\Cliente;

use CodeIgniter\Controller;
use App\Models\PedidoModel; //Llamar al Modelo Pedido (Info)

class MisPedidosController extends Controller
{
    /**
     * Muestra la lista principal de todos los pedidos de la empresa del cliente
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {   
        // Obtener el id del usuario desde la sesión
        $idUsuario = session()->get('id');
        // Seguridad: si no hay sesión activa, error 401
        if (!$idUsuario) {
            return $this->response->setJSON(['error' => 'No autorizado'])->setStatusCode(401);
        }
        // Instanciar el Objeto
        $modelo = new PedidoModel();
        // Solo pedimos la data básica al modelo
        $pedidos = $modelo->listarPorCliente($idUsuario);

        // Retornamos JSON para la de Postman
        return $this->response->setJSON([
            'empresa' => 'RF Marketing SAC',
            'fecha_consulta' => date('Y-m-d H:i:s'),
            'total_pedidos' => count($pedidos),
            'lista' => $pedidos
        ]);
    }
}