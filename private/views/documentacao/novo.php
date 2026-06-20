<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'documentacao';

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
            'tipo_documento_id' => (int) $_POST['tipo_documento_id'],
            'equipamento_id' => (int) $_POST['equipamento_id'],
            'fornecedor_id' => !empty($_POST['fornecedor_id']) ? (int) $_POST['fornecedor_id'] : null,
            'nome' => trim($_POST['nome']),
            'data_documento' => $_POST['data_documento'] ?: null,
            'data_validade' => $_POST['data_validade'] ?: null,
            'ficheiro_link' => trim($_POST['ficheiro_link']),
            'observacoes' => trim($_POST['observacoes'])
        ]);

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao criar documento.');
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

            <form class="form-medctrl" method="post">

                <div class="row">
                    
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Nome do Documento</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label">Tipo</label>

                        <select name="tipo_documento_id" class="form-select" required>
                            <option value="">Selecione</option>

                            <?php foreach ($tipos_documento as $tipo) : ?>
                                <option value="<?php echo $tipo->id; ?>">
                                    <?php echo htmlspecialchars($tipo->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label">Data</label>
                        <input type="date" name="data_documento" class="form-control">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Validade</label>
                        <input type="date" name="data_validade" class="form-control">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Equipamento</label>

                        <select name="equipamento_id" class="form-select" required>
                            <option value="">Selecionar equipamento</option>

                            <?php foreach ($equipamentos as $equipamento) : ?>
                                <option value="<?php echo $equipamento->id; ?>">
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
                                <option value="<?php echo $fornecedor->id; ?>">
                                    <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Ficheiro / Link</label>
                        <input
                            type="text"
                            name="ficheiro_link"
                            class="form-control"
                            placeholder="Ex: docs/manual.pdf ou URL"
                            required
                        >
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" class="form-control" rows="3"></textarea>
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