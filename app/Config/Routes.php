<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// AUTENTICACION

// Página de inicio redirige al login
$routes->get('/', 'Autenticacion\AuthController::login');
// Muestra el formulario de login
$routes->get('/login', 'Autenticacion\AuthController::login');
// Recibe y procesa los datos del formulario
$routes->post('/login', 'Autenticacion\AuthController::autenticar');
// Destruye la sesión y regresa al login
$routes->get('/logout', 'Autenticacion\AuthController::logout');

/* GRUPO ADMINISTRADOR */
$routes->group('admin', ['filter' => 'sesion'], function ($routes) {
    $routes->get('panel', 'Administrador\DashboardController::index'); // ← Administrador
});

/* USUARIOS */
$routes->get('admin/usuarios',                      'Administrador\UsuarioController::index');
$routes->get('admin/usuarios/listar',               'Administrador\UsuarioController::listar');
$routes->post('admin/usuarios/registrar',           'Administrador\UsuarioController::registrar');
$routes->get('admin/usuarios/buscar/(:num)',        'Administrador\UsuarioController::buscar/$1');
$routes->put('admin/usuarios/actualizar/(:num)',    'Administrador\UsuarioController::actualizar/$1');
$routes->put('admin/usuarios/toggle/(:num)',        'Administrador\UsuarioController::toggleEstado/$1');


/* GRUPO CLIENTE */
$routes->group('cliente', ['filter' => 'sesion'], function ($routes) {
    //Lista de Pedidos
    $routes->get('mis-pedidos', 'Cliente\MisPedidosController::index');
    //Detalle Pedido (id) - Prueba
    $routes->get('mis-pedidos/(:num)', 'Cliente\MisPedidosController::detalle/$1');
});

/* GRUPO EMPLEADO  */
$routes->group('empleado', ['filter' => 'sesion'], function ($routes) {
    $routes->get('mis-pedidos', 'Empleado\PedidosController::index');
});