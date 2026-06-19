<?php
$isEdit = $usuario->getId() !== null;
$action = $this->url($actionRoute, $actionParams);
?>

<div class="card app-card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?php echo $this->e($action); ?>" class="needs-validation-js" data-validar="usuario" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $this->e($usuario->getNome()); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $this->e($usuario->getEmail()); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="senha" class="form-label">Senha <?php echo $isEdit ? '' : '*'; ?></label>
                    <input type="password" class="form-control" id="senha" name="senha" <?php echo $isEdit ? '' : 'required'; ?>>
                    <?php if ($isEdit): ?>
                        <div class="form-text">Deixe em branco para manter a senha atual.</div>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo $this->e($usuario->getTelefone()); ?>" placeholder="(99) 99999-9999" inputmode="numeric" maxlength="15">
                </div>
                <div class="col-md-4">
                    <label for="perfil" class="form-label">Perfil *</label>
                    <select class="form-select" id="perfil" name="perfil" required>
                        <?php foreach ($perfilOptions as $perfil): ?>
                            <option value="<?php echo $this->e($perfil->value); ?>" <?php echo $this->selected($perfil->value, $usuario->getPerfil()); ?>>
                                <?php echo $this->e($perfil->value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-secondary" href="<?php echo $this->url('usuarios'); ?>">Cancelar</a>
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Atualizar' : 'Salvar'; ?></button>
            </div>
        </form>
    </div>
</div>
