<?php
namespace App\Controllers\Cliente;

use CodeIgniter\Controller;
use App\Models\PedidoModel;

class MisPedidosController extends Controller
{
    /**
     * Solo carga la vista HTML
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        $idUsuario = session()->get('id');
        if (!$idUsuario)
            return redirect()->to('/login');

        return view('cliente/index', ['titulo' => 'Mis Pedidos']);
    }

    /**
     * Busca los pedidos en BD y los devuelve como JSON
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function listarPedido()
    {
        // Verificar sesión — si no hay, responder con error 401
        $idUsuario = session()->get('id');
        if (!$idUsuario) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'mensaje' => 'No autenticado']);
        }
        // Instanciar el modelo de pedidos
        $modelo = new PedidoModel();
        // Ejecutar la consulta SQL con JOIN (ver PedidoModel::listarPedido)
        $pedidos = $modelo->listarPedido($idUsuario);
        // Devolver el array como JSON — status 200 por defecto
        return $this->response->setJSON($pedidos);
    }

    /**
     * Busca UN pedido específico y lo devuelve como JSON
     * @param int $id
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function detallePedido(int $id)
    {
        // Verificar sesión activa
        $idUsuario = session()->get('id');
        if (!$idUsuario) { return redirect()->to('/login');}
        // Cargar helper para badges y formato de fechas en la vista
        helper('pedido');
        // Instanciar el modelo
        $modelo = new PedidoModel();
        // Buscar el pedido completo con todos los JOINs
        $pedido = $modelo->detallePedido($id, $idUsuario);
        // Si no se encontró el pedido → redirigir a la lista con mensaje
        if (!$pedido) {
            return redirect()->to('/cliente')->with('error', 'Pedido no encontrado');
        }
        // Pasar los datos a la vista de detalle
        return view('cliente/detalle', [
            'titulo'  => 'Detalle del Pedido',
            'pedido'  => $pedido,
        ]);
    }
}