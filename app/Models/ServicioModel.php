<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicioModel extends Model{
    protected $table = 'servicios';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nombre', 'descripcion', 'activo'];

    /**
     * Trae solo los servicios activos
     * Se usa en la vista de selección para mostrar las opciones al cliente
     * @return array
     */
    public function listarServiciosActivos(): array
    {
        return $this->where('activo', true)->findAll();
    }
}