<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <div class="brand-mark">PH</div>
        <span class="auth-brand">ProjectHub</span>
    </div>
    <h1 class="auth-title mb-2">Entrar no sistema</h1>
    <p class="auth-subtitle mb-0">Acesse o painel do ProjectHub.</p>
</div>

<form method="post" action="<?php echo $this->url('auth/login'); ?>" class="needs-validation-js" data-validar="login" novalidate>
    <div class="mb-3">
        <label for="email" class="form-label">Email *</label>
        <input type="email" class="form-control form-control-lg" id="email" name="email" required autofocus>
    </div>
    <div class="mb-4">
        <label for="senha" class="form-label">Senha *</label>
        <input type="password" class="form-control form-control-lg" id="senha" name="senha" required>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100">Entrar</button>
</form>

<div class="text-center mt-4">
    <span class="text-muted">Ainda nao tem conta?</span>
    <a class="fw-semibold" href="<?php echo $this->url('cadastro'); ?>">Criar cadastro</a>
</div>
