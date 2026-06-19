<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalNovoParticipante">Novo participante</button>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($participantes)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhum participante cadastrado</h2>
            <p class="text-muted mb-0">Vincule usuarios existentes aos projetos cadastrados.</p>
        </div>
    </div>
<?php else: ?>
    <div class="card app-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Projeto</th>
                        <th>Funcao</th>
                        <th>Papel</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participantes as $participante): ?>
                        <tr>
                            <td><?php echo $this->e($participante->getUsuario()->getNome()); ?></td>
                            <td><?php echo $this->e($participante->getProjeto()->getNome()); ?></td>
                            <td><?php echo $this->e($participante->getFuncaoNoProjeto()); ?></td>
                            <td><?php echo $this->e($participante->getPapelAcesso()); ?></td>
                            <td>
                                <span class="badge <?php echo $participante->getAtivo() ? 'text-bg-success' : 'text-bg-secondary'; ?>">
                                    <?php echo $participante->getAtivo() ? 'Ativo' : 'Inativo'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<div class="modal fade" id="modalNovoParticipante" tabindex="-1" aria-labelledby="modalNovoParticipanteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?php echo $this->url('participantes/store'); ?>" class="needs-validation-js" data-validar="participante" novalidate>
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="modalNovoParticipanteLabel">Novo participante</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <?php if (empty($usuarios) || empty($projetos)): ?>
                        <div class="alert alert-warning">E necessario existir pelo menos um usuario e um projeto para cadastrar participantes.</div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="usuarioId" class="form-label">Usuario *</label>
                        <select class="form-select" id="usuarioId" name="usuarioId" required>
                            <option value="">Selecione</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo $this->e($usuario->getId()); ?>"><?php echo $this->e($usuario->getNome()); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="projetoId" class="form-label">Projeto *</label>
                        <select class="form-select" id="projetoId" name="projetoId" required>
                            <option value="">Selecione</option>
                            <?php foreach ($projetos as $projeto): ?>
                                <option value="<?php echo $this->e($projeto->getId()); ?>"><?php echo $this->e($projeto->getNome()); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="funcaoNoProjeto" class="form-label">Funcao no projeto *</label>
                        <input type="text" class="form-control" id="funcaoNoProjeto" name="funcaoNoProjeto" required>
                    </div>
                    <div class="mb-3">
                        <label for="papelAcesso" class="form-label">Papel de acesso *</label>
                        <select class="form-select" id="papelAcesso" name="papelAcesso" required>
                            <?php foreach ($papelOptions as $papel): ?>
                                <option value="<?php echo $this->e($papel->value); ?>"><?php echo $this->e($papel->value); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="ativo" name="ativo" checked>
                        <label class="form-check-label" for="ativo">Ativo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" <?php echo (empty($usuarios) || empty($projetos)) ? 'disabled' : ''; ?>>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
