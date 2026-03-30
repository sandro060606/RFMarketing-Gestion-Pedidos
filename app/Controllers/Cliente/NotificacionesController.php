<?php

namespace App\Controllers\Cliente;

use App\Controllers\BaseController;
use App\Models\NotificacionModel;

class NotificacionesController extends BaseController
{
    /**
     * Renderiza la vista de notificaciones del cliente
     * @return string Vista HTML renderizada
     */
    public function index()
    {
        $data['titulo'] = "Mis Notificaciones";
        return view('cliente/notificaciones', $data);
    }

    /**
     * Obtiene todas las notificaciones del cliente autenticado en formato JSON
     * Endpoint pensado para ser consumido por JavaScript (Fetch/AJAX)
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getNotificaciones()
    {
        // Obtener ID del usuario de la sesión actual
        $idUsuario = session()->get('id');
        // Validar que existe sesión válida
        if (!$idUsuario) {
            return $this->response->setStatusCode(401)->setJSON(['Error' => 'No autorizado']);
        }
        // Instanciar modelo y obtener notificaciones personalizadas
        $modelo = new NotificacionModel();
        $notificaciones = $modelo->listarPorUsuario($idUsuario);
        // Devolver notificaciones como JSON
        return $this->response->setJSON($notificaciones);
    }
}