<?php

namespace App\Controllers\Cliente;

use CodeIgniter\Controller;
use App\Models\EmpresaModel;
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

    /**
     * Obtiene el detalle de un pedido específico para el usuario actual
     * @param int $id ID del pedido
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function detalle(int $id)    
    {   
        // Obtener el id del usuario desde la sesión
        $idUsuario = session()->get('id');
        if (!$idUsuario) {
            return $this->jsonError('No autenticado', 401);
        }
        // Instanciamos el modelo     
        $modelo = new PedidoModel();
        //// Ejecutamos el método detallePedido (Pasando en IdUsuario y el IdPedido)
        $pedido = $modelo->detallePedido($id, $idUsuario);

        if (!$pedido) {
            return $this->jsonError('Pedido no encontrado', 404);
        }

        // Responde con el pedido en JSON
        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'pedido' => $pedido,
            ]);
    }

    // Respuesta de error (JSON) / BACKEND
    private function jsonError(string $mensaje, int $codigo = 400)
    {
        return $this->response
            ->setStatusCode($codigo)
            ->setJSON(['ok' => false, 'mensaje' => $mensaje]);
    }
}