<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'fornecedores';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

try {
    $sql = "
        SELECT
            f.*,
            tf.nome AS tipo_fornecedor
        FROM fornecedores f
        INNER JOIN tipos_fornecedor tf
            ON f.tipo_fornecedor_id = tf.id
        WHERE f.id = :id
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);

    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: lista.php');
        exit;
    }

    $sql_equipamentos = "
        SELECT
            e.id,
            e.codigo_inventario,
            e.designacao,
            e.marca,
            e.modelo
        FROM equipamento_fornecedor ef
        INNER JOIN equipamentos e
            ON ef.equipamento_id = e.id
        WHERE ef.fornecedor_id = :id
        AND e.ativo = 1
        ORDER BY e.codigo_inventario ASC
    ";

    $stmt_equipamentos = $ligacao->prepare($sql_equipamentos);
    $stmt_equipamentos->execute(['id' => $id]);

    $equipamentos = $stmt_equipamentos->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die('Erro ao carregar fornecedor.');
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-lg-10 p-4">
            <div class="page-header">
                <h2>
                    <i class="fa-solid fa-handshake me-2"></i>Detalhes do Fornecedor
                </h2>

                <a href="lista.php" class="btn btn-cancel btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>Voltar
                </a>
            </div>

            <?php if ((int) $fornecedor->ativo === 0) : ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    Este fornecedor encontra-se inativo.
                </div>
            <?php endif; ?>

            <div class="form-medctrl">

                <div class="mb-4">
                    <h3 class="mb-1">
                        <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                    </h3>

                    <span class="badge bg-primary">
                        <?php echo htmlspecialchars($fornecedor->tipo_fornecedor); ?>
                    </span>

                    <?php if ((int) $fornecedor->ativo === 1) : ?>
                        <span class="badge bg-success ms-1">Ativo</span>
                    <?php else : ?>
                        <span class="badge bg-danger ms-1">Inativo</span>
                    <?php endif; ?>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <p><strong>NIF:</strong> <?php echo htmlspecialchars($fornecedor->nif); ?></p>
                        <p><strong>Telefone:</strong> <?php echo !empty($fornecedor->telefone) ? htmlspecialchars($fornecedor->telefone) : '—'; ?></p>
                        <p><strong>Email:</strong> <?php echo !empty($fornecedor->email) ? htmlspecialchars($fornecedor->email) : '—'; ?></p>
                        <p><strong>Website:</strong> <?php echo !empty($fornecedor->website) ? htmlspecialchars($fornecedor->website) : '—'; ?></p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p><strong>Morada:</strong> <?php echo !empty($fornecedor->morada) ? htmlspecialchars($fornecedor->morada) : '—'; ?></p>
                        <p><strong>Pessoa de contacto:</strong> <?php echo !empty($fornecedor->pessoa_contacto) ? htmlspecialchars($fornecedor->pessoa_contacto) : '—'; ?></p>
                        <p><strong>Telefone contacto:</strong> <?php echo !empty($fornecedor->telefone_contacto) ? htmlspecialchars($fornecedor->telefone_contacto) : '—'; ?></p>
                        <p>
                            <strong>Situação:</strong>
                            <?php if ((int) $fornecedor->ativo === 1) : ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <hr>
                
                <div class="mt-3">
                    <h5 class="mb-3">
                        <i class="fa-solid fa-laptop-medical me-2"></i>Equipamentos Associados
                    </h5>

                    <?php if (count($equipamentos) === 0) : ?>

                        <p class="text-muted">
                            Não existem equipamentos associados a este fornecedor.
                        </p>

                    <?php else : ?>

                        <?php foreach ($equipamentos as $equipamento) : ?>
                            <div class="documento-card mb-2 d-flex justify-content-between align-items-center">
                                <span>
                                    <?php echo htmlspecialchars($equipamento->codigo_inventario); ?>
                                    |
                                    <?php echo htmlspecialchars($equipamento->designacao); ?>
                                    -
                                    <?php echo htmlspecialchars($equipamento->marca); ?>
                                    <?php echo htmlspecialchars($equipamento->modelo); ?>
                                </span>

                                <a href="../equipamentos/detalhes.php?id=<?php echo $equipamento->id; ?>" class="btn btn-save btn-sm">
                                    <i class="fa-solid fa-eye me-1"></i>Ver
                                </a>
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>

                <hr>

                <div class="mt-3">
                    <h5>Observações</h5>

                    <p class="text-muted">
                        <?php echo !empty($fornecedor->observacoes)
                            ? htmlspecialchars($fornecedor->observacoes)
                            : 'Sem observações registadas.'; ?>
                    </p>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="editar.php?id=<?php echo $fornecedor->id; ?>" class="btn btn-edit btn-sm">
                        <i class="fa-solid fa-pen me-1"></i> Editar
                    </a>

                    <?php if ((int) $fornecedor->ativo === 1) : ?>

                        <a href="apagar.php?id=<?php echo $fornecedor->id; ?>" class="btn btn-delete btn-sm">
                            <i class="fa-solid fa-ban me-1"></i> Inativar
                        </a>

                    <?php else : ?>

                        <a href="apagar.php?id=<?php echo $fornecedor->id; ?>" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reativar
                        </a>

                    <?php endif; ?>

                    <a href="lista.php" class="btn btn-cancel btn-sm">Cancelar</a>
                </div>

            </div>
        </main>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>