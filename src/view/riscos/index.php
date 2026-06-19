<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalNovoRisco">Novo risco</button>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($riscos)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhum risco cadastrado</h2>
            <p class="text-muted mb-0">Registre riscos para acompanhar probabilidade, impacto e criticidade.</p>
        </div>
    </div>
<?php else: ?>
    <div class="card app-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titulo</th>
                        <th>Projeto</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Probabilidade</th>
                        <th>Impacto</th>
                        <th>Criticidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riscos as $risco): ?>
                        <tr>
                            <td>
                                <strong><?php echo $this->e($risco->getTitulo()); ?></strong>
                                <div class="text-muted small"><?php echo $this->e($risco->getDescricao() ?? 'Sem descricao'); ?></div>
                            </td>
                            <td><?php echo $this->e($risco->getProjeto()->getNome()); ?></td>
                            <td><?php echo $this->e($risco->getCategoria()); ?></td>
                            <td><span class="badge text-bg-secondary"><?php echo $this->e($risco->getStatus()); ?></span></td>
                            <td><?php echo $this->e($risco->getProbabilidade()); ?></td>
                            <td><?php echo $this->e($risco->getImpacto()); ?></td>
                            <td><?php echo $this->e($risco->getCriticidade()); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<div class="modal fade" id="modalNovoRisco" tabindex="-1" aria-labelledby="modalNovoRiscoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="<?php echo $this->url('riscos/store'); ?>" class="needs-validation-js" data-validar="risco" novalidate>
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="modalNovoRiscoLabel">Novo risco</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <?php if (empty($projetos)): ?>
                        <div class="alert alert-warning">Cadastre um projeto antes de criar riscos.</div>
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
                            <label for="categoria" class="form-label">Categoria *</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <?php foreach ($categoriaOptions as $categoria): ?>
                                    <option value="<?php echo $this->e($categoria->value); ?>"><?php echo $this->e($categoria->value); ?></option>
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
                        <div class="col-md-2">
                            <label for="probabilidade" class="form-label">Prob.</label>
                            <input type="number" class="form-control" id="probabilidade" name="probabilidade" min="1" max="5" value="1">
                        </div>
                        <div class="col-md-2">
                            <label for="impacto" class="form-label">Impacto</label>
                            <input type="number" class="form-control" id="impacto" name="impacto" min="1" max="5" value="1">
                        </div>
                        <div class="col-12">
                            <label for="descricao" class="form-label">Descricao</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="estrategiaResposta" class="form-label">Estrategia de resposta</label>
                            <input type="text" class="form-control" id="estrategiaResposta" name="estrategiaResposta">
                        </div>
                        <div class="col-md-6">
                            <label for="planoMitigacao" class="form-label">Plano de mitigacao</label>
                            <input type="text" class="form-control" id="planoMitigacao" name="planoMitigacao">
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
