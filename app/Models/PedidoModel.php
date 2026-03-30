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
    public function listarPedido(int $idUsuario)
    {
        $sql = "
        SELECT 
            p.id, p.titulo, p.estado, p.prioridad, p.fechacreacion, 
            p.fechainicio, p.fechafin, p.num_modificaciones,
            s.nombre AS servicio,
            e.nombreempresa AS empresa
        FROM pedidos p
        INNER JOIN servicios s ON s.id = p.idservicio
        INNER JOIN formulario_pedidos fp ON fp.id = p.idformpedido
        INNER JOIN empresas e ON e.id = fp.idempresa
        WHERE e.idusuario = ?
        ORDER BY p.fechacreacion DESC
    ";
        // Se usa el ? para evitar inyecciones SQL
        return $this->db->query($sql, [$idUsuario])->getResultArray();
    }

    /**
     * Obtiene toda la información detallada de un pedido 
     * (datos del formulario, servicio, empresa y empleado asignado)
     * @param int $idPedido
     * @return array|null
     */
    public function detallePedido(int $idPedido, int $idUsuario): array|null
    {
        $sql = "
        SELECT 
            p.*, 
            fp.titulo AS form_titulo, 
            fp.area, 
            fp.objetivo_comunicacion, 
            fp.descripcion, 
            fp.tipo_requerimiento, 
            fp.canales_difusion, 
            fp.publico_objetivo, 
            fp.tiene_materiales, 
            fp.formatos_solicitados, 
            fp.fecharequerida, 
            s.nombre AS servicio, 
            e.nombreempresa AS empresa, 
            u.nombre AS empleado, 
            u.correo AS correo_empleado
        FROM pedidos p
        INNER JOIN formulario_pedidos fp ON fp.id = p.idformpedido
        LEFT JOIN usuarios u ON u.id = p.idempleado
        INNER JOIN empresas e ON e.id = fp.idempresa
        INNER JOIN servicios s ON s.id = p.idservicio
        WHERE p.id = ? AND e.idusuario = ?
    ";
        // El primer ? es $idPedido, el segundo ? es $idUsuario
        return $this->db->query($sql, [$idPedido, $idUsuario])->getRowArray();
    }

    public function contarPorEstado(string $estado): int
    {
        return $this->where('estado', $estado)->countAllResults();
    }

    public function contarPorEstadoEmpresa(string $estado, int $idEmpresa): int
    {
        return $this->db->table('pedidos p')
            ->join('formulario_pedidos fp', 'fp.id = p.idformpedido')
            ->where('p.estado', $estado)
            ->where('fp.idempresa', $idEmpresa)
            ->countAllResults();

    }
}