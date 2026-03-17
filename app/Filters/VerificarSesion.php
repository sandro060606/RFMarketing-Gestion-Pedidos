<?php

namespace App\Filters;

/**
 * Este filtro actúa en las rutas protegidas.
 * Se ejecuta ANTES (before) de que cualquier controlador sea invocado
 * Su función es evitar que usuarios no autenticados accedan a los paneles
 */

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class VerificarSesion implements FilterInterface
{
    /**
     * Lógica de pre-filtrado
     * @param RequestInterface $request  Información de la petición entrante.
     * @param mixed $arguments
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Accedemos a la sesión actual del sistema.
        $session = session();

        // 2. Verificamos si existe la clave 'rol' en la sesión.
        if (!$session->get('autenticado')) { 
            // 3. Cortamos el flujo: Redirigimos al formulario de login.
            return redirect()->to(base_url('login'))
                             ->with('error', 'Sesión expirada o no iniciada. Por favor, identifíquese.');
        }
    }

    /**
     * Lógica de post-filtrado.
     * -----------------------
     * Se ejecuta DESPUÉS de que el controlador haya terminado su trabajo
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
    
}