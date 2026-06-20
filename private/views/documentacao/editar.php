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

$tipos_documento = $ligacao->query("SELECT id, nome FROM tipos_documento ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);

$equipamentos = $ligacao->query("
    SELECT id, codigo_inventario, designacao
    FROM equipamentos
    WHERE ativo = 1
    ORDER BY codigo_inventario ASC
")->fetchAll(PDO::FETCH_OBJ);

$fornecedores = $ligacao->query("
    SELECT id, nome_empresa
    FROM fornecedores
    WHERE ativo = 1
    ORDER BY nome_empresa ASC
")->fetchAll(PDO::FETCH_OBJ);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $sql_update = "
            UPDATE documentos
            SET
                tipo_documento_id = :tipo_documento_id,
                equipamento_id = :equipamento_id,
                fornecedor_id = :fornecedor_id,
                nome = :nome,
                data_documento = :data_documento,
                data_validade = :data_validade,
                ficheiro_link = :ficheiro_link,
                observacoes = :observacoes
            WHERE id = :id
            AND ativo = 1
        ";

        $stmt_update = $ligacao->prepare($sql_update);

        $stmt_update->execute([
            'tipo_documento_id' => (int) $_POST['tipo_documento_id'],
            'equipamento_id' => (int) $_POST['equipamento_id'],
            'fornecedor_id' => !empty($_POST['fornecedor_id']) ? (int) $_POST['fornecedor_id'] : null,
            'nome' => trim($_POST['nome']),
            'data_documento' => $_POST['data_documento'] ?: null,
            'data_validade' => $_POST['data_validade'] ?: null,
            'ficheiro_link' => trim($_POST['ficheiro_link']),
            'observacoes' => trim($_POST['observacoes']),
            'id' => $id
        ]);

        header('Location: detalhes.php?id=' . $id);
        exit;

    } catch (PDOException $e) {
        die('Erro ao atualizar documento.');
    }
}

try {
    $sql = "
        SELECT *
        FROM documentos
        WHERE id = :id
        AND ativo = 1
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

            <div class="page-header">
                <h2>
                    <i class="fa-solid fa-pen-to-square me-2"></i> Editar Documento
                </h2>

                <a href="lista.php" class="btn btn-cancel btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>

            <form class="form-medctrl" method="post">

                <div class="row">

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Nome do Documento</label>
                        <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($documento->nome); ?>" required>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label">Tipo de Documento</label>

                        <select name="tipo_documento_id" class="form-select" required>
                            <?php foreach ($tipos_documento as $tipo) : ?>
                                <option value="<?php echo $tipo->id; ?>"
                                    <?php echo $tipo->id == $documento->tipo_documento_id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipo->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label">Data do Documento</label>
                        <input type="date" name="data_documento" class="form-control" value="<?php echo htmlspecialchars($documento->data_documento ?? ''); ?>">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Data de Validade</label>
                        <input type="date" name="data_validade" class="form-control" value="<?php echo htmlspecialchars($documento->data_validade ?? ''); ?>">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Equipamento associado</label>

                        <select name="equipamento_id" class="form-select" required>
                            <?php foreach ($equipamentos as $equipamento) : ?>
                                <option value="<?php echo $equipamento->id; ?>"
                                    <?php echo $equipamento->id == $documento->equipamento_id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($equipamento->codigo_inventario . ' | ' . $equipamento->designacao); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Fornecedor associado</label>

                        <select name="fornecedor_id" class="form-select">
                            <option value="">Nenhum</option>

                            <?php foreach ($fornecedores as $fornecedor) : ?>
                                <option value="<?php echo $fornecedor->id; ?>"
                                    <?php echo $fornecedor->id == $documento->fornecedor_id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Ficheiro / Link do Documento</label>
                        <input type="text" name="ficheiro_link" class="form-control" value="<?php echo htmlspecialchars($documento->ficheiro_link); ?>" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" class="form-control" rows="3"><?php echo htmlspecialchars($documento->observacoes ?? ''); ?></textarea>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="lista.php" class="btn btn-cancel btn-sm">Cancelar</a>

                    <button type="submit" class="btn btn-save btn-sm">
                        <i class="fa-regular fa-floppy-disk me-1"></i>Guardar alterações
                    </button>
                </div>

            </form>

        </main>
    
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>