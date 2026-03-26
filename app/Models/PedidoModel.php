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
        'num_modificaciones',
        'observacion_revision',
        'fechainicio',
        'horainicio',
        'fechafin',
        'horafin',
        'fechacompletado',
        'cancelacionmotivo',
        'fechacancelacion'
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
                p.id,p.titulo,p.estado,p.prioridad,p.fechacreacion,
                p.fechainicio,p.fechafin,p.num_modificaciones,
                s.nombre  AS servicio,
                e.nombreempresa AS empresa
            ')
            ->join('servicios s', 's.id = p.idservicio')
            ->join('formulario_pedidos fp', 'fp.id = p.idformpedido')
            ->join('empresas e', 'e.id = fp.idempresa')
            ->where('e.idusuario', $idUsuario) // Solo pedidos de su empresa (Filtro)
            ->orderBy('p.fechacreacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene toda la información detallada de un pedido 
     * (datos del formulario, servicio, empresa y empleado asignado)
     * @param int $idPedido
     * @return array|null
     */
    public function detallePedido(int $idPedido, int $idUsuario): array|null
    {
        return $this->db->table('pedidos p')
            ->select('
                p.*,
                fp.titulo           AS form_titulo,
                fp.area,fp.objetivo_comunicacion,fp.descripcion,fp.tipo_requerimiento,
                fp.canales_difusion,fp.publico_objetivo,fp.tiene_materiales,fp.formatos_solicitados,
                fp.fecharequerida,
                s.nombre            AS servicio,
                e.nombreempresa     AS empresa,
                u.nombre            AS empleado,
                u.correo            AS correo_empleado')
            ->join('formulario_pedidos fp', 'fp.id = p.idformpedido')
            ->join('usuarios u', 'u.id = p.idempleado', 'left') // LEFT: puede no tener empleado aún
            ->join('empresas e', 'e.id = fp.idempresa')
            ->join('servicios s', 's.id = p.idservicio')
            ->where('p.id', $idPedido)
            ->where('e.idusuario', $idUsuario) // seguridad: solo sus pedidos
            ->get()->getRowArray();
    }
}