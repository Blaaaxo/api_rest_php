<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Bramus\Router\Router;

// Cargar las variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Instanciar router
$router = new Router();

// Ruta de documentación
$router->get('/docs', function () {
    require_once __DIR__ . '/../public/docs/index.html';
});

// Cargar las rutas de autenticación
require_once __DIR__ . '/../src/Routes/AuthRoutes.php';

// Cargar las rutas de usuarios
require_once __DIR__ . '/../src/Routes/UserRoutes.php';

// Cargar las rutas de contraseñas
require_once __DIR__ . '/../src/Routes/PasswordRoutes.php';

// Iniciar router
$router->run();