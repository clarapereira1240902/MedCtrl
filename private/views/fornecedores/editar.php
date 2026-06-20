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

$tipos_fornecedor = $ligacao->query("SELECT id, nome FROM tipos_fornecedor ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $sql_update = "
            UPDATE fornecedores
            SET
                tipo_fornecedor_id = :tipo_fornecedor_id,
                nome_empresa = :nome_empresa,
                nif = :nif,
                telefone = :telefone,
                email = :email,
                morada = :morada,
                website = :website,
                pessoa_contacto = :pessoa_contacto,
                telefone_contacto = :telefone_contacto,
                observacoes = :observacoes
            WHERE id = :id
            AND ativo = 1
        ";

        $stmt_update = $ligacao->prepare($sql_update);

        $stmt_update->execute([
            'tipo_fornecedor_id' => (int) $_POST['tipo_fornecedor_id'],
            'nome_empresa' => trim($_POST['nome_empresa']),
            'nif' => trim($_POST['nif']),
            'telefone' => trim($_POST['telefone']),
            'email' => trim($_POST['email']),
            'morada' => trim($_POST['morada']),
            'website' => trim($_POST['website']),
            'pessoa_contacto' => trim($_POST['pessoa_contacto']),
            'telefone_contacto' => trim($_POST['telefone_contacto']),
            'observacoes' => trim($_POST['observacoes']),
            'id' => $id
        ]);

        header('Location: detalhes.php?id=' . $id);
        exit;

    } catch (PDOException $e) {
        die('Erro ao atualizar fornecedor.');
    }
}

try {
    $sql = "
        SELECT *
        FROM fornecedores
        WHERE id = :id
        AND ativo = 1
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);

    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: lista.php');
        exit;
    }

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
                    <i class="fa-solid fa-pen-to-square me-2"></i> Editar Fornecedor
                </h2>

                <a href="lista.php" class="btn btn-cancel btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>

            <form class="form-medctrl" method="post">

                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nome da Empresa</label>
                            <input type="text" name="nome_empresa" class="form-control" value="<?php echo htmlspecialchars($fornecedor->nome_empresa); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIF</label>
                            <input type="text" name="nif" class="form-control" value="<?php echo htmlspecialchars($fornecedor->nif); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="<?php echo htmlspecialchars($fornecedor->telefone ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($fornecedor->email ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Morada</label>
                            <input type="text" name="morada" class="form-control" value="<?php echo htmlspecialchars($fornecedor->morada ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control" value="<?php echo htmlspecialchars($fornecedor->website ?? ''); ?>">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pessoa de Contacto</label>
                            <input type="text" name="pessoa_contacto" class="form-control" value="<?php echo htmlspecialchars($fornecedor->pessoa_contacto ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone da Pessoa de Contacto</label>
                            <input type="text" name="telefone_contacto" class="form-control" value="<?php echo htmlspecialchars($fornecedor->telefone_contacto ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Fornecedor</label>
                            <select name="tipo_fornecedor_id" class="form-select" required>
                                <?php foreach ($tipos_fornecedor as $tipo) : ?>
                                    <option value="<?php echo $tipo->id; ?>"
                                        <?php echo $tipo->id == $fornecedor->tipo_fornecedor_id ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tipo->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!--
                        FUTURO:
                        Associar equipamentos ao fornecedor através da tabela
                        equipamento_fornecedor.
                        <div class="mb-3">
                            <label class="form-label">Equipamentos Associados</label>
                            <div class="border rounded p-3 bg-white">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="eq1">
                                    <label class="form-check-label" for="eq1">
                                        Monitor de Sinais Vitais MX450
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="eq2">
                                    <label class="form-check-label" for="eq2">
                                        Ventilador Pulmonar V500
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="eq3">
                                    <label class="form-check-label" for="eq3">
                                        ECG Philips TC70
                                    </label>
                                </div>
                            </div>
                        </div>
                        -->

                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="observacoes" class="form-control" rows="5"><?php echo htmlspecialchars($fornecedor->observacoes ?? ''); ?></textarea>
                        </div>
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