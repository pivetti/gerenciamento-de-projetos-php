<?php
$isEdit = $participante->getId() !== null;
$action = $this->url($actionRoute, $actionParams);
$usuarioAtual = $isEdit ? $participante->getUsuario()->getId() : null;
$projetoAtual = $isEdit ? $participante->getProjeto()->getId() : null;
?>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($usuarios) || empty($projetos)): ?>
    <div class="alert alert-warning">E necessario existir pelo menos um usuario e um projeto para cadastrar participantes.</div>
<?php endif; ?>

<div class="card app-card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?php echo $this->e($action); ?>" class="needs-validation-js" data-validar="participante" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="usuarioId" class="form-label">Usuario *</label>
                    <select class="form-select" id="usuarioId" name="usuarioId" required>
                        <option value="">Selecione</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo $this->e($usuario->getId()); ?>" <?php echo $this->selected((string) $usuario->getId(), (string) $usuarioAtual); ?>>
                                <?php echo $this->e($usuario->getNome()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="projetoId" class="form-label">Projeto *</label>
                    <select class="form-select" id="projetoId" name="projetoId" required>
                        <option value="">Selecione</option>
                        <?php foreach ($projetos as $projeto): ?>
                            <option value="<?php echo $this->e($projeto->getId()); ?>" <?php echo $this->selected((string) $projeto->getId(), (string) $projetoAtual); ?>>
                                <?php echo $this->e($projeto->getNome()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="funcaoNoProjeto" class="form-label">Funcao no projeto *</label>
                    <input type="text" class="form-control" id="funcaoNoProjeto" name="funcaoNoProjeto" value="<?php echo $this->e($participante->getFuncaoNoProjeto()); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="papelAcesso" class="form-label">Papel de acesso *</label>
                    <select class="form-select" id="papelAcesso" name="papelAcesso" required>
                        <?php foreach ($papelOptions as $papel): ?>
                            <option value="<?php echo $this->e($papel->value); ?>" <?php echo $this->selected($papel->value, $participante->getPapelAcesso()); ?>>
                                <?php echo $this->e($papel->value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="1" id="ativo" name="ativo" <?php echo $this->checked($participante->getAtivo()); ?>>
                        <label class="form-check-label" for="ativo">Ativo</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-secondary" href="<?php echo $this->url('participantes'); ?>">Cancelar</a>
                <button type="submit" class="btn btn-primary" <?php echo (empty($usuarios) || empty($projetos)) ? 'disabled' : ''; ?>><?php echo $isEdit ? 'Atualizar' : 'Salvar'; ?></button>
            </div>
        </form>
    </div>
</div>
