<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'documentacao';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

try {
    $sql = "
        SELECT
            d.*,
            td.nome AS tipo_documento,
            e.id AS equipamento_id,
            e.codigo_inventario,
            e.designacao AS equipamento,
            f.nome_empresa AS fornecedor
        FROM documentos d
        INNER JOIN tipos_documento td
            ON d.tipo_documento_id = td.id
        INNER JOIN equipamentos e
            ON d.equipamento_id = e.id
        LEFT JOIN fornecedores f
            ON d.fornecedor_id = f.id
        WHERE d.id = :id
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);

    $documento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$documento) {
        header('Location: lista.php');
        exit;
    }

} catch (PDOException $e) {
    die('Erro ao carregar documento.');
}

$nome_ficheiro = basename($documento->ficheiro_link);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-lg-10 p-4">
            <div class="page-header">
                <h2>
                    <i class="fa-solid fa-file-medical me-2"></i>Detalhes do Documento
                </h2>

                <a href="lista.php" class="btn btn-cancel btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>Voltar
                </a>
            </div>

            <?php if ((int) $documento->ativo === 0) : ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    Este documento encontra-se inativo.
                </div>
            <?php endif; ?>

            <div class="card-medctrl p-4">

                <div class="row">

                    <div class="col-12 col-md-6">
                        <p><strong>Tipo de Documento:</strong> <?php echo htmlspecialchars($documento->tipo_documento); ?></p>
                        <p><strong>Nome:</strong> <?php echo htmlspecialchars($documento->nome); ?></p>
                        <p><strong>Data do Documento:</strong> <?php echo !empty($documento->data_documento) ? htmlspecialchars($documento->data_documento) : '—'; ?></p>
                        <p><strong>Validade:</strong> <?php echo !empty($documento->data_validade) ? htmlspecialchars($documento->data_validade) : '—'; ?></p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p>
                            <strong>Equipamento:</strong>
                            <?php echo htmlspecialchars($documento->codigo_inventario . ' | ' . $documento->equipamento); ?>
                        </p>

                        <a href="../equipamentos/detalhes.php?id=<?php echo $documento->equipamento_id; ?>" class="btn btn-save btn-sm mb-3">
                            <i class="fa-solid fa-eye me-1"></i>Ver equipamento
                        </a>

                        <p>
                            <strong>Fornecedor:</strong>
                            <?php echo !empty($documento->fornecedor) ? htmlspecialchars($documento->fornecedor) : '—'; ?>
                        </p>

                        <p>
                            <strong>Situação:</strong>
                            <?php if ((int) $documento->ativo === 1) : ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </p>
                    </div>

                </div>

                <hr>

                <div class="mt-3">
                    <strong>Observações:</strong>
                    <p>
                        <?php echo !empty($documento->observacoes)
                            ? htmlspecialchars($documento->observacoes)
                            : 'Sem observações registadas.'; ?>
                    </p>
                </div>

                <hr>

                <div class="mt-4">
                    <h5 class="mb-3">
                        <i class="fa-solid fa-file-lines me-2"></i>Ver Documento
                    </h5>

                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <div>
                                <strong><?php echo htmlspecialchars($nome_ficheiro); ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($documento->tipo_documento); ?>
                                </small>
                            </div>

                            <a href="<?php echo htmlspecialchars($documento->ficheiro_link); ?>" target="_blank" class="btn btn-save btn-sm">
                                <i class="fa-solid fa-download me-1"></i>Abrir
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="editar.php?id=<?php echo $documento->id; ?>" class="btn btn-edit btn-sm">
                        <i class="fa-solid fa-pen me-1"></i>Editar
                    </a>

                    <?php if ((int) $documento->ativo === 1) : ?>

                        <a href="apagar.php?id=<?php echo $documento->id; ?>" class="btn btn-delete btn-sm">
                            <i class="fa-solid fa-ban me-1"></i>Inativar
                        </a>

                    <?php else : ?>

                        <a href="apagar.php?id=<?php echo $documento->id; ?>" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-rotate-left me-1"></i>Reativar
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