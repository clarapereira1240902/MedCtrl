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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE documentos
            SET ativo = 0
            WHERE id = :id
            AND ativo = 1
        ";

        $stmt = $ligacao->prepare($sql);
        $stmt->execute(['id' => $id]);

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao eliminar documento.');
    }
}

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
        WHERE d.id = :id
        AND d.ativo = 1
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

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-lg-10 p-4">
            <div class="d-flex justify-content-center mt-5">
                <div class="card shadow rounded text-center p-4" style="max-width:650px; width:100%;">

                    <div class="text-warning display-4 mb-3">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <h3 class="mb-2">Eliminar Documento</h3>

                    <p class="text-muted mb-3">
                        Tens a certeza que pretendes eliminar este documento?
                    </p>

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

                        <p class="mb-0">
                            <i class="fa-solid fa-calendar-days me-1"></i>
                            Validade:
                            <?php echo htmlspecialchars($documento->data_validade ?? '—'); ?>
                        </p>
                    </div>

                    <div class="alert alert-danger">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        Este documento encontra-se associado a um equipamento.
                        Após a eliminação deixará de estar disponível para consulta.
                    </div>

                    <form method="post" class="d-flex justify-content-center gap-3">
                        <a href="lista.php" class="btn btn-outline-secondary btn-sm px-4">
                            <i class="fa-solid fa-xmark me-1"></i>Cancelar
                        </a>

                        <button type="submit" class="btn btn-danger btn-sm px-4">
                            <i class="fa-solid fa-trash me-1"></i>Eliminar
                        </button>
                    </form>

                </div>
            </div>
        </main>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>