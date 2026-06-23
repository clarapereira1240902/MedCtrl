<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin_ou_tecnico();

$menu_ativo = 'documentacao';

function data_valida($data) {
    if (empty($data)) {
        return true;
    }

    $d = DateTime::createFromFormat('Y-m-d', $data);
    return $d && $d->format('Y-m-d') === $data;
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

$erro_formulario = '';
$form = $_POST;

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

    if (empty($erro_formulario) && !data_valida($data_documento)) {
        $erro_formulario = 'A data do documento não é válida.';
    }

    if (empty($erro_formulario) && !data_valida($data_validade)) {
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
                AND ativo = 1
                LIMIT 1
            ");

            $stmt_check_equipamento->execute([
                'id' => $equipamento_id
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
                AND ativo = 1
                LIMIT 1
            ");

            $stmt_check_fornecedor->execute([
                'id' => $fornecedor_id
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
                WHERE LOWER(nome) = LOWER(:nome)
                AND tipo_documento_id = :tipo_documento_id
                AND equipamento_id = :equipamento_id
                LIMIT 1
            ");

            $stmt_check_duplicado->execute([
                'nome' => $nome,
                'tipo_documento_id' => $tipo_documento_id,
                'equipamento_id' => $equipamento_id
            ]);

            if ($stmt_check_duplicado->fetch()) {
                $erro_formulario = 'Já existe um documento com o mesmo nome, tipo e equipamento.';
            }

        } catch (PDOException $e) {
            $erro_formulario = 'Erro ao verificar documentos duplicados.';
        }
    }

    if (empty($erro_formulario)) {

        if (empty($_FILES['ficheiro']['name'])) {
            $erro_formulario = 'Deves carregar um ficheiro.';
        } elseif ($_FILES['ficheiro']['error'] !== UPLOAD_ERR_OK) {
            $erro_formulario = 'Erro ao carregar o ficheiro.';
        }
    }

    if (empty($erro_formulario)) {

        try {
            $ficheiro_link = '';

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

            if (empty($erro_formulario)) {

                $sql = "
                    INSERT INTO documentos (
                        tipo_documento_id,
                        equipamento_id,
                        fornecedor_id,
                        nome,
                        data_documento,
                        data_validade,
                        ficheiro_link,
                        observacoes
                    ) VALUES (
                        :tipo_documento_id,
                        :equipamento_id,
                        :fornecedor_id,
                        :nome,
                        :data_documento,
                        :data_validade,
                        :ficheiro_link,
                        :observacoes
                    )
                ";

                $stmt = $ligacao->prepare($sql);

                $stmt->execute([
                    'tipo_documento_id' => $tipo_documento_id,
                    'equipamento_id' => $equipamento_id,
                    'fornecedor_id' => $fornecedor_id,
                    'nome' => $nome,
                    'data_documento' => !empty($data_documento) ? $data_documento : null,
                    'data_validade' => !empty($data_validade) ? $data_validade : null,
                    'ficheiro_link' => $ficheiro_link,
                    'observacoes' => $observacoes
                ]);

                $documento_id = $ligacao->lastInsertId();

                registar_log(
                    $ligacao,
                    'Criou documento',
                    'documentos',
                    (int) $documento_id,
                    'Novo documento registado: ' . $nome
                );

                header('Location: lista.php');
                exit;
            }

        } catch (PDOException $e) {
            $erro_formulario = 'Erro ao criar documento. Verifica se os dados preenchidos são válidos.';
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
                    <i class="fa-solid fa-plus me-2"></i>
                    Novo Documento
                </h2>
            </div>

            <hr>

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
                        <label class="form-label">Tipo</label>

                        <select name="tipo_documento_id" class="form-select" required>
                            <option value="">Selecione</option>

                            <?php foreach ($tipos_documento as $tipo) : ?>
                                <option value="<?php echo $tipo->id; ?>"
                                    <?php echo (($form['tipo_documento_id'] ?? '') == $tipo->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipo->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label">Data</label>
                        <input type="date" name="data_documento" class="form-control" value="<?php echo htmlspecialchars($form['data_documento'] ?? ''); ?>">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Validade</label>
                        <input type="date" name="data_validade" class="form-control" value="<?php echo htmlspecialchars($form['data_validade'] ?? ''); ?>">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Equipamento</label>

                        <select name="equipamento_id" class="form-select" required>
                            <option value="">Selecionar equipamento</option>

                            <?php foreach ($equipamentos as $equipamento) : ?>
                                <option value="<?php echo $equipamento->id; ?>"
                                    <?php echo (($form['equipamento_id'] ?? '') == $equipamento->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($equipamento->codigo_inventario . ' | ' . $equipamento->designacao); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Fornecedor</label>

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
                        <label class="form-label">Carregar Ficheiro</label>
                        <input type="file" name="ficheiro" class="form-control" required>
                        <small class="text-muted">
                            Formatos permitidos: PDF, Word ou imagem.
                        </small>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" class="form-control" rows="3"><?php echo htmlspecialchars($form['observacoes'] ?? ''); ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mb-4"> 
                        <a href="lista.php" class="btn btn-cancel btn-sm"> 
                            <i class="fa-solid fa-xmark me-1"></i> Cancelar 
                        </a> 

                        <button type="submit" class="btn btn-save btn-sm"> 
                            <i class="fa-regular fa-floppy-disk me-1"></i> Guardar 
                        </button> 
                    </div>

                </div>

            </form>

        </main>
    
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>