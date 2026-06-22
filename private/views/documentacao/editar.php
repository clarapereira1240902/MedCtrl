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

$erro_formulario = '';

try {
    $sql = "
        SELECT *
        FROM documentos
        WHERE id = :id
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

$tipos_documento = $ligacao->query("SELECT id, nome FROM tipos_documento ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);

$stmt_equipamentos = $ligacao->prepare("
    SELECT id, codigo_inventario, designacao
    FROM equipamentos
    WHERE ativo = 1
    OR id = :equipamento_id
    ORDER BY codigo_inventario ASC
");

$stmt_equipamentos->execute([
    'equipamento_id' => $documento->equipamento_id
]);

$equipamentos = $stmt_equipamentos->fetchAll(PDO::FETCH_OBJ);

$stmt_fornecedores = $ligacao->prepare("
    SELECT id, nome_empresa
    FROM fornecedores
    WHERE ativo = 1
    OR id = :fornecedor_id
    ORDER BY nome_empresa ASC
");

$stmt_fornecedores->execute([
    'fornecedor_id' => !empty($documento->fornecedor_id) ? $documento->fornecedor_id : 0
]);

$fornecedores = $stmt_fornecedores->fetchAll(PDO::FETCH_OBJ);

$form = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [
    'tipo_documento_id' => $documento->tipo_documento_id,
    'equipamento_id' => $documento->equipamento_id,
    'fornecedor_id' => $documento->fornecedor_id,
    'nome' => $documento->nome,
    'data_documento' => $documento->data_documento,
    'data_validade' => $documento->data_validade,
    'observacoes' => $documento->observacoes
];

$nome_ficheiro_atual = basename($documento->ficheiro_link);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo_documento_id = (int) ($_POST['tipo_documento_id'] ?? 0);
    $equipamento_id = (int) ($_POST['equipamento_id'] ?? 0);
    $fornecedor_id = !empty($_POST['fornecedor_id']) ? (int) $_POST['fornecedor_id'] : null;
    $nome = trim($_POST['nome'] ?? '');
    $data_documento = $_POST['data_documento'] ?? '';
    $data_validade = $_POST['data_validade'] ?? '';
    $observacoes = trim($_POST['observacoes'] ?? '');

    if ($nome === '') {
        $erro_formulario = 'O nome do documento é obrigatório.';
    }

    if (empty($erro_formulario) && $tipo_documento_id <= 0) {
        $erro_formulario = 'Tens de selecionar o tipo de documento.';
    }

    if (empty($erro_formulario) && $equipamento_id <= 0) {
        $erro_formulario = 'Tens de selecionar o equipamento associado.';
    }

    if (
        empty($erro_formulario) &&
        !empty($data_documento) &&
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_documento)
    ) {
        $erro_formulario = 'A data do documento não é válida.';
    }

    if (
        empty($erro_formulario) &&
        !empty($data_validade) &&
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_validade)
    ) {
        $erro_formulario = 'A data de validade não é válida.';
    }

    if (
        empty($erro_formulario) &&
        !empty($data_documento) &&
        !empty($data_validade) &&
        $data_validade < $data_documento
    ) {
        $erro_formulario = 'A data de validade não pode ser anterior à data do documento.';
    }

    if (empty($erro_formulario)) {
        try {
            $stmt_check_tipo = $ligacao->prepare("
                SELECT id
                FROM tipos_documento
                WHERE id = :id
                LIMIT 1
            ");

            $stmt_check_tipo->execute([
                'id' => $tipo_documento_id
            ]);

            if (!$stmt_check_tipo->fetch()) {
                $erro_formulario = 'O tipo de documento selecionado não é válido.';
            }

        } catch (PDOException $e) {
            $erro_formulario = 'Erro ao validar o tipo de documento.';
        }
    }

    if (empty($erro_formulario)) {
        try {
            $stmt_check_equipamento = $ligacao->prepare("
                SELECT id
                FROM equipamentos
                WHERE id = :id
                AND (ativo = 1 OR id = :equipamento_atual)
                LIMIT 1
            ");

            $stmt_check_equipamento->execute([
                'id' => $equipamento_id,
                'equipamento_atual' => $documento->equipamento_id
            ]);

            if (!$stmt_check_equipamento->fetch()) {
                $erro_formulario = 'O equipamento selecionado não é válido.';
            }

        } catch (PDOException $e) {
            $erro_formulario = 'Erro ao validar o equipamento.';
        }
    }

    if (empty($erro_formulario) && !empty($fornecedor_id)) {
        try {
            $stmt_check_fornecedor = $ligacao->prepare("
                SELECT id
                FROM fornecedores
                WHERE id = :id
                AND (ativo = 1 OR id = :fornecedor_atual)
                LIMIT 1
            ");

            $stmt_check_fornecedor->execute([
                'id' => $fornecedor_id,
                'fornecedor_atual' => !empty($documento->fornecedor_id) ? $documento->fornecedor_id : 0
            ]);

            if (!$stmt_check_fornecedor->fetch()) {
                $erro_formulario = 'O fornecedor selecionado não é válido.';
            }

        } catch (PDOException $e) {
            $erro_formulario = 'Erro ao validar o fornecedor.';
        }
    }

    if (empty($erro_formulario)) {
        try {
            $stmt_check_duplicado = $ligacao->prepare("
                SELECT id
                FROM documentos
                WHERE id <> :id
                AND ativo = 1
                AND LOWER(nome) = LOWER(:nome)
                AND tipo_documento_id = :tipo_documento_id
                AND equipamento_id = :equipamento_id
                LIMIT 1
            ");

            $stmt_check_duplicado->execute([
                'id' => $id,
                'nome' => $nome,
                'tipo_documento_id' => $tipo_documento_id,
                'equipamento_id' => $equipamento_id
            ]);

            if ($stmt_check_duplicado->fetch()) {
                $erro_formulario = 'Já existe outro documento ativo com o mesmo nome, tipo e equipamento.';
            }

        } catch (PDOException $e) {
            $erro_formulario = 'Erro ao verificar documentos duplicados.';
        }
    }

    if (empty($erro_formulario)) {
        try {
            $ficheiro_link = $documento->ficheiro_link;

            if (!empty($_FILES['ficheiro']['name'])) {

                if ($_FILES['ficheiro']['error'] !== UPLOAD_ERR_OK) {
                    $erro_formulario = 'Erro ao carregar o ficheiro.';
                } else {
                    $pasta_uploads = __DIR__ . '/../../../uploads/documentos/';

                    if (!is_dir($pasta_uploads)) {
                        mkdir($pasta_uploads, 0777, true);
                    }

                    $nome_original = basename($_FILES['ficheiro']['name']);
                    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));

                    $extensoes_permitidas = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];

                    if (!in_array($extensao, $extensoes_permitidas)) {
                        $erro_formulario = 'Tipo de ficheiro não permitido. Usa PDF, Word ou imagem.';
                    } else {
                        $nome_ficheiro = uniqid('doc_', true) . '.' . $extensao;
                        $caminho_destino = $pasta_uploads . $nome_ficheiro;

                        if (move_uploaded_file($_FILES['ficheiro']['tmp_name'], $caminho_destino)) {
                            $ficheiro_link = BASE_URL . '/uploads/documentos/' . $nome_ficheiro;
                        } else {
                            $erro_formulario = 'Erro ao carregar o ficheiro.';
                        }
                    }
                }
            }

            if (empty($erro_formulario)) {
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
                ";

                $stmt_update = $ligacao->prepare($sql_update);

                $stmt_update->execute([
                    'tipo_documento_id' => $tipo_documento_id,
                    'equipamento_id' => $equipamento_id,
                    'fornecedor_id' => $fornecedor_id,
                    'nome' => $nome,
                    'data_documento' => !empty($data_documento) ? $data_documento : null,
                    'data_validade' => !empty($data_validade) ? $data_validade : null,
                    'ficheiro_link' => $ficheiro_link,
                    'observacoes' => $observacoes,
                    'id' => $id
                ]);

                registar_log(
                    $ligacao,
                    'Editou documento',
                    'documentos',
                    (int) $id,
                    'Documento atualizado: ' . $nome
                );

                header('Location: detalhes.php?id=' . $id);
                exit;
            }

        } catch (PDOException $e) {
            $erro_formulario = 'Erro ao atualizar documento. Verifica se os dados preenchidos são válidos.';
        }
    }
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

            <?php if ((int) $documento->ativo === 0) : ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    Este documento encontra-se inativo. Podes editar os dados, mas a situação só é alterada no botão de reativação da lista/detalhes.
                </div>
            <?php endif; ?>

            <?php if (!empty($erro_formulario)) : ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    <?php echo htmlspecialchars($erro_formulario); ?>
                </div>
            <?php endif; ?>

            <form class="form-medctrl" method="post" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Nome do Documento</label>
                        <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($form['nome'] ?? ''); ?>" required>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label">Tipo de Documento</label>

                        <select name="tipo_documento_id" class="form-select" required>
                            <?php foreach ($tipos_documento as $tipo) : ?>
                                <option value="<?php echo $tipo->id; ?>"
                                    <?php echo (($form['tipo_documento_id'] ?? '') == $tipo->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipo->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label">Data do Documento</label>
                        <input type="date" name="data_documento" class="form-control" value="<?php echo htmlspecialchars($form['data_documento'] ?? ''); ?>">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Data de Validade</label>
                        <input type="date" name="data_validade" class="form-control" value="<?php echo htmlspecialchars($form['data_validade'] ?? ''); ?>">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Equipamento associado</label>

                        <select name="equipamento_id" class="form-select" required>
                            <?php foreach ($equipamentos as $equipamento) : ?>
                                <option value="<?php echo $equipamento->id; ?>"
                                    <?php echo (($form['equipamento_id'] ?? '') == $equipamento->id) ? 'selected' : ''; ?>>
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
                                    <?php echo (($form['fornecedor_id'] ?? '') == $fornecedor->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Documento Atual</label>

                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fa-solid fa-file-lines me-2"></i>
                                    <?php echo htmlspecialchars($nome_ficheiro_atual); ?>
                                </span>

                                <a href="<?php echo htmlspecialchars($documento->ficheiro_link); ?>" target="_blank" class="btn btn-save btn-sm">
                                    <i class="fa-solid fa-eye me-1"></i>Abrir
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Substituir Documento</label>
                        <input type="file" name="ficheiro" class="form-control">
                        <small class="text-muted">
                            Só escolhe um novo ficheiro se quiseres substituir o documento atual.
                        </small>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" class="form-control" rows="3"><?php echo htmlspecialchars($form['observacoes'] ?? ''); ?></textarea>
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