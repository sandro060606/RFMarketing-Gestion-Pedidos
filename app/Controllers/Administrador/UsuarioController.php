<?php

namespace App\Controllers\Administrador;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\AreaModel;


class UsuarioController extends Controller
{
    /**
     * Muestra la vista principal de usuarios.
     *
     * @return string HTML de la vista.
     */
    public function index(): string
    {
        $areaModel = new AreaModel();

        return view('administrador/usuarios/index', [
            'titulo'       => 'Usuarios',
            'tituloPagina' => 'USUARIOS',
            'paginaActual' => 'usuarios',
            'areas'        => $areaModel->obtenerActivas(),
        ]);
    }

    /**
     * Devuelve JSON con la lista de usuarios y área asociada.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function listar()
    {
        $db       = \Config\Database::connect();
        $usuarios = $db->table('usuarios u')
            ->select('u.*, a.nombre as area_nombre')
            ->join('areas a', 'a.id = u.idarea', 'left')
            ->get()->getResultArray();

        foreach ($usuarios as &$u) {
            $u['estado'] = ($u['estado'] === true || $u['estado'] === 't' || $u['estado'] == 1) ? 1 : 0;
        }

        return $this->response->setJSON($usuarios);
    }

    /**
     * Registra usuario; si el rol es cliente también crea empresa y responsable.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function registrar()
    {
        $model          = new UsuarioModel();
        $datos          = $this->request->getJSON(true);
        $datos['clave'] = password_hash($datos['clave'], PASSWORD_DEFAULT);

        $id = $model->insert($datos, true);

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al registrar']);
        }

        if ($datos['rol'] === 'cliente') {
            $empresaModel = new \App\Models\EmpresaModel();
            $idEmpresa    = $empresaModel->insert([
                'nombreempresa' => $datos['razonsocial'],
                'idusuario'     => $id,
                'ruc'           => $datos['numerodoc'] ?? '',
                'correo'        => $datos['correo'],
                'telefono'      => $datos['telefono'] ?? '',
            ], true);

            $db = \Config\Database::connect();
            $db->table('responsables_empresa')->insert([
                'idusuario'    => $id,
                'idempresa'    => $idEmpresa,
                'fecha_inicio' => date('Y-m-d H:i:s'),
                'estado'       => 'activo',
            ]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Usuario registrado correctamente']);
    }

    /**
     * Retorna datos de usuario por ID en formato JSON.
     *
     * @param int|null $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function buscar($id = null)
    {
        $model   = new UsuarioModel();
        $usuario = $model->find($id);
        $db      = \Config\Database::connect();

        if ($usuario['rol'] === 'cliente') {
            $empresa                   = $db->table('empresas')->where('idusuario', $id)->get()->getRowArray();
            $usuario['empresa_nombre'] = $empresa['nombreempresa'] ?? '';
        }

        return $this->response->setJSON($usuario);
    }

    /**
     * Actualiza datos del usuario. Hash de contraseña opcional.
     * También actualiza razón social si se envía.
     *
     * @param int|null $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function actualizar($id = null)
    {
        $model = new UsuarioModel();
        $datos = $this->request->getJSON(true);

        if (!empty($datos['clave'])) {
            $datos['clave'] = password_hash($datos['clave'], PASSWORD_DEFAULT);
        } else {
            unset($datos['clave']);
        }

        if (!$model->update($id, $datos)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar']);
        }

        if (isset($datos['razonsocial'])) {
            $db = \Config\Database::connect();
            $db->table('empresas')
                ->where('idusuario', $id)
                ->update(['nombreempresa' => $datos['razonsocial']]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Usuario actualizado correctamente']);
    }

    /**
     * Cambia estado de usuario (activo/inactivo) con reglas de rol.
     * Mantiene historial en tabla responsables_empresa para cliente.
     *
     * @param int|null $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function toggleEstado($id = null)
    {
        $model   = new UsuarioModel();
        $usuario = $model->find($id);

        if ($usuario['rol'] === 'administrador') {
            return $this->response->setJSON(['success' => false, 'message' => 'No se puede deshabilitar un administrador']);
        }

        $actual = ($usuario['estado'] === true || $usuario['estado'] === 't' || $usuario['estado'] == 1);
        $nuevo  = !$actual;

        $model->update($id, ['estado' => $nuevo]);

        if ($usuario['rol'] === 'cliente') {
            $db      = \Config\Database::connect();
            $empresa = $db->table('empresas')->where('idusuario', $id)->get()->getRowArray();

            if ($empresa) {
                if (!$nuevo) {
                    $db->table('responsables_empresa')
                        ->where('idempresa', $empresa['id'])
                        ->where('estado', 'activo')
                        ->update(['fecha_fin' => date('Y-m-d H:i:s'), 'estado' => 'inactivo']);
                } else {
                    $db->table('responsables_empresa')->insert([
                        'idusuario'    => $id,
                        'idempresa'    => $empresa['id'],
                        'fecha_inicio' => date('Y-m-d H:i:s'),
                        'estado'       => 'activo',
                    ]);
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $nuevo ? 'Usuario habilitado' : 'Usuario deshabilitado'
        ]);
    }
}