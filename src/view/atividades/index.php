<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalNovaAtividade">Nova atividade</button>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($atividades)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhuma atividade cadastrada</h2>
            <p class="text-muted mb-0">Cadastre atividades vinculadas a projetos existentes.</p>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($atividades as $atividade): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card app-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-2">
                            <h2 class="h5 mb-1"><?php echo $this->e($atividade->getTitulo()); ?></h2>
                            <span class="badge text-bg-secondary align-self-start"><?php echo $this->e($atividade->getStatus()); ?></span>
                        </div>
                        <p class="text-muted small mb-2"><?php echo $this->e($atividade->getProjeto()->getNome()); ?></p>
                        <p class="mb-3"><?php echo $this->e($atividade->getDescricao() ?? 'Sem descricao'); ?></p>
                        <dl class="row small mb-0">
                            <dt class="col-5">Prioridade</dt>
                            <dd class="col-7"><?php echo $this->e($atividade->getPrioridade()); ?></dd>
                            <dt class="col-5">Prazo</dt>
                            <dd class="col-7"><?php echo $this->formatDate($atividade->getPrazo()); ?></dd>
                            <dt class="col-5">Conclusao</dt>
                            <dd class="col-7"><?php echo $this->e($atividade->getPercentualConclusao()); ?>%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="modal fade" id="modalNovaAtividade" tabindex="-1" aria-labelledby="modalNovaAtividadeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="<?php echo $this->url('atividades/store'); ?>" class="needs-validation-js" data-validar="atividade" novalidate>
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="modalNovaAtividadeLabel">Nova atividade</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <?php if (empty($projetos)): ?>
                        <div class="alert alert-warning">Cadastre um projeto antes de criar atividades.</div>
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="titulo" class="form-label">Titulo *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="col-md-4">
                            <label for="projetoId" class="form-label">Projeto *</label>
                            <select class="form-select" id="projetoId" name="projetoId" required>
                                <option value="">Selecione</option>
                                <?php foreach ($projetos as $projeto): ?>
                                    <option value="<?php echo $this->e($projeto->getId()); ?>"><?php echo $this->e($projeto->getNome()); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <?php foreach ($statusOptions as $status): ?>
                                    <option value="<?php echo $this->e($status->value); ?>"><?php echo $this->e($status->value); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="prioridade" class="form-label">Prioridade *</label>
                            <select class="form-select" id="prioridade" name="prioridade" required>
                                <?php foreach ($prioridadeOptions as $prioridade): ?>
                                    <option value="<?php echo $this->e($prioridade->value); ?>"><?php echo $this->e($prioridade->value); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="responsavelId" class="form-label">Responsavel</label>
                            <select class="form-select" id="responsavelId" name="responsavelId">
                                <option value="">Sem responsavel</option>
                                <?php foreach ($participantes as $participante): ?>
                                    <option value="<?php echo $this->e($participante->getId()); ?>">
                                        <?php echo $this->e($participante->getUsuario()->getNome()); ?> - <?php echo $this->e($participante->getProjeto()->getNome()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="dataInicio" class="form-label">Data inicio</label>
                            <input type="date" class="form-control" id="dataInicio" name="dataInicio">
                        </div>
                        <div class="col-md-4">
                            <label for="prazo" class="form-label">Prazo</label>
                            <input type="date" class="form-control" id="prazo" name="prazo">
                        </div>
                        <div class="col-md-4">
                            <label for="percentualConclusao" class="form-label">Conclusao (%)</label>
                            <input type="number" class="form-control" id="percentualConclusao" name="percentualConclusao" min="0" max="100" value="0">
                        </div>
                        <div class="col-12">
                            <label for="descricao" class="form-label">Descricao</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" <?php echo empty($projetos) ? 'disabled' : ''; ?>>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
