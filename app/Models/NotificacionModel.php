<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    //Nombre de la tabla en la BD
    protected $table = "notificaciones";
    // Campo clave primaria
    protected $primaryKey = "id";
    // Tipo de retorno de datos (array)
    protected $returnType = "array";

    // Campos permitidos para insert/update
    protected $allowedFields = ['idpedido', 'idusuario', 'asunto', 'mensaje', 'tipoalerta', 'fechaenvio'];

    /**
     * Obtiene todas las notificaciones de un usuario específico, incluyendo información del pedido asociado
     * @param mixed $idUsuario $idUsuario ID del usuario cliente
     * @return array Array de notificaciones
     */
    public function listarPorUsuario($idUsuario)
    {
        $sql = '
            SELECT 
                n.id, 
                p.titulo AS pedido,  
                n.asunto, 
                n.mensaje, 
                n.fechaenvio, 
                n.tipoalerta
            FROM notificaciones n
            INNER JOIN pedidos p ON p.id = n.idpedido
            WHERE n.idusuario = ?
            ORDER BY n.fechaenvio DESC
        ';

        return $this->db->query($sql, [$idUsuario])->getResultArray();
    }

    /**
     * Cuenta el total de notificaciones asociadas a un usuario. 
     * Útil para mostrar badge de contador en interfaz
     * @param mixed $idUsuario  $idUsuario ID del usuario cliente
     * @return int|string Cantidad total de notificaciones del usuario
     */
    public function contarNotificaciones($idUsuario)
    {
        return $this->where('idusuario', $idUsuario)->countAllResults();
    }
}