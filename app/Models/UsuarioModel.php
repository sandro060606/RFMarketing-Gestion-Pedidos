<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nombre', 'apellidos', 'correo', 'telefono',
        'tipodoc', 'numerodoc', 'usuario', 'clave',
        'rol', 'idarea', 'esresponsable', 'estado'
    ];
// Busca usuario activo por nombre de usuario para el login
    public function buscarPorUsuario($usuario)
    {
        return $this->where('usuario', $usuario)
                    ->where('estado', true)
                    ->first();
    }
}