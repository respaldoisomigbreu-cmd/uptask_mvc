<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\loginController;
use Controllers\DashboardController;
use Controllers\TareaControllers;
use MVC\Router;
$router = new Router();

// Login
$router->get('/', [loginController::class, 'login']);
$router->post('/', [loginController::class, 'login']);
$router->get('/logout', [loginController::class, 'logout']);

//crear cuenta
$router->get('/crear', [loginController::class, 'crear']);
$router->post('/crear', [loginController::class, 'crear']);

//formulario olvide mi password
$router->get('/olvide', [loginController::class, 'olvide']);
$router->post('/olvide', [loginController::class, 'olvide']);

//Restablecer mi password
$router->get('/reestablecer', [loginController::class, 'reestablecer']);
$router->post('/reestablecer', [loginController::class, 'reestablecer']);

//confirmas cuenta
$router->get('/mensaje', [loginController::class, 'mensaje']);
$router->get('/confirmar', [loginController::class, 'confirmar']);

//ZONA PRIVADA DE PROYECTOS
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->get('/proyecto', [DashboardController::class, 'proyecto']);
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);
$router->get('/cambiar-password', [DashboardController::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboardController::class, 'cambiar_password']);


// API para tareas
$router->get('/api/tareas', [TareaControllers::class, 'index']);
$router->post('/api/tareas', [TareaControllers::class, 'crear']);
$router->post('/api/tareas/actualizar', [TareaControllers::class, 'actualizar']);
$router->post('/api/tareas/eliminar', [TareaControllers::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();