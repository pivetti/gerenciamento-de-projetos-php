<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->e($pageTitle); ?> | ProjectHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
</head>
<body class="<?php echo $layout === 'auth' ? 'auth-body' : 'app-body'; ?>">
<?php if ($layout === 'auth'): ?>
    <main class="auth-page">
        <div class="auth-card">
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $this->e($flash['type']); ?> alert-dismissible fade show" role="alert">
                    <?php echo $this->e($flash['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            <?php endif; ?>
<?php else: ?>
    <?php
        $navItems = [
            'dashboard' => 'Dashboard',
            'projetos' => 'Projetos',
            'atividades' => 'Atividades',
            'participantes' => 'Participantes',
            'recursos' => 'Recursos',
            'riscos' => 'Riscos',
            'custos' => 'Custos',
        ];
    ?>
    <div class="app-shell">
        <aside class="app-sidebar d-none d-lg-flex">
            <a class="sidebar-brand" href="<?php echo $this->url('dashboard'); ?>">
                <span class="brand-mark">PH</span>
                <span>ProjectHub</span>
            </a>
            <nav class="sidebar-nav">
                <?php foreach ($navItems as $route => $label): ?>
                    <a class="sidebar-link <?php echo $this->isActive($route, $currentRoute); ?>" href="<?php echo $this->url($route); ?>">
                        <?php echo $this->e($label); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-user">
                <span class="small text-muted">Logado como</span>
                <strong><?php echo $this->e($usuarioLogado['nome'] ?? 'Usuario'); ?></strong>
                <a class="btn btn-outline-secondary btn-sm rounded-pill mt-3" href="<?php echo $this->url('logout'); ?>">Sair</a>
            </div>
        </aside>

        <div class="app-content">
            <nav class="mobile-navbar navbar navbar-expand-lg d-lg-none">
                <div class="container-fluid">
                    <a class="navbar-brand fw-semibold" href="<?php echo $this->url('dashboard'); ?>">ProjectHub</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="Alternar navegacao">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mobileNav">
                        <ul class="navbar-nav mt-3">
                            <?php foreach ($navItems as $route => $label): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $this->isActive($route, $currentRoute); ?>" href="<?php echo $this->url($route); ?>">
                                        <?php echo $this->e($label); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $this->url('logout'); ?>">Sair</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="app-main">
                <div class="page-header">
                    <div>
                        <p class="page-kicker">ProjectHub</p>
                        <h1><?php echo $this->e($pageTitle); ?></h1>
                    </div>
                    <div class="user-chip d-none d-md-flex">
                        <span><?php echo $this->e($usuarioLogado['nome'] ?? 'Usuario'); ?></span>
                    </div>
                </div>

                <?php if ($flash): ?>
                    <div class="alert alert-<?php echo $this->e($flash['type']); ?> alert-dismissible fade show" role="alert">
                        <?php echo $this->e($flash['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif; ?>
<?php endif; ?>
