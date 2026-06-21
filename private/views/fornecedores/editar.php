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

$erro_formulario = '';

$tipos_fornecedor = $ligacao->query("SELECT id, nome FROM tipos_fornecedor ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);

$equipamentos = $ligacao->query("
    SELECT id, codigo_inventario, designacao
    FROM equipamentos
    WHERE ativo = 1
    ORDER BY codigo_inventario
")->fetchAll(PDO::FETCH_OBJ);

try {
    $sql = "
        SELECT *
        FROM fornecedores
        WHERE id = :id
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);

    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: lista.php');
        exit;
    }

    $stmt_equipamentos = $ligacao->prepare("
        SELECT equipamento_id
        FROM equipamento_fornecedor
        WHERE fornecedor_id = :id
    ");

    $stmt_equipamentos->execute(['id' => $id]);

    $equipamentos_associados = [];

    foreach ($stmt_equipamentos->fetchAll(PDO::FETCH_OBJ) as $item) {
        $equipamentos_associados[] = $item->equipamento_id;
    }

} catch (PDOException $e) {
    die('Erro ao carregar fornecedor.');
}

$form = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [
    'tipo_fornecedor_id' => $fornecedor->tipo_fornecedor_id,
    'nome_empresa' => $fornecedor->nome_empresa,
    'nif' => $fornecedor->nif,
    'telefone' => $fornecedor->telefone,
    'email' => $fornecedor->email,
    'morada' => $fornecedor->morada,
    'website' => $fornecedor->website,
    'pessoa_contacto' => $fornecedor->pessoa_contacto,
    'telefone_contacto' => $fornecedor->telefone_contacto,
    'observacoes' => $fornecedor->observacoes,
    'equipamentos' => $equipamentos_associados
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $stmt_check = $ligacao->prepare("
            SELECT id
            FROM fornecedores
            WHERE nif = :nif
            AND id <> :id
            LIMIT 1
        ");

        $stmt_check->execute([
            'nif' => trim($_POST['nif']),
            'id' => $id
        ]);

        if ($stmt_check->fetch()) {
            $erro_formulario = 'Já existe outro fornecedor com esse NIF.';
        }

        if (
            !empty($_POST['nif']) &&
            !preg_match('/^[0-9]{9}$/', trim($_POST['nif']))
        ) {
            $erro_formulario = 'O NIF deve conter exatamente 9 dígitos.';
        }

        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $erro_formulario = 'O email introduzido não é válido.';
        }

        if (
            !empty($_POST['telefone']) &&
            !preg_match('/^[0-9]{9}$/', trim($_POST['telefone']))
        ) {
            $erro_formulario = 'O telefone deve conter 9 dígitos.';
        }

        if (
            !empty($_POST['telefone_contacto']) &&
            !preg_match('/^[0-9]{9}$/', trim($_POST['telefone_contacto']))
        ) {
            $erro_formulario = 'O telefone da pessoa de contacto deve conter 9 dígitos.';
        }

        if (empty($erro_formulario)) {
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

            $ligacao->prepare("
                DELETE FROM equipamento_fornecedor
                WHERE fornecedor_id = :id
            ")->execute(['id' => $id]);

            if (!empty($_POST['equipamentos'])) {
                $stmt_assoc = $ligacao->prepare("
                    INSERT INTO equipamento_fornecedor (
                        equipamento_id,
                        fornecedor_id,
                        tipo_fornecedor_id
                    ) VALUES (
                        :equipamento_id,
                        :fornecedor_id,
                        :tipo_fornecedor_id
                    )
                ");

                foreach ($_POST['equipamentos'] as $equipamento_id) {
                    $stmt_assoc->execute([
                        'equipamento_id' => (int) $equipamento_id,
                        'fornecedor_id' => $id,
                        'tipo_fornecedor_id' => (int) $_POST['tipo_fornecedor_id']
                    ]);
                }
            }

            header('Location: detalhes.php?id=' . $id);
            exit;
        }

    } catch (PDOException $e) {
        $erro_formulario = 'Erro ao atualizar fornecedor. Verifica se os dados preenchidos são válidos.';
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
                    <i class="fa-solid fa-pen-to-square me-2"></i> Editar Fornecedor
                </h2>

                <a href="lista.php" class="btn btn-cancel btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>

            <?php if ((int) $fornecedor->ativo === 0) : ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    Este fornecedor encontra-se inativo. Podes editar os dados, mas a situação só é alterada no botão de reativação da lista/detalhes.
                </div>
            <?php endif; ?>

            <?php if (!empty($erro_formulario)) : ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    <?php echo htmlspecialchars($erro_formulario); ?>
                </div>
            <?php endif; ?>

            <form class="form-medctrl" method="post">

                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nome da Empresa</label>
                            <input type="text" name="nome_empresa" class="form-control" value="<?php echo htmlspecialchars($form['nome_empresa'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIF</label>
                            <input type="text" name="nif" class="form-control" value="<?php echo htmlspecialchars($form['nif'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="<?php echo htmlspecialchars($form['telefone'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($form['email'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Morada</label>
                            <input type="text" name="morada" class="form-control" value="<?php echo htmlspecialchars($form['morada'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control" value="<?php echo htmlspecialchars($form['website'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pessoa de Contacto</label>
                            <input type="text" name="pessoa_contacto" class="form-control" value="<?php echo htmlspecialchars($form['pessoa_contacto'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone da Pessoa de Contacto</label>
                            <input type="text" name="telefone_contacto" class="form-control" value="<?php echo htmlspecialchars($form['telefone_contacto'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Fornecedor</label>

                            <select name="tipo_fornecedor_id" class="form-select" required>
                                <?php foreach ($tipos_fornecedor as $tipo) : ?>
                                    <option value="<?php echo $tipo->id; ?>"
                                        <?php echo (($form['tipo_fornecedor_id'] ?? '') == $tipo->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tipo->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Equipamentos Associados</label>

                            <div class="border rounded p-3 bg-white" style="max-height: 200px; overflow-y: auto;">

                                <?php if (count($equipamentos) === 0) : ?>

                                    <p class="text-muted mb-0">Não existem equipamentos disponíveis.</p>

                                <?php else : ?>

                                    <?php foreach ($equipamentos as $equipamento) : ?>
                                        <div class="form-check mb-2">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="equipamentos[]"
                                                value="<?php echo $equipamento->id; ?>"
                                                id="equipamento_<?php echo $equipamento->id; ?>"
                                                <?php echo in_array($equipamento->id, $form['equipamentos'] ?? []) ? 'checked' : ''; ?>
                                            >

                                            <label class="form-check-label" for="equipamento_<?php echo $equipamento->id; ?>">
                                                <?php echo htmlspecialchars($equipamento->codigo_inventario . ' | ' . $equipamento->designacao); ?>
                                            </label>

                                        </div>
                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="observacoes" class="form-control" rows="3"><?php echo htmlspecialchars($form['observacoes'] ?? ''); ?></textarea>
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