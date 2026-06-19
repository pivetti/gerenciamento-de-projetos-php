<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <div class="brand-mark">PH</div>
        <span class="auth-brand">ProjectHub</span>
    </div>
    <h1 class="auth-title mb-2">Criar cadastro</h1>
    <p class="auth-subtitle mb-0">Informe seus dados para acessar o ProjectHub.</p>
</div>

<form method="post" action="<?php echo $this->url('auth/cadastro'); ?>" class="needs-validation-js" data-validar="cadastro" novalidate>
    <div class="mb-3">
        <label for="nome" class="form-label">Nome *</label>
        <input type="text" class="form-control" id="nome" name="nome" required autofocus>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email *</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="senha" class="form-label">Senha *</label>
        <input type="password" class="form-control" id="senha" name="senha" minlength="6" required>
        <div class="form-text">Use pelo menos 6 caracteres.</div>
    </div>
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(99) 99999-9999" inputmode="numeric" maxlength="15">
    </div>
    <div class="mb-4">
        <label for="perfil" class="form-label">Perfil *</label>
        <select class="form-select" id="perfil" name="perfil" required>
            <?php foreach ($perfilOptions as $perfil): ?>
                <option value="<?php echo $this->e($perfil->value); ?>"><?php echo $this->e($perfil->value); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100">Cadastrar</button>
</form>

<div class="text-center mt-4">
    <span class="text-muted">Ja possui conta?</span>
    <a class="fw-semibold" href="<?php echo $this->url('login'); ?>">Entrar</a>
</div>
