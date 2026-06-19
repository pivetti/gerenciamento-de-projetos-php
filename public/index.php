<?php

declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/controller/BaseController.php';
require_once dirname(__DIR__) . '/src/controller/AuthController.php';
require_once dirname(__DIR__) . '/src/controller/DashboardController.php';
require_once dirname(__DIR__) . '/src/controller/ProjetoController.php';
require_once dirname(__DIR__) . '/src/controller/AtividadeController.php';
require_once dirname(__DIR__) . '/src/controller/ParticipanteController.php';
require_once dirname(__DIR__) . '/src/controller/RecursoController.php';
require_once dirname(__DIR__) . '/src/controller/RiscoController.php';
require_once dirname(__DIR__) . '/src/controller/UsuarioController.php';
require_once dirname(__DIR__) . '/src/controller/CustoController.php';

use controller\AtividadeController;
use controller\AuthController;
use controller\CustoController;
use controller\DashboardController;
use controller\ParticipanteController;
use controller\ProjetoController;
use controller\RecursoController;
use controller\RiscoController;
use controller\UsuarioController;

$route = trim((string) ($_GET['route'] ?? 'dashboard'), '/');
$route = $route === '' ? 'dashboard' : $route;

$routes = [
    'login' => [AuthController::class, 'loginForm'],
    'cadastro' => [AuthController::class, 'cadastroForm'],
    'auth/login' => [AuthController::class, 'login'],
    'auth/cadastro' => [AuthController::class, 'cadastro'],
    'logout' => [AuthController::class, 'logout'],
    'dashboard' => [DashboardController::class, 'index'],
    'usuarios' => [UsuarioController::class, 'index'],
    'usuarios/create' => [UsuarioController::class, 'create'],
    'usuarios/store' => [UsuarioController::class, 'store'],
    'usuarios/edit' => [UsuarioController::class, 'edit'],
    'usuarios/update' => [UsuarioController::class, 'update'],
    'usuarios/delete' => [UsuarioController::class, 'delete'],
    'projetos' => [ProjetoController::class, 'index'],
    'projetos/create' => [ProjetoController::class, 'create'],
    'projetos/store' => [ProjetoController::class, 'store'],
    'projetos/edit' => [ProjetoController::class, 'edit'],
    'projetos/update' => [ProjetoController::class, 'update'],
    'projetos/delete' => [ProjetoController::class, 'delete'],
    'atividades' => [AtividadeController::class, 'index'],
    'atividades/create' => [AtividadeController::class, 'create'],
    'atividades/store' => [AtividadeController::class, 'store'],
    'atividades/edit' => [AtividadeController::class, 'edit'],
    'atividades/update' => [AtividadeController::class, 'update'],
    'atividades/delete' => [AtividadeController::class, 'delete'],
    'participantes' => [ParticipanteController::class, 'index'],
    'participantes/create' => [ParticipanteController::class, 'create'],
    'participantes/store' => [ParticipanteController::class, 'store'],
    'participantes/edit' => [ParticipanteController::class, 'edit'],
    'participantes/update' => [ParticipanteController::class, 'update'],
    'participantes/delete' => [ParticipanteController::class, 'delete'],
    'recursos' => [RecursoController::class, 'index'],
    'recursos/create' => [RecursoController::class, 'create'],
    'recursos/store' => [RecursoController::class, 'store'],
    'recursos/edit' => [RecursoController::class, 'edit'],
    'recursos/update' => [RecursoController::class, 'update'],
    'recursos/delete' => [RecursoController::class, 'delete'],
    'riscos' => [RiscoController::class, 'index'],
    'riscos/create' => [RiscoController::class, 'create'],
    'riscos/store' => [RiscoController::class, 'store'],
    'riscos/edit' => [RiscoController::class, 'edit'],
    'riscos/update' => [RiscoController::class, 'update'],
    'riscos/delete' => [RiscoController::class, 'delete'],
    'custos' => [CustoController::class, 'index'],
    'custos/create' => [CustoController::class, 'create'],
    'custos/store' => [CustoController::class, 'store'],
    'custos/edit' => [CustoController::class, 'edit'],
    'custos/update' => [CustoController::class, 'update'],
    'custos/delete' => [CustoController::class, 'delete'],
];

$publicRoutes = ['login', 'cadastro', 'auth/login', 'auth/cadastro'];

if (!isset($routes[$route])) {
    $_SESSION['flash'] = [
        'type' => 'warning',
        'message' => 'Pagina nao encontrada. Voce foi redirecionado para o dashboard.',
    ];

    header('Location: index.php?route=dashboard');
    exit;
}

if (!isset($_SESSION['usuario_logado']['id']) && !in_array($route, $publicRoutes, true)) {
    $_SESSION['flash'] = [
        'type' => 'warning',
        'message' => 'Faca login para acessar o sistema.',
    ];

    header('Location: index.php?route=login');
    exit;
}

[$controllerClass, $method] = $routes[$route];
$controller = new $controllerClass();
$controller->$method();
