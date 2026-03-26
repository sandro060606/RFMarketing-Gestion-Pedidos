<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpresaModel extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nombreempresa', 'idusuario', 'ruc', 'correo', 'telefono'];

    private array $colores = [
        '#FF6B6B',
        '#FFD93D',
        '#6BCB77',
        '#4D96FF',
        '#C77DFF',
        '#FF9F43'
    ];

    public function obtenerConStats(): array
    {
        $empresas = $this->findAll();
        $pedidoModel = new PedidoModel();

        foreach ($empresas as $i => &$empresa) {
            $color = $this->colores[$i % count($this->colores)];
            $empresa['color'] = $color;
            $empresa['inicial'] = strtoupper(substr($empresa['nombreempresa'], 0, 1));
            $empresa['por_aprobar'] = $pedidoModel->contarPorEstadoEmpresa('por_aprobar', $empresa['id']);
            $empresa['pendientes'] = $pedidoModel->contarPorEstadoEmpresa('por_aprobar', $empresa['id']);
            $empresa['activos'] = $pedidoModel->contarPorEstadoEmpresa('en_proceso', $empresa['id']);
            $empresa['completados'] = $pedidoModel->contarPorEstadoEmpresa('completado', $empresa['id']);
        }

        return $empresas;
    }
}