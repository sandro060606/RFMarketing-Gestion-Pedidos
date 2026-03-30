<?php

namespace App\Controllers\Cliente;

use App\Controllers\BaseController;
use App\Models\NotificacionModel;

class NotificacionesController extends BaseController
{
    /**
     * Obtiene todas las notificaciones del cliente autenticado
     * @return \CodeIgniter\HTTP\ResponseInterface
     * * Códigos de respuesta:
     * - 200: OK - Notificaciones obtenidas exitosamente
     * - 401: Unauthorized - Usuario no autenticado
     */
    public function getNotificaciones()
    {
        $idUsuario = session()->get('id');

        if (!$idUsuario) {
            return $this->response->setStatusCode(401)->setJSON(['Error' => 'No autorizado']);
        }

        $modelo = new NotificacionModel();
        return $this->response->setJSON($modelo->listarPorUsuario($idUsuario));
    }
}