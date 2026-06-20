<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'documentacao';

$pesquisa = trim($_GET['pesquisa'] ?? '');
$tipo_documento_id = $_GET['tipo_documento_id'] ?? '';
$validade = $_GET['validade'] ?? '';

$tipos_documento = $ligacao->query("SELECT id, nome FROM tipos_documento ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);

try {
    $sql = "
        SELECT
            d.id,
            d.nome,
            d.data_validade,
            td.nome AS tipo_documento,
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
        WHERE d.ativo = 1
        AND LOWER(CONCAT(
            d.nome, ' ',
            td.nome, ' ',
            e.codigo_inventario, ' ',
            e.designacao, ' ',
            IFNULL(f.nome_empresa, '')
        )) LIKE LOWER(:pesquisa)
        AND (:tipo_documento_id = '' OR d.tipo_documento_id = :tipo_documento_id)
        AND (
            :validade = ''
            OR (:validade = 'valido' AND (d.data_validade IS NULL OR d.data_validade >= CURDATE()))
            OR (:validade = 'expirar' AND d.data_validade BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY))
            OR (:validade = 'expirado' AND d.data_validade < CURDATE())
        )
        ORDER BY d.nome ASC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        'pesquisa' => '%' . $pesquisa . '%',
        'tipo_documento_id' => $tipo_documento_id,
        'validade' => $validade
    ]);

    $documentos = $stmt->fetchAll(PDO::FETCH_OBJ);
    $erro = '';

} catch (PDOException $e) {
    $erro = 'Aconteceu um erro ao carregar a documentação.';
    $documentos = [];
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
                    <i class="fa-solid fa-file-medical me-2"></i>Documentação
                </h2>

                <a href="novo.php" class="btn btn-primary-custom btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Novo Documento
                </a>
            </div>

            <hr>

            <div class="search-card mb-4">
                <h5>
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisa
                </h5>

                <form method="get">

                    <div class="search-row">
                        <input
                            type="text"
                            name="pesquisa"
                            class="form-control search-input"
                            placeholder="Documento, equipamento ou fornecedor"
                            value="<?php echo htmlspecialchars($pesquisa); ?>"
                        >

                        <button class="btn btn-save search-btn" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>

                        <button class="btn btn-outline-secondary search-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosDocumentacao">
                            <i class="fa-solid fa-sliders"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filtrosDocumentacao">
                        <div class="row g-3 pt-3 border-top">

                            <div class="col-12 col-md-6">
                                <label class="form-label">Tipo de Documento</label>

                                <select name="tipo_documento_id" class="form-select">
                                    <option value="">Todos</option>

                                    <?php foreach ($tipos_documento as $tipo) : ?>
                                        <option value="<?php echo $tipo->id; ?>"
                                            <?php echo $tipo_documento_id == $tipo->id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tipo->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Validade</label>

                                <select name="validade" class="form-select">
                                    <option value="" <?php echo $validade === '' ? 'selected' : ''; ?>>Todas</option>
                                    <option value="valido" <?php echo $validade === 'valido' ? 'selected' : ''; ?>>Válido</option>
                                    <option value="expirar" <?php echo $validade === 'expirar' ? 'selected' : ''; ?>>A expirar</option>
                                    <option value="expirado" <?php echo $validade === 'expirado' ? 'selected' : ''; ?>>Expirado</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="lista.php" class="btn btn-cancel btn-sm">
                                    <i class="fa-solid fa-rotate-left me-1"></i>Limpar
                                </a>

                                <button type="submit" class="btn btn-save btn-sm">
                                    <i class="fa-solid fa-filter me-1"></i>Aplicar filtros
                                </button>
                            </div>

                        </div>
                    </div>

                </form>
            </div>

            <?php if (!empty($erro)) : ?>

                <p class="text-center text-danger">
                    <?php echo htmlspecialchars($erro); ?>
                </p>

            <?php elseif (count($documentos) === 0) : ?>

                <p class="text-muted">
                    Não existem documentos registados.
                </p>

            <?php else : ?>

                <p class="text-muted">
                    Existem <?php echo count($documentos); ?> documentos registados.
                </p>

                <div class="table-responsive">
                    <table class="table table-medctrl align-middle">

                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Tipo</th>
                                <th>Equipamento</th>
                                <th>Fornecedor</th>
                                <th>Validade</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($documentos as $documento) : ?>

                                <tr>
                                    <td><?php echo htmlspecialchars($documento->nome); ?></td>

                                    <td>
                                        <?php
                                        $classeBadge = 'bg-secondary';

                                        switch ($documento->tipo_documento) {

                                            case 'Manual Técnico':
                                                $classeBadge = 'bg-primary';
                                                break;

                                            case 'Certificado':
                                                $classeBadge = 'bg-success';
                                                break;

                                            case 'Contrato':
                                                $classeBadge = 'bg-warning';
                                                break;

                                            case 'Ficha Técnica':
                                                $classeBadge = 'bg-info';
                                                break;

                                            case 'Relatório de Manutenção':
                                                $classeBadge = 'bg-danger';
                                                break;

                                            case 'Garantia':
                                                $classeBadge = 'bg-default';
                                                break;
                                        }
                                        ?>

                                        <span class="badge <?php echo $classeBadge; ?>">
                                            <?php echo htmlspecialchars($documento->tipo_documento); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($documento->codigo_inventario . ' | ' . $documento->equipamento); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($documento->fornecedor ?? '—'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($documento->data_validade ?? 'Sem validade'); ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="detalhes.php?id=<?php echo $documento->id; ?>" class="btn btn-sm btn-view-list">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="editar.php?id=<?php echo $documento->id; ?>" class="btn btn-sm btn-edit-list">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <a href="apagar.php?id=<?php echo $documento->id; ?>" class="btn btn-sm btn-delete-list">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            <?php endif; ?>

        </main>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>