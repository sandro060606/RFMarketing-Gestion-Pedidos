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

/* GRUPO CLIENTE */
$routes->group('cliente', ['filter' => 'sesion'], function ($routes) {
    $routes->get('/', 'Cliente\MisPedidosController::index');
    $routes->get('pedidos/listar', 'Cliente\MisPedidosController::listarPedido');
    $routes->get('pedidos/detalle/(:num)', 'Cliente\MisPedidosController::detallePedido/$1');

    //Prueba Notificaciones Get
    $routes->get('notificaciones/listar', 'Cliente\NotificacionesController::getNotificaciones');
});

/* GRUPO EMPLEADO  */
$routes->group('empleado', ['filter' => 'sesion'], function ($routes) {
    $routes->get('mis-pedidos', 'Empleado\PedidosController::index');
});