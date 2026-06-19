<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-primary" href="<?php echo $this->url('usuarios/create'); ?>">Novo usuario</a>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($usuarios)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhum usuario cadastrado</h2>
            <p class="text-muted mb-3">Cadastre usuarios para vincular participantes aos projetos.</p>
            <a class="btn btn-primary" href="<?php echo $this->url('usuarios/create'); ?>">Cadastrar usuario</a>
        </div>
    </div>
<?php else: ?>
    <div class="card app-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Perfil</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><strong><?php echo $this->e($usuario->getNome()); ?></strong></td>
                            <td><?php echo $this->e($usuario->getEmail()); ?></td>
                            <td><?php echo $this->e($usuario->getTelefone() ?? '-'); ?></td>
                            <td><span class="badge text-bg-secondary"><?php echo $this->e($usuario->getPerfil()); ?></span></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo $this->url('usuarios/edit', ['id' => $usuario->getId()]); ?>">Editar</a>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalExcluirUsuario<?php echo $this->e($usuario->getId()); ?>">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php foreach ($usuarios as $usuario): ?>
        <div class="modal fade" id="modalExcluirUsuario<?php echo $this->e($usuario->getId()); ?>" tabindex="-1" aria-labelledby="modalExcluirUsuarioLabel<?php echo $this->e($usuario->getId()); ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalExcluirUsuarioLabel<?php echo $this->e($usuario->getId()); ?>">Confirmar exclusao</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja excluir o usuario <?php echo $this->e($usuario->getNome()); ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form method="post" action="<?php echo $this->url('usuarios/delete', ['id' => $usuario->getId()]); ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
