<?php
namespace App\Controllers\Cliente;

use App\Controllers\BaseController;
use App\Models\ServicioModel;

class FormularioController extends BaseController{

    /**
     *Trae los servicios activos de BD y los transforma a JSON
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getServicios(){

        $idUsuario = session()->get('id');
        if (!$idUsuario){return $this->response->setStatusCode(401)->setJSON(['Error' => 'No autorizado']);}

        $modelo   = new ServicioModel();

        return $this->response->setJSON( $modelo->listarServiciosActivos());
    }
}
