<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-primary" href="<?php echo $this->url('custos/create'); ?>">Novo custo</a>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($custos)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhum custo cadastrado</h2>
            <p class="text-muted mb-3">Registre custos planejados, operacionais ou realizados por projeto.</p>
            <a class="btn btn-primary" href="<?php echo $this->url('custos/create'); ?>">Cadastrar custo</a>
        </div>
    </div>
<?php else: ?>
    <div class="card app-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Descricao</th>
                        <th>Projeto</th>
                        <th>Tipo</th>
                        <th>Previsto</th>
                        <th>Real</th>
                        <th>Data</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($custos as $custo): ?>
                        <tr>
                            <td>
                                <strong><?php echo $this->e($custo->getDescricao()); ?></strong>
                                <?php if ($custo->getAtividade()): ?>
                                    <div class="text-muted small">Atividade: <?php echo $this->e($custo->getAtividade()->getTitulo()); ?></div>
                                <?php endif; ?>
                                <?php if ($custo->getRecurso()): ?>
                                    <div class="text-muted small">Recurso: <?php echo $this->e($custo->getRecurso()->getNome()); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $this->e($custo->getProjeto()->getNome()); ?></td>
                            <td><span class="badge text-bg-secondary"><?php echo $this->e($custo->getTipo()); ?></span></td>
                            <td>R$ <?php echo $this->e(number_format($custo->getValorPrevisto(), 2, ',', '.')); ?></td>
                            <td><?php echo $custo->getValorReal() === null ? '-' : 'R$ ' . $this->e(number_format($custo->getValorReal(), 2, ',', '.')); ?></td>
                            <td><?php echo $this->formatDate($custo->getDataLancamento()); ?></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo $this->url('custos/edit', ['id' => $custo->getId()]); ?>">Editar</a>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalExcluirCusto<?php echo $this->e($custo->getId()); ?>">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php foreach ($custos as $custo): ?>
        <div class="modal fade" id="modalExcluirCusto<?php echo $this->e($custo->getId()); ?>" tabindex="-1" aria-labelledby="modalExcluirCustoLabel<?php echo $this->e($custo->getId()); ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalExcluirCustoLabel<?php echo $this->e($custo->getId()); ?>">Confirmar exclusao</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja excluir o custo <?php echo $this->e($custo->getDescricao()); ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form method="post" action="<?php echo $this->url('custos/delete', ['id' => $custo->getId()]); ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
