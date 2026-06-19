<?php

declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/controller/BaseController.php';
require_once dirname(__DIR__) . '/src/controller/DashboardController.php';
require_once dirname(__DIR__) . '/src/controller/ProjetoController.php';
require_once dirname(__DIR__) . '/src/controller/AtividadeController.php';
require_once dirname(__DIR__) . '/src/controller/ParticipanteController.php';
require_once dirname(__DIR__) . '/src/controller/RecursoController.php';
require_once dirname(__DIR__) . '/src/controller/RiscoController.php';

use controller\AtividadeController;
use controller\DashboardController;
use controller\ParticipanteController;
use controller\ProjetoController;
use controller\RecursoController;
use controller\RiscoController;

$route = trim((string) ($_GET['route'] ?? 'dashboard'), '/');
$route = $route === '' ? 'dashboard' : $route;

$routes = [
    'dashboard' => [DashboardController::class, 'index'],
    'projetos' => [ProjetoController::class, 'index'],
    'projetos/create' => [ProjetoController::class, 'create'],
    'projetos/store' => [ProjetoController::class, 'store'],
    'projetos/edit' => [ProjetoController::class, 'edit'],
    'projetos/update' => [ProjetoController::class, 'update'],
    'projetos/delete' => [ProjetoController::class, 'delete'],
    'atividades' => [AtividadeController::class, 'index'],
    'atividades/store' => [AtividadeController::class, 'store'],
    'participantes' => [ParticipanteController::class, 'index'],
    'participantes/store' => [ParticipanteController::class, 'store'],
    'recursos' => [RecursoController::class, 'index'],
    'recursos/store' => [RecursoController::class, 'store'],
    'riscos' => [RiscoController::class, 'index'],
    'riscos/store' => [RiscoController::class, 'store'],
];

if (!isset($routes[$route])) {
    $_SESSION['flash'] = [
        'type' => 'warning',
        'message' => 'Pagina nao encontrada. Voce foi redirecionado para o dashboard.',
    ];

    header('Location: index.php?route=dashboard');
    exit;
}

[$controllerClass, $method] = $routes[$route];
$controller = new $controllerClass();
$controller->$method();
