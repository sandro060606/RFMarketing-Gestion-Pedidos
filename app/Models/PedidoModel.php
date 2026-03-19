<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table = 'pedidos';      // Tabla de la BD que el Modelo Domina
    protected $primaryKey = 'id';      // Id Unico de Tabla
    protected $returnType = 'array';   // Retorna como Array, No Objeto

    // Campos permitidos para manipulación de datos (Mass Assignment Protection)
    protected $allowedFields = [
        'idformpedido',
        'idadmin',
        'idempleado',
        'idservicio',
        'titulo',
        'prioridad',
        'estado',
        'fechacreacion'
    ];

    /**
     * Consulta con JOIN para obtener pedidos vinculados a la empresa del cliente
     * @param int $idUsuario
     * @return array
     */
    public function listarPorCliente(int $idUsuario): array
    {
        return $this->db->table('pedidos p')
            ->select('
            p.id, 
            p.titulo, 
            p.estado, 
            p.prioridad, 
            p.fechacreacion,
            s.nombre AS servicio_nombre
        ')
            ->join('servicios s', 's.id = p.idservicio')
            ->join('formulario_pedidos fp', 'fp.id = p.idformpedido')
            ->join('empresas e', 'e.id = fp.idempresa')
            ->where('e.idusuario', $idUsuario)  //Filtro de Usuario
            ->orderBy('p.fechacreacion', 'DESC')
            ->get()->getResultArray();          //Ejecuta y devuelve como array
    }
}