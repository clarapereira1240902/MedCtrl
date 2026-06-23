<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin_ou_tecnico();

$menu_ativo = 'fornecedores';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

try {
    $sql = "
        SELECT
            f.id,
            f.nome_empresa,
            f.email,
            f.telefone,
            f.ativo,
            tf.nome AS tipo_fornecedor,
            COUNT(ef.id) AS total_equipamentos
        FROM fornecedores f
        INNER JOIN tipos_fornecedor tf
            ON f.tipo_fornecedor_id = tf.id
        LEFT JOIN equipamento_fornecedor ef
            ON ef.fornecedor_id = f.id
        WHERE f.id = :id
        GROUP BY
            f.id,
            f.nome_empresa,
            f.email,
            f.telefone,
            f.ativo,
            tf.nome
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

$fornecedor_ativo = ((int) $fornecedor->ativo === 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($fornecedor_ativo) {
            $novo_estado = 0;
        } else {
            $novo_estado = 1;
        }

        $sql = "
            UPDATE fornecedores
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
                'Reativou fornecedor',
                'fornecedores',
                (int) $id,
                'Fornecedor reativado: ' . $fornecedor->nome_empresa
            );
        } else {
            registar_log(
                $ligacao,
                'Inativou fornecedor',
                'fornecedores',
                (int) $id,
                'Fornecedor inativado: ' . $fornecedor->nome_empresa
            );
        }

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao alterar o estado do fornecedor.');
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

                    <div class="<?php echo $fornecedor_ativo ? 'text-warning' : 'text-success'; ?> display-4 mb-3">
                        <?php if ($fornecedor_ativo) : ?>
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        <?php else : ?>
                            <i class="fa-solid fa-circle-check"></i>
                        <?php endif; ?>
                    </div>

                    <?php if ($fornecedor_ativo) : ?>

                        <h3 class="mb-2">Inativar Fornecedor</h3>

                        <p class="text-muted mb-3">
                            Tens a certeza que pretendes inativar este fornecedor?
                        </p>

                    <?php else : ?>

                        <h3 class="mb-2">Reativar Fornecedor</h3>

                        <p class="text-muted mb-3">
                            Tens a certeza que pretendes reativar este fornecedor?
                        </p>

                    <?php endif; ?>

                    <div class="bg-light p-3 rounded mb-3">
                        <h4 class="mb-1">
                            <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                        </h4>

                        <span class="badge bg-primary mb-2">
                            <?php echo htmlspecialchars($fornecedor->tipo_fornecedor); ?>
                        </span>

                        <p class="mb-1">
                            <i class="fa-solid fa-at me-1"></i>
                            <?php echo htmlspecialchars($fornecedor->email ?? 'Sem email'); ?>
                        </p>

                        <p class="mb-1">
                            <i class="fa-solid fa-phone me-1"></i>
                            <?php echo htmlspecialchars($fornecedor->telefone ?? 'Sem telefone'); ?>
                        </p>

                        <p class="mb-0">
                            Situação:
                            <?php if ($fornecedor_ativo) : ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if ($fornecedor_ativo && $fornecedor->total_equipamentos > 0) : ?>
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            Este fornecedor está associado a
                            <strong><?php echo $fornecedor->total_equipamentos; ?> equipamentos</strong>.
                            O fornecedor ficará inativo, mas os registos associados serão preservados.
                        </div>
                    <?php endif; ?>

                    <form method="post" class="d-flex justify-content-center gap-3">

                        <a href="lista.php" class="btn btn-outline-secondary btn-sm px-4">
                            <i class="fa-solid fa-xmark me-1"></i>Cancelar
                        </a>

                        <?php if ($fornecedor_ativo) : ?>

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