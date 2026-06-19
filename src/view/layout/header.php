<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->e($pageTitle); ?> | Gerenciamento de Projetos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
        }

        .app-card {
            border-radius: .5rem;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?php echo $this->url('dashboard'); ?>">Gerenciamento de Projetos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPrincipal" aria-controls="navbarPrincipal" aria-expanded="false" aria-label="Alternar navegacao">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarPrincipal">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->isActive('dashboard', $currentRoute); ?>" href="<?php echo $this->url('dashboard'); ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->isActive('projetos', $currentRoute); ?>" href="<?php echo $this->url('projetos'); ?>">Projetos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->isActive('atividades', $currentRoute); ?>" href="<?php echo $this->url('atividades'); ?>">Atividades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->isActive('participantes', $currentRoute); ?>" href="<?php echo $this->url('participantes'); ?>">Participantes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->isActive('recursos', $currentRoute); ?>" href="<?php echo $this->url('recursos'); ?>">Recursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->isActive('riscos', $currentRoute); ?>" href="<?php echo $this->url('riscos'); ?>">Riscos</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Sistema academico</p>
            <h1 class="h3 mb-0"><?php echo $this->e($pageTitle); ?></h1>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $this->e($flash['type']); ?> alert-dismissible fade show" role="alert">
            <?php echo $this->e($flash['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>
