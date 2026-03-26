<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PedidosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                // Pedido 1 — ya completado (el del ejemplo real)
                'idformpedido' => 1,
                'idadmin' => 1,
                'idempleado' => 6,
                'idservicio' => 1,
                'titulo' => 'Recepción Documentaria Admisión 2025-1',
                'prioridad' => 'alta',
                'estado' => 'completado',
                'num_modificaciones' => 0,
                'observacion_revision' => null,
                'fechainicio' => '2026-02-21',
                'horainicio' => '16:00:00',
                'fechafin' => '2026-02-27',
                'horafin' => '17:01:00',
                'fechacreacion' => '2026-02-20 08:30:00',
                'fechacompletado' => '2026-02-27 17:30:00',
                'cancelacionmotivo' => null,
                'fechacancelacion' => null,
            ],
            [
                'idformpedido' => 2,
                'idadmin' => 1,    // admin que recibió el formulario
                'idempleado' => null, // aún sin asignar
                'idservicio' => 1,
                'titulo' => null, // admin aún no lo define
                'prioridad' => null, // admin aún no la define
                'estado' => 'por_aprobar',
                'num_modificaciones' => 0,    // recién llegó, sin modificaciones
                'observacion_revision' => null,
                'fechainicio' => null, // nadie ha empezado
                'horainicio' => null,
                'fechafin' => null,
                'horafin' => null,
                'fechacreacion' => '2026-03-12 08:30:00', // cuando llegó el formulario
                'fechacompletado' => null,
                'cancelacionmotivo' => null,
                'fechacancelacion' => null,
            ],
            [
                'idformpedido' => 3,
                'idadmin' => 1,
                'idempleado' => 6,
                'idservicio' => 1,
                'titulo' => 'Banner Semana de Ciencias UAI 2026',
                'prioridad' => 'media',
                'estado' => 'en_proceso',
                'num_modificaciones' => 0,
                'observacion_revision' => null,
                'fechainicio' => '2026-03-21',
                'horainicio' => '09:00:00',
                'fechafin' => '2026-04-17',
                'horafin' => null,
                'fechacreacion' => '2026-03-20 10:15:00',
                'fechacompletado' => null,
                'cancelacionmotivo' => null,
                'fechacancelacion' => null,
            ],
            [
                'idformpedido' => 4,
                'idadmin' => 1,
                'idempleado' => null, 
                'idservicio' => 1,
                'titulo' => null,
                'prioridad' => null,
                'estado' => 'por_aprobar',
                'num_modificaciones' => 0,
                'observacion_revision' => null,
                'fechainicio' => null,
                'horainicio' => null,
                'fechafin' => null,
                'horafin' => null,
                'fechacreacion' => '2026-03-25 08:00:00',
                'fechacompletado' => null,
                'cancelacionmotivo' => null,
                'fechacancelacion' => null,
            ],
        ];
        $this->db->table('pedidos')->insertBatch($data);
    }
}
