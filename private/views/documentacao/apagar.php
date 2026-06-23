<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin_ou_tecnico();

$menu_ativo = 'documentacao';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

try {
    $sql = "
        SELECT
            d.id,
            d.nome,
            d.data_validade,
            d.ativo,
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

$documento_ativo = ((int) $documento->ativo === 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($documento_ativo) {
            $novo_estado = 0;
        } else {
            $novo_estado = 1;
        }

        $sql = "
            UPDATE documentos
            SET ativo = :ativo
            WHERE id = :id
        ";

        $stmt = $ligacao->prepare($sql);
        $stmt->execute([
            'ativo' => $novo_estado,
            'id' => $id
        ]);

        if ((int) $novo_estado === 1) {
            registar_log(
                $ligacao,
                'Reativou documento',
                'documentos',
                (int) $id,
                'Documento reativado: ' . $documento->nome
            );
        } else {
            registar_log(
                $ligacao,
                'Inativou documento',
                'documentos',
                (int) $id,
                'Documento inativado: ' . $documento->nome
            );
        }

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao alterar o estado do documento.');
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-lg-10 p-4">
            <div class="d-flex justify-content-center mt-5">
                <div class="card shadow rounded text-center p-4" style="max-width:650px; width:100%;">

                    <div class="<?php echo $documento_ativo ? 'text-warning' : 'text-success'; ?> display-4 mb-3">
                        <?php if ($documento_ativo) : ?>
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        <?php else : ?>
                            <i class="fa-solid fa-circle-check"></i>
                        <?php endif; ?>
                    </div>

                    <?php if ($documento_ativo) : ?>

                        <h3 class="mb-2">Inativar Documento</h3>

                        <p class="text-muted mb-3">
                            Tens a certeza que pretendes inativar este documento?
                        </p>

                    <?php else : ?>

                        <h3 class="mb-2">Reativar Documento</h3>

                        <p class="text-muted mb-3">
                            Tens a certeza que pretendes reativar este documento?
                        </p>

                    <?php endif; ?>

                    <div class="bg-light p-3 rounded mb-3">
                        <h4 class="mb-2">
                            <?php echo htmlspecialchars($documento->nome); ?>
                        </h4>

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
                                $classeBadge = 'bg-secondary';
                                break;
                        }
                        ?>

                        <span class="badge <?php echo $classeBadge; ?> mb-3 d-inline-block">
                            <?php echo htmlspecialchars($documento->tipo_documento); ?>
                        </span>

                        <p class="mb-1">
                            <i class="fa-solid fa-laptop-medical me-1"></i>
                            <?php echo htmlspecialchars($documento->codigo_inventario . ' | ' . $documento->equipamento); ?>
                        </p>

                        <p class="mb-1">
                            <i class="fa-solid fa-handshake me-1"></i>
                            <?php echo htmlspecialchars($documento->fornecedor ?? 'Sem fornecedor associado'); ?>
                        </p>

                        <p class="mb-1">
                            <i class="fa-solid fa-calendar-days me-1"></i>
                            Validade:
                            <?php echo htmlspecialchars($documento->data_validade ?? '—'); ?>
                        </p>

                        <p class="mb-0">
                            Situação:
                            <?php if ($documento_ativo) : ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if ($documento_ativo) : ?>
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            Este documento encontra-se associado a um equipamento.
                            Após ser inativado, deixará de estar disponível na listagem normal.
                        </div>
                    <?php endif; ?>

                    <form method="post" class="d-flex justify-content-center gap-3">
                        <a href="lista.php" class="btn btn-outline-secondary btn-sm px-4">
                            <i class="fa-solid fa-xmark me-1"></i>Cancelar
                        </a>

                        <?php if ($documento_ativo) : ?>

                            <button type="submit" class="btn btn-danger btn-sm px-4">
                                <i class="fa-solid fa-ban me-1"></i>Inativar
                            </button>

                        <?php else : ?>

                            <button type="submit" class="btn btn-success btn-sm px-4">
                                <i class="fa-solid fa-rotate-left me-1"></i>Reativar
                            </button>

                        <?php endif; ?>
                    </form>

                </div>
            </div>
        </main>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>